<?php
namespace App\Core;

class AuthMiddleware {
    // 1. Chặn nếu chưa đăng nhập
    public static function isLoggedIn() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'client/auth/login');
            exit;
        }
    }

    // 2. Kiểm tra vai trò linh hoạt
    public static function hasRole(array $allowedRoles) {
        self::isLoggedIn();
        $userRole = $_SESSION['user_role'] ?? 'member';
        
        if (!in_array($userRole, $allowedRoles)) {
            echo "<script>alert('Bạn không có quyền truy cập!'); window.location.href='" . BASE_URL . "';</script>";
            exit;
        }
    }

    // 3. Bảo vệ khu vực Staff (Cả Admin và Staff đều vào được)
    public static function isStaffArea() {
        self::hasRole(['staff', 'admin']);
    }

    // 4. Bảo vệ khu vực Admin tối cao (Chỉ Admin mới vào được)
    public static function onlyAdmin() {
        self::hasRole(['admin']);
    }
}