<?php
namespace App\Controllers\Client;
use App\Core\Controller;

class HomeController extends Controller {
    public function index() {
        // Ngày 1: Chưa có DB, hiển thị View tĩnh trước
        $data = ['title' => 'Trang chủ - Swimming Store'];
        $this->view('client/home/index', $data);
    }
}
?>