<?php
namespace App\Core;

class App {
    protected $controller = 'HomeController';
    protected $action = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->getUrl();
        $folder = 'Client'; // Mặc định là Client

        // 1. Kiểm tra Admin
        if (isset($url[0]) && strtolower($url[0]) == 'admin') {
            $folder = 'Admin';
            array_shift($url);
            $this->controller = 'DashboardController';
        } 
        // 2. Kiểm tra Client (để URL đẹp hơn, ta cho phép bỏ chữ client)
        elseif (isset($url[0]) && strtolower($url[0]) == 'client') {
            $folder = 'Client';
            array_shift($url);
        }

        // 3. Xác định Controller
        if (isset($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            if (file_exists(ROOT_PATH . "/app/Controllers/$folder/" . $controllerName . ".php")) {
                $this->controller = $controllerName;
                array_shift($url);
            }
        }

        // 4. Require Controller
        require_once ROOT_PATH . "/app/Controllers/$folder/" . $this->controller . ".php";
        $controllerClass = "App\\Controllers\\$folder\\" . $this->controller;
        $this->controller = new $controllerClass;

        // 5. Xác định Method
        if (isset($url[0])) {
            if (method_exists($this->controller, $url[0])) {
                $this->action = $url[0];
                array_shift($url);
            }
        }

        // 6. Chạy
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
?>