<?php
namespace App\Controllers\Staff;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class ReviewController extends Controller {
    public function __construct() {
        AuthMiddleware::hasRole(['staff']);
    }

    public function index() {
        $model = $this->model('ReviewModel');
        $reviews = $model->getAllReviews();

        // Truyền prefix staff
        $this->view('admin/reviews/index', [
            'reviews' => $reviews,
            'role_prefix' => 'staff'
        ]);
    }

    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['review_id'];
            $status = $_POST['status'];
            
            $model = $this->model('ReviewModel');
            $model->updateStatus($id, $status);
            
            // [SỬA LỖI Ở ĐÂY]: Chuyển hướng về STAFF
            header('Location: ' . BASE_URL . 'staff/review');
            exit;
        }
    }
}