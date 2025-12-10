<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware; // Nhúng middleware

class UserController extends Controller {
    public function __construct() {
        // Chặn ngay từ cửa
        AuthMiddleware::isAdmin();
    }

    public function index() {
        $model = $this->model('UserModel');
        $users = $model->getAllUsers();
        $this->view('admin/users/index', ['users' => $users]);
    }
}
?>