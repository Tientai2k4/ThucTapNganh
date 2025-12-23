<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class CategoryController extends Controller {
    public function __construct() {
        // Kiểm tra quyền Admin
        AuthMiddleware::onlyAdmin(); 
    }

    public function index() {
        $model = $this->model('CategoryModel');
        $categories = $model->getAll();
        $this->view('admin/categories/index', ['categories' => $categories]);
    }

    public function create() {
        $model = $this->model('CategoryModel');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name']);
            $desc = trim($_POST['description']);
            // Nếu chọn "Là danh mục gốc" (value rỗng) thì set là NULL
            $parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;
            
            $model->create($name, $desc, $parent_id);
            header('Location: ' . BASE_URL . 'admin/category');
            exit;
        } else {
            // Lấy toàn bộ danh mục để lọc ở View
            $categories = $model->getAll();
            $this->view('admin/categories/create', ['categories' => $categories]);
        }
    }
    
    public function edit($id) {
        $model = $this->model('CategoryModel');
        $category = $model->getById($id); 
        $categories = $model->getAll(); 

        if (!$category) {
            header('Location: ' . BASE_URL . 'admin/category');
            exit;
        }

        $this->view('admin/categories/edit', [
            'category' => $category,
            'categories' => $categories
        ]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name']);
            $desc = trim($_POST['description']);
            $parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;
            
            // Validate: Không được chọn chính nó làm cha
            if ($parent_id == $id) {
                $parent_id = null; 
            }

            $model = $this->model('CategoryModel');
            $model->update($id, $name, $desc, $parent_id);
            header('Location: ' . BASE_URL . 'admin/category');
            exit;
        } else {
             header('Location: ' . BASE_URL . 'admin/category/edit/' . $id);
             exit;
        }
    }

    public function delete($id) {
        $model = $this->model('CategoryModel');
        $model->delete($id);
        header('Location: ' . BASE_URL . 'admin/category');
        exit;
    }
}
?>