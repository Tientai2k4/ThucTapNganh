<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware;
use App\Models\BrandModel; 

class ProductController extends Controller {
   public function __construct() {
        // Cho phép cả nhân viên và admin quản lý kho/sản phẩm
        AuthMiddleware::isStaffArea(); 
    }

    // [CẬP NHẬT] index: Thêm xử lý bộ lọc
    public function index() {
        $productModel = $this->model('ProductModel');
        $categoryModel = $this->model('CategoryModel'); // Cần để hiện dropdown lọc danh mục

        // Lấy tham số từ URL (VD: ?keyword=abc&sort=price_desc)
        $filters = [
            'keyword'     => $_GET['keyword'] ?? '',
            'category_id' => $_GET['category_id'] ?? '',
            'sort'        => $_GET['sort'] ?? 'newest'
        ];

        // Gọi hàm getAdminList vừa viết ở Model
        $products = $productModel->getAdminList($filters);
        $categories = $categoryModel->getAll();

        // Truyền data sang View
        $data = [
            'products'   => $products,
            'categories' => $categories, // Để hiện trong <select>
            'filters'    => $filters     // Để giữ lại giá trị đã chọn trên form
        ];

        $this->view('admin/products/index', $data);
    }

    public function create() {
        $catModel = $this->model('CategoryModel');
        $brandModel = $this->model('BrandModel'); 
        $data = [
            'categories' => $catModel->getAll(),
            'brands' => $brandModel->getAll()
        ];
        $this->view('admin/products/create', $data);
    }

    // store: GIỮ NGUYÊN (Code bạn đã ổn)
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = $this->model('ProductModel');
            
            // 1. Upload Ảnh Chính
            $mainImageName = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = ROOT_PATH . "/public/uploads/";
                $mainImageName = time() . '_main_' . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $mainImageName);
            }

            // 2. Chuẩn bị dữ liệu
            $data = [
                'name' => $_POST['name'],
                'sku_code' => $_POST['sku_code'],
                'category_id' => $_POST['category_id'],
                'brand_id' => !empty($_POST['brand_id']) ? $_POST['brand_id'] : null,
                'price' => $_POST['price'],
                'sale_price' => !empty($_POST['sale_price']) ? $_POST['sale_price'] : 0,
                'description' => $_POST['description'],
                'image' => $mainImageName
            ];
            
            $productId = $model->add($data);

            if ($productId) {
                // 3. Upload Ảnh Phụ (Gallery)
                $this->processGalleryUpload($productId, $model); // Tách hàm riêng để tái sử dụng

                // 4. Lưu Biến thể
                if (isset($_POST['variants']) && is_array($_POST['variants'])) {
                    foreach ($_POST['variants'] as $variant) {
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

    // edit: GIỮ NGUYÊN
    public function edit($id) {
        $model = $this->model('ProductModel');
        $product = $model->getById($id);
        
        if (!$product) { die("Sản phẩm không tồn tại"); }

        $variants = $model->getVariants($id);
        $gallery = $model->getGalleryImages($id); 
        $catModel = $this->model('CategoryModel');
        $brandModel = $this->model('BrandModel'); 
        
        $data = [
            'product' => $product,
            'variants' => $variants,
            'gallery' => $gallery, 
            'categories' => $catModel->getAll(),
            'brands' => $brandModel->getAll()
        ];
        $this->view('admin/products/edit', $data);
    }

    // update: CẦN SỬA ĐỂ UPLOAD THÊM ẢNH PHỤ
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = $this->model('ProductModel');
            
            $currentProduct = $model->getById($id);
            if (!$currentProduct) { die("Lỗi: Sản phẩm cần cập nhật không tồn tại."); }
            
            $imageName = $currentProduct['image'];

            // 1. Xử lý ảnh chính (Nếu có thay đổi)
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = ROOT_PATH . "/public/uploads/";
                $imageName = time() . '_main_' . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $imageName);
                
                // Xóa ảnh chính cũ nếu cần
                if ($currentProduct['image'] && file_exists($targetDir . $currentProduct['image'])) {
                     unlink($targetDir . $currentProduct['image']);
                }
            }

            // 2. Dữ liệu update
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

            if ($model->update($id, $data)) {
                // [MỚI] 3. Xử lý Upload thêm Ảnh Phụ (Quan trọng: Code cũ thiếu phần này)
                $this->processGalleryUpload($id, $model);
                
                // [MỚI] 4. Xử lý thêm biến thể mới (Nếu muốn đơn giản)
                if (isset($_POST['variants']) && is_array($_POST['variants'])) {
                    // Logic này chỉ thêm mới, không sửa/xóa biến thể cũ trong form edit này
                    // Bạn có thể cần custom thêm nếu muốn sửa từng biến thể
                    foreach ($_POST['variants'] as $variant) {
                        if (!empty($variant['size']) && !empty($variant['color'])) {
                            // Chỉ add nếu có đủ dữ liệu
                            $stock = !empty($variant['stock']) ? (int)$variant['stock'] : 0;
                            // Gọi hàm addVariant (lưu ý: hàm này insert mới)
                            $model->addVariant($id, $variant['size'], $variant['color'], $stock);
                        }
                    }
                }

                header('Location: ' . BASE_URL . 'admin/product/edit/' . $id);
                exit;
            } else {
                echo "Lỗi cập nhật sản phẩm.";
            }
        }
    }
    
    // [HÀM MỚI] Xử lý xóa ảnh phụ (Khi bấm nút Xóa ở View Edit)
    public function deleteGallery($imageId, $productId) {
        $model = $this->model('ProductModel');
        
        // 1. Lấy thông tin ảnh để lấy tên file
        $image = $model->getGalleryImageById($imageId);
        
        if ($image) {
            // 2. Xóa file vật lý
            $filePath = ROOT_PATH . "/public/uploads/" . $image['image_url'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            // 3. Xóa trong database
            $model->deleteGalleryImage($imageId);
        }
        
        // 4. Quay lại trang sửa
        header('Location: ' . BASE_URL . 'admin/product/edit/' . $productId);
        exit;
    }

    // [HÀM PHỤ] Giúp code gọn hơn, dùng cho cả store và update
    private function processGalleryUpload($productId, $model) {
        if (isset($_FILES['gallery'])) {
            $totalFiles = count($_FILES['gallery']['name']);
            $targetDir = ROOT_PATH . "/public/uploads/";
            
            for ($i = 0; $i < $totalFiles; $i++) {
                if ($_FILES['gallery']['error'][$i] == 0) {
                    $galleryName = time() . "_sub_{$i}_" . basename($_FILES['gallery']['name'][$i]);
                    if (move_uploaded_file($_FILES['gallery']['tmp_name'][$i], $targetDir . $galleryName)) {
                        $model->addGalleryImage($productId, $galleryName);
                    }
                }
            }
        }
    }

    // delete: GIỮ NGUYÊN
    public function delete($id) {
        AuthMiddleware::onlyAdmin(); 
        $model = $this->model('ProductModel');
        if ($model->delete($id)) {
            header('Location: ' . BASE_URL . 'admin/product');
            exit;
        }
    }
}
?>