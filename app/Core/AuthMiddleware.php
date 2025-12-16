<?php
namespace App\Core;

/**
 * Class AuthMiddleware
 * Xử lý việc kiểm tra trạng thái đăng nhập và phân quyền truy cập.
 */
class AuthMiddleware {
    
    // Đảm bảo session được khởi động (nên được đặt trong index.php hoặc App.php)
    // Nếu bạn không chắc, hãy thêm session_start() vào đây.
    // public static function startSession() {
    //     if (session_status() === PHP_SESSION_NONE) {
    //         session_start();
    //     }
    // }
    
    // 1. Chặn nếu chưa đăng nhập (Dùng cho mọi khu vực cần đăng nhập)
    public static function isLoggedIn() {
        // self::startSession(); // Gọi nếu cần
        
        if (!isset($_SESSION['user_id'])) {
            // Chuyển hướng về trang đăng nhập
            header('Location: ' . BASE_URL . 'client/auth/login');
            exit;
        }
    }

    /**
     * Chặn nếu không phải Admin hoặc Staff.
     * Dùng cho mọi Controller trong thư mục Admin (để bảo vệ khu vực quản trị chung).
     * @param array $allowedRoles Mặc định là admin và staff.
     */
    public static function hasRole(array $allowedRoles = ['admin', 'staff']) {
        // 1. Kiểm tra trạng thái đăng nhập trước
        self::isLoggedIn(); 
        
        // 2. Kiểm tra vai trò
        $userRole = $_SESSION['user_role'] ?? 'member'; // Mặc định là member nếu không xác định
        
        if (!in_array($userRole, $allowedRoles)) {
            // Chuyển hướng đến trang báo lỗi chung (hoặc trang chủ)
            self::accessDenied("Bạn không có quyền truy cập trang này.");
        }
    }

    // 3. Chỉ dành riêng cho ADMIN TỐI CAO (Chặn Staff, chỉ cho phép admin)
    public static function onlyAdmin() {
        // Sử dụng hàm hasRole() với chỉ Admin
        self::hasRole(['admin']); 
    }

    // 4. (Tùy chọn) Hàm hỗ trợ hiển thị thông báo lỗi
    private static function accessDenied(string $message) {
        // Tùy chọn 1: Chuyển hướng về trang báo lỗi thân thiện hơn
        // header('Location: ' . BASE_URL . 'error/403'); 
        // exit;

        // Tùy chọn 2: Hiển thị thông báo HTML (như bạn đã làm, nhưng gọn hơn)
        echo "<!DOCTYPE html>
            <html lang='en'>
            <head><title>Truy cập bị từ chối</title></head>
            <body style='font-family: Arial, sans-serif; text-align: center; padding: 50px;'>
                <h1 style='color: red;'>Truy cập bị từ chối (403 Forbidden)</h1>
                <p>{$message}</p>
                <a href='" . BASE_URL . "'>Quay lại trang chủ</a>
            </body>
            </html>";
        exit;
    }
}