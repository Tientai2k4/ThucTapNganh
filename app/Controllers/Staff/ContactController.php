<?php
namespace App\Controllers\Staff;
use App\Core\Controller;
use App\Core\AuthMiddleware;
use App\Core\MailHelper; // [QUAN TRỌNG] Để gửi mail

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
            'sort'    => $_GET['sort'] ?? 'newest'
        ];
        
        $contacts = $model->getFilterList($filters);
        
        // Đếm số chưa đọc để hiển thị thông báo
        $unreadCount = count(array_filter($contacts, fn($c) => $c['status'] == 0));

        $this->view('staff/contacts/index', [
            'contacts'    => $contacts,
            'filters'     => $filters,
            'unread'      => $unreadCount,
            'role_prefix' => 'staff' // Để View biết đường dẫn là staff
        ]);
    }

    // [MỚI] Xử lý trả lời Email thật
    public function reply() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $emailKhachHang = $_POST['email'];
            $subject = $_POST['subject'];
            $content = $_POST['content'];

            if (empty($emailKhachHang) || empty($content)) {
                $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Lỗi: Thiếu thông tin gửi mail.'];
                header('Location: ' . BASE_URL . 'staff/contact');
                exit;
            }

            // Gửi mail qua SendGrid
            $isSent = MailHelper::send($emailKhachHang, $subject, $content);

            if ($isSent) {
                // Cập nhật trạng thái thành Đã trả lời (2)
                $this->model('ContactModel')->updateStatus($id, 2);
                $_SESSION['alert'] = ['type' => 'success', 'message' => 'Đã gửi phản hồi thành công!'];
            } else {
                $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gửi mail thất bại. Kiểm tra hệ thống.'];
            }

            header('Location: ' . BASE_URL . 'staff/contact');
            exit;
        }
    }

    // Đánh dấu đã đọc
    public function mark($id) {
        $this->model('ContactModel')->updateStatus($id, 1);
        $_SESSION['alert'] = ['type' => 'info', 'message' => 'Đã đánh dấu đã xem.'];
        header('Location: ' . BASE_URL . 'staff/contact');
    }

    // Xóa liên hệ
    public function delete($id) {
        $this->model('ContactModel')->delete($id);
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Đã xóa tin nhắn.'];
        header('Location: ' . BASE_URL . 'staff/contact');
    }
}
?>