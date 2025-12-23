<?php
namespace App\Models;
use App\Core\Model;

class CategoryModel extends Model {
    protected $table = 'categories';

    // Lấy danh sách danh mục, kèm theo tên của danh mục cha (để hiển thị rõ ràng)
    public function getAll() {
        $sql = "SELECT c.*, p.name as parent_name 
                FROM {$this->table} c 
                LEFT JOIN {$this->table} p ON c.parent_id = p.id 
                ORDER BY c.id DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function create($name, $description, $parent_id) {
        $sql = "INSERT INTO {$this->table} (name, description, parent_id) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        
        // Nếu parent_id rỗng hoặc bằng 0 thì set là NULL (Danh mục gốc)
        if (empty($parent_id)) {
            $parent_id = null;
        }
        
        $stmt->bind_param("ssi", $name, $description, $parent_id);
        return $stmt->execute();
    }

    public function update($id, $name, $description, $parent_id) {
        $sql = "UPDATE {$this->table} SET name = ?, description = ?, parent_id = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        
        if (empty($parent_id)) {
            $parent_id = null;
        }

        $stmt->bind_param("ssii", $name, $description, $parent_id, $id); 
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