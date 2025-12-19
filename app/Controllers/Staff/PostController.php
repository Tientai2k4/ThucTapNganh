<?php
namespace App\Controllers\Staff;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class PostController extends Controller {
    public function __construct() {
        AuthMiddleware::isStaffArea();
    }

    public function index() {
        $model = $this->model('PostModel');
        $posts = $model->getAllPosts();
        $this->view('staff/posts/index', ['posts' => $posts]);
    }

    public function create() {
        $this->view('staff/posts/create');
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $targetDir = ROOT_PATH . "/public/uploads/posts/";
            if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

            $thumbnail = null;
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
                $thumbnail = time() . '_' . basename($_FILES["thumbnail"]["name"]);
                move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $targetDir . $thumbnail);
            }

            $data = [
                'title' => $_POST['title'],
                'slug' => $this->createSlug($_POST['title']),
                'thumbnail' => $thumbnail,
                'excerpt' => $_POST['excerpt'],
                'content' => $_POST['content'],
                'user_id' => $_SESSION['user_id'],
                'status' => isset($_POST['status']) ? 1 : 0
            ];

            if ($this->model('PostModel')->add($data)) {
                header('Location: ' . BASE_URL . 'staff/post');
                exit;
            }
        }
    }

    private function createSlug($str) {
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return $str . '-' . time();
    }
}