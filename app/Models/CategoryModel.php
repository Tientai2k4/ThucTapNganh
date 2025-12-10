<?php
namespace App\Models;
use App\Core\Model;

class CategoryModel extends Model {
    protected $table = 'categories';

    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Phương thức mới: Lấy danh mục theo ID
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function create($name, $description) {
        $sql = "INSERT INTO {$this->table} (name, description) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $name, $description);
        return $stmt->execute();
    }

    // Phương thức mới: Cập nhật danh mục
    public function update($id, $name, $description) {
        $sql = "UPDATE {$this->table} SET name = ?, description = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        // Lưu ý: bind_param là "ssi" (string, string, integer)
        $stmt->bind_param("ssi", $name, $description, $id); 
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>