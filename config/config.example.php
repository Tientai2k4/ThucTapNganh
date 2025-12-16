<?php
// config/config.example.php
// FILE NÀY ĐỂ UP LÊN GIT - KHÔNG ĐIỀN KEY THẬT

// 1. Cấu hình Database
if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'swimming_store');
    define('DB_PORT', 3306);
}

// 2. Cấu hình SendGrid Email
if (!defined('SENDGRID_API_KEY')) {
    define('SENDGRID_API_KEY', ''); // Để trống
}

// 3. Cấu hình Google Login (Lấy từ console.cloud.google.com)
define('GOOGLE_CLIENT_ID', '');
define('GOOGLE_CLIENT_SECRET', '');
define('GOOGLE_REDIRECT_URL', 'http://localhost/ThucTapNganh/client/auth/googleCallback');

// 4. Cấu hình Chung
ini_set('display_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Ho_Chi_Minh');

define('BASE_URL', 'http://localhost/ThucTapNganh/');
define('ROOT_PATH', dirname(__DIR__)); 
?>