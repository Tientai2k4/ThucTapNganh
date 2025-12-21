<?php
namespace App\Core;

class Controller {
    public function model($model) {
        require_once ROOT_PATH . "/app/Models/" . $model . ".php";
        $class = "App\\Models\\" . $model; 
        return new $class;
    }

    public function view($view, $data = []) {
        // Ưu tiên 1: KIỂM TRA NẾU LÀ VIEW CỦA STAFF
        if (strpos($view, 'staff/') === 0) {
            // Nạp Header Admin (để lấy CSS), nhưng Sidebar PHẢI là của Staff
            require_once ROOT_PATH . "/app/Views/admin/layouts/header.php"; 
            require_once ROOT_PATH . "/app/Views/staff/layouts/sidebar.php"; 
            require_once ROOT_PATH . "/app/Views/" . $view . ".php";
            require_once ROOT_PATH . "/app/Views/admin/layouts/footer.php";
        } 
        // Ưu tiên 2: KIỂM TRA NẾU LÀ VIEW CỦA ADMIN
        elseif (strpos($view, 'admin/') === 0 && strpos($view, 'auth') === false) {
            require_once ROOT_PATH . "/app/Views/admin/layouts/header.php";
            require_once ROOT_PATH . "/app/Views/admin/layouts/sidebar.php";
            require_once ROOT_PATH . "/app/Views/" . $view . ".php";
            require_once ROOT_PATH . "/app/Views/admin/layouts/footer.php";
        }
        // Ưu tiên 3: CLIENT
        elseif (strpos($view, 'client/') === 0 && strpos($view, 'auth') === false) {
            require_once ROOT_PATH . "/app/Views/client/layouts/header.php";
            require_once ROOT_PATH . "/app/Views/" . $view . ".php";
            require_once ROOT_PATH . "/app/Views/client/layouts/footer.php";
        } else {
            require_once ROOT_PATH . "/app/Views/" . $view . ".php";
        }
    }
}