<?php
namespace App\Models;
use App\Core\Model;

class WishlistModel extends Model {
    
    // Lấy danh sách yêu thích của User
    public function getWishlist($userId) {
        $sql = "SELECT p.*, w.id as wishlist_id 
                FROM wishlists w 
                JOIN products p ON w.product_id = p.id 
                WHERE w.user_id = ? ORDER BY w.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Xóa khỏi danh sách yêu thích
    public function remove($id, $userId) {
        $stmt = $this->conn->prepare("DELETE FROM wishlists WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $userId);
        return $stmt->execute();
    }
}