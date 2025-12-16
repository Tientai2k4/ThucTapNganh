<?php
namespace App\Models;
use App\Core\Model;

class AddressModel extends Model {
    protected $table = 'user_addresses';

    public function getByUserId($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY is_default DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function add($data) {
        // Nếu đây là địa chỉ đầu tiên, set mặc định luôn
        $count = count($this->getByUserId($data['user_id']));
        $isDefault = ($count == 0) ? 1 : 0;

        $sql = "INSERT INTO {$this->table} (user_id, recipient_name, phone, address, is_default) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isssi", $data['user_id'], $data['name'], $data['phone'], $data['address'], $isDefault);
        return $stmt->execute();
    }

    public function delete($id, $userId) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $userId);
        return $stmt->execute();
    }

    public function setDefault($id, $userId) {
        // Reset tất cả về 0
        $this->conn->query("UPDATE {$this->table} SET is_default = 0 WHERE user_id = $userId");
        // Set cái này về 1
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET is_default = 1 WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $userId);
        return $stmt->execute();
    }
}
?>