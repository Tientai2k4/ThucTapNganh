<?php
namespace App\Controllers\Staff;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class OrderController extends Controller {
    public function __construct() {
        AuthMiddleware::hasRole(['staff']); 
    }

    public function index() {
        $model = $this->model('OrderModel');
        // Lấy danh sách đơn hàng
        $orders = $model->getAllOrders();
        
        // [QUAN TRỌNG] Truyền biến 'role_prefix' => 'staff' xuống View
        $this->view('admin/orders/index', [
            'orders' => $orders,
            'role_prefix' => 'staff' 
        ]);
    }

    public function detail($code) {
        $model = $this->model('OrderModel');
        $order = $model->getOrderByCode($code);
        if (!$order) { echo "Đơn hàng không tồn tại"; return; }
        $details = $model->getOrderDetails($order['id']);

        // [QUAN TRỌNG] Truyền biến 'role_prefix' => 'staff' xuống View
        $this->view('admin/orders/detail', [
            'order' => $order,
            'details' => $details,
            'role_prefix' => 'staff'
        ]);
    }

    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['order_id'];
            $status = $_POST['status'];
            $code = $_POST['order_code'];

            // Chặn quyền Hủy đơn đối với Staff
            if ($status == 'cancelled') {
                echo "<script>alert('Bạn không có quyền Hủy đơn!'); window.history.back();</script>";
                return;
            }

            $model = $this->model('OrderModel');
            $model->updateStatus($id, $status);

            // [SỬA LỖI Ở ĐÂY]: Chuyển hướng về STAFF, không phải ADMIN
            header('Location: ' . BASE_URL . 'staff/order/detail/' . $code);
            exit;
        }
    }
}