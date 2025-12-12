<?php
namespace App\Models;
use App\Core\Model;

class ReviewModel extends Model {
    protected $table = 'reviews';

    // Lấy tất cả đánh giá kèm tên User và tên Product
    public function getAllReviews() {
        $sql = "SELECT 
            r.*, 
            u.full_name AS user_name,
            p.name AS product_name, 
            p.image AS product_image
        FROM reviews r 
        LEFT JOIN users u ON r.user_id = u.id 
        LEFT JOIN products p ON r.product_id = p.id 
        ORDER BY r.created_at DESC";

    $result = $this->conn->query($sql);
    if ($result === FALSE) {
        error_log("SQL Error in getAllReviews: " . $this->conn->error); 
        return [];
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}

    // Cập nhật trạng thái (Duyệt/Ẩn)
    public function updateStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET status = ? WHERE id = ?");
        $stmt->bind_param("ii", $status, $id);
        return $stmt->execute();
    }

    // Admin trả lời đánh giá
    public function replyReview($id, $replyContent) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET reply_content = ? WHERE id = ?");
        $stmt->bind_param("si", $replyContent, $id);
        return $stmt->execute();
    }

    // Xóa đánh giá (Nếu cần)
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // 1. Lấy danh sách đánh giá đã duyệt của một sản phẩm
    public function getApprovedByProductId($productId) {
        $sql = "SELECT r.*, u.full_name 
                FROM reviews r 
                LEFT JOIN users u ON r.user_id = u.id 
                WHERE r.product_id = ? AND r.status = 1 
                ORDER BY r.created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // 2. Thêm đánh giá mới
    public function create($data) {
        $sql = "INSERT INTO reviews (product_id, user_id, rating, comment, status, created_at) 
                VALUES (?, ?, ?, ?, 0, NOW())"; 
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiis", 
            $data['product_id'], 
            $data['user_id'], 
            $data['rating'], 
            $data['comment'] 
        );
        
        return $stmt->execute();
    }
  
}
?>