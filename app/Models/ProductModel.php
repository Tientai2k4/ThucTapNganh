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
}
?>