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
    public function detail($code) {
        $model = $this->model('OrderModel');
        $order = $model->getOrderByCode($code);
        
        if (!$order) {
            echo "Đơn hàng không tồn tại"; return;
        }

        $details = $model->getOrderDetails($order['id']);

        $this->view('admin/orders/detail', [
            'order' => $order,
            'details' => $details
        ]);
    }

    // Cập nhật trạng thái (Duyệt/Giao hàng/Hủy)
    public function updateStatus() {
        // [Bảo vệ] Nếu chỉ Admin được phép hủy đơn hàng, bạn có thể thêm kiểm tra riêng tại đây:
        // if ($_POST['status'] == 'cancelled') { AuthMiddleware::onlyAdmin(); } 

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['order_id'];
            $status = $_POST['status'];
            $code = $_POST['order_code'];

            $model = $this->model('OrderModel');

            // [LOGIC MỚI] Nếu Admin chọn "Hủy đơn", gọi hàm cancelOrderById để Rollback kho
            if ($status == 'cancelled') {
                $result = $model->cancelOrderById($id);
                if ($result !== true) {
                    // Nếu lỗi, có thể lưu vào session flash message (ở đây echo tạm)
                    echo "Lỗi hủy đơn: " . $result;
                    return;
                }
            } else {
                // Các trạng thái khác (Duyệt, Giao hàng) chỉ cần update status
                $model->updateStatus($id, $status);
            }

            header('Location: ' . BASE_URL . 'admin/order/detail/' . $code);
        }
    }
}