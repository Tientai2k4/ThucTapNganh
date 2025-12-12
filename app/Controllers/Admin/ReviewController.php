<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class ReviewController extends Controller {
    public function __construct() {
        AuthMiddleware::isAdmin(); // Chỉ Admin mới được vào
    }

    public function index() {
        $model = $this->model('ReviewModel');
        $reviews = $model->getAllReviews();
        $data = [
            'reviews' => $reviews // Gói biến vào mảng data
        ];
        $this->view('admin/reviews/index', $data);
    }

    // Duyệt (Hiện) hoặc Ẩn đánh giá
    public function toggleStatus($id, $status) {
        $model = $this->model('ReviewModel');
        $model->updateStatus($id, $status);
        header('Location: ' . BASE_URL . 'admin/review');
        exit;
    }

    // Xử lý form trả lời đánh giá
    public function reply($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $replyContent = $_POST['reply_content'] ?? '';
            if (!empty($replyContent)) {
                $model = $this->model('ReviewModel');
                $model->replyReview($id, $replyContent);
            }
            header('Location: ' . BASE_URL . 'admin/review');
            exit;
        }
    }
    
    // Xóa đánh giá
    public function delete($id) {
        $model = $this->model('ReviewModel');
        $model->delete($id);
        header('Location: ' . BASE_URL . 'admin/review');
        exit;
    }
}
?>