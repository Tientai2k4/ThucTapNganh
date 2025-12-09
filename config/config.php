<?php
// config/config.php

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