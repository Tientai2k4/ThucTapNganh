<?php
namespace App\Models;
use App\Core\Model;

class ContactModel extends Model {
    protected $table = 'contacts';

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (full_name, email, phone, message, status) VALUES (?, ?, ?, ?, 0)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $data['name'], $data['email'], $data['phone'], $data['message']);
        return $stmt->execute();
    }

    public function getFilterList($filters = []) {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $types = "";
        $values = [];

        if (!empty($filters['keyword'])) {
            $sql .= " AND (full_name LIKE ? OR email LIKE ? OR phone LIKE ?)";
            $types .= "sss";
            $keyword = "%" . $filters['keyword'] . "%";
            $values[] = $keyword; $values[] = $keyword; $values[] = $keyword;
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $sql .= " AND status = ?";
            $types .= "i";
            $values[] = (int)$filters['status'];
        }

        $sort = $filters['sort'] ?? 'newest';
        $sql .= ($sort == 'oldest') ? " ORDER BY created_at ASC" : " ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($sql);
        if (!empty($values)) {
            $stmt->bind_param($types, ...$values);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Nâng cấp: Cập nhật trạng thái tùy ý (0: Mới, 1: Đã xem, 2: Đã trả lời)
    public function updateStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET status = ? WHERE id = ?");
        $stmt->bind_param("ii", $status, $id);
        return $stmt->execute();
    }
    
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>