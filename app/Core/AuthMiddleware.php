<?php
namespace App\Core;

class AuthMiddleware {
    public static function isAdmin() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
            header('Location: ' . BASE_URL . 'client/auth/login');
            exit;
        }
    }
}
?>