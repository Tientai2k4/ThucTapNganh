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
        $catModel = $this->model('CategoryModel');
        $data = [
            'categories' => $catModel->getAll(),
            'brands' => [] 
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
            $productId = $model->add($data); 

            if ($productId) {
                // 4. Xử lý biến thể
                if (isset($_POST['variants']) && is_array($_POST['variants'])) {
                    foreach ($_POST['variants'] as $variant) {
                        if (!empty($variant['size']) && !empty($variant['color'])) {
                            $model->addVariant($productId, $variant['size'], $variant['color'], (int)$variant['stock']);
                        }
                    }
                }
                header('Location: ' . BASE_URL . 'admin/product');
                exit;
            } else {
                echo "Lỗi thêm sản phẩm.";
            }
        }
    }

    // --- CÁC HÀM MỚI THÊM VÀO ---

    // Hiển thị form sửa
    public function edit($id) {
        $model = $this->model('ProductModel');
        $product = $model->getById($id);
        
        if (!$product) {
            die("Sản phẩm không tồn tại");
        }

        $variants = $model->getVariants($id);
        $catModel = $this->model('CategoryModel');
        
        $data = [
            'product' => $product,
            'variants' => $variants,
            'categories' => $catModel->getAll()
        ];
        $this->view('admin/products/edit', $data);
    }

    // Xử lý cập nhật
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = $this->model('ProductModel');
            
            // Lấy thông tin cũ để giữ lại ảnh nếu người dùng không up ảnh mới
            $currentProduct = $model->getById($id);
            $imageName = $currentProduct['image'];

            // 1. Kiểm tra nếu có up ảnh mới thì lấy tên mới
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = ROOT_PATH . "/public/uploads/";
                $imageName = time() . '_' . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $imageName);
            }

            // 2. Gom dữ liệu cập nhật
            $data = [
                'name' => $_POST['name'],
                'sku_code' => $_POST['sku_code'],
                'category_id' => $_POST['category_id'],
                'brand_id' => $_POST['brand_id'] ?? null,
                'price' => $_POST['price'],
                'sale_price' => $_POST['sale_price'] ?? 0,
                'description' => $_POST['description'],
                'image' => $imageName // Dùng ảnh mới hoặc cũ
            ];

            // 3. Gọi Model update
            if ($model->update($id, $data)) {
                // Xử lý cập nhật biến thể (Logic đơn giản: Có thể thêm code xóa cũ thêm mới ở đây nếu cần)
                // Hiện tại chỉ redirect về danh sách
                header('Location: ' . BASE_URL . 'admin/product');
                exit;
            } else {
                echo "Lỗi cập nhật sản phẩm.";
            }
        }
    }
}
?>