<?php
namespace App\Models;
use App\Core\Model;

class PostModel extends Model {
    protected $table = 'posts';

    // Lấy tất cả bài viết (kèm tên tác giả) - Dùng cho Admin
    public function getAllPosts() {
        $sql = "SELECT p.*, u.full_name as author_name 
                FROM {$this->table} p 
                LEFT JOIN users u ON p.user_id = u.id 
                ORDER BY p.created_at DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy chi tiết bài viết theo ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Thêm bài viết mới
    public function add($data) {
        $sql = "INSERT INTO {$this->table} (title, slug, thumbnail, excerpt, content, user_id, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssii", 
            $data['title'], 
            $data['slug'], 
            $data['thumbnail'], 
            $data['excerpt'], 
            $data['content'], 
            $data['user_id'],
            $data['status']
        );
        
        return $stmt->execute();
    }

    // Cập nhật bài viết
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET title=?, slug=?, thumbnail=?, excerpt=?, content=?, status=? WHERE id=?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssii", 
            $data['title'], 
            $data['slug'], 
            $data['thumbnail'], 
            $data['excerpt'], 
            $data['content'], 
            $data['status'],
            $id
        );
        
        return $stmt->execute();
    }

    // Xóa bài viết
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // --- Client Side ---

    // [MỚI - SỬA LỖI] Lấy bài viết mới nhất (có giới hạn số lượng)
    public function getLatestPosts($limit = 4) {
        $sql = "SELECT * FROM {$this->table} WHERE status = 1 ORDER BY created_at DESC LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy tất cả bài viết active cho trang danh sách tin tức
    public function getActivePosts() {
        $sql = "SELECT * FROM {$this->table} WHERE status = 1 ORDER BY created_at DESC";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }
    
    // Lấy bài viết theo Slug (để URL đẹp) hoặc ID
    public function getBySlugOrId($slugOrId) {
        // Kiểm tra xem là ID (số) hay Slug (chuỗi)
        if (is_numeric($slugOrId)) {
            $sql = "SELECT p.*, u.full_name as author_name FROM {$this->table} p LEFT JOIN users u ON p.user_id = u.id WHERE p.id = ? AND p.status = 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $slugOrId);
        } else {
            $sql = "SELECT p.*, u.full_name as author_name FROM {$this->table} p LEFT JOIN users u ON p.user_id = u.id WHERE p.slug = ? AND p.status = 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $slugOrId);
        }
        
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // 1. Đếm tổng số bài viết active để tính số trang
public function countActivePosts() {
    $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE status = 1";
    $result = $this->conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

// 2. Lấy danh sách bài viết theo phân trang
public function getPostsPagination($limit, $offset) {
    $sql = "SELECT * FROM {$this->table} WHERE status = 1 ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
}
?>