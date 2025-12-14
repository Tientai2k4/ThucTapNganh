<?php
namespace App\Controllers\Admin;
use App\Core\Controller;

class DashboardController extends Controller {
   public function index() {
        $model = $this->model('ReportModel');
        
        $data = [
            'revenue' => $model->getRevenueByDate(),
            'top_products' => $model->getTopProducts()
        ];
        
        $this->view('admin/dashboard/index', $data);
    }
}
?>