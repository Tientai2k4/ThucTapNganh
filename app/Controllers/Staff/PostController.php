<?php
namespace App\Controllers\Staff;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class PostController extends Controller {
    
    public function __construct() {
        // Chỉ cho phép Content Staff và Admin
        AuthMiddleware::isContent(); 
    }

    // 1. Danh sách bài viết
    public function index() {
        $model = $this->model('PostModel');
        $posts = $model->getAllPosts(); // Lấy danh sách (kèm tên tác giả)
        
        $this->view('staff/posts/index', [
            'posts' => $posts
        ]);
    }

    // 2. Form tạo bài viết
    public function create() {
        $this->view('staff/posts/create');
    }

    // 3. Xử lý lưu bài viết
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $targetDir = ROOT_PATH . "/public/uploads/posts/";
            if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

            $thumbnail = 'default_post.png';
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
                $thumbnail = time() . '_' . basename($_FILES["thumbnail"]["name"]);
                move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $targetDir . $thumbnail);
            }

            $data = [
                'title'     => $_POST['title'],
                'slug'      => $this->createSlug($_POST['title']),
                'thumbnail' => $thumbnail,
                'excerpt'   => $_POST['excerpt'],
                'content'   => $_POST['content'],
                'user_id'   => $_SESSION['user_id'], // Tác giả là người đang đăng nhập
                'status'    => isset($_POST['status']) ? 1 : 0
            ];

            $this->model('PostModel')->add($data);
            header('Location: ' . BASE_URL . 'staff/post?msg=created');
        }
    }

    // 4. Form sửa bài viết
    public function edit($id) {
        $post = $this->model('PostModel')->getById($id);
        if (!$post) die("Bài viết không tồn tại");
        $this->view('staff/posts/edit', ['post' => $post]);
    }

    // 5. Cập nhật bài viết
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = $this->model('PostModel');
            $currentPost = $model->getById($id);
            
            $thumbnail = $currentPost['thumbnail'];
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
                $targetDir = ROOT_PATH . "/public/uploads/posts/";
                $thumbnail = time() . '_' . basename($_FILES["thumbnail"]["name"]);
                move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $targetDir . $thumbnail);
            }

            $data = [
                'title'     => $_POST['title'],
                'slug'      => $this->createSlug($_POST['title']), // Cập nhật slug theo title mới
                'thumbnail' => $thumbnail,
                'excerpt'   => $_POST['excerpt'],
                'content'   => $_POST['content'],
                'status'    => isset($_POST['status']) ? 1 : 0
            ];

            $model->update($id, $data);
            header('Location: ' . BASE_URL . 'staff/post?msg=updated');
        }
    }

    // 6. Xóa bài viết
    public function delete($id) {
        $this->model('PostModel')->delete($id);
        header('Location: ' . BASE_URL . 'staff/post?msg=deleted');
    }

    // Hàm tạo Slug URL thân thiện
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
        return $str . '-' . time();
    }
}
?>