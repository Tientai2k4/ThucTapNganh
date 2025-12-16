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
            'category_id' => $_GET['cat'] ?? null,       // Danh mục (radio)
            'brands'      => $_GET['brand'] ?? [],       // Thương hiệu (checkbox array)
            'sizes'       => $_GET['size'] ?? [],        // Kích thước (checkbox array)
            'price_min'   => $_GET['min'] ?? 0,
            'price_max'   => $_GET['max'] ?? 5000000,
            'keyword'     => $_GET['keyword'] ?? ''
            

        ];
        // Đảm bảo brand/size là mảng khi không chọn gì (để tránh lỗi)
        if (!is_array($filters['brands'])) $filters['brands'] = [];
        if (!is_array($filters['sizes'])) $filters['sizes'] = [];
        $data = [
            // Lọc sản phẩm theo các tham số
            'products' => $prodModel->filterProducts($filters),
            'categories' => $catModel->getAll(),
            'brands' => $brandModel->getAll(),
            'filters' => $filters // Gửi lại bộ lọc hiện tại cho View
        ];
        
        $this->view('client/products/index', $data);
    }

   public function detail($id) {
        // 1. Lấy sản phẩm
        $model = $this->model('ProductModel');
        $product = $model->getById($id);
        $variants = $model->getVariants($id);

        $isWished = false;
    
        if (isset($_SESSION['user_id'])) {
            // Kiểm tra xem user này đã thích sản phẩm này chưa
            $isWished = $model->isInWishlist($_SESSION['user_id'], $id); 
        }
        // Kiểm tra nếu sản phẩm không tồn tại
        if (!$product) {
            header('Location: ' . BASE_URL); 
            exit;
        }

        // 2. [QUAN TRỌNG] Gọi ReviewModel nằm TRONG hàm này
        $reviewModel = $this->model('ReviewModel');
        $reviews = $reviewModel->getApprovedByProductId($id);

        $data = [
            'title' => $product['name'],
            'product' => $product,
            'variants' => $variants,
            'reviews' => $reviews, // Truyền biến reviews sang View
            'is_wished' => $isWished
        ];
        
        $this->view('client/products/detail', $data);
    }
    //  HÀM XỬ LÝ GỬI ĐÁNH GIÁ TỪ FORM 
    public function postReview() {
        // Đảm bảo session đã bật
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productId = $_POST['product_id'];
            
            // --- [SỬA ĐOẠN NÀY] ---
            // Dựa vào ảnh bạn gửi: $_SESSION['user_id']
            $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;

            // Nếu code trên vẫn không nhận, thử các trường hợp dự phòng:
            if (!$userId && isset($_SESSION['user']['id'])) {
                $userId = $_SESSION['user']['id'];
            }

            $data = [
                'product_id' => $productId,
                'user_id'    => $userId, 
                'rating'     => $_POST['rating'],
                'comment'    => htmlspecialchars($_POST['comment']) 
            ];

            $reviewModel = $this->model('ReviewModel');
            $reviewModel->create($data);

            header('Location: ' . BASE_URL . 'product/detail/' . $productId);
            exit;
        }
    }

    // 
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