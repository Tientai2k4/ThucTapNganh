<?php
namespace App\Controllers\Client;
use App\Core\Controller;

class CheckoutController extends Controller {

    // Hiển thị form thanh toán
    public function index() {
        if (empty($_SESSION['cart'])) {
            header('Location: ' . BASE_URL . 'cart');
            exit;
        }

        // Nếu đã đăng nhập thì lấy thông tin user điền sẵn
        $user = null;
        if (isset($_SESSION['user_id'])) {
            // (Tùy chọn) Gọi UserModel lấy thông tin chi tiết user
        }

        $this->view('client/checkout/index', ['user' => $user]);
    }

    // Xử lý đặt hàng [cite: 101-113]
    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 1. Lấy dữ liệu từ Session Giỏ hàng (Tính toán lại tiền cho an toàn)
            $cartController = new CartController(); 
            // Lưu ý: Logic lấy lại dữ liệu giỏ hàng nên tách thành hàm public trong Cart hoặc Model để tái sử dụng
            // Ở đây tôi viết gọn lại logic lấy cart:
            $prodModel = $this->model('ProductModel');
            $variantIds = array_keys($_SESSION['cart']);
            $products = $prodModel->getVariantsDetail($variantIds);
            
            $cartItems = [];
            $totalMoney = 0;
            foreach ($products as $p) {
                $qty = $_SESSION['cart'][$p['variant_id']];
                $price = ($p['sale_price'] > 0) ? $p['sale_price'] : $p['price'];
                $subtotal = $price * $qty;
                $totalMoney += $subtotal;
                
                $cartItems[] = [
                    'variant_id' => $p['variant_id'],
                    'name' => $p['name'],
                    'size' => $p['size'],
                    'color' => $p['color'],
                    'qty' => $qty,
                    'price' => $price,
                    'subtotal' => $subtotal
                ];
            }

            // 2. Lấy thông tin khách hàng từ Form
            $customerData = [
                'name' => $_POST['full_name'],
                'phone' => $_POST['phone'],
                'email' => $_POST['email'],
                'address' => $_POST['address']
            ];
            $userId = $_SESSION['user_id'] ?? null;

            // 3. Gọi Model xử lý Transaction (TV1 viết)
            $orderModel = $this->model('OrderModel');
            $orderCode = $orderModel->createOrder($userId, $customerData, $cartItems, $totalMoney);

            if ($orderCode) {
                // 4. Thành công: Xóa giỏ hàng & Chuyển trang [cite: 120-121]
                unset($_SESSION['cart']);
                header('Location: ' . BASE_URL . 'checkout/success?code=' . $orderCode);
            } else {
                // Thất bại
                $this->view('client/checkout/index', ['error' => 'Đặt hàng thất bại! Có thể sản phẩm vừa hết hàng.']);
            }
        }
    }

    public function success() {
        $this->view('client/checkout/success');
    }
}
?>