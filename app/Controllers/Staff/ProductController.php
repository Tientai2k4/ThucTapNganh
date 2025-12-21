<?php
namespace App\Controllers\Staff;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class ProductController extends Controller {
    
    public function __construct() {
        AuthMiddleware::isSales(); 
    }

    // 1. Danh sách sản phẩm (Chế độ xem Kho)
    public function index() {
        $productModel = $this->model('ProductModel');
        $categoryModel = $this->model('CategoryModel');

        $filters = [
            'keyword'     => $_GET['keyword'] ?? '',
            'category_id' => $_GET['category_id'] ?? '',
            'sort'        => $_GET['sort'] ?? 'newest'
        ];

        // Tận dụng hàm getAdminList (có join brand, category)
        $products = $productModel->getAdminList($filters);
        
        // Lấy danh sách biến thể để tính tổng tồn kho hiển thị
        foreach ($products as &$p) {
            $variants = $productModel->getVariants($p['id']);
            $p['total_stock'] = array_sum(array_column($variants, 'stock_quantity'));
            $p['variants_count'] = count($variants);
        }

        $this->view('staff/products/index', [
            'products'   => $products,
            'categories' => $categoryModel->getAll(),
            'filters'    => $filters
        ]);
    }

    // 2. Chỉnh sửa nhanh (Chỉ cho phép sửa Giá và Tồn kho)
    // Sales không nên sửa mô tả, hình ảnh hay xóa sản phẩm
    public function quickEdit($id) {
        $model = $this->model('ProductModel');
        $product = $model->getById($id);
        
        if (!$product) die("Sản phẩm không tồn tại");

        $variants = $model->getVariants($id);

        $this->view('staff/products/quick_edit', [
            'product'  => $product,
            'variants' => $variants
        ]);
    }

    // 3. Lưu cập nhật nhanh
    public function updateStock() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productId = $_POST['product_id'];
            $price = $_POST['price'];
            $salePrice = $_POST['sale_price'];
            
            $model = $this->model('ProductModel');

            // 1. Cập nhật giá sản phẩm cha
            // Lưu ý: Ta cần viết 1 hàm updatePriceOnly trong Model để an toàn, 
            // nhưng ở đây ta dùng update() hiện có và giữ nguyên các trường cũ.
            $current = $model->getById($productId);
            $data = [
                'category_id' => $current['category_id'],
                'brand_id'    => $current['brand_id'],
                'name'        => $current['name'],
                'sku_code'    => $current['sku_code'],
                'price'       => $price,
                'sale_price'  => $salePrice,
                'description' => $current['description'],
                'image'       => $current['image']
            ];
            $model->update($productId, $data);

            // 2. Cập nhật tồn kho từng biến thể
            if (isset($_POST['variants'])) {
                // Database raw query để update nhanh (Hoặc viết thêm hàm trong Model)
                // Ở đây giả lập gọi hàm update variant (Bạn cần đảm bảo logic update variant có trong Model)
                // Tốt nhất là dùng vòng lặp xóa cũ thêm mới hoặc update từng dòng.
                
                // Giải pháp an toàn: Chúng ta dùng vòng lặp update trực tiếp SQL
                $db = $model->getConn(); // Giả sử Model có hàm getConn() trả về $conn
                $stmt = $db->prepare("UPDATE product_variants SET stock_quantity = ? WHERE id = ?");
                
                foreach ($_POST['variants'] as $vid => $qty) {
                    $qty = (int)$qty;
                    $stmt->bind_param("ii", $qty, $vid);
                    $stmt->execute();
                }
            }

            header('Location: ' . BASE_URL . 'staff/product?msg=updated');
        }
    }
    // [MỚI 1] Hiển thị Form thêm sản phẩm
    public function create() {
        $catModel = $this->model('CategoryModel');
        $brandModel = $this->model('BrandModel');
        
        $this->view('staff/products/create', [
            'categories' => $catModel->getAll(),
            'brands'     => $brandModel->getAll()
        ]);
    }

    // [MỚI 2] Xử lý Lưu sản phẩm mới
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = $this->model('ProductModel');
            
            // 1. Xử lý Ảnh đại diện
            $mainImageName = 'default.png';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = ROOT_PATH . "/public/uploads/";
                $mainImageName = time() . '_staff_' . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $mainImageName);
            }

            // 2. Gom dữ liệu
            $data = [
                'name'        => $_POST['name'],
                'sku_code'    => $_POST['sku_code'],
                'category_id' => $_POST['category_id'],
                'brand_id'    => !empty($_POST['brand_id']) ? $_POST['brand_id'] : null,
                'price'       => $_POST['price'],
                'sale_price'  => !empty($_POST['sale_price']) ? $_POST['sale_price'] : 0,
                'description' => $_POST['description'],
                'image'       => $mainImageName
            ];
            
            // 3. Lưu vào DB
            $productId = $model->add($data);

            if ($productId) {
                // 4. Lưu Biến thể (Màu/Size) ngay lập tức
                if (isset($_POST['variants']) && is_array($_POST['variants'])) {
                    foreach ($_POST['variants'] as $variant) {
                        if (!empty($variant['size']) && !empty($variant['color'])) {
                            $stock = !empty($variant['stock']) ? (int)$variant['stock'] : 0;
                            $model->addVariant($productId, $variant['size'], $variant['color'], $stock);
                        }
                    }
                }
                
                // Thành công -> Quay về danh sách
                header('Location: ' . BASE_URL . 'staff/product?msg=created');
                exit;
            } else {
                echo "<script>alert('Lỗi hệ thống: Không thể tạo sản phẩm'); window.history.back();</script>";
            }
        }
    }
}
?>