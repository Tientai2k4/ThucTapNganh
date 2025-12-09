<?php
namespace App\Controllers\Admin;
use App\Core\Controller;

class DashboardController extends Controller {
    public function index() {
        // Tạm thời chưa check login để test layout trước
        $data = ['title' => 'Tổng quan hệ thống'];
        $this->view('admin/dashboard/index', $data);
    }
}
?>