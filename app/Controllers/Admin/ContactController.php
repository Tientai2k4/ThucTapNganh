<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware;
use App\Core\MailHelper; // [QUAN TRỌNG] Import MailHelper vào

class ContactController extends Controller {
    
    public function __construct() {
        AuthMiddleware::isStaffArea(); 
    }

    public function index() {
        $model = $this->model('ContactModel');

        $filters = [
            'keyword' => $_GET['keyword'] ?? '',
            'status'  => $_GET['status'] ?? '', 
            'sort'    => $_GET['sort'] ?? 'newest'
        ];

        $contacts = $model->getFilterList($filters);

        $this->view('admin/contacts/index', [
            'contacts' => $contacts,
            'filters'  => $filters
        ]);
    }

    // [CẬP NHẬT] Xử lý gửi phản hồi qua Email thật bằng SendGrid
    public function reply() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 1. Lấy dữ liệu từ Form Modal
            $id = $_POST['id'];
            $emailKhachHang = $_POST['email'];
            $subject = $_POST['subject']; // Tiêu đề mail
            $content = $_POST['content']; // Nội dung mail (HTML)

            // Validate cơ bản
            if (empty($emailKhachHang) || empty($content)) {
                $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Lỗi: Thiếu thông tin email hoặc nội dung.'];
                header('Location: ' . BASE_URL . 'admin/contact');
                exit;
            }

            // 2. Gọi MailHelper để gửi mail thật
            // Hàm này trả về true nếu mã HTTP là 202 (Accepted) của SendGrid
            $isSent = MailHelper::send($emailKhachHang, $subject, $content);

            if ($isSent) {
                // 3. Nếu gửi thành công -> Cập nhật trạng thái trong DB thành "Đã trả lời" (Status = 2)
                $model = $this->model('ContactModel');
                $model->updateStatus($id, 2); 

                $_SESSION['alert'] = ['type' => 'success', 'message' => 'Đã gửi email phản hồi thành công cho khách hàng!'];
            } else {
                // 4. Gửi thất bại (Do sai API Key hoặc lỗi mạng)
                $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gửi mail thất bại! Vui lòng kiểm tra API Key SendGrid hoặc kết nối mạng.'];
            }

            // Quay lại trang danh sách
            header('Location: ' . BASE_URL . 'admin/contact');
            exit;
        }
    }

    public function mark($id) {
        $model = $this->model('ContactModel');
        // Status 1: Đã xem
        $model->updateStatus($id, 1);
        $_SESSION['alert'] = ['type' => 'info', 'message' => 'Đã đánh dấu là đã xem.'];
        header('Location: ' . BASE_URL . 'admin/contact');
    }

    public function delete($id) {
        $model = $this->model('ContactModel');
        if ($model->delete($id)) {
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Đã xóa tin nhắn liên hệ.'];
        } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Lỗi khi xóa dữ liệu.'];
        }
        header('Location: ' . BASE_URL . 'admin/contact');
    }
}
?>