<?php
namespace App\Controllers\Client;
use App\Core\Controller;

class PageController extends Controller {

    // Trang Hướng dẫn mua hàng
    public function buying_guide() {
        $this->view('client/pages/buying_guide', ['title' => 'Hướng dẫn mua hàng']);
    }

    // Trang Chính sách đổi trả
    public function return_policy() {
        $this->view('client/pages/return_policy', ['title' => 'Chính sách đổi trả']);
    }

    // Trang Chính sách vận chuyển
    public function shipping_policy() {
        $this->view('client/pages/shipping_policy', ['title' => 'Chính sách vận chuyển']);
    }

    // Trang Chính sách bảo mật
    public function privacy_policy() {
        $this->view('client/pages/privacy_policy', ['title' => 'Chính sách bảo mật']);
    }
}