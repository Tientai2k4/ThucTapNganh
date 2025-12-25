<?php
namespace App\Models;
use App\Core\Model;

class ReviewModel extends Model {
    protected $table = 'reviews';

    // =================================================================
    // PHẦN 1: LOGIC CHO KHÁCH HÀNG (CLIENT)
    // =================================================================

    // 1. Kiểm tra quyền đánh giá (Đã mua + Đã nhận + Đã thanh toán)
    public function checkPurchaseEligibility($userId, $productId) {
        $sql = "SELECT o.id 
                FROM orders o
                JOIN order_details od ON o.id = od.order_id
                JOIN product_variants pv ON od.product_variant_id = pv.id
                WHERE o.user_id = ? 
                  AND pv.product_id = ? 
                  AND o.status = 'completed' 
                  AND o.payment_status = 1
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->num_rows > 0;
    }

    // 2. Kiểm tra xem đã đánh giá chưa
    public function hasReviewed($userId, $productId) {
        $sql = "SELECT id FROM reviews WHERE user_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    // 3. Lấy danh sách đánh giá đã duyệt (Hiện thị ở trang chi tiết SP)
    public function getApprovedByProductId($productId) {
        $sql = "SELECT r.*, u.full_name 
                FROM reviews r 
                LEFT JOIN users u ON r.user_id = u.id 
                WHERE r.product_id = ? AND r.status = 1 
                ORDER BY r.created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // 4. Thống kê sao
    public function getRatingSummary($productId) {
        $reviews = $this->getApprovedByProductId($productId);
        $totalReview = count($reviews);
        $totalPoint = 0;
        $starCount = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];

        foreach ($reviews as $r) {
            $rating = (int)$r['rating'];
            $totalPoint += $rating;
            if (isset($starCount[$rating])) {
                $starCount[$rating]++;
            }
        }

        $average = $totalReview > 0 ? round($totalPoint / $totalReview, 1) : 0;

        return [
            'total_review' => $totalReview,
            'average'      => $average,
            'star_count'   => $starCount
        ];
    }

    // 5. Thêm đánh giá mới
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

    // =================================================================
    // PHẦN 2: LOGIC CHO QUẢN TRỊ VIÊN (ADMIN) - Đây là phần bạn bị thiếu
    // =================================================================

    // 6. Lấy tất cả đánh giá (Cho trang Admin Index)
    public function getAllReviews() {
        $sql = "SELECT r.*, u.full_name AS user_name, p.name AS product_name, p.image AS product_image
                FROM reviews r 
                LEFT JOIN users u ON r.user_id = u.id 
                LEFT JOIN products p ON r.product_id = p.id 
                ORDER BY r.created_at DESC";
        $result = $this->conn->query($sql);
        
        if (!$result) {
            return [];
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // 7. Cập nhật trạng thái (Duyệt/Ẩn)
    public function updateStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET status = ? WHERE id = ?");
        $stmt->bind_param("ii", $status, $id);
        return $stmt->execute();
    }

    // 8. Trả lời đánh giá
    public function replyReview($id, $replyContent) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET reply_content = ? WHERE id = ?");
        $stmt->bind_param("si", $replyContent, $id);
        return $stmt->execute();
    }

    // 9. Xóa đánh giá
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>