<?php
// app/Core/App.php
namespace App\Core;

class App {
    protected $controller = 'HomeController'; // Mặc định là Client Home
    protected $action = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->getUrl();

        // Mặc định folder là Client
        $folder = 'Client';

        // Kiểm tra xem có phải truy cập vào Admin không
        if (isset($url[0]) && strtolower($url[0]) == 'admin') {
            $folder = 'Admin';
            array_shift($url); // Xóa chữ 'admin' khỏi mảng URL
            $this->controller = 'DashboardController'; // Mặc định của Admin
        }

        // 1. Kiểm tra file Controller có tồn tại không
        if (isset($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            if (file_exists(ROOT_PATH . "/app/Controllers/$folder/" . $controllerName . ".php")) {
                $this->controller = $controllerName;
                array_shift($url);
            }
        }

        // 2. Require file Controller
        require_once ROOT_PATH . "/app/Controllers/$folder/" . $this->controller . ".php";
        
        // 3. Khởi tạo Class Controller
        $controllerClass = "App\\Controllers\\$folder\\" . $this->controller;
        $this->controller = new $controllerClass;

        // 4. Kiểm tra Action (Method)
        if (isset($url[0])) {
            if (method_exists($this->controller, $url[0])) {
                $this->action = $url[0];
                array_shift($url);
            }
        }

        // 5. Lấy tham số
        $this->params = $url ? array_values($url) : [];

        // 6. Gọi hàm
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