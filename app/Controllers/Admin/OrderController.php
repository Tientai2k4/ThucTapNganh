<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class OrderController extends Controller {
    public function __construct() {
        AuthMiddleware::hasRole(); 
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

    // Cập nhật trạng thái & Mã vận đơn (Thủ công)
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['order_id'];
            $status = $_POST['status']; 
            $code = $_POST['order_code'];
            
            // Lấy mã vận đơn admin nhập tay (nếu có)
            $manualTrackingCode = isset($_POST['tracking_code']) ? trim($_POST['tracking_code']) : '';

            $model = $this->model('OrderModel');

            // 1. Cập nhật trạng thái
            if ($status == 'cancelled') {
                $result = $model->cancelOrderById($id);
                if ($result !== true) {
                    echo "Lỗi hủy đơn: " . $result; return;
                }
            } else {
                $model->updateStatus($id, $status);
            }

            // 2. Cập nhật Mã vận đơn (Nếu admin có nhập)
            if (!empty($manualTrackingCode)) {
                $model->updateTrackingCode($id, $manualTrackingCode);
            }

            header('Location: ' . BASE_URL . 'admin/order/detail/' . $code);
        }
    }
}