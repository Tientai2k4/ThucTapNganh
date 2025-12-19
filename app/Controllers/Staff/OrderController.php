<?php
namespace App\Controllers\Staff;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class OrderController extends Controller {
    public function __construct() {
        AuthMiddleware::isStaffArea();
    }

    public function index() {
        $model = $this->model('OrderModel');
        $orders = $model->getAllOrders();
        // Gửi view riêng của staff
        $this->view('staff/orders/index', ['orders' => $orders]);
    }

    public function detail($code) {
        $model = $this->model('OrderModel');
        $order = $model->getOrderByCode($code);
        if (!$order) { die("Không tìm thấy đơn hàng"); }
        $details = $model->getOrderDetails($order['id']);

        $this->view('staff/orders/detail', ['order' => $order, 'details' => $details]);
    }

    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['order_id'];
            $status = $_POST['status'];
            $code = $_POST['order_code'];
            
            // Nhân viên không có quyền Hủy đơn hàng đã được duyệt
            if ($status == 'cancelled' && $_SESSION['user_role'] != 'admin') {
                echo "<script>alert('Bạn không có quyền hủy đơn hàng. Vui lòng liên hệ Admin!'); window.history.back();</script>";
                return;
            }

            $this->model('OrderModel')->updateStatus($id, $status);
            header('Location: ' . BASE_URL . 'staff/order/detail/' . $code);
            exit;
        }
    }
}