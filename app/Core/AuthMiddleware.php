<?php
namespace App\Core;

class AuthMiddleware {
    
    // Khởi động session nếu chưa có
    private static function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // 1. Kiểm tra đã đăng nhập chưa
    public static function isLoggedIn() {
        self::startSession();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'client/auth/login');
            exit;
        }
    }

    // 2. Kiểm tra vai trò (Core logic)
    public static function hasRole(array $allowedRoles) {
        self::isLoggedIn();
        $userRole = $_SESSION['user_role'] ?? 'member';
        
        // Nếu quyền hiện tại không nằm trong danh sách cho phép
        if (!in_array($userRole, $allowedRoles)) {
            // Lưu thông báo lỗi vào session để hiển thị
            $_SESSION['error'] = "Bạn không có quyền truy cập chức năng này!";
            echo "<script>
                alert('Quyền truy cập bị từ chối!');
                window.location.href='" . BASE_URL . "staff/dashboard';
            </script>";
            exit;
        }
    }

    // --- CÁC HÀM PHÂN QUYỀN CỤ THỂ ---

    // Chỉ Admin tối cao
    public static function onlyAdmin() {
        self::hasRole(['admin']);
    }

    // Nhân viên Bán hàng + Admin
    public static function isSales() {
        self::hasRole(['sales_staff', 'admin']);
    }

    // Nhân viên Nội dung + Admin
    public static function isContent() {
        self::hasRole(['content_staff', 'admin']);
    }

    // Nhân viên CSKH + Admin
    public static function isCare() {
        self::hasRole(['care_staff', 'admin']);
    }

    // Khu vực chung cho mọi nhân viên
    public static function isStaffArea() {
        self::hasRole(['sales_staff', 'content_staff', 'care_staff', 'admin']);
    }
}