<?php
namespace App\Controllers\Client;

use App\Core\Controller;

class UserController extends Controller {
    
    // =================================================================
    // PHẦN 1: QUẢN LÝ HỒ SƠ & TÀI KHOẢN
    // =================================================================

    // Hiển thị trang Hồ sơ cá nhân & Danh sách địa chỉ
    public function profile() {
        // 1. Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'client/auth/login'); 
            exit;
        }

        $userId = $_SESSION['user_id'];
        
        // 2. Lấy thông tin chi tiết user từ Database
        // (Để đảm bảo dữ liệu luôn mới nhất, không chỉ lấy từ Session)
        $userModel = $this->model('UserModel');
        $user = $userModel->findById($userId); 

        // Nếu user bị xóa hoặc lỗi, đăng xuất luôn
        if (!$user) {
            session_destroy();
            header('Location: ' . BASE_URL);
            exit;
        }

        // 3. Lấy danh sách địa chỉ nhận hàng
        $addrModel = $this->model('AddressModel');
        $addresses = $addrModel->getByUserId($userId);

        // 4. Truyền dữ liệu ra View
        $this->view('client/user/profile', [
            'user' => $user,
            'addresses' => $addresses
        ]);
    }

    // Xử lý Cập nhật thông tin cá nhân (Tên, Số điện thoại)
    public function updateInfo() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_SESSION['user_id'])) return;

            $userId = $_SESSION['user_id'];
            $fullName = trim($_POST['full_name']);
            $phone = trim($_POST['phone_number']);

            // Validate đơn giản
            if (empty($fullName) || empty($phone)) {
                echo "<script>alert('Vui lòng không để trống Họ tên và Số điện thoại!'); window.history.back();</script>";
                return;
            }

            $userModel = $this->model('UserModel');
            $result = $userModel->updateProfile($userId, $fullName, $phone);

            if ($result) {
                // Cập nhật lại Session để Header hiển thị đúng tên mới ngay lập tức
                $_SESSION['user_name'] = $fullName;
                
                echo "<script>alert('Cập nhật thông tin thành công!'); window.location.href='" . BASE_URL . "user/profile';</script>";
            } else {
                echo "<script>alert('Lỗi hệ thống, vui lòng thử lại.'); window.history.back();</script>";
            }
        }
    }

  // Xử lý Cập nhật Ảnh đại diện (Avatar) - PHIÊN BẢN ĐÃ SỬA LỖI ĐƯỜNG DẪN
    public function updateAvatar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_SESSION['user_id'])) return;

            // Kiểm tra file
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
                $userId = $_SESSION['user_id'];
                
                // 1. Cấu hình đường dẫn lưu trữ
                // Đường dẫn tương đối để lưu vào DB (để hiển thị trên web)
                $dbPathFolder = "public/uploads/avatars/";
                
                // Đường dẫn Tuyệt đối vật lý trên ổ cứng (để PHP lưu file)
                // Lưu ý: dirname(__DIR__, 3) sẽ lấy thư mục gốc của dự án (ThucTapNganh)
                // App\Controllers\Client -> thoát ra 3 cấp là về Root
                $projectRoot = dirname(__DIR__, 3); 
                $uploadDir = $projectRoot . "/" . $dbPathFolder;

                // 2. Tự động tạo thư mục nếu chưa tồn tại
                if (!file_exists($uploadDir)) {
                    // 0777 là quyền ghi, true là tạo đệ quy (tạo cả uploads lẫn avatars)
                    mkdir($uploadDir, 0777, true); 
                }

                // 3. Validate file
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $filename = $_FILES['avatar']['name'];
                $filesize = $_FILES['avatar']['size'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                if (!in_array($ext, $allowed)) {
                    echo "<script>alert('Chỉ chấp nhận file ảnh (JPG, PNG, GIF, WEBP)!'); window.history.back();</script>";
                    return;
                }

                if ($filesize > 5 * 1024 * 1024) { // Tăng lên 5MB
                    echo "<script>alert('Ảnh quá lớn (Tối đa 5MB)!'); window.history.back();</script>";
                    return;
                }

                // 4. Tạo tên file và Di chuyển
                $newFilename = "user_" . $userId . "_" . time() . "." . $ext;
                $destPath = $uploadDir . $newFilename; // Đường dẫn vật lý để lưu
                
                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $destPath)) {
                    
                    // Lưu đường dẫn web vào CSDL (VD: http://localhost/ThucTapNganh/public/uploads/avatars/...)
                    $webUrl = BASE_URL . $dbPathFolder . $newFilename;

                    $userModel = $this->model('UserModel');
                    $userModel->updateAvatar($userId, $webUrl);

                    // Cập nhật Session
                    $_SESSION['user_avatar'] = $webUrl;

                    echo "<script>alert('Đổi ảnh đại diện thành công!'); window.location.href='" . BASE_URL . "user/profile';</script>";
                } else {
                    // In lỗi chi tiết ra để debug nếu vẫn lỗi
                    $error = error_get_last();
                    echo "<script>alert('Lỗi server: " . $error['message'] . "'); window.history.back();</script>";
                }
            } else {
                echo "<script>alert('Vui lòng chọn ảnh!'); window.history.back();</script>";
            }
        }
    }
    // Xử lý Đổi mật khẩu
    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_SESSION['user_id'])) return;

            $userId = $_SESSION['user_id'];
            $oldPass = $_POST['old_password'];
            $newPass = $_POST['new_password'];
            $confirmPass = $_POST['confirm_password'];

            $userModel = $this->model('UserModel');

            // 1. Kiểm tra mật khẩu cũ
            if (!$userModel->checkPassword($userId, $oldPass)) {
                echo "<script>alert('Mật khẩu cũ không chính xác!'); window.history.back();</script>";
                return;
            }

            // 2. Kiểm tra mật khẩu mới trùng khớp
            if ($newPass !== $confirmPass) {
                echo "<script>alert('Mật khẩu xác nhận không khớp!'); window.history.back();</script>";
                return;
            }

            // 3. Thực hiện đổi
            if ($userModel->changePassword($userId, $newPass)) {
                echo "<script>alert('Đổi mật khẩu thành công!'); window.location.href='" . BASE_URL . "user/profile';</script>";
            } else {
                echo "<script>alert('Lỗi hệ thống!'); window.history.back();</script>";
            }
        }
    }

    // =================================================================
    // PHẦN 2: QUẢN LÝ SỔ ĐỊA CHỈ
    // =================================================================

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
    // PHẦN 3: QUẢN LÝ ĐƠN HÀNG (Lịch sử & Hủy đơn)
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