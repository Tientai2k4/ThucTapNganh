<?php
namespace App\Controllers\Admin;
use App\Core\Controller;

class DashboardController extends Controller {
    public function index() {
        // Kiểm tra đăng nhập (Middleware đơn giản)
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
            header('Location: ' . BASE_URL . 'admin/auth/login');
            exit;
        }

        $data = ['title' => 'Trang chủ Admin'];
        $this->view('admin/dashboard/index', $data);
    }
}
?>