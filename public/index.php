<?php
session_start();

// Load Config
require_once dirname(__DIR__) . '/config/config.php';
require_once __DIR__ . '/../app/Core/Helpers.php';


// Autoload Class
spl_autoload_register(function ($className) {
    // Chuyển Namespace thành đường dẫn (Ví dụ App\Core\App => app/Core/App.php)
    $file = ROOT_PATH . '/' . str_replace('\\', '/', $className) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Khởi chạy App
$app = new App\Core\App();
?>