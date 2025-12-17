<?php
namespace App\Controllers\Staff;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class DashboardController extends Controller {
    
    public function __construct() {
        AuthMiddleware::hasRole(['staff', 'admin']); // Cho phép Staff và Admin
    }

    public function index() {
        $orderModel = $this->model('OrderModel');
        $contactModel = $this->model('ContactModel'); 
        $reviewModel = $this->model('ReviewModel');

        // Lấy dữ liệu thống kê
        $pendingOrders = $orderModel->getOrdersByStatus('pending');
        
        // [SỬA LỖI TẠI ĐÂY] Đổi getAll() thành getAllReviews()
        $reviews = $reviewModel->getAllReviews(); 
        
        // Lọc đánh giá chưa duyệt (status = 0)
        $pendingReviewsCount = 0;
        if (!empty($reviews)) {
            $pendingReviewsCount = count(array_filter($reviews, function($r) { 
                return $r['status'] == 0; 
            }));
        }

        $data = [
            'title' => 'Khu vực làm việc Nhân viên',
            'stats' => [
                'pending_orders' => count($pendingOrders),
                'pending_reviews' => $pendingReviewsCount,
                // Giả sử contact chưa làm thì để 0
                'unread_contacts' => 0 
            ],
            // Lấy 5 đơn mới nhất để hiện bảng
            'recent_orders' => array_slice($pendingOrders, 0, 5)
        ];

        $this->view('staff/dashboard/index', $data);
    }
}