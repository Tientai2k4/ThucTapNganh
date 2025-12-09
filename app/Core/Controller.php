<?php
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
        // Nếu view nằm trong thư mục admin, tự động load layout admin
        if (strpos($view, 'admin/') === 0 && strpos($view, 'auth') === false) {
            // Load header Admin
            require_once ROOT_PATH . "/app/Views/admin/layouts/header.php";
            require_once ROOT_PATH . "/app/Views/" . $view . ".php";
            require_once ROOT_PATH . "/app/Views/admin/layouts/footer.php";
        } 
        // View thường (Client hoặc Login Admin không cần layout Dashboard)
        else {
            require_once ROOT_PATH . "/app/Views/" . $view . ".php";
        }
    }
}
?>