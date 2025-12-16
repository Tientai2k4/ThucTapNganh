<?php
// app/Controllers/Client/WishlistController.php
namespace App\Controllers\Client;
use App\Core\Controller;

class WishlistController extends Controller {

    // Xem danh sách yêu thích
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'client/auth/login'); exit;
        }

        $userId = $_SESSION['user_id'];
        $model = $this->model('ProductModel'); 
        $products = $model->getWishlistByUser($userId);
        
        $this->view('client/user/wishlist', ['products' => $products, 'title' => 'Sản phẩm yêu thích']);
    }

    // Thêm/Xóa yêu thích (Toggle) - Dùng AJAX
    public function toggle() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => false, 'message' => 'Vui lòng đăng nhập']); return;
        }
        
        // Lấy dữ liệu từ AJAX
        $data = json_decode(file_get_contents("php://input"), true);
        $productId = $data['product_id'] ?? 0;
        $userId = $_SESSION['user_id'];
        
        $model = $this->model('ProductModel');
        $action = $model->toggleWishlist($userId, $productId); // added hoặc removed
        
        echo json_encode(['status' => true, 'action' => $action]);
    }
}
?>