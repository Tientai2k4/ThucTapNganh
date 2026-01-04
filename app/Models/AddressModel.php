<?php
namespace App\Models;
use App\Core\Model;

class AddressModel extends Model {
    protected $table = 'user_addresses';

    // --- PHẦN 1: QUẢN LÝ ĐỊA CHỈ USER (Giữ lại từ code cũ) ---
    public function getByUserId($userId) {
        // Kiểm tra bảng tồn tại để tránh lỗi nếu chưa tạo bảng
        $check = $this->conn->query("SHOW TABLES LIKE '{$this->table}'");
        if ($check && $check->num_rows > 0) {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY is_default DESC");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    public function add($data) {
        // Code cũ của bạn
        $existing = $this->getByUserId($data['user_id']);
        $isDefault = (count($existing) == 0) ? 1 : 0;
        
        $sql = "INSERT INTO {$this->table} (user_id, recipient_name, phone, address, is_default) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $phone = $data['phone_number'] ?? '';
        $stmt->bind_param("isssi", $data['user_id'], $data['name'], $data['phone'], $data['address'], $isDefault);
        return $stmt->execute();
    }

    // --- PHẦN 2: LẤY DỮ LIỆU ĐỊA CHÍNH (Tỉnh/Huyện/Xã) - BẮT BUỘC THÊM ---
    
    // Hàm này đang thiếu nên gây lỗi Fatal Error ở CheckoutController
    public function getAllProvinces() {
        $sql = "SELECT province_id as ProvinceID, province_name as ProvinceName 
                FROM ghn_provinces 
                ORDER BY province_name ASC";
        
        $result = $this->conn->query($sql);
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function getDistrictsByProvince($provinceId) {
        $sql = "SELECT district_id as DistrictID, district_name as DistrictName 
                FROM ghn_districts 
                WHERE province_id = ? 
                ORDER BY district_name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $provinceId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];
        while ($row = $result->fetch_assoc()) { $data[] = $row; }
        return $data;
    }

    public function getWardsByDistrict($districtId) {
        $sql = "SELECT ward_code as WardCode, ward_name as WardName 
                FROM ghn_wards 
                WHERE district_id = ? 
                ORDER BY ward_name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $districtId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];
        while ($row = $result->fetch_assoc()) { $data[] = $row; }
        return $data;
    }
}