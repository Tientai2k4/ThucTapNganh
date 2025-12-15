<?php
namespace App\Models;
use App\Core\Model;

class CouponModel extends Model {
    protected $table = 'coupons';

    // Lấy tất cả coupon
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy một coupon theo ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Thêm coupon mới
    public function add($data) {
        $sql = "INSERT INTO {$this->table} (code, discount_type, discount_value, min_order_value, quantity, start_date, end_date, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
        $stmt = $this->conn->prepare($sql);
        // Kiểu dữ liệu: string, string, integer, integer, integer, string, string
        $stmt->bind_param("ssiiiss", 
            $data['code'], $data['discount_type'], $data['discount_value'], 
            $data['min_order_value'], $data['quantity'], $data['start_date'], $data['end_date']
        );
        return $stmt->execute();
    }

    // Cập nhật coupon
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET 
                code = ?, discount_type = ?, discount_value = ?, 
                min_order_value = ?, quantity = ?, start_date = ?, 
                end_date = ?, status = ?
                WHERE id = ?";
        
        $stmt = $this->conn->prepare($sql);
        // Kiểu dữ liệu: string, string, integer, integer, integer, string, string, integer, integer
        $stmt->bind_param("ssiiissii", 
            $data['code'], $data['discount_type'], $data['discount_value'], 
            $data['min_order_value'], $data['quantity'], $data['start_date'], 
            $data['end_date'], $data['status'], $id
        );
        return $stmt->execute();
    }

    // Xóa coupon
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    // Hàm kiểm tra mã giảm giá (Dùng cho Client)
    public function findByCode($code) {
        $today = date('Y-m-d H:i:s');
        $sql = "SELECT * FROM {$this->table} WHERE code = ? AND status = 1 AND start_date <= ? AND end_date >= ? AND quantity > 0";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $code, $today, $today);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>