<?php
namespace App\Core;

class App {
    protected $controller = 'HomeController';
    protected $action = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->getUrl();
        $folder = 'Client'; // Mặc định thư mục là Client

        // 1. Phân luồng thư mục Controller (Admin / Staff / Client)
        if (isset($url[0])) {
            $firstParam = strtolower($url[0]);
            
            if ($firstParam == 'admin') {
                $folder = 'Admin';
                $this->controller = 'DashboardController'; // Controller mặc định của Admin
                array_shift($url);
            } 
            elseif ($firstParam == 'staff') {
                $folder = 'Staff';
                $this->controller = 'DashboardController'; // Controller mặc định của Staff
                array_shift($url);
            } 
            elseif ($firstParam == 'client') {
                // Nếu URL có chữ /client/ thì bỏ đi để folder vẫn là Client
                array_shift($url);
            }
        }

        // 2. Xác định tên Controller cụ thể từ URL
        if (isset($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            if (file_exists(ROOT_PATH . "/app/Controllers/$folder/" . $controllerName . ".php")) {
                $this->controller = $controllerName;
                array_shift($url);
            }
        }

        // 3. Nạp file Controller và Khởi tạo class
        require_once ROOT_PATH . "/app/Controllers/$folder/" . $this->controller . ".php";
        $controllerClass = "App\\Controllers\\$folder\\" . $this->controller;
        $this->controller = new $controllerClass;

        // 4. Xác định Action (Hàm) trong Controller
        if (isset($url[0])) {
            if (method_exists($this->controller, $url[0])) {
                $this->action = $url[0];
                array_shift($url);
            }
        }

        // 5. Khởi chạy ứng dụng với tham số còn lại
        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->controller, $this->action], $this->params);
    }

    public function getUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}