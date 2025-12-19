<?php
namespace App\Controllers\Staff;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class ProductController extends Controller {
    public function __construct() {
        AuthMiddleware::isStaffArea();
    }

    // Xem danh sách sản phẩm để kiểm kho
    public function index() {
        $model = $this->model('ProductModel');
        $products = $model->getAll();
        $this->view('staff/products/index', ['products' => $products]);
    }

    // Form chỉnh sửa nhanh (Giá/Tồn kho)
    public function edit($id) {
        $model = $this->model('ProductModel');
        $product = $model->getById($id);
        if (!$product) die("Sản phẩm không tồn tại");

        $data = [
            'product' => $product,
            'variants' => $model->getVariants($id),
            'categories' => $this->model('CategoryModel')->getAll()
        ];
        $this->view('staff/products/edit', $data);
    }

    // Xử lý cập nhật cho Staff
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = $this->model('ProductModel');
            
            // Staff chỉ cập nhật thông tin cơ bản, không được đổi ảnh chính nếu không cần thiết
            $data = [
                'name' => $_POST['name'],
                'sku_code' => $_POST['sku_code'],
                'category_id' => $_POST['category_id'],
                'brand_id' => $_POST['brand_id'] ?? null,
                'price' => $_POST['price'],
                'sale_price' => $_POST['sale_price'] ?? 0,
                'description' => $_POST['description'],
                'image' => $_POST['current_image'] // Giữ nguyên ảnh
            ];

            if ($model->update($id, $data)) {
                header('Location: ' . BASE_URL . 'staff/product');
                exit;
            }
        }
    }
}