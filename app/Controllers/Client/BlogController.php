<?php
namespace App\Controllers\Client;
use App\Core\Controller;

class BlogController extends Controller {
    
    // Trang danh sách tin tức
    public function index() {
        $model = $this->model('PostModel');
        $posts = $model->getActivePosts();
        
        $data = [
            'title' => 'Tin tức & Kinh nghiệm bơi lội',
            'posts' => $posts
        ];
        $this->view('client/blog/index', $data);
    }

    // Trang chi tiết bài viết
    public function detail($slugOrId) {
        $model = $this->model('PostModel');
        $post = $model->getBySlugOrId($slugOrId);

        if (!$post) {
            header('Location: ' . BASE_URL . 'blog'); // Không tìm thấy thì về trang blog
            exit;
        }

        $data = [
            'title' => $post['title'],
            'post' => $post
        ];
        $this->view('client/blog/detail', $data);
    }
}
?>