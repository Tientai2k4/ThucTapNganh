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

    // 1. Chức năng Quick Edit (Hiển thị form)
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

    // 2. Xử lý Cập nhật Kho & Thêm biến thể mới
    public function updateStock() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productId = $_POST['product_id'];
            
            // 1. Cập nhật giá sản phẩm cha
            $priceData = [
                'price' => $_POST['price'],
                'sale_price' => !empty($_POST['sale_price']) ? $_POST['sale_price'] : 0,
            ];
            
            // Lưu ý: Cần viết hàm updatePriceOnly trong Model cho tối ưu, 
            // nhưng ở đây ta dùng update full (lấy lại dữ liệu cũ để tránh mất)
            $current = $this->productModel->getById($productId);
            $fullData = array_merge($current, $priceData); // Ghi đè giá mới vào data cũ
            // Mapping lại đúng key cho hàm update
            $updateData = [
                'category_id' => $fullData['category_id'],
                'brand_id' => $fullData['brand_id'],
                'name' => $fullData['name'],
                'sku_code' => $fullData['sku_code'],
                'price' => $fullData['price'],
                'sale_price' => $fullData['sale_price'],
                'description' => $fullData['description'],
                'image' => $fullData['image']
            ];
            $this->productModel->update($productId, $updateData);

            // 2. Cập nhật biến thể CŨ (Existing Variants)
            if (isset($_POST['variants']) && is_array($_POST['variants'])) {
                $db = $this->productModel->getConn();
                $stmt = $db->prepare("UPDATE product_variants SET stock_quantity = ? WHERE id = ?");
                foreach ($_POST['variants'] as $vid => $qty) {
                    $qty = (int)$qty;
                    $stmt->bind_param("ii", $qty, $vid);
                    $stmt->execute();
                }
            }

            // 3. Thêm biến thể MỚI (New Variants)
            if (isset($_POST['new_variants']) && is_array($_POST['new_variants'])) {
                foreach ($_POST['new_variants'] as $newVar) {
                    if (!empty($newVar['size'])) {
                        $color = !empty($newVar['color']) ? trim($newVar['color']) : 'Mặc định';
                        $stock = !empty($newVar['stock']) ? (int)$newVar['stock'] : 0;
                        
                        // Gọi Model thêm mới
                        $this->productModel->addVariant($productId, $newVar['size'], $color, $stock);
                    }
                }
            }

            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Đã cập nhật kho và thêm biến thể thành công.'];
            header('Location: ' . BASE_URL . 'staff/product/quickEdit/' . $productId);
            exit;
        }
    }
}
?>