<?php
namespace App\Controllers\Client;
use App\Core\Controller;

class CheckoutController extends Controller {

    // Config Sandbox ZaloPay (Key chuẩn Sandbox)
    private $config = [
        "app_id" => 2553,
        "key1" => "PcY4iZIKFCIdgZvA6ueMcMHHUbRLYjPL", // Key tạo đơn hàng
        "key2" => "kLtgPl8HHhfvMuDHPwKfgfsY4Ydm9eIz", // Key kiểm tra trạng thái
        "endpoint" => "https://sb-openapi.zalopay.vn/v2/create"
    ];

    public function index() {
        if (empty($_SESSION['cart'])) {
            header('Location: ' . BASE_URL . 'cart');
            exit;
        }
        $this->view('client/checkout/index');
    }

    // XỬ LÝ NÚT ĐẶT HÀNG
    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // --- FIX LỖI 1: KIỂM TRA GIỎ HÀNG RỖNG ---
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                header('Location: ' . BASE_URL . 'cart'); 
                exit;
            }
            // ------------------------------------------

            // 1. CHUẨN BỊ DỮ LIỆU
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
                    'variant_id' => $p['variant_id'], 'name' => $p['name'],
                    'size' => $p['size'], 'color' => $p['color'],
                    'qty' => $qty, 'price' => $price, 'subtotal' => $subtotal
                ];
            }

            $customerData = [
                'name' => $_POST['full_name'], 'phone' => $_POST['phone'],
                'email' => $_POST['email'], 'address' => $_POST['address']
            ];
            $paymentMethod = $_POST['payment_method']; 
            $userId = $_SESSION['user_id'] ?? null;

            // 2. TẠO ĐƠN VÀ TRỪ KHO
            $orderModel = $this->model('OrderModel');
            $orderCode = $orderModel->createOrder($userId, $customerData, $cartItems, $totalMoney, $paymentMethod);

            if ($orderCode) {
                unset($_SESSION['cart']); // Xóa giỏ hàng

                // 3. PHÂN NHÁNH THANH TOÁN
                if ($paymentMethod == 'COD') {
                    header('Location: ' . BASE_URL . 'checkout/success?code=' . $orderCode);
                } else {
                    $this->processZaloPay($orderCode, $totalMoney);
                }
            } else {
                $this->view('client/checkout/index', ['error' => 'Hết hàng hoặc lỗi hệ thống.']);
            }
        }
    }

    // --- HÀM TẠO URL THANH TOÁN ZALOPAY (ĐÃ FIX LỖI CHỮ KÝ) ---
    private function processZaloPay($orderCode, $amount) {
        $transID = rand(0,1000000); 
        $app_trans_id = date("ymd") . "_" . $transID;

        // Chuẩn bị dữ liệu JSON chính xác
        $embeddata = json_encode(['redirecturl' => BASE_URL . "checkout/zaloReturn?internal_code=" . $orderCode]);
        $items = json_encode([]); // Item phải là chuỗi JSON rỗng "[]" nếu không gửi chi tiết

        $order = [
            "app_id" => $this->config["app_id"],
            "app_user" => "user123",
            "app_time" => round(microtime(true) * 1000), // miliseconds
            "amount" => (int)$amount, // Phải ép kiểu về số nguyên (int)
            "app_trans_id" => $app_trans_id, 
            "embed_data" => $embeddata,
            "item" => $items,
            "description" => "Thanh toan don hang #$orderCode",
            "bank_code" => "zalopayapp", // Mặc định mở ví ZaloPay hoặc QR
        ];

        // --- TẠO CHỮ KÝ (MAC) CHUẨN ---
        // Công thức: appid|app_trans_id|appuser|amount|apptime|embeddata|item
        $data = $order["app_id"] . "|" . $order["app_trans_id"] . "|" . $order["app_user"] . "|" . $order["amount"]
            . "|" . $order["app_time"] . "|" . $order["embed_data"] . "|" . $order["item"];
        
        $order["mac"] = hash_hmac("sha256", $data, $this->config["key1"]);

        // Gửi cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->config["endpoint"]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($order));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Bỏ qua SSL (Fix localhost)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $result = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            $this->model('OrderModel')->cancelOrder($orderCode);
            $this->view('client/checkout/index', ['error' => 'Lỗi cURL: ' . $error]);
            return;
        }

        $result_json = json_decode($result, true);

        if (isset($result_json['return_code']) && $result_json['return_code'] == 1) {
            // Thành công -> Chuyển sang ZaloPay
            header('Location: ' . $result_json['order_url']);
            exit;
        } else {
            // Thất bại
            $this->model('OrderModel')->cancelOrder($orderCode);
            $msg = $result_json['sub_return_message'] ?? $result_json['return_message'] ?? 'Lỗi không xác định';
            $this->view('client/checkout/index', ['error' => 'ZaloPay Error: ' . $msg]);
        }
    }

    // --- XỬ LÝ RETURN ---
    public function zaloReturn() {
        $orderCode = $_GET['internal_code'] ?? null;
        $status = $_GET['status'] ?? 0; 

        if ($status == 1) {
            header('Location: ' . BASE_URL . 'checkout/success?code=' . $orderCode);
        } else {
            if ($orderCode) {
                $this->model('OrderModel')->cancelOrder($orderCode);
            }
            $data = ['error' => 'Giao dịch thanh toán không thành công. Đơn hàng đã bị hủy.'];
            $this->view('client/checkout/failure', $data); 
        }
    }
}
?>