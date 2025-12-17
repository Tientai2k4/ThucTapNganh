<?php
namespace App\Controllers\Client;
use App\Core\Controller;

class ContactController extends Controller {

    // Hiển thị Form Liên hệ
    public function index() {
        $data = ['title' => 'Liên hệ với chúng tôi'];
        $this->view('client/contact/index', $data);
    }

    // Xử lý gửi Form
    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name'    => htmlspecialchars($_POST['name']),
                'email'   => htmlspecialchars($_POST['email']),
                'phone'   => htmlspecialchars($_POST['phone']),
                'message' => htmlspecialchars($_POST['message'])
            ];

            // 1. Lưu vào Database
            $model = $this->model('ContactModel');
            if ($model->create($data)) {
                
                // 2. Gửi Email thông báo cho Admin (Sử dụng MailHelper có sẵn)
                require_once ROOT_PATH . '/app/Core/MailHelper.php';
                
                $adminEmail = 'dat09269@gmail.com'; // Email Admin nhận thông báo
                $subject = "Liên hệ mới từ: " . $data['name'];
                $content = "
                    <h3>Bạn nhận được liên hệ mới từ Website Swimming Store</h3>
                    <p><strong>Họ tên:</strong> {$data['name']}</p>
                    <p><strong>Email:</strong> {$data['email']}</p>
                    <p><strong>SĐT:</strong> {$data['phone']}</p>
                    <p><strong>Nội dung:</strong><br>{$data['message']}</p>
                    <p><em>Vui lòng đăng nhập trang quản trị để xử lý.</em></p>
                ";
                
                // Gửi mail (Không cần check kết quả mail để tránh làm chậm trải nghiệm user)
                \App\Core\MailHelper::send($adminEmail, $subject, $content);

                // 3. Thông báo thành công
                echo "<script>alert('Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất.'); window.location.href='" . BASE_URL . "contact';</script>";
            } else {
                echo "<script>alert('Lỗi hệ thống. Vui lòng thử lại sau.'); window.history.back();</script>";
            }
        }
    }
}
?>