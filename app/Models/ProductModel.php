<?php
namespace App\Models;
use App\Core\Model;

class ProductModel extends Model {
    protected $table = 'products';

    // Thêm sản phẩm mới
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

    // Lấy tất cả sản phẩm (kèm tên danh mục)
    public function getAll() {
        $sql = "SELECT p.*, c.name as cat_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                ORDER BY p.id DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    // Lấy sản phẩm để hiển thị trang chủ
    public function getHomeProducts($limit = 8) {
        // Lấy sản phẩm đang bán (is_active = 1)
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY created_at DESC LIMIT $limit";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    // Thêm biến thể vào bảng product_variants
    public function addVariant($productId, $size, $color, $stock) {
        $sql = "INSERT INTO product_variants (product_id, size, color, stock_quantity) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issi", $productId, $size, $color, $stock);
        return $stmt->execute();
    }
    // Lọc sản phẩm theo danh mục, thương hiệu, giá
    public function filterProducts($filters) {
        $sql = "SELECT * FROM products WHERE is_active = 1";
        $params = [];
        $types = "";

        // Lọc theo danh mục
        if (!empty($filters['category'])) {
            $sql .= " AND category_id = ?";
            $params[] = $filters['category'];
            $types .= "i";
        }
        // Lọc theo thương hiệu
        if (!empty($filters['brand'])) {
            $sql .= " AND brand_id = ?";
            $params[] = $filters['brand'];
            $types .= "i";
        }
        // Lọc theo giá
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
}
?>