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
            // Lọc sản phẩm theo các tham số
            'products' => $prodModel->filterProducts($filters),
            'categories' => $catModel->getAll(),
            'brands' => $brandModel->getAll(),
            'current_filters' => $filters // Gửi lại bộ lọc hiện tại cho View
        ];
        
        $this->view('client/products/index', $data);
    }
    public function detail($id) {
        $model = $this->model('ProductModel');
        $product = $model->getById($id);
        $variants = $model->getVariants($id);

        // Kiểm tra nếu sản phẩm không tồn tại
        if (!$product) {
            // Chuyển hướng hoặc báo lỗi
            header('Location: ' . BASE_URL); 
            exit;
        }

        $data = [
            'title' => $product['name'],
            'product' => $product,
            'variants' => $variants
        ];
        $this->view('client/products/detail', $data);
    }

    // --- HÀM ĐÃ SỬA ---
    public function checkStock() {
        // 1. Chỉ nhận method POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // 2. Đặt header JSON để trình duyệt hiểu
            header('Content-Type: application/json');

            // 3. Lấy dữ liệu gửi từ Fetch JS
            $input = json_decode(file_get_contents('php://input'), true);
            $variantId = $input['variant_id'] ?? 0;

            if ($variantId) {
                // 4. Gọi Model để lấy dữ liệu (An toàn hơn query trực tiếp)
                $model = $this->model('ProductModel');
                $stock = $model->getVariantStock($variantId);
                
                // 5. Trả về JSON
                echo json_encode(['success' => true, 'stock' => $stock]);
            } else {
                echo json_encode(['success' => false, 'stock' => 0]);
            }
            exit;
        }
    }
}
?>