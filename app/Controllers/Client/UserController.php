<?php
namespace App\Controllers\Client;
use App\Core\Controller;

class UserController extends Controller {
    
    // Trang Lịch sử mua hàng
    public function history() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'client/auth/login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $model = $this->model('OrderModel');
        $orders = $model->getOrdersByUserId($userId);

        $this->view('client/user/history', ['orders' => $orders]);
    }

    // Xem chi tiết đơn hàng (Client)
    public function orderDetail($code) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL); exit;
        }

        $model = $this->model('OrderModel');
        $order = $model->getOrderByCode($code);
        
        // Kiểm tra xem đơn hàng này có phải của user đang login không
        if (!$order || $order['user_id'] != $_SESSION['user_id']) {
            echo "Bạn không có quyền xem đơn hàng này."; return;
        }

        $details = $model->getOrderDetails($order['id']);

        $this->view('client/user/order_detail', [
            'order' => $order,
            'details' => $details
        ]);
    }
}
?>