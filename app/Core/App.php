<?php
namespace App\Core;

class App {
    protected $controller = 'HomeController'; // Mặc định Client
    protected $action = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->getUrl();

        // LOGIC PHÂN LUỒNG: ADMIN hay CLIENT
        $folder = 'Client'; // Mặc định là Client
        
        // Nếu URL bắt đầu bằng 'admin' (ví dụ: /admin/dashboard)
        if (isset($url[0]) && strtolower($url[0]) == 'admin') {
            $folder = 'Admin';
            array_shift($url); // Xóa chữ 'admin' khỏi mảng URL
            
            // Controller mặc định của Admin là Dashboard
            $this->controller = 'DashboardController'; 
        }

        // 1. Kiểm tra File Controller có tồn tại không
        if (isset($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            // Kiểm tra đường dẫn file
            if (file_exists(ROOT_PATH . "/app/Controllers/$folder/" . $controllerName . ".php")) {
                $this->controller = $controllerName;
                array_shift($url);
            }
        }

        // 2. Require Controller và Khởi tạo
        require_once ROOT_PATH . "/app/Controllers/$folder/" . $this->controller . ".php";
        
        $controllerClass = "App\\Controllers\\$folder\\" . $this->controller;
        $this->controller = new $controllerClass;

        // 3. Kiểm tra Method (Action)
        if (isset($url[0])) {
            if (method_exists($this->controller, $url[0])) {
                $this->action = $url[0];
                array_shift($url);
            }
        }

        // 4. Lấy tham số còn lại
        $this->params = $url ? array_values($url) : [];

        // 5. Chạy hàm
        call_user_func_array([$this->controller, $this->action], $this->params);
    }

    public function getUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
?>