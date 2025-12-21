<?php
namespace App\Controllers\Client;
use App\Core\Controller;

class ProductController extends Controller {
    
   public function index() {
        $prodModel = $this->model('ProductModel');
        $catModel = $this->model('CategoryModel');
        $brandModel = $this->model('BrandModel'); 

        // 1. CẤU HÌNH PHÂN TRANG
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $limit = 12; // Hiển thị 12 sản phẩm/trang
        $offset = ($page - 1) * $limit;

        // 2. NHẬN THAM SỐ LỌC
        $filters = [
            'category_id' => $_GET['cat'] ?? null,
            'brands'      => $_GET['brand'] ?? [],
            'sizes'       => $_GET['size'] ?? [],
            'target'      => $_GET['target'] ?? null,
            'price_min'   => $_GET['min'] ?? 0,
            'price_max'   => $_GET['max'] ?? 5000000,
            'keyword'     => $_GET['keyword'] ?? '',
            'limit'       => $limit,   // Truyền limit xuống Model
            'offset'      => $offset   // Truyền offset xuống Model
        ];

        if (!is_array($filters['brands'])) $filters['brands'] = [];
        if (!is_array($filters['sizes'])) $filters['sizes'] = [];

        // 3. LẤY DỮ LIỆU
        $products = $prodModel->filterProducts($filters);
        $totalProducts = $prodModel->countFilterProducts($filters); // Hàm đếm tổng để tính số trang
        $totalPages = ceil($totalProducts / $limit);

        $data = [
            'products' => $products,
            'categories' => $catModel->getAll(),
            'brands' => $brandModel->getAll(),
            'filters' => $filters,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_products' => $totalProducts
            ]
        ];
        
        $this->view('client/products/index', $data);
    }

   public function detail($id) {
        // 1. Lấy sản phẩm chính
        $model = $this->model('ProductModel');
        $product = $model->getById($id);
        
        // Kiểm tra nếu sản phẩm không tồn tại
        if (!$product) {
            header('Location: ' . BASE_URL); 
            exit;
        }

        // 2. Lấy các dữ liệu phụ trợ (Đây là phần BỔ SUNG để hiện ảnh con và sp liên quan)
        $variants = $model->getVariants($id);
        $gallery = $model->getGalleryImages($id); // [QUAN TRỌNG] Lấy ảnh album
        
        // Lấy sản phẩm liên quan (cùng danh mục, trừ sản phẩm hiện tại)
        $related = $model->getRelatedProducts($product['category_id'], $id, 4);

        $isWished = false;
        if (isset($_SESSION['user_id'])) {
            // Kiểm tra xem user này đã thích sản phẩm này chưa
            $isWished = $model->isInWishlist($_SESSION['user_id'], $id); 
        }

        // 3. Gọi ReviewModel lấy đánh giá
        $reviewModel = $this->model('ReviewModel');
        $reviews = $reviewModel->getApprovedByProductId($id);

        $data = [
            'title' => $product['name'],
            'product' => $product,
            'variants' => $variants,
            'gallery' => $gallery, // Truyền biến gallery sang View
            'related' => $related, // Truyền biến related sang View
            'reviews' => $reviews,
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

    // API Kiểm tra tồn kho
    public function checkStock() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            $input = json_decode(file_get_contents('php://input'), true);
            $variantId = $input['variant_id'] ?? 0;

            if ($variantId) {
                $model = $this->model('ProductModel');
                $stock = $model->getVariantStock($variantId);
                echo json_encode(['success' => true, 'stock' => $stock]);
            } else {
                echo json_encode(['success' => false, 'stock' => 0]);
            }
            exit;
        }
    }

    // API Xử lý Live Search
    public function liveSearch() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $keyword = $_GET['keyword'] ?? '';
            
            if (mb_strlen($keyword) > 1) {
                $model = $this->model('ProductModel');
                $products = $model->searchByName($keyword, 5);
                
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'data' => $products
                ]);
            } else {
                echo json_encode(['success' => false, 'data' => []]);
            }
            exit;
        }
    }
}
?>