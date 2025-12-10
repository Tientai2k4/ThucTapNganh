<?php
namespace App\Controllers\Client;
use App\Core\Controller;

class AboutController extends Controller {
    public function index() {
        $data = ['title' => 'Giới thiệu - Swimming Store'];
        $this->view('client/about/index', $data);
    }
}
?>