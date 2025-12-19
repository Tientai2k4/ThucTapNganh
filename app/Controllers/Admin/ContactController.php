<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class ContactController extends Controller {
    
    public function __construct() {
        // Sử dụng phương thức đã định nghĩa ở Middleware
        AuthMiddleware::isStaffArea(); 
    }

    // [CẬP NHẬT] Danh sách liên hệ có lọc
    public function index() {
        $model = $this->model('ContactModel');

        // Lấy bộ lọc từ URL
        $filters = [
            'keyword' => $_GET['keyword'] ?? '',
            'status'  => $_GET['status'] ?? '', // '' là tất cả, 0 là chưa xem, 1 là đã xem
            'sort'    => $_GET['sort'] ?? 'newest'
        ];

        // Gọi hàm lọc mới
        $contacts = $model->getFilterList($filters);

        // Truyền data sang view
        $this->view('admin/contacts/index', [
            'contacts' => $contacts,
            'filters'  => $filters
        ]);
    }

    // Đánh dấu đã xử lý
    public function mark($id) {
        $model = $this->model('ContactModel');
        $model->markAsRead($id);
        
        // Quay lại trang danh sách (giữ lại trang hiện tại nếu có thể, ở đây redirect đơn giản)
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