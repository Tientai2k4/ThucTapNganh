<?php
namespace App\Controllers\Admin;
use App\Core\Controller;

class AuthController extends Controller {
    
    public function login() {
        $this->view('admin/auth/login');
    }

    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $userModel = $this->model('UserModel');
            $user = $userModel->login($email, $password);

            if ($user) {
                if ($user['role'] == 'admin' || $user['role'] == 'staff') {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['full_name'];
                    $_SESSION['user_role'] = $user['role'];

                    header('Location: ' . BASE_URL . 'admin/dashboard');
                } else {
                    $error = "Bạn không có quyền truy cập Admin!";
                    $this->view('admin/auth/login', ['error' => $error]);
                }
            } else {
                $error = "Email hoặc mật khẩu không đúng!";
                $this->view('admin/auth/login', ['error' => $error]);
            }
        }
    }

    public function logout() {
        session_destroy();
        header('Location: ' . BASE_URL . 'admin/auth/login');
    }
}
?>