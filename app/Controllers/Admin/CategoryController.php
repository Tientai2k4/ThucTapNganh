<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class CategoryController extends Controller {
    public function __construct() {
        AuthMiddleware::onlyAdmin(); 
    }

    public function index() {
        $model = $this->model('CategoryModel');

        // Lấy tham số tìm kiếm từ URL
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : null;
        $parentId = isset($_GET['parent_id']) ? $_GET['parent_id'] : null;

        // Lấy danh sách đã lọc
        $categories = $model->getAll($keyword, $parentId);
        
        // Lấy danh sách danh mục gốc để hiển thị vào Select Option lọc
        $rootCategories = $model->getRootCategories();

        $this->view('admin/categories/index', [
            'categories' => $categories,
            'root_categories' => $rootCategories, // Truyền thêm biến này
            'filters' => ['keyword' => $keyword, 'parent_id' => $parentId] // Giữ lại trạng thái lọc
        ]);
    }

    public function create() {
        $model = $this->model('CategoryModel');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name']);
            $desc = trim($_POST['description']);
            $parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;
            
            $model->create($name, $desc, $parent_id);
            header('Location: ' . BASE_URL . 'admin/category');
            exit;
        } else {
            // Lấy toàn bộ danh mục để chọn cha
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