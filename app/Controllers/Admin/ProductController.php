<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware;
// [CẦN THÊM] Sử dụng BrandModel
use App\Models\BrandModel; 

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
        $brandModel = $this->model('BrandModel'); // Giả định đã có BrandModel
        $data = [
            'categories' => $catModel->getAll(),
            'brands' => $brandModel->getAll() // Cần tạo BrandModel nếu chưa có
        ];
        $this->view('admin/products/create', $data);
    }

    // Xử lý lưu (Store)
    public function store() {
        // AuthMiddleware::onlyAdmin(); // Tùy chọn bảo vệ cấp cao

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = $this->model('ProductModel');
            
            // 1. Upload Ảnh Chính
            $mainImageName = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                // Cần định nghĩa ROOT_PATH trước khi sử dụng. Giả sử đã định nghĩa.
                $targetDir = ROOT_PATH . "/public/uploads/";
                $mainImageName = time() . '_main_' . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $mainImageName);
            }

            // 2. Chuẩn bị dữ liệu chung
            $data = [
                'name' => $_POST['name'],
                'sku_code' => $_POST['sku_code'],
                'category_id' => $_POST['category_id'],
                'brand_id' => !empty($_POST['brand_id']) ? $_POST['brand_id'] : null, // Fix: Dùng !empty
                'price' => $_POST['price'],
                'sale_price' => !empty($_POST['sale_price']) ? $_POST['sale_price'] : 0, // Fix: Dùng !empty
                'description' => $_POST['description'],
                'image' => $mainImageName
            ];
            
            // 3. Lưu sản phẩm chính
            $productId = $model->add($data);

            if ($productId) {
                // 4. Upload Ảnh Phụ (Gallery)
                if (isset($_FILES['gallery'])) {
                    $totalFiles = count($_FILES['gallery']['name']);
                    $targetDir = ROOT_PATH . "/public/uploads/";
                    
                    for ($i = 0; $i < $totalFiles; $i++) {
                        if ($_FILES['gallery']['error'][$i] == 0) {
                            $galleryName = time() . "_sub_{$i}_" . basename($_FILES['gallery']['name'][$i]);
                            if (move_uploaded_file($_FILES['gallery']['tmp_name'][$i], $targetDir . $galleryName)) {
                                // Lưu vào bảng product_images
                                $model->addGalleryImage($productId, $galleryName);
                            }
                        }
                    }
                }

                // 5. Lưu Biến thể
                if (isset($_POST['variants']) && is_array($_POST['variants'])) {
                    foreach ($_POST['variants'] as $variant) {
                        // Thêm kiểm tra dữ liệu đầu vào cho biến thể
                        if (!empty($variant['size']) && !empty($variant['color'])) {
                            $stock = !empty($variant['stock']) ? (int)$variant['stock'] : 0;
                            $model->addVariant($productId, $variant['size'], $variant['color'], $stock);
                        }
                    }
                }
                
                header('Location: ' . BASE_URL . 'admin/product');
                exit;
            } else {
                echo "Lỗi: Không thể thêm sản phẩm.";
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
        $gallery = $model->getGalleryImages($id); // [MỚI] Lấy ảnh phụ
        $catModel = $this->model('CategoryModel');
        $brandModel = $this->model('BrandModel'); 
        
        $data = [
            'product' => $product,
            'variants' => $variants,
            'gallery' => $gallery, // [MỚI] Đưa ảnh phụ vào view
            'categories' => $catModel->getAll(),
            'brands' => $brandModel->getAll()
        ];
        $this->view('admin/products/edit', $data);
    }

    // Xử lý cập nhật
    public function update($id) {
        // AuthMiddleware::onlyAdmin(); // Tùy chọn bảo vệ cấp cao

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = $this->model('ProductModel');
            
            $currentProduct = $model->getById($id);
            if (!$currentProduct) { die("Lỗi: Sản phẩm cần cập nhật không tồn tại."); }
            
            $imageName = $currentProduct['image'];

            // 1. Kiểm tra nếu có up ảnh mới thì lấy tên mới (Ảnh chính)
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = ROOT_PATH . "/public/uploads/";
                $imageName = time() . '_main_' . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $imageName);
                
                // [TÙY CHỌN] Xóa ảnh cũ 
                // if ($currentProduct['image'] && file_exists($targetDir . $currentProduct['image'])) {
                //     unlink($targetDir . $currentProduct['image']);
                // }
            }

            // 2. Gom dữ liệu cập nhật
            $data = [
                'name' => $_POST['name'],
                'sku_code' => $_POST['sku_code'],
                'category_id' => $_POST['category_id'],
                'brand_id' => !empty($_POST['brand_id']) ? $_POST['brand_id'] : null,
                'price' => $_POST['price'],
                'sale_price' => !empty($_POST['sale_price']) ? $_POST['sale_price'] : 0,
                'description' => $_POST['description'],
                'image' => $imageName 
            ];

            // 3. Gọi Model update
            if ($model->update($id, $data)) {
                // [NÊN THÊM LOGIC CẬP NHẬT BIẾN THỂ & GALLERY TẠI ĐÂY]
                // Hiện tại, tạm thời redirect:
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
        
        if (!$product) {
            die("Sản phẩm không tồn tại.");
        }
        
        if ($model->delete($id)) {
            // [TÙY CHỌN] Xóa ảnh và biến thể/gallery liên quan (Cần code thêm)
            
            header('Location: ' . BASE_URL . 'admin/product');
            exit;
        } else {
            die("Lỗi xóa sản phẩm.");
        }
    }
}