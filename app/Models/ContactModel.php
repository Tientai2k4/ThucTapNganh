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

    // [THÊM MỚI] Hàm lấy danh sách có lọc và sắp xếp
    public function getFilterList($filters = []) {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        
        $types = "";
        $values = [];

        // 1. Tìm kiếm (Tên, Email, SĐT)
        if (!empty($filters['keyword'])) {
            $sql .= " AND (full_name LIKE ? OR email LIKE ? OR phone LIKE ?)";
            $types .= "sss";
            $keyword = "%" . $filters['keyword'] . "%";
            $values[] = $keyword;
            $values[] = $keyword;
            $values[] = $keyword;
        }

        // 2. Lọc theo trạng thái (0: Chưa xem, 1: Đã xem)
        if (isset($filters['status']) && $filters['status'] !== '') {
            $sql .= " AND status = ?";
            $types .= "i";
            $values[] = (int)$filters['status'];
        }

        // 3. Sắp xếp
        $sort = $filters['sort'] ?? 'newest';
        switch ($sort) {
            case 'oldest':
                $sql .= " ORDER BY created_at ASC";
                break;
            case 'newest':
            default:
                $sql .= " ORDER BY created_at DESC";
                break;
        }

        // Thực thi
        $stmt = $this->conn->prepare($sql);
        
        if (!empty($values)) {
            $bind_params = [];
            $params_ref = array_merge([$types], $values);
            foreach ($params_ref as $key => $value) $bind_params[$key] = &$params_ref[$key];
            call_user_func_array([$stmt, 'bind_param'], $bind_params);
        }

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
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

    // Hàm cũ (có thể giữ lại hoặc bỏ nếu không dùng nơi khác)
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY status ASC, created_at DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>