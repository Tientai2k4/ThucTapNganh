<?php
namespace App\Controllers\Client;
use App\Core\Controller;

class HomeController extends Controller {
    public function index() {
       // 1. Lấy danh mục từ DB để hiển thị lên Sidebar
        $catModel = $this->model('CategoryModel');
        $categories = $catModel->getAll();

        $data = [
            'title' => 'Trang chủ - Swimming Store',
            'categories' => $categories // Truyền biến này ra View
        ];
        
        $this->view('client/home/index', $data);
    }
}
?>