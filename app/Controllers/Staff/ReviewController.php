<?php
namespace App\Controllers\Staff;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class ReviewController extends Controller {
    
    public function __construct() {
        AuthMiddleware::isCare(); 
    }

    public function index() {
        $model = $this->model('ReviewModel');
        $reviews = $model->getAllReviews();
        
        $this->view('staff/reviews/index', [
            'reviews' => $reviews
        ]);
    }

    // Ẩn/Hiện review
    public function toggleStatus($id, $status) {
        $this->model('ReviewModel')->updateStatus($id, (int)$status);
        header('Location: ' . BASE_URL . 'staff/review');
    }

    // Trả lời review
    public function reply($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $content = $_POST['reply_content'];
            $this->model('ReviewModel')->replyReview($id, $content);
            header('Location: ' . BASE_URL . 'staff/review?msg=replied');
        }
    }
}
?>