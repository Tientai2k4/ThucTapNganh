<?php
namespace App\Models;
use App\Core\Model;

class SliderModel extends Model {
    protected $table = 'sliders';

    // Lấy tất cả slider sắp xếp theo thứ tự ưu tiên
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY sort_order ASC, created_at DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy 1 slider để sửa
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Thêm slider mới
    public function add($data) {
        $sql = "INSERT INTO {$this->table} (image, link_url, sort_order, status) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssii", $data['image'], $data['link_url'], $data['sort_order'], $data['status']);
        return $stmt->execute();
    }

    // Cập nhật slider
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET image = ?, link_url = ?, sort_order = ?, status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssiii", $data['image'], $data['link_url'], $data['sort_order'], $data['status'], $id);
        return $stmt->execute();
    }

    // Xóa slider
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>