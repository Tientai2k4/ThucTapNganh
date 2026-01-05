<?php
namespace App\Controllers\Staff;

use App\Core\Controller;
use App\Core\AuthMiddleware;

class OrderController extends Controller {
    
    private $orderModel;

    public function __construct() {
        AuthMiddleware::isStaffArea(); 
        $this->orderModel = $this->model('OrderModel');
    }

    // 1. Danh sách đơn hàng
    public function index() {
        // Lấy tham số filter an toàn
        $filters = [
            'keyword' => isset($_GET['keyword']) ? trim($_GET['keyword']) : '',
            'status'  => isset($_GET['status']) ? trim($_GET['status']) : '',
            'sort'    => isset($_GET['sort']) ? trim($_GET['sort']) : 'newest'
        ];

        try {
            $orders = $this->orderModel->getFilterOrders($filters);
            
            // Tính toán thống kê
            $stats = [
                'pending'    => 0,
                'processing' => 0,
                'shipped'    => 0
            ];
            foreach ($orders as $o) {
                if (isset($stats[$o['status']])) {
                    $stats[$o['status']]++;
                }
            }

            $this->view('staff/orders/index', [
                'orders'  => $orders,
                'filters' => $filters,
                'stats'   => $stats
            ]);
        } catch (\Exception $e) {
            // Xử lý lỗi nếu DB sập hoặc lỗi query
            echo "Lỗi hệ thống: " . $e->getMessage();
        }
    }

    // 2. Chi tiết đơn hàng
    public function detail($code) {
        $order = $this->orderModel->getOrderByCode($code);
        
        if (!$order) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Không tìm thấy đơn hàng mã: ' . htmlspecialchars($code)];
            header('Location: ' . BASE_URL . 'staff/order');
            exit;
        }

        $details = $this->orderModel->getOrderDetails($order['id']);

        $this->view('staff/orders/detail', [
            'order'   => $order,
            'details' => $details
        ]);
    }

    // 3. Xử lý cập nhật trạng thái (CORE LOGIC)
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'staff/order');
            exit;
        }

        // Lấy dữ liệu đầu vào
        $orderId = $_POST['order_id'] ?? null;
        $orderCode = $_POST['order_code'] ?? '';
        $newStatus = $_POST['status'] ?? '';
        $trackingCode = isset($_POST['tracking_code']) ? trim($_POST['tracking_code']) : '';

        if (!$orderId || !$newStatus) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Dữ liệu không hợp lệ.'];
            header('Location: ' . BASE_URL . 'staff/order');
            exit;
        }

        // Lấy trạng thái thực tế từ DB (Không tin tưởng dữ liệu từ Form gửi lên để tránh hack)
        $currentOrder = $this->orderModel->getOrderById($orderId);
        if (!$currentOrder) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Đơn hàng không tồn tại.'];
            header('Location: ' . BASE_URL . 'staff/order');
            exit;
        }

        $currentStatus = $currentOrder['status'];

        // [KIỂM TRA 1] Nếu đơn đã đóng, cấm sửa tuyệt đối
        if ($currentStatus === 'completed' || $currentStatus === 'cancelled') {
            $_SESSION['alert'] = ['type' => 'warning', 'message' => 'Đơn hàng đã kết thúc, không thể thay đổi trạng thái.'];
            header('Location: ' . BASE_URL . 'staff/order/detail/' . $orderCode);
            exit;
        }

        // [KIỂM TRA 2] Validate luồng trạng thái (Workflow)
        if ($currentStatus !== $newStatus) {
            $isValidTransition = $this->validateTransition($currentStatus, $newStatus);
            if (!$isValidTransition) {
                $_SESSION['alert'] = ['type' => 'danger', 'message' => "Không thể chuyển từ " . strtoupper($currentStatus) . " sang " . strtoupper($newStatus)];
                header('Location: ' . BASE_URL . 'staff/order/detail/' . $orderCode);
                exit;
            }
        }

        // [THỰC HIỆN] 
        // 1. Cập nhật mã vận đơn (Nếu có nhập)
        if (!empty($trackingCode)) {
            $this->orderModel->updateTrackingCode($orderId, $trackingCode);
        }

        // 2. Xử lý chuyển trạng thái
        if ($newStatus !== $currentStatus) {
            if ($newStatus === 'cancelled') {
                // Trường hợp hủy: Phải hoàn kho
                $result = $this->orderModel->cancelOrderById($orderId);
                if ($result === true) {
                    $_SESSION['alert'] = ['type' => 'success', 'message' => 'Đã hủy đơn và hoàn kho thành công.'];
                } else {
                    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Lỗi khi hủy đơn: ' . $result];
                }
            } else {
                // Cập nhật trạng thái thông thường
                $this->orderModel->updateStatus($orderId, $newStatus);

                // Nếu hoàn thành: Cập nhật Payment = 1
                if ($newStatus === 'completed') {
                    $this->orderModel->updatePaymentStatusByCode($orderCode, 1);
                    $_SESSION['alert'] = ['type' => 'success', 'message' => 'Giao hàng thành công! Đã xác nhận thanh toán.'];
                } else {
                    $_SESSION['alert'] = ['type' => 'success', 'message' => 'Đã cập nhật trạng thái đơn hàng.'];
                }
            }
        } else {
            $_SESSION['alert'] = ['type' => 'info', 'message' => 'Đã cập nhật thông tin vận đơn.'];
        }

        header('Location: ' . BASE_URL . 'staff/order/detail/' . $orderCode);
    }

    /**
     * Hàm kiểm tra luồng đi của đơn hàng
     * pending -> processing -> shipped -> completed
     * pending/processing/shipped -> cancelled
     */
private function validateTransition($current, $new) {
        $allowed = [
            // Chờ xử lý -> Chuẩn bị HOẶC Hủy
            'pending'    => ['processing', 'cancelled'],     
            
            // Đang chuẩn bị -> Đang giao hàng (SỬA LẠI TỪ shipped -> shipping)
            'processing' => ['shipping', 'cancelled'],       
            
            // Đang giao hàng -> Thành công HOẶC Hủy (SỬA LẠI TỪ shipped -> shipping)
            'shipping'   => ['completed', 'cancelled'],      
            
            'completed'  => [],                              
            'cancelled'  => []                               
        ];

        // Nếu trạng thái mới nằm trong danh sách cho phép thì OK
        return in_array($new, $allowed[$current] ?? []);
    }
}
?>