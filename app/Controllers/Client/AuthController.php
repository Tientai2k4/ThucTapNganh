<?php
namespace App\Controllers\Client;
use App\Core\Controller;

class AuthController extends Controller {

    // === LOGIN ===
    public function login() {
        $this->view('client/auth/login');
    }

    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $pass = $_POST['password'];

            $model = $this->model('UserModel');
            $user = $model->login($email, $pass);

            if ($user) {
                // Lưu session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_role'] = $user['role'];

                // --> THÊM DÒNG NÀY: Tạo thông báo
                 $_SESSION['flash_message'] = "Đăng nhập thành công! Chào mừng bạn quay lại.";

                // Nếu là admin thì vào trang admin, khách thì vào trang chủ
                if ($user['role'] == 'admin') {
                    header('Location: ' . BASE_URL . 'admin/dashboard');
                } else {
                    header('Location: ' . BASE_URL);
                }
            } else {
                $this->view('client/auth/login', ['error' => 'Sai email hoặc mật khẩu']);
            }
        }
    }

    // === REGISTER ===
    public function register() {
        $this->view('client/auth/register');
    }

    public function processRegister() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['full_name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $pass = $_POST['password'];
            $confirm = $_POST['confirm_password'];

            if ($pass != $confirm) {
                $this->view('client/auth/register', ['error' => 'Mật khẩu nhập lại không khớp']);
                return;
            }

            $model = $this->model('UserModel');
            $result = $model->register($name, $email, $pass, $phone);

            if ($result === true) {
                $this->view('client/auth/login', ['success' => 'Đăng ký thành công! Mời đăng nhập.']);
            } else {
                $this->view('client/auth/register', ['error' => $result]);
            }
        }
    }

    public function logout() {
        session_destroy();
        header('Location: ' . BASE_URL);
    }

    // 1. Hiện Form nhập email
    public function forgotPassword() {
        $this->view('client/auth/forgot_password');
    }

    // 2. Xử lý gửi mail
public function sendResetLink() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        
        // Kiểm tra email có tồn tại trong hệ thống không (Gọi hàm mới tạo)
        $userModel = $this->model('UserModel');
        $user = $userModel->findByEmail($email);

        if (!$user) {
            // Nếu không tìm thấy, báo lỗi hoặc (tốt hơn) báo thành công ảo để tránh lộ thông tin user
            $this->view('client/auth/forgot_password', ['error' => 'Email không tồn tại trong hệ thống.']);
            return; 
        }

        // Tạo Token và Gửi Mail chỉ khi user tồn tại
        $resetModel = $this->model('PasswordResetModel');
        $token = $resetModel->createToken($email);

        if ($token) {
            // Gửi mail
            require_once ROOT_PATH . '/app/Core/MailHelper.php';
            $link = BASE_URL . 'client/auth/resetPassword?token=' . $token;
            $content = "<p>Chào bạn,</p><p>Vui lòng click vào link sau để đặt lại mật khẩu:</p><a href='$link'>Đặt lại mật khẩu</a><p>Hoặc truy cập link: $link</p>";
            
            // Gửi mail thật (với API SendGrid đã cấu hình)
            $isSent = \App\Core\MailHelper::send($email, 'Khôi phục mật khẩu tài khoản Swimming Store', $content);
            
            if ($isSent) {
                 $this->view('client/auth/forgot_password', ['success' => 'Link khôi phục đã được gửi vào email của bạn.']);
            } else {
                 $this->view('client/auth/forgot_password', ['error' => 'Lỗi gửi mail, vui lòng kiểm tra lại cấu hình SendGrid.']);
            }
        } else {
            $this->view('client/auth/forgot_password', ['error' => 'Có lỗi xảy ra khi tạo token, vui lòng thử lại.']);
        }
    }
}

    // 3. Hiện Form đặt mật khẩu mới
    public function resetPassword() {
        $token = $_GET['token'] ?? '';
        $resetModel = $this->model('PasswordResetModel');
        $email = $resetModel->verifyToken($token);

        if ($email) {
            $this->view('client/auth/reset_password', ['token' => $token]);
        } else {
            echo "Link không hợp lệ hoặc đã hết hạn.";
        }
    }

    // 4. Xử lý lưu mật khẩu mới 
    public function processResetPassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $token = $_POST['token'];
            $pass = $_POST['password'];
            $confirm = $_POST['confirm_password'];

            if ($pass != $confirm) {
                $this->view('client/auth/reset_password', ['token' => $token, 'error' => 'Mật khẩu không khớp']);
                return;
            }

            $resetModel = $this->model('PasswordResetModel');
            $email = $resetModel->verifyToken($token);

            if ($email) {
                $resetModel->updatePassword($email, $pass);
                $resetModel->deleteToken($email); // Xóa token để không dùng lại được
                
                header('Location: ' . BASE_URL . 'client/auth/login?success=reset_ok');
            } else {
                echo "Lỗi xác thực token.";
            }
        }
    }
}
?>