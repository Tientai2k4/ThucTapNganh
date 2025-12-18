<?php
namespace App\Controllers\Client;
use App\Core\Controller;

class HomeController extends Controller {
    public function index() {
        
        // 1. Danh mục
        $catModel = $this->model('CategoryModel');
        $categories = $catModel->getAll();

        // 2. Sản phẩm nổi bật (Lấy 9 sản phẩm)
        $prodModel = $this->model('ProductModel');
        $products = $prodModel->getHomeProducts(9); 

        // 3. Slider
        $sliderModel = $this->model('SliderModel');
        $sliders = $sliderModel->getAll(); 
        $activeSliders = array_filter($sliders, function($s) {
            return $s['status'] == 1;
        });

        // 4. Thương hiệu
        $brandModel = $this->model('BrandModel');
        $brands = $brandModel->getAll(); 

        // 5. Tin tức (Lấy 4 tin)
        $postModel = $this->model('PostModel');
        $posts = $postModel->getLatestPosts(4); 

        // 6. [MỚI] Mã giảm giá (Lấy 6 mã)
        // Lưu ý: Bạn cần chắc chắn file CouponModel.php đã có trong thư mục Models
        $couponModel = $this->model('CouponModel');
        $coupons = $couponModel->getAvailableCoupons(6);

        $data = [
            'title'      => 'Trang chủ - Swimming Store',
            'categories' => $categories, 
            'products'   => $products,
            'sliders'    => $activeSliders,
            'brands'     => $brands,
            'posts'      => $posts,
            'coupons'    => $coupons // Truyền sang View
        ];
        
        $this->view('client/home/index', $data);
    }
}
?>