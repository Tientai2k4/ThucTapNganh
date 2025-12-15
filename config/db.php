<?php
// config/db.php

// Tạo kết nối mới mỗi khi file này được gọi
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT); 

if ($conn->connect_error) {
    die("Lỗi kết nối Cơ sở dữ liệu: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>