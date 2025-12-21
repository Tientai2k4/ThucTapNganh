<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class ReviewController extends Controller {
   public function __construct() {
        // Sử dụng phương thức đã định nghĩa ở Middleware mới để cho phép cả Admin và Staff
        AuthMiddleware::isStaffArea(); 
    }


    public function index() {
        $model = $this->model('ReviewModel');
        $reviews = $model->getAllReviews();
        $data = [
            'reviews' => $reviews 
        ];
        $this->view('admin/reviews/index', $data);
    }

    // Duyệt (Hiện) hoặc Ẩn đánh giá
    public function toggleStatus($id, $status) {
        // Chức năng này thường dành cho Admin/Staff
        AuthMiddleware::isStaffArea(); 

        $model = $this->model('ReviewModel');
        $model->updateStatus($id, (int)$status);
        header('Location: ' . BASE_URL . 'admin/review');
        exit;
    }

    // Xử lý form trả lời đánh giá
    public function reply($id) {
        // Chức năng này thường dành cho Admin/Staff
        AuthMiddleware::isStaffArea(); 
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $replyContent = trim($_POST['reply_content'] ?? '');
            
            if (!empty($replyContent)) {
                $model = $this->model('ReviewModel');
                $model->replyReview($id, $replyContent);
            }
            // [Tùy chọn] Nên dùng flash message để báo thành công
            header('Location: ' . BASE_URL . 'admin/review');
            exit;
        }
    }
    
    // Xóa đánh giá
    public function delete($id) {
        // [BẢO VỆ CẤP CAO] Chức năng xóa thường chỉ dành cho Admin cấp cao
        AuthMiddleware::onlyAdmin(); 
        
        $model = $this->model('ReviewModel');
        $model->delete($id);
        
        header('Location: ' . BASE_URL . 'admin/review');
        exit;
    }
}