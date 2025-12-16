<?php
namespace App\Models;
use App\Core\Model;

class UserModel extends Model {
    protected $table = 'users';

    // 1. Đăng nhập thường
    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }

    // 2. Đăng ký thường
    public function register($full_name, $email, $password, $phone) {
        $check = $this->findByEmail($email);
        if ($check) return "Email này đã được sử dụng.";

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO {$this->table} (full_name, email, password, phone_number, role, status) VALUES (?, ?, ?, ?, 'member', 1)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $full_name, $email, $hashed_password, $phone);
        
        return $stmt->execute();
    }

    // 3. Tìm theo Email
    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0 ? $result->fetch_assoc() : false;
    }

    // 4. Tìm theo Google ID
    public function findByGoogleId($googleId) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE google_id = ?");
        $stmt->bind_param("s", $googleId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0 ? $result->fetch_assoc() : false;
    }

    // 5. Tạo User từ Google
    public function createFromGoogle($data) {
        $randomPass = password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO {$this->table} (full_name, email, password, google_id, role, status, avatar) VALUES (?, ?, ?, ?, 'member', 1, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssss", $data['name'], $data['email'], $randomPass, $data['id'], $data['picture']);
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }

    // 6. Cập nhật Google ID
    public function updateGoogleId($id, $googleId, $avatar) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET google_id = ?, avatar = ? WHERE id = ?");
        $stmt->bind_param("ssi", $googleId, $avatar, $id);
        $stmt->execute();
    }


    // 7. Tìm User theo ID
    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0 ? $result->fetch_assoc() : false;
    }

    // 8. Lấy tất cả người dùng (Đã bổ sung để sửa lỗi Fatal Error)
    /**
     * Lấy tất cả người dùng từ database
     */
    public function getAllUsers() {
        $sql = "SELECT id, full_name, email, phone_number, role, status, created_at, avatar 
                FROM {$this->table} 
                ORDER BY created_at DESC";
        $result = $this->conn->query($sql);
        
        $users = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        return $users;
    }
// 9. [MỚI] Cập nhật thông tin cá nhân (Tên, SĐT)
    public function updateProfile($id, $fullName, $phone) {
        $sql = "UPDATE {$this->table} SET full_name = ?, phone_number = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $fullName, $phone, $id);
        return $stmt->execute();
    }

    // 10. [MỚI] Đổi mật khẩu (Dành cho người đã đăng nhập)
    public function changePassword($id, $newPassword) {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE {$this->table} SET password = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $hashed, $id);
        return $stmt->execute();
    }

}




?>

    
    
