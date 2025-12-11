<?php
namespace App\Models;
use App\Core\Model;

class ReviewModel extends Model {
    protected $table = 'reviews';

    // Lấy tất cả đánh giá kèm tên User và tên Product
    public function getAllReviews() {
        $sql = "SELECT r.*, u.full_name as user_name, p.name as product_name, p.image as product_image
                FROM reviews r 
                LEFT JOIN users u ON r.user_id = u.id 
                LEFT JOIN products p ON r.product_id = p.id 
                ORDER BY r.created_at DESC";
        $result = $this->conn->query($sql);
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
}
?>