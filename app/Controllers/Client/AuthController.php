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
}
?>