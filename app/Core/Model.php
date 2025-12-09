<?php
// app/Core/Model.php
namespace App\Core;

class Model {
    protected $conn;

    public function __construct() {
        // Nhúng file kết nối db.php dùng chung
        require ROOT_PATH . '/config/db.php';
        /** @var \mysqli $conn */
        $this->conn = $conn;
    }
}
?>