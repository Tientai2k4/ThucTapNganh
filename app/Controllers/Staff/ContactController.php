<?php
namespace App\Controllers\Staff;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class ContactController extends Controller {
    public function __construct() {
        AuthMiddleware::hasRole(['staff']);
    }

    public function index() {
        $model = $this->model('ContactModel');
        // Lưu ý: Đảm bảo ContactModel có hàm getAll(). 
        // Nếu ContactModel dùng getAllContacts() thì sửa lại cho khớp.
        $contacts = $model->getAll(); 
        
        $this->view('admin/contacts/index', [
            'contacts' => $contacts,
            'role_prefix' => 'staff'
        ]);
    }

    // Staff đánh dấu đã xử lý
    public function updateStatus($id) {
        $model = $this->model('ContactModel');
        // Staff chỉ được update status, không được xóa
        $model->markAsRead($id); // Hoặc tên hàm updateStatus($id, 1) tùy Model của bạn
        
        header('Location: ' . BASE_URL . 'staff/contact');
        exit;
    }
}