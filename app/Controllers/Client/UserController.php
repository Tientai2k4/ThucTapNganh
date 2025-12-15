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

    // Hủy đơn hàng 
    public function cancelOrder() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_SESSION['user_id'])) return;

            $orderCode = $_POST['order_code'];
            $model = $this->model('OrderModel');
            
            // Kiểm tra đơn hàng có phải của user này không
            $order = $model->getOrderByCode($orderCode);
            if ($order && $order['user_id'] == $_SESSION['user_id']) {
                
                // Chỉ cho hủy khi đơn mới đặt (Pending)
                if ($order['status'] == 'pending') {
                    $model->cancelOrder($order['id'], 'Khách hàng hủy');
                    header('Location: ' . BASE_URL . 'user/history?msg=cancelled');
                } else {
                    echo "Đơn hàng đang giao hoặc đã xử lý, không thể hủy.";
                }
            }
        }
    }
}
?>