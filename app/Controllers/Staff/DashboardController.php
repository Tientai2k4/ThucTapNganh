<?php
namespace App\Controllers\Staff;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class DashboardController extends Controller {
    public function __construct() {
        AuthMiddleware::isStaffArea(); // Admin và Staff vào được
    }

    public function index() {
        $orderModel = $this->model('OrderModel');
        $reviewModel = $this->model('ReviewModel');

        $pendingOrders = $orderModel->getOrdersByStatus('pending');
        $reviews = $reviewModel->getAllReviews(); 
        
        $pendingReviewsCount = !empty($reviews) ? count(array_filter($reviews, fn($r) => $r['status'] == 0)) : 0;

        $data = [
            'title' => 'Bàn làm việc Nhân viên',
            'stats' => [
                'pending_orders' => count($pendingOrders),
                'pending_reviews' => $pendingReviewsCount,
                'unread_contacts' => 0 
            ],
            'recent_orders' => array_slice($pendingOrders, 0, 5)
        ];
        $this->view('staff/dashboard/index', $data); // Load view staff
    }
}