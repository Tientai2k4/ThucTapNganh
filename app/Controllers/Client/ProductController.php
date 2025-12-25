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
        $limit = 12; 
        $offset = ($page - 1) * $limit;

        // 2. NHẬN DỮ LIỆU TỪ GET (Đồng bộ tên biến với View)
        $filters = [
            // Thay đổi quan trọng: dùng 'category_id' thay vì 'cat' để khớp với form
            'category_id' => $_GET['category_id'] ?? null, 
            'brands'      => $_GET['brand'] ?? [],
            'sizes'       => $_GET['size'] ?? [],
            'price_min'   => $_GET['min'] ?? 0,
            'price_max'   => $_GET['max'] ?? 5000000,
            'keyword'     => $_GET['keyword'] ?? '',
            'sort'        => $_GET['sort'] ?? 'newest',
            'limit'       => $limit,
            'offset'      => $offset
        ];

        if (!is_array($filters['brands'])) $filters['brands'] = [];
        if (!is_array($filters['sizes'])) $filters['sizes'] = [];

        // 3. LẤY DỮ LIỆU
        $products = $prodModel->filterProducts($filters);
        $totalProducts = $prodModel->countFilterProducts($filters);
        $totalPages = ceil($totalProducts / $limit);

        // Giữ lại URL query string hiện tại để dùng cho phân trang
        // Loại bỏ page cũ để tạo link page mới
        $queryParams = $_GET;
        unset($queryParams['url']); // Loại bỏ tham số route của MVC nếu có
        unset($queryParams['page']);
        $queryString = http_build_query($queryParams);

        $data = [
            'products' => $products,
            'categories' => $catModel->getAll(),
            'brands' => $brandModel->getAll(),
            'filters' => $filters,
            'query_string' => $queryString, // Truyền chuỗi query sang view
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_products' => $totalProducts
            ]
        ];
        
        $this->view('client/products/index', $data);
    }
public function detail($id) {
        $prodModel = $this->model('ProductModel');
        $product = $prodModel->getById($id);
        
        if (!$product) {
            header('Location: ' . BASE_URL); 
            exit;
        }

        // Lấy dữ liệu cơ bản
        $variants = $prodModel->getVariants($id);
        $gallery = $prodModel->getGalleryImages($id);
        $related = $prodModel->getRelatedProducts($product['category_id'], $id, 4);

        // Wishlist
        $isWished = false;
        if (isset($_SESSION['user_id'])) {
            $isWished = $prodModel->isInWishlist($_SESSION['user_id'], $id); 
        }

        // --- XỬ LÝ LOGIC ĐÁNH GIÁ (REVIEW) ---
        $reviewModel = $this->model('ReviewModel');
        $reviews = $reviewModel->getApprovedByProductId($id);
        $ratingSummary = $reviewModel->getRatingSummary($id);

        // Mặc định: Không được đánh giá
        $canReview = false;
        $reviewMessage = "Vui lòng <a href='".BASE_URL."auth/login'>đăng nhập</a> để đánh giá sản phẩm.";

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            
            // Bước 1: Kiểm tra lịch sử mua hàng (Đã nhận + Đã thanh toán)
            $isPurchased = $reviewModel->checkPurchaseEligibility($userId, $id);
            
            // Bước 2: Kiểm tra đã đánh giá trước đó chưa
            $hasReviewed = $reviewModel->hasReviewed($userId, $id);

            if ($isPurchased && !$hasReviewed) {
                // Đủ điều kiện
                $canReview = true;
                $reviewMessage = "";
            } elseif ($hasReviewed) {
                // Đã mua nhưng đánh giá rồi
                $reviewMessage = "Bạn đã đánh giá sản phẩm này rồi.";
            } else {
                // Chưa mua hoặc đơn chưa hoàn thành/chưa thanh toán
                $reviewMessage = "Bạn chỉ có thể đánh giá sau khi mua, nhận hàng và thanh toán thành công sản phẩm này.";
            }
        }

        $data = [
            'title' => $product['name'],
            'product' => $product,
            'variants' => $variants,
            'gallery' => $gallery,
            'related' => $related,
            'reviews' => $reviews,
            'rating_summary' => $ratingSummary,
            'is_wished' => $isWished,
            // Truyền 2 biến quan trọng này sang View
            'can_review' => $canReview,       
            'review_message' => $reviewMessage
        ];
        
        $this->view('client/products/detail', $data);
    }

    public function postReview() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productId = $_POST['product_id'];
            
            // 1. Kiểm tra đăng nhập
            $userId = $_SESSION['user_id'] ?? ($_SESSION['user']['id'] ?? null);

            if (!$userId) {
                $_SESSION['redirect_url'] = BASE_URL . 'product/detail/' . $productId;
                header('Location: ' . BASE_URL . 'auth/login');
                exit;
            }

            // 2. KIỂM TRA BẢO MẬT (Chặn phá hoại qua API/Postman)
            $reviewModel = $this->model('ReviewModel');
            
            $isPurchased = $reviewModel->checkPurchaseEligibility($userId, $productId);
            $hasReviewed = $reviewModel->hasReviewed($userId, $productId);

            if (!$isPurchased) {
                echo "<script>alert('LỖI: Bạn chưa đủ điều kiện đánh giá (Chưa mua/Chưa nhận hàng/Chưa thanh toán)!'); window.location.href='".BASE_URL."product/detail/$productId';</script>";
                exit;
            }

            if ($hasReviewed) {
                echo "<script>alert('LỖI: Bạn đã đánh giá sản phẩm này rồi!'); window.location.href='".BASE_URL."product/detail/$productId';</script>";
                exit;
            }

            // 3. Dữ liệu hợp lệ -> Lưu vào DB
            $data = [
                'product_id' => $productId,
                'user_id'    => $userId, 
                'rating'     => (int)$_POST['rating'],
                'comment'    => htmlspecialchars($_POST['comment']) 
            ];

            $reviewModel->create($data);
            
            echo "<script>alert('Cảm ơn bạn! Đánh giá đã được gửi và đang chờ duyệt.'); window.location.href='".BASE_URL."product/detail/$productId#reviews-section';</script>";
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