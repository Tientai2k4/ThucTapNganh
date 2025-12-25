<?php
namespace App\Controllers\Client;
use App\Core\Controller;

class BlogController extends Controller {
    
    public function index() {
    $model = $this->model('PostModel');

    // --- CẤU HÌNH PHÂN TRANG ---
    $limit = 6; 
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
    if ($page < 1) $page = 1;
    
    $offset = ($page - 1) * $limit;

   
    $total_posts = $model->countActivePosts(); 
    
    
    $total_pages = ceil($total_posts / $limit); 

    
    $posts = $model->getPostsPagination($limit, $offset); 

    $data = [
        'title' => 'Tin tức & Kinh nghiệm bơi lội',
        'posts' => $posts,              
        'current_page' => $page,        
        'total_pages' => $total_pages   
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