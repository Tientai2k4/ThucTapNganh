<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class OrderController extends Controller {
    public function __construct() {
        // Sử dụng phương thức đã định nghĩa ở Middleware mới để cho phép cả Admin và Staff
        AuthMiddleware::isStaffArea(); 
    }

    public function index() {
        $model = $this->model('OrderModel');
        
        // Lấy tham số từ URL
        $filters = [
            'keyword' => $_GET['keyword'] ?? '',
            'status'  => $_GET['status'] ?? '',
            'sort'    => $_GET['sort'] ?? 'newest'
        ];

        // Gọi hàm lọc mới trong Model
        $orders = $model->getFilterOrders($filters);

        // Truyền data sang View
        $this->view('admin/orders/index', [
            'orders'  => $orders,
            'filters' => $filters
        ]);
    }

    // Xem chi tiết đơn hàng
  // Xem chi tiết đơn hàng
    public function detail($code) {
        $model = $this->model('OrderModel');
        $order = $model->getOrderByCode($code);
        
        if (!$order) {
            echo "Đơn hàng không tồn tại"; return;
        }

        $details = $model->getOrderDetails($order['id']);
        
        // [FIX] Lấy địa chỉ đầy đủ (Tỉnh/Huyện/Xã) thay vì chỉ lấy số nhà
        $fullAddress = $model->getOrderFullAddress($order['id']);
        if (!empty($fullAddress)) {
            $order['shipping_address'] = $fullAddress;
        }

        $this->view('admin/orders/detail', [
            'order' => $order,
            'details' => $details
        ]);
    }

    // Cập nhật trạng thái & Mã vận đơn (Thủ công)
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['order_id'];
            $status = $_POST['status']; 
            $code = $_POST['order_code'];
            $reason = $_POST['cancel_reason'] ?? '';
            // Lấy mã vận đơn admin nhập tay (nếu có)
            $manualTrackingCode = isset($_POST['tracking_code']) ? trim($_POST['tracking_code']) : '';

            $model = $this->model('OrderModel');

            // 1. Cập nhật trạng thái
            if ($status == 'cancelled') {
                $result = $model->cancelOrderById($id, $reason);
                if ($result !== true) {
                    echo "Lỗi khi hủy và hoàn kho: " . $result; return;
                }
            } else {
                // Cập nhật trạng thái đơn hàng (ví dụ: đang giao hàng, hoàn thành...)
                $model->updateStatus($id, $status);

                // [SỬA LỖI QUAN TRỌNG TẠI ĐÂY]
                // Nếu trạng thái là 'completed' (Hoàn thành), bắt buộc phải cập nhật payment_status = 1
                // Để User có thể đánh giá được sản phẩm (vì ReviewModel yêu cầu payment_status = 1)
                if ($status == 'completed') {
                    $model->updatePaymentStatusByCode($code, 1);
                }
            }

            // 2. Cập nhật Mã vận đơn (Nếu admin có nhập)
            if (!empty($manualTrackingCode)) {
                $model->updateTrackingCode($id, $manualTrackingCode);
            }

            header('Location: ' . BASE_URL . 'admin/order/detail/' . $code);
        }
    }
        // In đơn hàng
       public function print($orderCode) {
        $orderModel = $this->model('OrderModel');
        $order = $orderModel->getOrderByCode($orderCode);

        if (!$order) {
            echo "Đơn hàng không tồn tại!";
            return;
        }

        $details = $orderModel->getOrderDetails($order['id']);

        $data = [
            'order' => $order,
            'details' => $details
        ];
        if (!empty($data)) {
            extract($data);
        }

        $viewPath = ROOT_PATH . "/app/Views/admin/orders/print_template.php";
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            echo "Lỗi: Không tìm thấy file mẫu hóa đơn tại: " . $viewPath;
        }
    }
}
?>