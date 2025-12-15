<?php
// config/config.php
if (!defined('SENDGRID_API_KEY')) {
    define('SENDGRID_API_KEY', 'DIEN_KEY_CUA_BAN_VAO_DAY'); // Để trống
}
// Kiểm tra nếu chưa định nghĩa thì mới định nghĩa (Tránh lỗi Warning)
if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'swimming_store');
    define('DB_PORT', 3307);
}

// Bật báo cáo lỗi
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cấu hình múi giờ
date_default_timezone_set('Asia/Ho_Chi_Minh');

// URL gốc (Sửa lại đúng tên thư mục project của bạn)
define('BASE_URL', 'http://localhost/ThucTapNganh/');

// Đường dẫn gốc trên ổ cứng
define('ROOT_PATH', dirname(__DIR__)); 
?>