<?php
namespace App\Core;

class Model {
    protected $conn;

    public function __construct() {
        // Nhúng file kết nối db.php
        require ROOT_PATH . '/config/db.php';
        /** @var \mysqli $conn */
        $this->conn = $conn;
    }
}
?>