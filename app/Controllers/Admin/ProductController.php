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
            'brands' => [] // Thêm model brand nếu cần
        ];
        $this->view('admin/products/create', $data);
    }

    // Xử lý lưu (Store) - ĐÃ CẬP NHẬT
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // 1. Xử lý Upload ảnh
            $imageName = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = ROOT_PATH . "/public/uploads/";
                // Tạo tên file ngẫu nhiên
                $imageName = time() . '_' . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $imageName);
            }

            // 2. Gom dữ liệu sản phẩm chính
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

            // 3. Gọi Model lưu sản phẩm cha
            $model = $this->model('ProductModel');
            
            // LƯU Ý: Hàm add() trong Model phải trả về ID vừa insert (return $this->db->lastInsertId();)
            $productId = $model->add($data); 

            if ($productId) {
                // 4. Xử lý lưu Biến thể (Variants) - PHẦN MỚI THÊM
                if (isset($_POST['variants']) && is_array($_POST['variants'])) {
                    foreach ($_POST['variants'] as $variant) {
                        // Kiểm tra dữ liệu cơ bản (Size và Color không được rỗng)
                        if (!empty($variant['size']) && !empty($variant['color'])) {
                            $model->addVariant(
                                $productId, 
                                $variant['size'], 
                                $variant['color'], 
                                (int)$variant['stock'] // Ép kiểu số cho chắc chắn
                            );
                        }
                    }
                }

                // Lưu xong thì quay về trang danh sách
                header('Location: ' . BASE_URL . 'admin/product');
                exit;
            } else {
                echo "Lỗi thêm sản phẩm chính (Có thể do trùng mã SKU hoặc lỗi Database)";
            }
        }
    }
}
?>