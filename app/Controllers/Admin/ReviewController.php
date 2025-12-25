<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class ReviewController extends Controller {
    public function __construct() {
        AuthMiddleware::isStaffArea(); 
    }

    public function index() {
        $model = $this->model('ReviewModel');
        $data = ['reviews' => $model->getAllReviews()];
        $this->view('admin/reviews/index', $data);
    }

    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['review_id'];
            $status = $_POST['status'];
            $this->model('ReviewModel')->updateStatus($id, $status);
            header('Location: ' . BASE_URL . 'admin/review');
            exit;
        }
    }

    // [CHUẨN HÓA] Hàm trả lời
    public function reply($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $replyContent = trim($_POST['reply_content'] ?? '');
            if (!empty($replyContent)) {
                $this->model('ReviewModel')->replyReview($id, $replyContent);
            }
            header('Location: ' . BASE_URL . 'admin/review');
            exit;
        }
    }
    
    public function delete($id) {
        AuthMiddleware::onlyAdmin(); 
        $this->model('ReviewModel')->delete($id);
        header('Location: ' . BASE_URL . 'admin/review');
        exit;
    }
}
?>