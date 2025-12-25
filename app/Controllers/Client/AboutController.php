<?php
namespace App\Controllers\Client;
use App\Core\Controller;

class AboutController extends Controller {
    public function index() {
        $data = ['title' => 'Giới thiệu - Thế Giới Bơi Lội'];
        $this->view('client/about/index', $data);
    }
}
?>