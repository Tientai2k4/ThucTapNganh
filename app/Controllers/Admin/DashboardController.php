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

    public function index() {
        $dateFilter = $_GET['date'] ?? date('Y-m-d');

        $data = [
            'counters'        => $this->model->getCounters($dateFilter),
            
            'recent_orders'   => $this->model->getRecentOrders(),
            'recent_contacts' => $this->model->getRecentContacts(),
            'low_stock'       => $this->model->getLowStockLimit(),
            'top_customers'   => $this->model->getTopCustomersLimit(),
            'top_products'    => $this->model->getTopSellingProducts(5),
            
            // [MỚI - QUAN TRỌNG] Lấy danh sách đơn hủy để hiển thị bảng
            'recent_cancelled_orders' => $this->model->getRecentCancelledOrders(),
            
            // Thống kê (số liệu ô đỏ)
            'cancelled_stats' => $this->model->getCancelledOrderStats($dateFilter),
            
            'filter_date'     => $dateFilter 
        ];
        $this->view('admin/dashboard/index', $data);
    }

    // Các hàm view khác giữ nguyên
    public function view_low_stock() {
        $data['products'] = $this->model->getAllLowStock();
        $this->view('admin/dashboard/detail_low_stock', $data);
    }

    public function view_customers() {
        $data['customers'] = $this->model->getAllTopCustomers();
        $this->view('admin/dashboard/detail_customers', $data);
    }

    public function view_revenue() {
        $orderModel = $this->model('OrderModel');
        $type = $_GET['type'] ?? 'date'; 
        $from = $_GET['from'] ?? null;   
        $to = $_GET['to'] ?? null;      

        $typeTexts = [
            'date' => 'Từng Ngày',
            'month' => 'Từng Tháng',
            'year' => 'Từng Năm'
        ];

        $data = [
            'revenue'        => $orderModel->getRevenueReport($type, $from, $to),
            'view_type'      => $type,
            'view_type_text' => $typeTexts[$type] ?? 'Từng Ngày'
        ];

        $this->view('admin/dashboard/detail_revenue', $data);
    }
}