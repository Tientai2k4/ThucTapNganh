<?php
namespace App\Controllers\Client;

use App\Core\Controller;

class UserController extends Controller {
    
    // =================================================================
    // PHẦN 1: QUẢN LÝ HỒ SƠ & SỔ ĐỊA CHỈ (Code mới ngày 3)
    // =================================================================

    // Trang Hồ sơ cá nhân & Danh sách địa chỉ
    public function profile() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'client/auth/login'); 
            exit;
        }

        $userId = $_SESSION['user_id'];
        
        // 1. Lấy thông tin user
        $userModel = $this->model('UserModel');
        // Lưu ý: Đảm bảo bạn đã thêm hàm findById vào UserModel như hướng dẫn trước
        $user = $userModel->findById($userId); 

        // 2. Lấy danh sách địa chỉ nhận hàng
        $addrModel = $this->model('AddressModel');
        $addresses = $addrModel->getByUserId($userId);

        $this->view('client/user/profile', [
            'user' => $user,
            'addresses' => $addresses
        ]);
    }

    // Xử lý thêm địa chỉ mới
    public function addAddress() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_SESSION['user_id'])) return;

            $data = [
                'user_id' => $_SESSION['user_id'],
                'name' => $_POST['recipient_name'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address']
            ];
            
            $model = $this->model('AddressModel');
            $model->add($data);
            header('Location: ' . BASE_URL . 'user/profile');
        }
    }

    // Xóa địa chỉ
    public function deleteAddress($id) {
        if (!isset($_SESSION['user_id'])) return;

        $model = $this->model('AddressModel');
        $model->delete($id, $_SESSION['user_id']);
        header('Location: ' . BASE_URL . 'user/profile');
    }
    
    // Đặt địa chỉ mặc định
    public function setDefaultAddress($id) {
        if (!isset($_SESSION['user_id'])) return;

        $model = $this->model('AddressModel');
        $model->setDefault($id, $_SESSION['user_id']);
        header('Location: ' . BASE_URL . 'user/profile');
    }

    // =================================================================
    // PHẦN 2: QUẢN LÝ ĐƠN HÀNG (Code cũ - Lịch sử & Hủy đơn)
    // =================================================================

    // Trang Lịch sử mua hàng
    public function history() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'client/auth/login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $model = $this->model('OrderModel');
        
        // Lấy danh sách đơn hàng của user
        $orders = $model->getOrdersByUserId($userId);

        $this->view('client/user/history', ['orders' => $orders]);
    }

    // Xem chi tiết đơn hàng
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

    // Hủy đơn hàng (Chỉ cho phép khi đơn đang Pending)
    public function cancelOrder() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_SESSION['user_id'])) return;

            $orderCode = $_POST['order_code'];
            $model = $this->model('OrderModel');
            
            // 1. Kiểm tra đơn hàng có phải của user này không
            $order = $model->getOrderByCode($orderCode);
            if ($order && $order['user_id'] == $_SESSION['user_id']) {
                
                // 2. Chỉ cho hủy khi đơn mới đặt (Pending)
                if ($order['status'] == 'pending') {
                    $model->cancelOrder($order['id'], 'Khách hàng hủy');
                    header('Location: ' . BASE_URL . 'user/history?msg=cancelled');
                } else {
                    echo "<script>alert('Đơn hàng đang giao hoặc đã xử lý, không thể hủy.'); window.history.back();</script>";
                }
            }
        }
    }
}
?>