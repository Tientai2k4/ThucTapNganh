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
        // Tách path view để kiểm tra
        // $view format: 'admin/dashboard/index' hoặc 'client/home/index'
        
        // Nếu là view admin (nhưng không phải login) thì load layout admin
        if (strpos($view, 'admin/') === 0 && strpos($view, 'auth') === false) {
            require_once ROOT_PATH . "/app/Views/admin/layouts/header.php";
            require_once ROOT_PATH . "/app/Views/admin/layouts/sidebar.php"; // Tách sidebar riêng cho gọn
            require_once ROOT_PATH . "/app/Views/" . $view . ".php";
            require_once ROOT_PATH . "/app/Views/admin/layouts/footer.php";
        } 
        // Nếu là view client thì load layout client
        elseif (strpos($view, 'client/') === 0 && strpos($view, 'auth') === false) {
            require_once ROOT_PATH . "/app/Views/client/layouts/header.php";
            require_once ROOT_PATH . "/app/Views/" . $view . ".php";
            require_once ROOT_PATH . "/app/Views/client/layouts/footer.php";
        }
        // Các trường hợp khác (Login, Register, Ajax...) không cần layout
        else {
            require_once ROOT_PATH . "/app/Views/" . $view . ".php";
        }
    }
}
?>