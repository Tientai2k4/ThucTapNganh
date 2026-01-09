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
                            // [THÊM GIÁ]
                            $vPrice = !empty($variant['price']) ? (float)$variant['price'] : 0;
                            $vSale = !empty($variant['sale_price']) ? (float)$variant['sale_price'] : 0;
                            
                            $model->addVariant($productId, $variant['size'], $variant['color'], $stock, $vPrice, $vSale);
                        }
                    }
                }
                
                $_SESSION['alert'] = ['type' => 'success', 'message' => 'Thêm sản phẩm mới thành công!'];
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
                
                // 4. [QUAN TRỌNG] Cập nhật các biến thể CŨ (Sửa biến thể)
                if (isset($_POST['old_variants']) && is_array($_POST['old_variants'])) {
                    foreach ($_POST['old_variants'] as $variantId => $variantData) {
                        // Chỉ cập nhật nếu có ID
                        if (!empty($variantId)) {
                            $stock = !empty($variantData['stock']) ? (int)$variantData['stock'] : 0;
                            $vPrice = !empty($variantData['price']) ? (float)$variantData['price'] : 0;
                            $vSale = !empty($variantData['sale_price']) ? (float)$variantData['sale_price'] : 0;
                            
                            // Gọi hàm updateVariant trong Model (cần bổ sung hàm này trong Model nếu chưa có)
                            // $model->updateVariant($variantId, $variantData['size'], $variantData['color'], $stock, $vPrice, $vSale);
                            
                            // Vì Model bạn đưa chưa có updateVariant, tôi sẽ dùng cách xóa cũ thêm mới hoặc giả định bạn thêm hàm này vào Model
                            // Ở đây tôi giả định bạn đã thêm hàm updateVariant vào Model như tôi viết ở trên
                             $model->updateVariant($variantId, $variantData['size'], $variantData['color'], $stock, $vPrice, $vSale);
                        }
                    }
                }

                $_SESSION['alert'] = ['type' => 'success', 'message' => 'Đã cập nhật sản phẩm: ' . $data['name']];
                header('Location: ' . BASE_URL . 'admin/product');
                exit;
            } else {
                $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Có lỗi xảy ra khi cập nhật.'];
                header('Location: ' . BASE_URL . 'admin/product/edit/' . $id);
                exit;
            }
        }
    }
    
    // Hàm thêm biến thể mới từ trang Edit (Nút "Thêm dòng biến thể mới" gọi Action này hoặc dùng JS submit form update)
    // Tùy cách bạn implement, ở đây tôi viết action riêng cho rõ ràng
    public function addVariant() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productId = $_POST['product_id'];
            $size = trim($_POST['size']);
            $color = trim($_POST['color']);
            $stock = (int)$_POST['stock'];
            $price = !empty($_POST['price']) ? (float)$_POST['price'] : 0;
            $salePrice = !empty($_POST['sale_price']) ? (float)$_POST['sale_price'] : 0;

            if (empty($size) || empty($color)) {
                $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Vui lòng nhập Size và Màu sắc!'];
                header('Location: ' . BASE_URL . 'admin/product/edit/' . $productId);
                exit;
            }

            $model = $this->model('ProductModel');
            if ($model->addVariant($productId, $size, $color, $stock, $price, $salePrice)) {
                $_SESSION['alert'] = ['type' => 'success', 'message' => 'Đã thêm biến thể mới.'];
            } else {
                $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Lỗi: Không thể thêm biến thể.'];
            }
            header('Location: ' . BASE_URL . 'admin/product/edit/' . $productId);
            exit;
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
            
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Đã xóa ảnh phụ.'];
        }
        
        header('Location: ' . BASE_URL . 'admin/product/edit/' . $productId);
        exit;
    }
    
    // Xóa biến thể
    public function deleteVariant($variantId) {
        $model = $this->model('ProductModel');
        // Cần hàm này trong model
        $model->deleteVariantById($variantId);
        
        // Quay lại trang trước đó
        if(isset($_SERVER['HTTP_REFERER'])) {
             header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
             header('Location: ' . BASE_URL . 'admin/product'); 
        }
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