<?php
namespace App\Models;
use App\Core\Model;

class CategoryModel extends Model {
    protected $table = 'categories';

    // CẢI TIẾN: Thêm tham số $keyword và $parentId để lọc
    public function getAll($keyword = null, $parentId = null) {
        $sql = "SELECT c.*, p.name as parent_name 
                FROM {$this->table} c 
                LEFT JOIN {$this->table} p ON c.parent_id = p.id 
                WHERE c.status = 1";

        $params = [];
        $types = "";

        // Lọc theo từ khóa
        if (!empty($keyword)) {
            $sql .= " AND c.name LIKE ?";
            $params[] = "%{$keyword}%";
            $types .= "s";
        }

        // Lọc theo danh mục cha (Nếu chọn 'root' thì tìm parent_id IS NULL)
        if ($parentId !== null && $parentId !== '') {
            if ($parentId == 'root') {
                $sql .= " AND c.parent_id IS NULL";
            } else {
                $sql .= " AND c.parent_id = ?";
                $params[] = $parentId;
                $types .= "i";
            }
        }

        // Sắp xếp: Ưu tiên danh mục cha lên trước, sau đó đến ID mới nhất
        $sql .= " ORDER BY c.parent_id ASC, c.id DESC";

        $stmt = $this->conn->prepare($sql);

        // Bind params động (Nếu có tham số truyền vào)
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // MỚI: Chỉ lấy các danh mục gốc để đổ vào Dropdown bộ lọc
    public function getRootCategories() {
        $sql = "SELECT * FROM {$this->table} WHERE parent_id IS NULL AND status = 1
         ORDER BY id DESC";
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
    // Thay vì DELETE, chúng ta UPDATE status về 0
    $sql = "UPDATE {$this->table} SET status = 0 WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
}
?>