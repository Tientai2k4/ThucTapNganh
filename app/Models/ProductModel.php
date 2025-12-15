<?php
namespace App\Models;

use App\Core\Model;

class ProductModel extends Model {
    protected $table = 'products';

    // 1. Thêm sản phẩm mới (Thông tin chung)
    public function add($data) {
        $sql = "INSERT INTO {$this->table} (category_id, brand_id, name, sku_code, price, sale_price, image, description, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iisssiss", 
            $data['category_id'], $data['brand_id'], $data['name'], 
            $data['sku_code'], $data['price'], $data['sale_price'], 
            $data['image'], $data['description']
        );
        
        if ($stmt->execute()) {
            return $this->conn->insert_id; // Trả về ID sản phẩm vừa tạo
        }
        return false;
    }

    // 2. Thêm biến thể (Size/Màu) vào bảng product_variants
    public function addVariant($productId, $size, $color, $stock) {
        $sql = "INSERT INTO product_variants (product_id, size, color, stock_quantity) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issi", $productId, $size, $color, $stock);
        return $stmt->execute();
    }

    // 3. Cập nhật thông tin sản phẩm
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET 
                category_id = ?, brand_id = ?, name = ?, sku_code = ?, 
                price = ?, sale_price = ?, description = ?, image = ? 
                WHERE id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iisssissi", 
            $data['category_id'], $data['brand_id'], $data['name'], 
            $data['sku_code'], $data['price'], $data['sale_price'], 
            $data['description'], $data['image'], $id
        );
        
        return $stmt->execute();
    }

    // --- CÁC HÀM LẤY DỮ LIỆU (READ) ---

    // Lấy sản phẩm theo ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Lấy danh sách biến thể theo ID sản phẩm
    public function getVariants($productId) {
        $stmt = $this->conn->prepare("SELECT * FROM product_variants WHERE product_id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy tất cả sản phẩm (kèm tên danh mục) - Dùng cho trang Admin
    public function getAll() {
        $sql = "SELECT p.*, c.name as cat_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                ORDER BY p.id DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy sản phẩm để hiển thị trang chủ (Client)
    public function getHomeProducts($limit = 8) {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY created_at DESC LIMIT $limit";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lọc sản phẩm (Filter)
    public function filterProducts($filters) {
        $sql = "SELECT * FROM products WHERE is_active = 1";
        $params = [];
        $types = "";

        if (!empty($filters['category'])) {
            $sql .= " AND category_id = ?";
            $params[] = $filters['category'];
            $types .= "i";
        }
        if (!empty($filters['brand'])) {
            $sql .= " AND brand_id = ?";
            $params[] = $filters['brand'];
            $types .= "i";
        }
        if (!empty($filters['price_range'])) {
            if ($filters['price_range'] == 'duoi_500') {
                $sql .= " AND price < 500000";
            } elseif ($filters['price_range'] == '500_1tr') {
                $sql .= " AND price BETWEEN 500000 AND 1000000";
            } elseif ($filters['price_range'] == 'tren_1tr') {
                $sql .= " AND price > 1000000";
            }
        }

        $sql .= " ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Hàm hỗ trợ Giỏ hàng: Lấy chi tiết biến thể từ mảng ID
    public function getVariantsDetail($variantIds) {
        if (empty($variantIds)) return [];
        
        $ids = implode(',', array_map('intval', $variantIds));
        
        $sql = "SELECT pv.id as variant_id, pv.size, pv.color, pv.product_id, 
                       p.name, p.price, p.sale_price, p.image 
                FROM product_variants pv
                JOIN products p ON pv.product_id = p.id
                WHERE pv.id IN ($ids)";
                
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // --- CÁC HÀM XỬ LÝ KHO (STOCK MANAGEMENT) - NGÀY 10 & 12 ---

    // [MỚI] Hàm kiểm tra xem kho có đủ hàng cho số lượng khách muốn mua không
    public function checkStockAvailability($variantId, $qty) {
        $sql = "SELECT stock_quantity FROM product_variants WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $variantId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        // Kiểm tra nếu tồn tại biến thể và số lượng tồn >= số lượng mua
        if ($result && $result['stock_quantity'] >= $qty) {
            return true;
        }
        return false;
    }

    // Sử dụng điều kiện stock_quantity >= qty ngay trong câu SQL để tránh Race Condition
    public function decreaseStock($variantId, $qty) {
        $sql = "UPDATE product_variants SET stock_quantity = stock_quantity - ? WHERE id = ? AND stock_quantity >= ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $qty, $variantId, $qty);
        $stmt->execute();
        
        // Trả về true nếu trừ thành công (có dòng bị ảnh hưởng)
        return $stmt->affected_rows > 0;
    }

    public function increaseStock($variantId, $qty) {
        $sql = "UPDATE product_variants SET stock_quantity = stock_quantity + ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $qty, $variantId);
        return $stmt->execute();
    }

    // Lấy số lượng tồn kho (Trả về số nguyên)
    public function getVariantStock($variantId) {
        $sql = "SELECT stock_quantity FROM product_variants WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $variantId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        return $result ? (int)$result['stock_quantity'] : 0;
    }

    // Lấy thông tin tồn kho (Trả về mảng)
    // Lưu ý: Đã sửa 'quantity' thành 'stock_quantity' để khớp với CSDL chuẩn
    public function getStockByVariantId($variantId) {
        $sql = "SELECT stock_quantity FROM product_variants WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $variantId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>