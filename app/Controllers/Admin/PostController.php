<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class PostController extends Controller {
  public function __construct() {
        // Sử dụng phương thức đã định nghĩa ở Middleware mới để cho phép cả Admin và Staff
        AuthMiddleware::isStaffArea(); 
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
            // [SỬA LẠI] Đường dẫn lưu trữ vào thư mục con 'posts'
            $targetDir = ROOT_PATH . "/public/uploads/posts/";
            
            // Tự động tạo thư mục nếu chưa có
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $thumbnail = null;
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
                // Đổi tên file để tránh trùng lặp & an toàn
                $filename = time() . '_' . basename($_FILES["thumbnail"]["name"]);
                // Di chuyển file vào thư mục đích
                if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $targetDir . $filename)) {
                    $thumbnail = $filename; // Chỉ lưu tên file vào DB
                }
            }

            $slug = $this->createSlug($_POST['title']);

            $data = [
                'title'     => $_POST['title'],
                'slug'      => $slug,
                'thumbnail' => $thumbnail,
                'excerpt'   => $_POST['excerpt'],
                'content'   => $_POST['content'],
                'user_id'   => $_SESSION['user_id'] ?? 1, // Fallback ID 1 nếu lỗi session
                'status'    => isset($_POST['status']) ? 1 : 0
            ];

            $model = $this->model('PostModel');
            if ($model->add($data)) {
                header('Location: ' . BASE_URL . 'admin/post');
                exit;
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
            
            // Giữ lại ảnh cũ mặc định
            $thumbnail = $currentPost['thumbnail'];
            
            // [SỬA LẠI] Upload ảnh mới (nếu có) vào thư mục 'posts'
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
                $targetDir = ROOT_PATH . "/public/uploads/posts/";
                if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

                $filename = time() . '_' . basename($_FILES["thumbnail"]["name"]);
                
                if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $targetDir . $filename)) {
                    $thumbnail = $filename;
                    
                    // (Tùy chọn) Xóa ảnh cũ để tiết kiệm dung lượng
                    // if ($currentPost['thumbnail'] && file_exists($targetDir . $currentPost['thumbnail'])) {
                    //    unlink($targetDir . $currentPost['thumbnail']);
                    // }
                }
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
                exit;
            } else {
                echo "Lỗi cập nhật.";
            }
        }
    }

    public function delete($id) {
        $model = $this->model('PostModel');
        // (Tùy chọn) Lấy thông tin bài viết để xóa file ảnh vật lý trước khi xóa DB
        $model->delete($id);
        header('Location: ' . BASE_URL . 'admin/post');
    }

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