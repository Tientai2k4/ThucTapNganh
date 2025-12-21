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
            'top_customers'   => $this->model->getTopCustomersLimit()
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
        $data['revenue'] = $this->model->getRevenueDetail();
        $this->view('admin/dashboard/detail_revenue', $data);
    }
}