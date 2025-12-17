<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class PostController extends Controller {
    public function __construct() {
        AuthMiddleware::isAdminOrStaff(); // Admin hoặc Staff đều được viết bài
    }

    public function index() {
        $model = $this->model('PostModel');
        $posts = $model->getAllPosts();
        $this->view('admin/posts/index', ['posts' => $posts]);
    }

    public function create() {
        $this->view('admin/posts/create');
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Xử lý upload ảnh Thumbnail
            $thumbnail = null;
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
                $targetDir = ROOT_PATH . "/public/uploads/";
                $thumbnail = time() . '_blog_' . basename($_FILES["thumbnail"]["name"]);
                move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $targetDir . $thumbnail);
            }

            // Tạo Slug tự động từ Title (Hàm đơn giản)
            $slug = $this->createSlug($_POST['title']);

            $data = [
                'title'     => $_POST['title'],
                'slug'      => $slug,
                'thumbnail' => $thumbnail,
                'excerpt'   => $_POST['excerpt'],
                'content'   => $_POST['content'],
                'user_id'   => $_SESSION['user_id'],
                'status'    => isset($_POST['status']) ? 1 : 0
            ];

            $model = $this->model('PostModel');
            if ($model->add($data)) {
                header('Location: ' . BASE_URL . 'admin/post');
            } else {
                echo "Lỗi thêm bài viết.";
            }
        }
    }

    public function edit($id) {
        $model = $this->model('PostModel');
        $post = $model->getById($id);
        if (!$post) { die('Bài viết không tồn tại'); }
        $this->view('admin/posts/edit', ['post' => $post]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = $this->model('PostModel');
            $currentPost = $model->getById($id);
            
            $thumbnail = $currentPost['thumbnail'];
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
                $targetDir = ROOT_PATH . "/public/uploads/";
                $thumbnail = time() . '_blog_' . basename($_FILES["thumbnail"]["name"]);
                move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $targetDir . $thumbnail);
            }

            $slug = $this->createSlug($_POST['title']);

            $data = [
                'title'     => $_POST['title'],
                'slug'      => $slug,
                'thumbnail' => $thumbnail,
                'excerpt'   => $_POST['excerpt'],
                'content'   => $_POST['content'],
                'status'    => isset($_POST['status']) ? 1 : 0
            ];

            if ($model->update($id, $data)) {
                header('Location: ' . BASE_URL . 'admin/post');
            } else {
                echo "Lỗi cập nhật.";
            }
        }
    }

    public function delete($id) {
        $model = $this->model('PostModel');
        $model->delete($id);
        header('Location: ' . BASE_URL . 'admin/post');
    }

    // Hàm tạo Slug đơn giản (TV1 có thể copy hàm này)
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
        return $str . '-' . time(); // Thêm time để tránh trùng
    }
}
?>