<?php
namespace App\Controllers\Staff;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class ReviewController extends Controller {
    public function __construct() {
        AuthMiddleware::isStaffArea();
    }

    public function index() {
        $model = $this->model('ReviewModel');
        $reviews = $model->getAllReviews();
        $this->view('staff/reviews/index', ['reviews' => $reviews]);
    }

    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['review_id'];
            $status = $_POST['status'];
            $this->model('ReviewModel')->updateStatus($id, $status);
            header('Location: ' . BASE_URL . 'staff/review');
            exit;
        }
    }
}