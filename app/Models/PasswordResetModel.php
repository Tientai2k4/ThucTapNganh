<?php
namespace App\Models;
use App\Core\Model;

class PasswordResetModel extends Model {
    
    // Tạo token reset
    public function createToken($email) {
        // Xóa token cũ nếu có
        $this->conn->query("DELETE FROM password_resets WHERE email = '$email'");

        $token = bin2hex(random_bytes(32)); // Tạo chuỗi ngẫu nhiên
        $sql = "INSERT INTO password_resets (email, token) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $email, $token);
        
        if ($stmt->execute()) {
            return $token;
        }
        return false;
    }

    // Kiểm tra token có hợp lệ không
    public function verifyToken($token) {
        $stmt = $this->conn->prepare("SELECT email FROM password_resets WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc()['email'];
        }
        return false;
    }

    // Xóa token sau khi dùng xong
    public function deleteToken($email) {
        $this->conn->query("DELETE FROM password_resets WHERE email = '$email'");
    }

    // Cập nhật mật khẩu mới
    public function updatePassword($email, $newPassword) {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hash, $email);
        return $stmt->execute();
    }
}
?>