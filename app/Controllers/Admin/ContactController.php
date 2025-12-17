<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class ContactController extends Controller {
    
    public function __construct() {
AuthMiddleware::hasRole();    }

    // Danh sách liên hệ
    public function index() {
        $model = $this->model('ContactModel');
        $contacts = $model->getAll();
        $this->view('admin/contacts/index', ['contacts' => $contacts]);
    }

    // Đánh dấu đã xử lý
    public function mark($id) {
        $model = $this->model('ContactModel');
        $model->markAsRead($id);
        header('Location: ' . BASE_URL . 'admin/contact');
    }

    // Xóa liên hệ
    public function delete($id) {
        $model = $this->model('ContactModel');
        $model->delete($id);
        header('Location: ' . BASE_URL . 'admin/contact');
    }
}
?>