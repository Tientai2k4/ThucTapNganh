<?php
namespace App\Models;
use App\Core\Model;

class UserModel extends Model {
    
    // Kiểm tra đăng nhập
    public function checkLogin($email, $password) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Kiểm tra mật khẩu mã hóa
            // Trong DB mẫu bạn đưa: $2y$10$HashedPasswordHere...
            // Khi đăng nhập thật, hãy dùng password_verify
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }
}
?>