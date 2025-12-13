<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class OrderController extends Controller {
    public function __construct() {
        AuthMiddleware::isAdmin();
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

    // Cập nhật trạng thái (Duyệt/Giao hàng/Hủy)
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['order_id'];
            $status = $_POST['status'];
            $code = $_POST['order_code'];

            $model = $this->model('OrderModel');
            $model->updateStatus($id, $status);

            header('Location: ' . BASE_URL . 'admin/order/detail/' . $code);
        }
    }
}
?>