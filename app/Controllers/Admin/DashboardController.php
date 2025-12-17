<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class DashboardController extends Controller {
    public function index() {
        // Chỉ Admin mới được xem thống kê doanh thu
        AuthMiddleware::onlyAdmin();

        $model = $this->model('ReportModel');
        
        $data = [
            'title'         => 'Bảng điều khiển quản trị',
            'counters'      => $model->getCounters(),
            'revenue'       => $model->getRevenueByDate(),
            'top_products'  => $model->getTopProducts(),
            'low_stock'     => $model->getLowStockProducts(),
            'top_customers' => $model->getTopCustomers()
        ];
        
        $this->view('admin/dashboard/index', $data);
    }
}