<?php
namespace App\Controllers\Staff;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class PostController extends Controller {
    public function __construct() {
        AuthMiddleware::hasRole(['staff']);
    }

    public function index() {
        $model = $this->model('PostModel');
        
        // [SỬA LỖI TẠI ĐÂY] Model bạn cung cấp dùng tên hàm là getAllPosts()
        $posts = $model->getAllPosts(); 
        
        // Sử dụng lại View của Admin, truyền prefix staff
        $this->view('admin/posts/index', [
            'posts' => $posts,
            'role_prefix' => 'staff'
        ]);
    }

    // Staff thêm bài viết
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Xử lý upload ảnh (Giản lược để code ngắn gọn, bạn copy logic upload từ Admin qua nếu cần)
            $thumbnail = 'default.jpg'; 
            if (!empty($_FILES['thumbnail']['name'])) {
                $target_dir = "public/uploads/";
                $thumbnail = time() . "_" . basename($_FILES["thumbnail"]["name"]);
                move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_dir . $thumbnail);
            }

            $data = [
                'title' => $_POST['title'],
                'slug' => $this->createSlug($_POST['title']),
                'thumbnail' => $thumbnail,
                'excerpt' => $_POST['excerpt'],
                'content' => $_POST['content'],
                'user_id' => $_SESSION['user_id'],
                'status' => 1 // Mặc định Staff viết là hiện (hoặc 0 nếu muốn chờ duyệt)
            ];

            $model = $this->model('PostModel');
            $model->add($data);
            
            header('Location: ' . BASE_URL . 'staff/post');
            exit;
        }
        
        // Dùng View tạo bài viết của Admin
        $this->view('admin/posts/create', ['role_prefix' => 'staff']);
    }

    // Hàm tạo slug đơn giản (Hỗ trợ tiếng Việt)
    private function createSlug($str) {
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return $str;
    }
}