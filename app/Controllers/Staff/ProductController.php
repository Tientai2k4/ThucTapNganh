<?php
namespace App\Controllers\Staff;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class ProductController extends Controller {
    
    private $productModel;

    public function __construct() {
        AuthMiddleware::isStaffArea(); 
        $this->productModel = $this->model('ProductModel');
    }

    public function index() {
        $filters = [
            'keyword'     => $_GET['keyword'] ?? '',
            'category_id' => $_GET['category_id'] ?? '',
            'sort'        => $_GET['sort'] ?? 'newest'
        ];

        $products = $this->productModel->getAdminList($filters);
        
        $categoryModel = $this->model('CategoryModel');
        $categories = $categoryModel->getAll();

        $data = [
            'products'   => $products,
            'categories' => $categories,
            'filters'    => $filters
        ];

        $this->view('staff/products/index', $data);
    }

    // [MỚI] Hiển thị form nhập hàng
    public function create() {
        $categoryModel = $this->model('CategoryModel');
        $brandModel = $this->model('BrandModel');

        $data = [
            'categories' => $categoryModel->getAll(),
            'brands'     => $brandModel->getAll()
        ];
        
        $this->view('staff/products/create', $data);
    }

    // [MỚI] Xử lý lưu sản phẩm mới
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 1. Upload Ảnh Chính
            $mainImageName = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = ROOT_PATH . "/public/uploads/";
                $mainImageName = time() . '_main_' . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $mainImageName);
            }

            // 2. Chuẩn bị dữ liệu
            $data = [
                'category_id' => $_POST['category_id'],
                'brand_id'    => !empty($_POST['brand_id']) ? $_POST['brand_id'] : null,
                'name'        => $_POST['name'],
                'sku_code'    => $_POST['sku_code'],
                'price'       => $_POST['price'],
                'sale_price'  => !empty($_POST['sale_price']) ? $_POST['sale_price'] : 0,
                'description' => $_POST['description'],
                'image'       => $mainImageName
            ];
            
            $productId = $this->productModel->add($data);

            if ($productId) {
                // 3. Upload Ảnh Phụ
                $this->processGalleryUpload($productId);

                // 4. Lưu Biến thể (Size/Màu/Giá riêng)
                if (isset($_POST['variants']) && is_array($_POST['variants'])) {
                    foreach ($_POST['variants'] as $variant) {
                        if (!empty($variant['size']) && !empty($variant['color'])) {
                            $stock = !empty($variant['stock']) ? (int)$variant['stock'] : 0;
                            
                            // Lấy giá riêng của biến thể (nếu có)
                            $vPrice = !empty($variant['price']) ? (float)$variant['price'] : 0;
                            $vSale  = !empty($variant['sale_price']) ? (float)$variant['sale_price'] : 0;

                            $this->productModel->addVariant($productId, $variant['size'], $variant['color'], $stock, $vPrice, $vSale);
                        }
                    }
                }
                
                $_SESSION['alert'] = ['type' => 'success', 'message' => 'Nhập hàng mới thành công!'];
                header('Location: ' . BASE_URL . 'staff/product');
                exit;
            } else {
                $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Lỗi hệ thống: Không thể thêm sản phẩm.'];
                header('Location: ' . BASE_URL . 'staff/product/create');
                exit;
            }
        }
    }

    // Chức năng Sửa nhanh (Quick Edit) cho Staff
    public function quickEdit($id) {
        $product = $this->productModel->getById($id);
        if (!$product) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Sản phẩm không tồn tại'];
            header('Location: ' . BASE_URL . 'staff/product'); 
            exit;
        }

        $variants = $this->productModel->getVariants($id);

        $this->view('staff/products/quick_edit', [
            'product'  => $product,
            'variants' => $variants
        ]);
    }

    // Xử lý Cập nhật Kho & Thêm biến thể mới từ trang Quick Edit
    public function updateStock() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productId = $_POST['product_id'];
            
            // 1. Cập nhật giá sản phẩm cha
            $priceData = [
                'price' => $_POST['price'],
                'sale_price' => !empty($_POST['sale_price']) ? $_POST['sale_price'] : 0,
            ];
            
            $current = $this->productModel->getById($productId);
            // Mapping lại dữ liệu để update (giữ nguyên thông tin cũ, chỉ sửa giá)
            $updateData = [
                'category_id' => $current['category_id'],
                'brand_id'    => $current['brand_id'],
                'name'        => $current['name'],
                'sku_code'    => $current['sku_code'],
                'price'       => $priceData['price'],
                'sale_price'  => $priceData['sale_price'],
                'description' => $current['description'],
                'image'       => $current['image']
            ];
            $this->productModel->update($productId, $updateData);

            // 2. Cập nhật số lượng tồn kho biến thể CŨ
            // Lưu ý: View Quick Edit trả về mảng variants[id] = quantity
            if (isset($_POST['variants']) && is_array($_POST['variants'])) {
                $db = $this->productModel->getConn();
                $stmt = $db->prepare("UPDATE product_variants SET stock_quantity = ? WHERE id = ?");
                foreach ($_POST['variants'] as $vid => $qty) {
                    $qty = (int)$qty;
                    $stmt->bind_param("ii", $qty, $vid);
                    $stmt->execute();
                }
            }

            // 3. Thêm biến thể MỚI
            if (isset($_POST['new_variants']) && is_array($_POST['new_variants'])) {
                foreach ($_POST['new_variants'] as $newVar) {
                    if (!empty($newVar['size'])) {
                        $color = !empty($newVar['color']) ? trim($newVar['color']) : 'Mặc định';
                        $stock = !empty($newVar['stock']) ? (int)$newVar['stock'] : 0;
                        
                        // Staff Quick Edit mặc định giá biến thể mới theo giá sản phẩm cha (0, 0)
                        $this->productModel->addVariant($productId, $newVar['size'], $color, $stock, 0, 0);
                    }
                }
            }

            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Đã cập nhật kho và giá thành công.'];
            header('Location: ' . BASE_URL . 'staff/product/quickEdit/' . $productId);
            exit;
        }
    }

    private function processGalleryUpload($productId) {
        if (isset($_FILES['gallery'])) {
            $totalFiles = count($_FILES['gallery']['name']);
            $targetDir = ROOT_PATH . "/public/uploads/";
            
            for ($i = 0; $i < $totalFiles; $i++) {
                if ($_FILES['gallery']['error'][$i] == 0) {
                    $galleryName = time() . "_sub_{$i}_" . basename($_FILES['gallery']['name'][$i]);
                    if (move_uploaded_file($_FILES['gallery']['tmp_name'][$i], $targetDir . $galleryName)) {
                        $this->productModel->addGalleryImage($productId, $galleryName);
                    }
                }
            }
        }
    }
}
?>