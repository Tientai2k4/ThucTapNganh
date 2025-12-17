<?php
namespace App\Controllers\Staff;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class DashboardController extends Controller {
    public function __construct() {
        AuthMiddleware::isStaff(); // Bảo vệ route
    }

    public function index() {
        // Staff chỉ xem các thống kê đơn giản như đơn hàng chờ xử lý
        $orderModel = $this->model('OrderModel');
        $data = [
            'title' => 'Khu vực làm việc Nhân viên',
            'pending_orders' => count($orderModel->getOrdersByStatus('pending')),
            'new_contacts' => 0 // Có thể lấy từ ContactModel
        ];
        $this->view('staff/dashboard/index', $data);
    }
}