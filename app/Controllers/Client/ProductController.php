<?php
namespace App\Controllers\Client;
use App\Core\Controller;

class ProductController extends Controller {

    /**
     * Hiển thị danh sách sản phẩm và Bộ lọc (Filter)
     * URL: BASE_URL/product
     */
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

    /**
     * Hiển thị chi tiết sản phẩm
     * URL: BASE_URL/product/detail/{id}
     */
    public function detail($id) {
        $model = $this->model('ProductModel');
        $product = $model->getById($id);
        
        if (!$product) {
            // Chuyển hướng về trang chủ nếu không tìm thấy sản phẩm
            header('Location: ' . BASE_URL); 
            exit;
        }
        
        // Lấy danh sách biến thể (Size/Màu)
        $variants = $model->getVariants($id);

        $data = [
            'title' => $product['name'],
            'product' => $product,
            'variants' => $variants
        ];
        $this->view('client/products/detail', $data);
    }

    /**
     * API: Trả về JSON số lượng tồn kho của biến thể
     * URL: BASE_URL/product/checkStock (POST)
     */
    public function checkStock() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Đặt header JSON để trình duyệt hiểu
            header('Content-Type: application/json');

            // Lấy dữ liệu gửi từ Fetch JS
            $input = json_decode(file_get_contents('php://input'), true);
            $variantId = $input['variant_id'] ?? 0;

            if ($variantId) {
                // Gọi Model để lấy dữ liệu tồn kho an toàn
                $model = $this->model('ProductModel');
                $stock = $model->getVariantStock($variantId);
                
                // Trả về JSON
                echo json_encode(['success' => true, 'stock' => $stock]);
            } else {
                echo json_encode(['success' => false, 'stock' => 0]);
            }
            exit;
        }
    }
}
?>