<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class ProductController extends Controller {
    public function __construct() {
        AuthMiddleware::isAdmin();
    }

    // Hiển thị danh sách
    public function index() {
        $model = $this->model('ProductModel');
        $products = $model->getAll();
        $this->view('admin/products/index', ['products' => $products]);
    }

    // Form thêm mới
    public function create() {
        // Cần lấy danh mục & thương hiệu để đổ vào thẻ <select>
        $catModel = $this->model('CategoryModel');
        $brandModel = $this->model('BrandModel'); // Giả định bạn đã tạo file này
        
        $data = [
            'categories' => $catModel->getAll(),
            'brands' => [] // $brandModel->getAll()
        ];
        $this->view('admin/products/create', $data);
    }

    // Xử lý lưu (Store)
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 1. Xử lý Upload ảnh
            $imageName = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = ROOT_PATH . "/public/uploads/";
                // Tạo tên file ngẫu nhiên tránh trùng
                $imageName = time() . '_' . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $imageName);
            }

            // 2. Gom dữ liệu
            $data = [
                'name' => $_POST['name'],
                'sku_code' => $_POST['sku_code'],
                'category_id' => $_POST['category_id'],
                'brand_id' => $_POST['brand_id'] ?? null,
                'price' => $_POST['price'],
                'sale_price' => $_POST['sale_price'] ?? 0,
                'description' => $_POST['description'],
                'image' => $imageName
            ];

            // 3. Gọi Model lưu
            $model = $this->model('ProductModel');
            if ($model->add($data)) {
                header('Location: ' . BASE_URL . 'admin/product');
            } else {
                echo "Lỗi thêm sản phẩm";
            }
        }
    }
}
?>