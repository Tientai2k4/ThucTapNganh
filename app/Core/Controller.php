<?php
// app/Core/Controller.php

namespace App\Core;

class Controller {
    // Hàm gọi Model
    public function model($model) {
        require_once ROOT_PATH . "/app/Models/" . $model . ".php";
        $class = "App\\Models\\" . $model; 
        return new $class;
    }

    // Hàm gọi View
   public function view($view, $data = []) {
    // 1. Kiểm tra nếu là view dành cho ADMIN
    if (strpos($view, 'admin/') === 0 && strpos($view, 'auth') === false) {
        require_once ROOT_PATH . "/app/Views/admin/layouts/header.php";
        require_once ROOT_PATH . "/app/Views/admin/layouts/sidebar.php";
        require_once ROOT_PATH . "/app/Views/" . $view . ".php";
        require_once ROOT_PATH . "/app/Views/admin/layouts/footer.php";
    } 
    // 2. KIỂM TRA NẾU LÀ VIEW DÀNH CHO STAFF
    elseif (strpos($view, 'staff/') === 0) {
        // Staff dùng chung Header CSS của Admin để có giao diện quản lý
        require_once ROOT_PATH . "/app/Views/admin/layouts/header.php"; 
        require_once ROOT_PATH . "/app/Views/staff/layouts/sidebar.php"; 
        require_once ROOT_PATH . "/app/Views/" . $view . ".php";
        require_once ROOT_PATH . "/app/Views/admin/layouts/footer.php";
    }
    // 3. Nếu là view dành cho CLIENT
    elseif (strpos($view, 'client/') === 0 && strpos($view, 'auth') === false) {
        require_once ROOT_PATH . "/app/Views/client/layouts/header.php";
        require_once ROOT_PATH . "/app/Views/" . $view . ".php";
        require_once ROOT_PATH . "/app/Views/client/layouts/footer.php";
    } else {
        require_once ROOT_PATH . "/app/Views/" . $view . ".php";
    }
 }
}
?>