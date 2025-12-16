<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware; // Nhúng middleware

class UserController extends Controller {
    public function __construct() {
        // [BẢO VỆ CẤP CAO] Quản lý user, đặc biệt là phân quyền, chỉ nên dành cho Admin cấp cao
        AuthMiddleware::onlyAdmin();
    }

    public function index() {
        $model = $this->model('UserModel');
        // Giả sử có hàm getAllUsers()
        $users = $model->getAllUsers(); 
        $this->view('admin/users/index', ['users' => $users]);
    }
    
    // [Nên thêm] Hàm edit/update user role
    // public function updateRole($id) {
    //     AuthMiddleware::onlyAdmin(); 
    //     // ... logic update role ...
    // }
}