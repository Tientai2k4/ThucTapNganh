<?php
namespace App\Controllers\Client;
use App\Core\Controller;

class ProductController extends Controller {
    public function index() {
        $prodModel = $this->model('ProductModel');
        $catModel = $this->model('CategoryModel');
        $brandModel = $this->model('BrandModel'); 

        // Nhận tham số từ URL
        $filters = [
            'category' => $_GET['category'] ?? null,
            'brand' => $_GET['brand'] ?? null,
            'price_range' => $_GET['price'] ?? null
        ];

        $data = [
            'products' => $prodModel->filterProducts($filters),
            'categories' => $catModel->getAll(),
            'brands' => $brandModel->getAll()
        ];
        
        $this->view('client/products/index', $data);
    }
    
    // Hàm detail sẽ làm ở Ngày 6
}
?>