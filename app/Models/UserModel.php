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
    public function getAllUsers($filters = []) {
        // Khởi tạo câu SQL cơ bản với điều kiện WHERE 1=1 để dễ nối chuỗi
        $sql = "SELECT id, full_name, email, phone_number, role, status, created_at, avatar 
                FROM {$this->table} 
                WHERE 1=1";
        
        $types = "";
        $params = [];

        // Lọc theo từ khóa (Tên, Email, SĐT)
        if (!empty($filters['keyword'])) {
            $sql .= " AND (full_name LIKE ? OR email LIKE ? OR phone_number LIKE ?)";
            $keyword = "%" . $filters['keyword'] . "%";
            $types .= "sss";
            $params[] = $keyword;
            $params[] = $keyword;
            $params[] = $keyword;
        }

        // Lọc theo Vai trò
        if (!empty($filters['role'])) {
            $sql .= " AND role = ?";
            $types .= "s";
            $params[] = $filters['role'];
        }

        // Sắp xếp mới nhất lên đầu
        $sql .= " ORDER BY created_at DESC";

        // Thực thi prepare
        $stmt = $this->conn->prepare($sql);
        
        // Bind tham số động nếu có bộ lọc (tránh lỗi khi không lọc gì)
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();

        $users = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        return $users;
    }
// 9. Cập nhật thông tin cá nhân (Họ tên, SĐT)
    public function updateProfile($id, $fullName, $phone) {
        // Lưu ý: Trong database cột là 'phone_number', không phải 'phone'
        $sql = "UPDATE {$this->table} SET full_name = ?, phone_number = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $fullName, $phone, $id);
        return $stmt->execute();
    }
// 10. Cập nhật Avatar (Ảnh đại diện)
    public function updateAvatar($id, $avatarPath) {
        $sql = "UPDATE {$this->table} SET avatar = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $avatarPath, $id);
        return $stmt->execute();
    }

  // 11. Kiểm tra mật khẩu cũ (Để phục vụ chức năng đổi mật khẩu)
    public function checkPassword($id, $passwordInput) {
        $user = $this->findById($id);
        if ($user && password_verify($passwordInput, $user['password'])) {
            return true;
        }
        return false;
    }

    // 12. Đổi mật khẩu mới
    public function changePassword($id, $newPassword) {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE {$this->table} SET password = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $hashed, $id);
        return $stmt->execute();
    }
// 11. [MỚI] Cập nhật Vai trò và Trạng thái (Dùng cho Admin)
    public function updateUserStatusAndRole($id, $role, $status) {
        $sql = "UPDATE {$this->table} SET role = ?, status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        // role là string, status là int, id là int
        $stmt->bind_param("sii", $role, $status, $id);
        return $stmt->execute();
    }

    // 12. [MỚI] Xóa vĩnh viễn người dùng
    public function deleteUser($id) {
        // Lưu ý: Nếu user này có đơn hàng, việc xóa có thể bị chặn bởi khóa ngoại (Foreign Key)
        // Nên xóa các dữ liệu liên quan trước hoặc dùng Soft Delete (ẩn đi thay vì xóa thật)
        
        // Cách 1: Xóa thật (Cần đảm bảo DB có ON DELETE CASCADE hoặc xóa đơn hàng trước)
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}




?>

    
    
