<?php
namespace App\Models;

use App\Core\Model;

class ProductModel extends Model {
    protected $table = 'products';

    // 1. Thêm sản phẩm mới (Thông tin chung)
    public function add($data) {
        // Lưu ý: Cột is_active mặc định là 1
        $sql = "INSERT INTO {$this->table} (category_id, brand_id, name, sku_code, price, sale_price, image, description, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iisssiss", 
            $data['category_id'], 
            $data['brand_id'], 
            $data['name'], 
            $data['sku_code'], 
            $data['price'], 
            $data['sale_price'], 
            $data['image'], 
            $data['description']
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

    // [MỚI] 3. Thêm ảnh phụ vào bảng product_images
    public function addGalleryImage($productId, $imageUrl) {
        $sql = "INSERT INTO product_images (product_id, image_url) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $productId, $imageUrl);
        return $stmt->execute();
    }

    // [MỚI] 4. Lấy danh sách ảnh phụ
    public function getGalleryImages($productId) {
        $sql = "SELECT * FROM product_images WHERE product_id = ? ORDER BY id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // 5. Cập nhật thông tin sản phẩm
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

    // [MỚI] 6. Xóa sản phẩm theo ID (Cần hàm này cho Controller)
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // --- CÁC HÀM LẤY DỮ LIỆU (READ) ---

    // [CẬP NHẬT] 7. Lấy sản phẩm theo ID (Kèm tên danh mục, thương hiệu)
    public function getById($id) {
        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name 
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN brands b ON p.brand_id = b.id
                WHERE p.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // [CẬP NHẬT] 8. Lấy danh sách biến thể theo ID sản phẩm (Chỉ lấy stock > 0)
    public function getVariants($productId) {
        $stmt = $this->conn->prepare("SELECT * FROM product_variants WHERE product_id = ? AND stock_quantity > 0");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // [CŨ - GIỮ NGUYÊN] Lấy tất cả sản phẩm (kèm tên danh mục) - Dùng cho trang Admin
    public function getAll() {
        $sql = "SELECT p.*, c.name as cat_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                ORDER BY p.id DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // [CŨ - GIỮ NGUYÊN] Lấy sản phẩm để hiển thị trang chủ (Client)
    public function getHomeProducts($limit = 8) {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY created_at DESC LIMIT $limit";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // [CẬP NHẬT - NÂNG CAO] 9. Lọc sản phẩm (Filter Advanced)
    public function filterProducts($filters) {
        // Sử dụng DISTINCT để tránh lặp sản phẩm khi join với variants
        $sql = "SELECT DISTINCT p.* FROM products p 
                LEFT JOIN product_variants pv ON p.id = pv.product_id 
                WHERE p.is_active = 1";
        
        $params = [];
        $types = "";
        $values = [];

        // 1. Lọc theo Danh mục (category_id)
        if (!empty($filters['category_id'])) {
            $sql .= " AND p.category_id = ?";
            $types .= "i";
            $values[] = $filters['category_id'];
        }

        // 2. Lọc theo Thương hiệu (brands - MẢNG)
        if (!empty($filters['brands']) && is_array($filters['brands'])) {
            $placeholders = implode(',', array_fill(0, count($filters['brands']), '?'));
            $sql .= " AND p.brand_id IN ($placeholders)";
            $types .= str_repeat('i', count($filters['brands']));
            $values = array_merge($values, $filters['brands']);
        }

        // 3. Lọc theo Size (sizes - MẢNG)
        if (!empty($filters['sizes']) && is_array($filters['sizes'])) {
            $placeholders = implode(',', array_fill(0, count($filters['sizes']), '?'));
            $sql .= " AND pv.size IN ($placeholders)";
            $types .= str_repeat('s', count($filters['sizes']));
            $values = array_merge($values, $filters['sizes']);
        }

        // 4. Lọc theo Khoảng Giá
        if (!empty($filters['price_min'])) {
            $sql .= " AND (p.price >= ? OR p.sale_price >= ?)";
            $types .= "dd";
            $values[] = $filters['price_min'];
            $values[] = $filters['price_min']; // Check cả giá sale
        }
        if (!empty($filters['price_max'])) {
            $sql .= " AND (p.price <= ? OR p.sale_price <= ?)";
            $types .= "dd";
            $values[] = $filters['price_max'];
            $values[] = $filters['price_max'];
        }

        // 5. Tìm kiếm từ khóa (Keyword)
        if (!empty($filters['keyword'])) {
            $sql .= " AND p.name LIKE ?";
            $types .= "s";
            $values[] = "%" . $filters['keyword'] . "%";
        }

        $sql .= " ORDER BY p.created_at DESC";

        // Thực thi Dynamic Query
        $stmt = $this->conn->prepare($sql);
        if (!empty($values)) {
            // Cần tạo mảng tham chiếu cho call_user_func_array
            $params_ref = array_merge([$types], $values);
            $bind_params = [];
            foreach ($params_ref as $key => $value) {
                $bind_params[$key] = &$params_ref[$key];
            }
            call_user_func_array([$stmt, 'bind_param'], $bind_params);
        }
        
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // [MỚI] 10. Sản phẩm liên quan (Cross-sell)
    public function getRelatedProducts($categoryId, $excludeId, $limit = 4) {
        $sql = "SELECT * FROM products WHERE category_id = ? AND id != ? AND is_active = 1 ORDER BY RAND() LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $categoryId, $excludeId, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // [CŨ - GIỮ NGUYÊN] Hàm hỗ trợ Giỏ hàng: Lấy chi tiết biến thể từ mảng ID
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

    // --- CÁC HÀM XỬ LÝ KHO (STOCK MANAGEMENT) ---

    // [CŨ - GIỮ NGUYÊN] Hàm kiểm tra xem kho có đủ hàng cho số lượng khách muốn mua không
    public function checkStockAvailability($variantId, $qty) {
        $sql = "SELECT stock_quantity FROM product_variants WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $variantId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        if ($result && $result['stock_quantity'] >= $qty) {
            return true;
        }
        return false;
    }

    // [CŨ - GIỮ NGUYÊN] Giảm tồn kho (chống Race Condition)
    public function decreaseStock($variantId, $qty) {
        $sql = "UPDATE product_variants SET stock_quantity = stock_quantity - ? WHERE id = ? AND stock_quantity >= ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $qty, $variantId, $qty);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    // [CŨ - GIỮ NGUYÊN] Tăng tồn kho
    public function increaseStock($variantId, $qty) {
        $sql = "UPDATE product_variants SET stock_quantity = stock_quantity + ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $qty, $variantId);
        return $stmt->execute();
    }

    // [CŨ - GIỮ NGUYÊN] Lấy số lượng tồn kho (Trả về số nguyên)
    public function getVariantStock($variantId) {
        $sql = "SELECT stock_quantity FROM product_variants WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $variantId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ? (int)$result['stock_quantity'] : 0;
    }

    // --- CÁC HÀM XỬ LÝ WISHLIST (GIỮ NGUYÊN) ---
    
    // [CŨ - GIỮ NGUYÊN] Lấy danh sách sản phẩm yêu thích của 1 user (cho trang Wishlist)
    public function getWishlistByUser($userId) {
        $sql = "SELECT p.*, w.id as wishlist_id
                FROM products p 
                JOIN wishlists w ON p.id = w.product_id 
                WHERE w.user_id = ?
                ORDER BY w.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // [CŨ - GIỮ NGUYÊN] Kiểm tra sản phẩm đã có trong Wishlist chưa
    public function isInWishlist($userId, $productId) {
        $sql = "SELECT id FROM wishlists WHERE user_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    // [CŨ - GIỮ NGUYÊN] Thêm hoặc Xóa sản phẩm yêu thích (Toggle Logic)
    public function toggleWishlist($userId, $productId) {
        if ($this->isInWishlist($userId, $productId)) {
            // Đã có -> Xóa
            $sql = "DELETE FROM wishlists WHERE user_id = ? AND product_id = ?";
            $action = 'removed';
        } else {
            // Chưa có -> Thêm
            $sql = "INSERT INTO wishlists (user_id, product_id) VALUES (?, ?)";
            $action = 'added';
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        return $action;
    }
    
}
?>