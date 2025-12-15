<?php
namespace App\Controllers\Client;
use App\Core\Controller;

class HomeController extends Controller {
    public function index() {
        
        // 1. Lấy danh mục
        $catModel = $this->model('CategoryModel');
        $categories = $catModel->getAll();

        // 2. Lấy sản phẩm nổi bật
        $prodModel = $this->model('ProductModel');
        $products = $prodModel->getHomeProducts(8); // Lấy 8 sản phẩm mới nhất

        // 3. LẤY DANH SÁCH SLIDER (PHẦN MỚI BỔ SUNG)
        // Cần tạo SliderModel nếu bạn chưa tạo trong thư mục Models/
        $sliderModel = $this->model('SliderModel');
        // Lấy tất cả slider đang hoạt động (Giả định Model có hàm getActiveSliders() 
        // hoặc dùng getAll() và lọc ở View, nhưng gọi riêng là tốt nhất)
        $sliders = $sliderModel->getAll(); 
        
        // Chỉ lấy những slider có status = 1 để hiển thị ra ngoài
        $activeSliders = array_filter($sliders, function($s) {
            return $s['status'] == 1;
        });


        $data = [
            'title' => 'Trang chủ - Swimming Store',
            'categories' => $categories, 
            'products' => $products,
            'sliders' => $activeSliders // TRUYỀN BIẾN SLIDER VÀO VIEW
        ];
        
        $this->view('client/home/index', $data);
    }
}
?>