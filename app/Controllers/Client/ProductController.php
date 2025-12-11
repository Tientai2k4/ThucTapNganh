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
     public function detail($id) {
        $model = $this->model('ProductModel');
        $product = $model->getById($id);
        $variants = $model->getVariants($id);

        $data = [
            'title' => $product['name'],
            'product' => $product,
            'variants' => $variants
        ];
        $this->view('client/products/detail', $data);
    }

    // API trả về JSON số lượng tồn kho
    public function checkStock() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $variantId = $input['variant_id'];

            // Query DB lấy số lượng
            $conn = (new \App\Core\Database())->conn; // Kết nối nhanh hoặc gọi qua Model
            $sql = "SELECT stock_quantity FROM product_variants WHERE id = " . intval($variantId);
            $res = $conn->query($sql)->fetch_assoc();
            
            echo json_encode(['stock' => $res ? $res['stock_quantity'] : 0]);
            exit;
        }
    }
    
    
}
