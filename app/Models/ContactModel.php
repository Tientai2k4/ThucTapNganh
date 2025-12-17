<?php
namespace App\Models;
use App\Core\Model;

class ContactModel extends Model {
    protected $table = 'contacts';

    // 1. Thêm liên hệ mới
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (full_name, email, phone, message, status) VALUES (?, ?, ?, ?, 0)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $data['name'], $data['email'], $data['phone'], $data['message']);
        return $stmt->execute();
    }

    // 2. Lấy tất cả liên hệ (Mới nhất lên đầu)
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY status ASC, created_at DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // 3. Đánh dấu đã xử lý
    public function markAsRead($id) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET status = 1 WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    // 4. Xóa liên hệ
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>