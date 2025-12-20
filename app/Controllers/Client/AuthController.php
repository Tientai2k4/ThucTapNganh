<?php
namespace App\Controllers\Client;
use App\Core\Controller;

class AuthController extends Controller {

// Sửa lại hàm index để tự động đăng nhập nếu có Cookie
public function index() {
    if (isset($_SESSION['user_id'])) {
        $model = $this->model('UserModel');
        $user = $model->findById($_SESSION['user_id']);
        $this->redirectUser($user['role']);
    } elseif (isset($_COOKIE['remember_user'])) {
        // Tự động đăng nhập từ Cookie
        $userId = $_COOKIE['remember_user'];
        $model = $this->model('UserModel');
        $user = $model->findById($userId);
        if ($user) {
            $this->setUserSession($user);
            $this->redirectUser($user['role']);
        }
    }
    $this->login();
}
    // === LOGIN ===
    public function login() {
        $this->view('client/auth/login');
    }

public function processLogin() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $remember = isset($_POST['remember']); // Kiểm tra xem người dùng có tích nút Lưu không

        $model = $this->model('UserModel');
        $user = $model->login($email, $password);

        if ($user) {
            $this->setUserSession($user);

            // === XỬ LÝ LƯU THÔNG TIN (COOKIE) ===
            if ($remember) {
                // Tạo một token ngẫu nhiên hoặc lưu ID người dùng (an toàn hơn là tạo token trong DB)
                // Ở đây tôi ví dụ lưu ID đơn giản, thực tế nên dùng Token
                setcookie('remember_user', $user['id'], time() + (86400 * 30), "/"); // Lưu trong 30 ngày
            }

            $this->redirectUser($user['role']);
        } else {
            $this->view('client/auth/login', ['error' => 'Sai email hoặc mật khẩu']);
        }
    }
}

    // === REGISTER ===
    public function register() {
        $this->view('client/auth/register');
    }

    public function processRegister() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['full_name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $pass = $_POST['password'];
            $confirm = $_POST['confirm_password'];

            if ($pass != $confirm) {
                $this->view('client/auth/register', ['error' => 'Mật khẩu nhập lại không khớp']);
                return;
            }

            $model = $this->model('UserModel');
            $result = $model->register($name, $email, $pass, $phone);

            if ($result === true) {
                $this->view('client/auth/login', ['success' => 'Đăng ký thành công! Mời đăng nhập.']);
            } else {
                $this->view('client/auth/register', ['error' => $result]);
            }
        }
    }




public function googleCallback() {
        if (isset($_GET['code'])) {
            $code = $_GET['code'];
            
            // 1. Lấy Access Token từ Google
            $tokenUrl = 'https://oauth2.googleapis.com/token';
            $postData = [
                'code' => $code,
                'client_id' => GOOGLE_CLIENT_ID,
                'client_secret' => GOOGLE_CLIENT_SECRET,
                'redirect_uri' => GOOGLE_REDIRECT_URL,
                'grant_type' => 'authorization_code'
            ];
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $tokenUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = json_decode(curl_exec($ch), true);
            curl_close($ch);
            
            if (isset($response['access_token'])) {
                // 2. Lấy thông tin User
                $infoUrl = 'https://www.googleapis.com/oauth2/v2/userinfo?access_token=' . $response['access_token'];
                $userInfo = json_decode(file_get_contents($infoUrl), true);
                
                // 3. Xử lý logic DB
                $model = $this->model('UserModel');
                
                // Check Google ID
                $user = $model->findByGoogleId($userInfo['id']);
                
                if (!$user) {
                    // Check Email
                    $userByEmail = $model->findByEmail($userInfo['email']);
                    if ($userByEmail) {
                        // Email đã có -> Link Google ID
                        $model->updateGoogleId($userByEmail['id'], $userInfo['id'], $userInfo['picture']);
                        $user = $userByEmail;
                        $user['avatar'] = $userInfo['picture']; // Update session avatar immediately
                    } else {
                        // Tạo mới
                        $newId = $model->createFromGoogle($userInfo);
                        $user = $model->findByGoogleId($userInfo['id']);
                    }
                }
                
                $this->setUserSession($user);
                $this->redirectUser($user['role']);

            } else {
                echo "Lỗi xác thực Google: " . ($response['error_description'] ?? 'Unknown error');
            }
        }
    }
    private function setUserSession($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_avatar'] = $user['avatar'];
    }
private function redirectUser($role) {
        switch ($role) {
            case 'admin':
                // Admin vào trang tổng quát của admin
                header('Location: ' . BASE_URL . 'admin/dashboard');
                break;

            case 'sales_staff':
                // Nhân viên bán hàng vào thẳng trang quản lý đơn hàng/kho
                header('Location: ' . BASE_URL . 'staff/dashboard');
                break;

            case 'content_staff':
                // Nhân viên nội dung vào trang blog/banner
                header('Location: ' . BASE_URL . 'staff/dashboard'); 
                break;

            case 'care_staff':
                // Nhân viên CSKH vào trang liên hệ/review
                header('Location: ' . BASE_URL . 'staff/dashboard');
                break;

            default:
                // Khách hàng về trang chủ
                header('Location: ' . BASE_URL);
                break;
        }
        exit;
    }
// Đảm bảo hàm logout xóa sạch các dấu vết
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_unset();
        session_destroy();
        if (isset($_COOKIE['remember_user'])) {
            setcookie('remember_user', '', time() - 3600, '/');
        }
        header('Location: ' . BASE_URL . 'client/auth/login');
        exit;
    }

    // 1. Hiện Form nhập email
    public function forgotPassword() {
        $this->view('client/auth/forgot_password');
    }

    // 2. Xử lý gửi mail
public function sendResetLink() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        
        // Kiểm tra email có tồn tại trong hệ thống không (Gọi hàm mới tạo)
        $userModel = $this->model('UserModel');
        $user = $userModel->findByEmail($email);

        if (!$user) {
            // Nếu không tìm thấy, báo lỗi hoặc (tốt hơn) báo thành công ảo để tránh lộ thông tin user
            $this->view('client/auth/forgot_password', ['error' => 'Email không tồn tại trong hệ thống.']);
            return; 
        }

        // Tạo Token và Gửi Mail chỉ khi user tồn tại
        $resetModel = $this->model('PasswordResetModel');
        $token = $resetModel->createToken($email);

        if ($token) {
            // Gửi mail
            require_once ROOT_PATH . '/app/Core/MailHelper.php';
            $link = BASE_URL . 'client/auth/resetPassword?token=' . $token;
            $content = "<p>Chào bạn,</p><p>Vui lòng click vào link sau để đặt lại mật khẩu:</p><a href='$link'>Đặt lại mật khẩu</a><p>Hoặc truy cập link: $link</p>";
            
            // Gửi mail thật (với API SendGrid đã cấu hình)
            $isSent = \App\Core\MailHelper::send($email, 'Khôi phục mật khẩu tài khoản Swimming Store', $content);
            
            if ($isSent) {
                 $this->view('client/auth/forgot_password', ['success' => 'Link khôi phục đã được gửi vào email của bạn.']);
            } else {
                 $this->view('client/auth/forgot_password', ['error' => 'Lỗi gửi mail, vui lòng kiểm tra lại cấu hình SendGrid.']);
            }
        } else {
            $this->view('client/auth/forgot_password', ['error' => 'Có lỗi xảy ra khi tạo token, vui lòng thử lại.']);
        }
    }
}

    // 3. Hiện Form đặt mật khẩu mới
    public function resetPassword() {
        $token = $_GET['token'] ?? '';
        $resetModel = $this->model('PasswordResetModel');
        $email = $resetModel->verifyToken($token);

        if ($email) {
            $this->view('client/auth/reset_password', ['token' => $token]);
        } else {
            echo "Link không hợp lệ hoặc đã hết hạn.";
        }
    }

    // 4. Xử lý lưu mật khẩu mới 
    public function processResetPassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $token = $_POST['token'];
            $pass = $_POST['password'];
            $confirm = $_POST['confirm_password'];

            if ($pass != $confirm) {
                $this->view('client/auth/reset_password', ['token' => $token, 'error' => 'Mật khẩu không khớp']);
                return;
            }

            $resetModel = $this->model('PasswordResetModel');
            $email = $resetModel->verifyToken($token);

            if ($email) {
                $resetModel->updatePassword($email, $pass);
                $resetModel->deleteToken($email); // Xóa token để không dùng lại được
                
                header('Location: ' . BASE_URL . 'client/auth/login?success=reset_ok');
            } else {
                echo "Lỗi xác thực token.";
            }
        }
    }
}
?>