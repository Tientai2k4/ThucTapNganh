<?php
namespace App\Controllers\Staff;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class ContactController extends Controller {
    public function __construct() {
        // Chỉ cho phép Staff và Admin vào khu vực này
        AuthMiddleware::isStaffArea();
    }

    public function index() {
        $model = $this->model('ContactModel');
        $contacts = $model->getAll(); 
        
        // QUAN TRỌNG: Phải gọi đúng thư mục view 'staff/...'
        // Nếu gọi 'admin/contacts/index' thì sidebar nạp vào sẽ là của Admin
        $this->view('staff/contacts/index', [
            'contacts' => $contacts
        ]);
    }

    public function mark($id) {
        $this->model('ContactModel')->markAsRead($id);
        header('Location: ' . BASE_URL . 'staff/contact');
        exit;
    }
}