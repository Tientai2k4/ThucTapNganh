<?php
// app/Core/App.php
namespace App\Core;

class App {
    protected $controller = 'HomeController';
    protected $action = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->getUrl();

        // 1. Xử lý thư mục và Controller (Admin/Client)
        if (isset($url[0]) && strtolower($url[0]) == 'admin') {
            $folder = 'Admin';
            array_shift($url);
            $this->controller = 'DashboardController'; 
        } else {
            // Đây là phần sửa đổi để Controller Client hoạt động đúng
            // Nếu URL bắt đầu bằng 'client', loại bỏ nó khỏi mảng URL,
            // nhưng Controller mặc định vẫn là 'HomeController' (hoặc bạn có thể tự định nghĩa)
            if (isset($url[0]) && strtolower($url[0]) == 'client') {
                $folder = 'Client';
                array_shift($url); // Xóa chữ 'client' khỏi mảng URL
            } else {
                $folder = 'Client'; // Mặc định là Client
            }
        }
        
        // 2. TÌM Controller
        // Lúc này, $url[0] sẽ là 'auth' (nếu URL gốc là .../client/auth/forgotPassword)
        if (isset($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            // Kiểm tra file Controller có tồn tại trong thư mục $folder không
            if (file_exists(ROOT_PATH . "/app/Controllers/$folder/" . $controllerName . ".php")) {
                $this->controller = $controllerName;
                array_shift($url);
            }
        }

        // 3. Require file Controller
        // Đảm bảo file được load. Nếu $this->controller là 'AuthController', nó sẽ load AuthController.php
        require_once ROOT_PATH . "/app/Controllers/$folder/" . $this->controller . ".php";
        
        // 4. Khởi tạo Class Controller
        $controllerClass = "App\\Controllers\\$folder\\" . $this->controller;
        $this->controller = new $controllerClass;

        // 5. Kiểm tra Action (Method)
        // Lúc này, $url[0] sẽ là 'forgotPassword'
        if (isset($url[0])) {
            if (method_exists($this->controller, $url[0])) {
                $this->action = $url[0];
                array_shift($url);
            }
        }

        // 6. Lấy tham số và Gọi hàm
        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->controller, $this->action], $this->params);
    }

    public function getUrl() {
        // ... giữ nguyên ...
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
?>