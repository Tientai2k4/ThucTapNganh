<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class UserController extends Controller {
    
    public function __construct() {
        // Chỉ Admin tối cao mới được vào đây
        AuthMiddleware::onlyAdmin();
    }

    // 1. Hiển thị danh sách
    public function index() {
        $model = $this->model('UserModel');
        $filters = [
            'keyword' => $_GET['keyword'] ?? '', // Tìm theo tên, email, sđt
            'role'    => $_GET['role'] ?? ''     // Tìm theo vai trò
        ];
        $users = $model->getAllUsers($filters);
        $this->view('admin/users/index', [
            'users'   => $users,
            'filters' => $filters 
        ]);
    }

    // 2. Hiển thị form sửa (Edit)
    public function edit($id) {
        $model = $this->model('UserModel');
        $user = $model->findById($id);

        if (!$user) {
            echo "Người dùng không tồn tại.";
            return;
        }

        $this->view('admin/users/edit', ['user' => $user]);
    }

    // 3. Xử lý cập nhật (Update)
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['user_id'];
            $role = $_POST['role'];
            $status = $_POST['status']; // 1: Hoạt động, 0: Khóa

            $model = $this->model('UserModel');
            
            // Không cho phép tự hạ quyền hoặc khóa chính mình
            if ($id == $_SESSION['user_id']) {
                echo "<script>alert('Bạn không thể tự thay đổi quyền của chính mình!'); window.history.back();</script>";
                return;
            }

            if ($model->updateUserStatusAndRole($id, $role, $status)) {
                header('Location: ' . BASE_URL . 'admin/user');
            } else {
                echo "Lỗi cập nhật.";
            }
        }
    }

    // 4. Xử lý xóa vĩnh viễn (Delete)
    public function delete($id) {
        if ($id == $_SESSION['user_id']) {
            echo "<script>alert('Không thể tự xóa tài khoản đang đăng nhập!'); window.location.href='" . BASE_URL . "admin/user';</script>";
            return;
        }

        $model = $this->model('UserModel');
        if ($model->deleteUser($id)) {
            header('Location: ' . BASE_URL . 'admin/user');
        } else {
            echo "<script>alert('Lỗi: Người dùng này có thể đang có đơn hàng liên kết. Hãy xóa đơn hàng trước!'); window.location.href='" . BASE_URL . "admin/user';</script>";
        }
    }
}
?>