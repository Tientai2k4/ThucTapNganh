<?php
namespace App\Models;
use App\Core\Model;

class UserModel extends Model {
    protected $table = 'users';

    // Đăng ký thành viên mới
    public function register($full_name, $email, $password, $phone) {
        // 1. Kiểm tra email tồn tại chưa
        $check = $this->conn->prepare("SELECT id FROM {$this->table} WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            return "Email này đã được sử dụng.";
        }

        // 2. Mã hóa mật khẩu
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
        
        // 3. Insert
        $sql = "INSERT INTO {$this->table} (full_name, email, password, phone_number, role, status) VALUES (?, ?, ?, ?, 'member', 1)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $full_name, $email, $hashed_pass, $phone);
        
        if ($stmt->execute()) {
            return true;
        }
        return "Lỗi hệ thống: " . $stmt->error;
    }

    // Kiểm tra đăng nhập
    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify hash password
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }

    // Lấy danh sách user (Cho trang Admin quản lý)
    public function getAllUsers() {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>