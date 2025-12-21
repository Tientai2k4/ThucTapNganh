<?php
namespace App\Controllers\Staff;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class ContactController extends Controller {
    
    public function __construct() {
        // Chỉ cho phép Care Staff và Admin
        AuthMiddleware::isCare(); 
    }

    public function index() {
        $model = $this->model('ContactModel');
        $filters = [
            'keyword' => $_GET['keyword'] ?? '',
            'status'  => $_GET['status'] ?? '',
            'sort'    => 'newest'
        ];
        
        $contacts = $model->getFilterList($filters);
        
        // Đếm số chưa đọc
        $unreadCount = count(array_filter($contacts, fn($c) => $c['status'] == 0));

        $this->view('staff/contacts/index', [
            'contacts' => $contacts,
            'filters'  => $filters,
            'unread'   => $unreadCount
        ]);
    }

    // Đánh dấu đã đọc
    public function mark($id) {
        $this->model('ContactModel')->markAsRead($id);
        header('Location: ' . BASE_URL . 'staff/contact');
    }

    // Xóa liên hệ spam
    public function delete($id) {
        $this->model('ContactModel')->delete($id);
        header('Location: ' . BASE_URL . 'staff/contact');
    }
}
?>