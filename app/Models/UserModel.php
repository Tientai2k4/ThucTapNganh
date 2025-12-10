<?php
namespace App\Models;
use App\Core\Model;

class UserModel extends Model {
 
    protected $table = 'users';
    // 1. HÀM ĐĂNG NHẬP 
    public function login($email, $password) {
        // Chuẩn bị câu lệnh SQL
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        // Gán giá trị vào dấu ? (s = string)
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();   
            // Kiểm tra mật khẩu (So sánh mật khẩu nhập vào với mật khẩu mã hóa trong DB)
            if (password_verify($password, $user['password'])) {
                return $user; // Trả về thông tin user nếu đúng
            }
        }
        return false; // Sai email hoặc mật khẩu
    }

    // 2. HÀM ĐĂNG KÝ 
    public function register($full_name, $email, $password, $phone) {
        // Bước 1: Kiểm tra xem email đã tồn tại chưa
        $checkSql = "SELECT id FROM users WHERE email = ?";
        $stmtCheck = $this->conn->prepare($checkSql);
        $stmtCheck->bind_param("s", $email);
        $stmtCheck->execute();
        if ($stmtCheck->get_result()->num_rows > 0) {
            return "Email này đã được sử dụng.";
        }
        // Bước 2: Mã hóa mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Bước 3: Thêm vào CSDL
        $sql = "INSERT INTO users (full_name, email, password, phone_number, role, status) VALUES (?, ?, ?, ?, 'member', 1)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $full_name, $email, $hashed_password, $phone);
        
        if ($stmt->execute()) {
            return true; // Đăng ký thành công
        } else {
            return "Lỗi hệ thống: " . $this->conn->error;
        }
    }

    // 3. HÀM LẤY DANH SÁCH (Dùng cho Admin quản lý sau này)
    public function getAllUsers() {
        $sql = "SELECT * FROM users ORDER BY id DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy danh sách user (Cho trang Admin quản lý)
    public function getAllUsers() {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>