<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware;
class CategoryController extends Controller {
    public function __construct() {
        // [QUAN TRỌNG] Chỉ Admin tối cao mới được quản lý Danh mục
        // Content Staff KHÔNG ĐƯỢC PHÉP vào đây
        AuthMiddleware::onlyAdmin(); 
    }

    public function index() {
        $model = $this->model('CategoryModel');
        $categories = $model->getAll();
        $this->view('admin/categories/index', ['categories' => $categories]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $desc = $_POST['description'];
            
            $model = $this->model('CategoryModel');
            $model->create($name, $desc);
            header('Location: ' . BASE_URL . 'admin/category');
        } else {
            $this->view('admin/categories/create');
        }
    }
    
    // Phương thức mới: Hiển thị form chỉnh sửa
    public function edit($id) {
        $model = $this->model('CategoryModel');
        $category = $model->getById($id); // Lấy dữ liệu danh mục

        if (!$category) {
            // Xử lý khi không tìm thấy danh mục (ví dụ: chuyển hướng về trang index)
            header('Location: ' . BASE_URL . 'admin/category');
            return;
        }

        $this->view('admin/categories/edit', ['category' => $category]);
    }

    // Phương thức mới: Xử lý cập nhật dữ liệu
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $desc = $_POST['description'];
            
            $model = $this->model('CategoryModel');
            $model->update($id, $name, $desc);
            header('Location: ' . BASE_URL . 'admin/category');
        } else {
             // Nếu không phải POST, chuyển hướng về trang edit hoặc index
             header('Location: ' . BASE_URL . 'admin/category/edit/' . $id);
        }
    }

    public function delete($id) {
        $model = $this->model('CategoryModel');
        $model->delete($id);
        header('Location: ' . BASE_URL . 'admin/category');
    }
}
?>