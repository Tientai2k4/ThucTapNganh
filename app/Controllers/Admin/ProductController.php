<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware;
use App\Models\BrandModel; 

class ProductController extends Controller {
    public function __construct() {
        AuthMiddleware::isStaffArea(); 
    }

    public function index() {
        $productModel = $this->model('ProductModel');
        $categoryModel = $this->model('CategoryModel');

        $filters = [
            'keyword'     => $_GET['keyword'] ?? '',
            'category_id' => $_GET['category_id'] ?? '',
            'sort'        => $_GET['sort'] ?? 'newest'
        ];

        $products = $productModel->getAdminList($filters);
        $categories = $categoryModel->getAll();

        $data = [
            'products'   => $products,
            'categories' => $categories,
            'filters'    => $filters
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
                // 3. Upload Ảnh Phụ
                $this->processGalleryUpload($productId, $model);

                // 4. Lưu Biến thể
                if (isset($_POST['variants']) && is_array($_POST['variants'])) {
                    foreach ($_POST['variants'] as $variant) {
                        if (!empty($variant['size']) && !empty($variant['color'])) {
                            $stock = !empty($variant['stock']) ? (int)$variant['stock'] : 0;
                            $model->addVariant($productId, $variant['size'], $variant['color'], $stock);
                        }
                    }
                }
                
                // [THÔNG BÁO] Set session thông báo
                $_SESSION['alert'] = ['type' => 'success', 'message' => 'Thêm sản phẩm mới thành công!'];

                // [THOÁT] Chuyển về trang danh sách
                header('Location: ' . BASE_URL . 'admin/product');
                exit;
            } else {
                $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Lỗi hệ thống: Không thể thêm sản phẩm.'];
                header('Location: ' . BASE_URL . 'admin/product/create');
                exit;
            }
        }
    }

    public function edit($id) {
        $model = $this->model('ProductModel');
        $product = $model->getById($id);
        
        if (!$product) { 
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Sản phẩm không tồn tại!'];
            header('Location: ' . BASE_URL . 'admin/product');
            exit;
        }

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

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = $this->model('ProductModel');
            
            $currentProduct = $model->getById($id);
            if (!$currentProduct) { die("Lỗi: Sản phẩm cần cập nhật không tồn tại."); }
            
            $imageName = $currentProduct['image'];

            // 1. Xử lý ảnh chính
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = ROOT_PATH . "/public/uploads/";
                $imageName = time() . '_main_' . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $imageName);
                
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
                // 3. Xử lý Upload thêm Ảnh Phụ
                $this->processGalleryUpload($id, $model);
                
                // 4. Xử lý thêm biến thể mới
                if (isset($_POST['variants']) && is_array($_POST['variants'])) {
                    foreach ($_POST['variants'] as $variant) {
                        if (!empty($variant['size']) && !empty($variant['color'])) {
                            $stock = !empty($variant['stock']) ? (int)$variant['stock'] : 0;
                            $model->addVariant($id, $variant['size'], $variant['color'], $stock);
                        }
                    }
                }

                // [THÔNG BÁO] Đã cập nhật xong
                $_SESSION['alert'] = ['type' => 'success', 'message' => 'Đã cập nhật sản phẩm: ' . $data['name']];

                // [THOÁT] QUAY VỀ TRANG DANH SÁCH (Theo yêu cầu)
                header('Location: ' . BASE_URL . 'admin/product');
                exit;
            } else {
                $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Có lỗi xảy ra khi cập nhật.'];
                header('Location: ' . BASE_URL . 'admin/product/edit/' . $id);
                exit;
            }
        }
    }
    
    public function delete($id) {
        AuthMiddleware::isStaffArea(); 

        if ($_SESSION['user_role'] !== 'admin') {
            $_SESSION['alert'] = ['type' => 'warning', 'message' => 'Nhân viên không có quyền xóa sản phẩm.'];
            header('Location: ' . BASE_URL . 'admin/product');
            exit;
        }

        $model = $this->model('ProductModel');
        $product = $model->getById($id);
        
        if ($product && $model->delete($id)) {
            if (!empty($product['image'])) {
                $imagePath = ROOT_PATH . '/public/uploads/' . $product['image'];
                if (file_exists($imagePath)) unlink($imagePath);
            }
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Đã xóa sản phẩm thành công.'];
        } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Lỗi: Không thể xóa sản phẩm.'];
        }

        header('Location: ' . BASE_URL . 'admin/product');
        exit;
    }

    public function deleteGallery($imageId, $productId) {
        $model = $this->model('ProductModel');
        $image = $model->getGalleryImageById($imageId);
        
        if ($image) {
            $filePath = ROOT_PATH . "/public/uploads/" . $image['image_url'];
            if (file_exists($filePath)) unlink($filePath);
            
            $model->deleteGalleryImage($imageId);
            
            // Thông báo nhỏ khi xóa ảnh
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Đã xóa ảnh phụ.'];
        }
        
        // Riêng xóa ảnh gallery thì nên ở lại trang Edit để người dùng thấy kết quả ngay
        header('Location: ' . BASE_URL . 'admin/product/edit/' . $productId);
        exit;
    }

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
}
?>