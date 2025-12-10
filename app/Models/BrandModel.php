<?php
namespace App\Models;
use App\Core\Model;

class BrandModel extends Model {
    protected $table = 'brands';

    // Lấy tất cả
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy 1 dòng theo ID
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Thêm mới
    public function create($name, $logo) {
        $sql = "INSERT INTO {$this->table} (name, logo) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $name, $logo);
        return $stmt->execute();
    }

    // Cập nhật (Có đổi ảnh logo)
    public function updateWithLogo($id, $name, $logo) {
        $sql = "UPDATE {$this->table} SET name = ?, logo = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $name, $logo, $id); 
        return $stmt->execute();
    }
    
    // Cập nhật (Không đổi ảnh, giữ logo cũ)
    public function updateNameOnly($id, $name) {
        $sql = "UPDATE {$this->table} SET name = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $name, $id); 
        return $stmt->execute();
    }

    // Xóa
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>