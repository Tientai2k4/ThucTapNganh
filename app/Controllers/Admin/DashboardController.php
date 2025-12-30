<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class DashboardController extends Controller {
    private $model;

    public function __construct() {
        AuthMiddleware::onlyAdmin();
        $this->model = $this->model('ReportModel');
    }

    // Trang chủ Dashboard
    public function index() {
        $data = [
            'counters'        => $this->model->getCounters(),
            'recent_orders'   => $this->model->getRecentOrders(),   // [MỚI]
            'recent_contacts' => $this->model->getRecentContacts(), // [MỚI]
            'low_stock'       => $this->model->getLowStockLimit(),
            'top_customers'   => $this->model->getTopCustomersLimit(),
            'top_products'    => $this->model->getTopSellingProducts(5),
            'cancelled_stats'  => $this->model->getCancelledOrderStats() 
        ];
        $this->view('admin/dashboard/index', $data);
    }

    // Các trang chi tiết giữ nguyên
    public function view_low_stock() {
        $data['products'] = $this->model->getAllLowStock();
        $this->view('admin/dashboard/detail_low_stock', $data);
    }

    public function view_customers() {
        $data['customers'] = $this->model->getAllTopCustomers();
        $this->view('admin/dashboard/detail_customers', $data);
    }

    public function view_revenue() {
    // 1. Gọi OrderModel thay vì ReportModel cho phần này
    $orderModel = $this->model('OrderModel');

    // 2. Lấy tham số lọc từ URL (Query String)
    $type = $_GET['type'] ?? 'date'; // Mặc định xem theo ngày
    $from = $_GET['from'] ?? null;   // Ngày bắt đầu
    $to = $_GET['to'] ?? null;       // Ngày kết thúc

    // 3. Tên tiêu đề tương ứng
    $typeTexts = [
        'date' => 'Từng Ngày',
        'month' => 'Từng Tháng',
        'year' => 'Từng Năm'
    ];

    // 4. Lấy dữ liệu linh hoạt từ hàm mới trong OrderModel
    $data = [
        'revenue'        => $orderModel->getRevenueReport($type, $from, $to),
        'view_type'      => $type,
        'view_type_text' => $typeTexts[$type] ?? 'Từng Ngày'
    ];

    // 5. Trả về View hiện tại của bạn
    $this->view('admin/dashboard/detail_revenue', $data);
  }
    
}