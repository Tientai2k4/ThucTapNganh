<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class OrderController extends Controller {
    public function __construct() {
        AuthMiddleware::hasRole(); 
    }

    // Danh sách đơn hàng
    public function index() {
        $model = $this->model('OrderModel');
        $orders = $model->getAllOrders();
        $this->view('admin/orders/index', ['orders' => $orders]);
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