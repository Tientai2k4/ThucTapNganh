<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class ProductController extends Controller {
    public function __construct() {
        // [SỬA LỖI] Sử dụng hàm hasRole() để bảo vệ. Mặc định cho phép admin và staff.
        AuthMiddleware::hasRole(); 
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
        // [Bảo vệ chức năng chỉnh sửa/lưu trữ]
        // Nếu muốn chỉ Admin cấp cao được thêm/sửa:
        // AuthMiddleware::onlyAdmin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // 1. Xử lý Upload ảnh
            $imageName = null;
            // Cần định nghĩa ROOT_PATH trước khi sử dụng
            // if (!defined('ROOT_PATH')) { define('ROOT_PATH', dirname(__DIR__, 2)); } 
            
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
                        // Thêm kiểm tra dữ liệu đầu vào cho biến thể
                        if (!empty($variant['size']) && !empty($variant['color']) && isset($variant['stock'])) {
                            $model->addVariant($productId, $variant['size'], $variant['color'], (int)$variant['stock']);
                        }
                    }
                }
                header('Location: ' . BASE_URL . 'admin/product');
                exit;
            } else {
                // Xử lý lỗi: có thể chuyển hướng kèm thông báo lỗi
                // $_SESSION['error'] = "Lỗi thêm sản phẩm.";
                // header('Location: ' . BASE_URL . 'admin/product/create');
                echo "Lỗi thêm sản phẩm.";
            }
        }
    }

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
        // [Bảo vệ chức năng chỉnh sửa/lưu trữ]
        // Nếu muốn chỉ Admin cấp cao được thêm/sửa:
        // AuthMiddleware::onlyAdmin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = $this->model('ProductModel');
            
            $currentProduct = $model->getById($id);
            if (!$currentProduct) { die("Lỗi: Sản phẩm cần cập nhật không tồn tại."); }
            
            $imageName = $currentProduct['image'];

            // 1. Kiểm tra nếu có up ảnh mới thì lấy tên mới
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = ROOT_PATH . "/public/uploads/";
                $imageName = time() . '_' . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $imageName);
                
                // [TÙY CHỌN] Xóa ảnh cũ nếu nó tồn tại để tránh rác đĩa
                // if ($currentProduct['image'] && file_exists($targetDir . $currentProduct['image'])) {
                //     unlink($targetDir . $currentProduct['image']);
                // }
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
                'image' => $imageName 
            ];

            // 3. Gọi Model update
            if ($model->update($id, $data)) {
                // [NÊN THÊM] Logic cập nhật/xóa biến thể cũ và thêm biến thể mới
                // Hiện tại chỉ redirect về danh sách
                header('Location: ' . BASE_URL . 'admin/product');
                exit;
            } else {
                echo "Lỗi cập nhật sản phẩm.";
            }
        }
    }
    
    // Xử lý xóa (Thêm hàm delete)
    public function delete($id) {
        // [BẢO VỆ CẤP CAO] Chức năng xóa thường chỉ dành cho Admin cấp cao
        AuthMiddleware::onlyAdmin(); 
        
        $model = $this->model('ProductModel');
        $product = $model->getById($id);
        
        if ($model->delete($id)) {
            // [TÙY CHỌN] Xóa ảnh và biến thể liên quan
            
            header('Location: ' . BASE_URL . 'admin/product');
            exit;
        } else {
            die("Lỗi xóa sản phẩm.");
        }
    }
}