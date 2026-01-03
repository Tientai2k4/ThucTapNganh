<?php
namespace App\Controllers\Client;

use App\Core\Controller;

class UserController extends Controller {
    
    public function __construct() {
        // Kiểm tra đăng nhập cho toàn bộ Controller này
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'auth/login'); 
            exit;
        }
    }

    // =================================================================
    // PHẦN 1: QUẢN LÝ HỒ SƠ (PROFILE)
    // =================================================================

    public function profile() {
        $userId = $_SESSION['user_id'];
        $userModel = $this->model('UserModel');
        $user = $userModel->findById($userId); 

        if (!$user) {
            session_destroy();
            header('Location: ' . BASE_URL);
            exit;
        }

        $addrModel = $this->model('AddressModel');
        $addresses = $addrModel->getByUserId($userId);

        $this->view('client/user/profile', [
            'user' => $user,
            'addresses' => $addresses
        ]);
    }

    public function updateInfo() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $fullName = trim($_POST['full_name']);
            $phone = trim($_POST['phone_number']);

            if (empty($fullName) || empty($phone)) {
                echo "<script>alert('Vui lòng điền đầy đủ thông tin!'); window.history.back();</script>";
                return;
            }
            if (!preg_match('/^0[0-9]{9}$/', $phone)) {
                echo "<script>alert('Số điện thoại không hợp lệ (Phải đủ 10 số và bắt đầu bằng 0)!'); window.history.back();</script>";
                return;
            }

            $userModel = $this->model('UserModel');
            $result = $userModel->updateProfile($userId, $fullName, $phone);

            if ($result) {
                $_SESSION['user_name'] = $fullName;
                echo "<script>alert('Cập nhật thành công!'); window.location.href='" . BASE_URL . "user/profile';</script>";
            } else {
                echo "<script>alert('Có lỗi xảy ra!'); window.history.back();</script>";
            }
        }
    }

    public function updateAvatar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
            $userId = $_SESSION['user_id'];
            
            $dbPathFolder = "public/uploads/avatars/";
            $projectRoot = dirname(__DIR__, 3); 
            $uploadDir = $projectRoot . "/" . $dbPathFolder;

            if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true); 

            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) {
                echo "<script>alert('File không hợp lệ!'); window.history.back();</script>";
                return;
            }

            $newFilename = "u" . $userId . "_" . time() . "." . $ext;
            
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $newFilename)) {
                $webUrl = BASE_URL . $dbPathFolder . $newFilename;
                $this->model('UserModel')->updateAvatar($userId, $webUrl);
                $_SESSION['user_avatar'] = $webUrl;
                echo "<script>alert('Đổi ảnh đại diện thành công!'); window.location.href='" . BASE_URL . "user/profile';</script>";
            } else {
                echo "<script>alert('Lỗi upload file!'); window.history.back();</script>";
            }
        }
    }

    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $oldPass = $_POST['old_password'];
            $newPass = $_POST['new_password'];
            $confirmPass = $_POST['confirm_password'];

            $userModel = $this->model('UserModel');

            if (!$userModel->checkPassword($userId, $oldPass)) {
                echo "<script>alert('Mật khẩu cũ không đúng!'); window.history.back();</script>";
                return;
            }

            if ($newPass !== $confirmPass) {
                echo "<script>alert('Mật khẩu xác nhận không khớp!'); window.history.back();</script>";
                return;
            }

            if (strlen($newPass) < 6) {
                echo "<script>alert('Mật khẩu mới quá ngắn!'); window.history.back();</script>";
                return;
            }

            if ($userModel->changePassword($userId, $newPass)) {
                echo "<script>alert('Đổi mật khẩu thành công!'); window.location.href='" . BASE_URL . "user/profile';</script>";
            } else {
                echo "<script>alert('Lỗi hệ thống!'); window.history.back();</script>";
            }
        }
    }

    // =================================================================
    // PHẦN 2: QUẢN LÝ ĐỊA CHỈ
    // =================================================================

    public function addAddress() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'user_id' => $_SESSION['user_id'],
                'name' => $_POST['recipient_name'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address']
            ];
            $this->model('AddressModel')->add($data);
            header('Location: ' . BASE_URL . 'user/profile');
        }
    }

    public function deleteAddress($id) {
        $this->model('AddressModel')->delete($id, $_SESSION['user_id']);
        header('Location: ' . BASE_URL . 'user/profile');
    }
    
    public function setDefaultAddress($id) {
        $this->model('AddressModel')->setDefault($id, $_SESSION['user_id']);
        header('Location: ' . BASE_URL . 'user/profile');
    }

    // =================================================================
    // PHẦN 3: QUẢN LÝ ĐƠN HÀNG (HISTORY, DETAIL, CANCEL, RE-ORDER)
    // =================================================================

    // 1. Danh sách đơn hàng
    public function history() {
        $userId = $_SESSION['user_id'];
        $model = $this->model('OrderModel');
        
        // Sắp xếp đơn mới nhất lên đầu
        $orders = $model->getOrdersByUserId($userId);

        $this->view('client/user/history', ['orders' => $orders]);
    }

    // 2. Chi tiết đơn hàng
    public function orderDetail($code) {
        $model = $this->model('OrderModel');
        $order = $model->getOrderByCode($code);
        
        // Bảo mật: Chỉ xem được đơn của chính mình
        if (!$order || $order['user_id'] != $_SESSION['user_id']) {
            echo "<script>alert('Đơn hàng không tồn tại hoặc bạn không có quyền xem!'); window.location.href='" . BASE_URL . "user/history';</script>"; 
            return;
        }

        $details = $model->getOrderDetails($order['id']);

        $this->view('client/user/order_detail', [
            'order' => $order,
            'details' => $details
        ]);
    }

    // 3. Hủy đơn hàng (Chỉ Pending/Processing chưa thanh toán)
    public function cancelOrder() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $orderCode = $_POST['order_code'];
            $reason = $_POST['cancel_reason'] ?? 'Khách hàng đổi ý';
            
            $model = $this->model('OrderModel');
            $order = $model->getOrderByCode($orderCode);

            if ($order && $order['user_id'] == $_SESSION['user_id']) {
                // Chỉ cho hủy khi đơn mới (Pending) hoặc Chờ thanh toán
                if ($order['status'] == 'pending' || $order['status'] == 'pending_payment') {
                    // Gọi hàm hủy trong Model (Hàm này đã có logic hoàn kho)
                    $model->cancelOrder($order['order_code']); 
                    echo "<script>alert('Đã hủy đơn hàng thành công!'); window.location.href='" . BASE_URL . "user/orderDetail/$orderCode';</script>";
                } else {
                    echo "<script>alert('Đơn hàng đang được xử lý hoặc đã giao, không thể hủy!'); window.history.back();</script>";
                }
            } else {
                echo "<script>alert('Không tìm thấy đơn hàng!'); window.history.back();</script>";
            }
        }
    }

    // 4. [MỚI] Mua lại (Re-order)
    public function repurchase($orderCode) {
        $model = $this->model('OrderModel');
        $prodModel = $this->model('ProductModel');
        
        $order = $model->getOrderByCode($orderCode);
        
        if (!$order || $order['user_id'] != $_SESSION['user_id']) {
            header('Location: ' . BASE_URL . 'user/history');
            exit;
        }

        // Lấy chi tiết sản phẩm trong đơn cũ
        $details = $model->getOrderDetails($order['id']);
        
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $countAdded = 0;
        $countOutStock = 0;

        foreach ($details as $item) {
            $variantId = $item['product_variant_id'];
            $qtyNeeded = 1; // Mặc định thêm 1, user vào giỏ chỉnh sau, hoặc dùng $item['quantity']

            // Kiểm tra tồn kho thực tế hiện tại
            $currentStock = $prodModel->getVariantStock($variantId);

            if ($currentStock > 0) {
                // Logic thêm vào giỏ (Cộng dồn nếu đã có)
                if (isset($_SESSION['cart'][$variantId])) {
                    if ($_SESSION['cart'][$variantId] < $currentStock) {
                        $_SESSION['cart'][$variantId]++;
                        $countAdded++;
                    }
                } else {
                    $_SESSION['cart'][$variantId] = 1;
                    $countAdded++;
                }
            } else {
                $countOutStock++;
            }
        }

        // Thông báo kết quả
        if ($countOutStock > 0 && $countAdded > 0) {
            $msg = "Đã thêm $countAdded sản phẩm vào giỏ. Có $countOutStock sản phẩm hiện đã hết hàng.";
        } elseif ($countOutStock > 0 && $countAdded == 0) {
            $msg = "Rất tiếc, các sản phẩm trong đơn này đều đã hết hàng.";
        } else {
            $msg = "Đã thêm toàn bộ sản phẩm vào giỏ hàng!";
        }

        echo "<script>alert('$msg'); window.location.href='" . BASE_URL . "cart';</script>";
    }
}
?>