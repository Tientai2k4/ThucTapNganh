<?php
namespace App\Controllers\Staff;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class OrderController extends Controller {
    
    public function __construct() {
        // Chỉ cho phép Nhân viên Bán hàng và Admin truy cập
        AuthMiddleware::isSales(); 
    }

    // 1. Danh sách đơn hàng (Giao diện tập trung bộ lọc)
    public function index() {
        $model = $this->model('OrderModel');
        
        // Nhận bộ lọc từ URL
        $filters = [
            'keyword' => $_GET['keyword'] ?? '',
            'status'  => $_GET['status'] ?? '',
            'sort'    => $_GET['sort'] ?? 'newest'
        ];

        // Sử dụng hàm lọc mạnh mẽ đã có trong Model
        $orders = $model->getFilterOrders($filters);

        // Thống kê nhanh để hiển thị trên đầu trang
        $stats = [
            'pending' => count(array_filter($orders, fn($o) => $o['status'] === 'pending')),
            'processing' => count(array_filter($orders, fn($o) => $o['status'] === 'processing')),
        ];

        $this->view('staff/orders/index', [
            'orders'  => $orders,
            'filters' => $filters,
            'stats'   => $stats
        ]);
    }

    // 2. Chi tiết đơn hàng (Giao diện Hóa đơn & Thao tác)
    public function detail($code) {
        $model = $this->model('OrderModel');
        $order = $model->getOrderByCode($code);
        
        if (!$order) {
            echo "<script>alert('Đơn hàng không tồn tại'); window.location.href='".BASE_URL."staff/order';</script>";
            return;
        }

        $details = $model->getOrderDetails($order['id']);

        $this->view('staff/orders/detail', [
            'order'   => $order,
            'details' => $details
        ]);
    }

    // 3. Xử lý trạng thái (Logic chặt chẽ hơn Admin)
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['order_id'];
            $status = $_POST['status'];
            $code = $_POST['order_code'];
            $currentStatus = $_POST['current_status'];

            $model = $this->model('OrderModel');

            // [BẢO VỆ] Nhân viên không được phép hoàn tác đơn đã Thành công hoặc Đã hủy
            if ($currentStatus == 'completed' || $currentStatus == 'cancelled') {
                echo "<script>alert('Không thể thay đổi trạng thái đơn hàng đã Hoàn thành hoặc Đã hủy!'); window.history.back();</script>";
                return;
            }

            // [LOGIC KHO] Nếu Hủy đơn -> Phải hoàn kho
            if ($status == 'cancelled') {
                $result = $model->cancelOrderById($id); // Hàm này đã có trong Model bạn cung cấp
                if ($result !== true) {
                    echo "<script>alert('Lỗi hủy đơn: $result'); window.history.back();</script>";
                    return;
                }
            } else {
                // Các trạng thái: processing (Duyệt), shipped (Giao)
                $model->updateStatus($id, $status);
            }

            // Nếu đơn hàng giao thành công, tự động cập nhật thanh toán
            if ($status == 'completed') {
                $model->updatePaymentStatusByCode($code, 1);
            }

            header('Location: ' . BASE_URL . 'staff/order/detail/' . $code . '?msg=updated');
        }
    }
}
?>