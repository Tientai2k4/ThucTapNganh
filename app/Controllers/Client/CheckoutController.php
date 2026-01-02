<?php
namespace App\Controllers\Client;
use App\Core\Controller;
use App\Models\UserModel;
use App\Models\AddressModel;
use App\Models\OrderModel;



class CheckoutController extends Controller {

    
    
   // --- 1. CẤU HÌNH PAYOS 
    private $payos_config = [
        "client_id" => "51569971-2423-4c84-8239-a6cc3f4a94cb",
        "api_key" => "9dfe8a71-1c01-4e95-ad68-db8b2856233d",
        "checksum_key" => "e79303ffa96438e65bb837b3a4dc34752e8c1e1bd90bf7f0474efeab2eca7c69"
    ];

   public function index() {
        // Dọn dẹp đơn treo quá 15 phút trước khi khách mua hàng
        // Để đảm bảo kho hàng hiển thị đúng nhất
    $this->model('OrderModel')->autoCancelExpiredOrders(15);
        if (empty($_SESSION['cart'])) {
            header('Location: ' . BASE_URL . 'cart');
            exit;
        }
        
        $prodModel = $this->model('ProductModel');
        $variantIds = array_keys($_SESSION['cart']);
        $products = $prodModel->getVariantsDetail($variantIds);
        
        $totalMoney = 0;
        $cartItems = [];
        foreach ($products as $p) {
            $qty = $_SESSION['cart'][$p['variant_id']];
            $price = ($p['sale_price'] > 0) ? $p['sale_price'] : $p['price'];
            $totalMoney += $price * $qty;

            $p['qty'] = $qty;
            $p['line_total'] = $price * $qty;
            $cartItems[] = $p;
        }

        $data = ['totalMoney' => $totalMoney, 'provinces' => [],'cart_items' => $cartItems]; // Khởi tạo provinces rỗng
        
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $userModel = $this->model('UserModel');
            $user = $userModel->findById($userId);
            $data['user_email'] = $user['email'] ?? '';
            $data['user_name_from_db'] = $user['name'] ?? ($_SESSION['user_name'] ?? ''); 
            $addrModel = $this->model('AddressModel'); 
            $addresses = $addrModel->getByUserId($userId); 
            $data['addresses'] = $addresses;
            $defaultAddress = null;
            foreach ($addresses as $addr) {
                if ($addr['is_default'] == 1) { $defaultAddress = $addr; break; }
            }
            $data['defaultAddress'] = $defaultAddress; 
        }
            $addressModel = $this->model('AddressModel');
        $data['provinces'] = $addressModel->getAllProvinces();
        $this->view('client/checkout/index', $data);
    }

    // XỬ LÝ NÚT ĐẶT HÀNG
    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // LẤY DỮ LIỆU ĐỊA CHỈ TỪ FORM (MỚI)
                $provinceId = $_POST['province_id'] ?? null;
                $districtId = $_POST['district_id'] ?? null;
                $wardCode   = $_POST['ward_code'] ?? null;
            // --- FIX LỖI 1: KIỂM TRA GIỎ HÀNG RỖNG ---
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                header('Location: ' . BASE_URL . 'cart'); 
                exit;
            }
            

            // 1. CHUẨN BỊ DỮ LIỆU
            $prodModel = $this->model('ProductModel');
            $variantIds = array_keys($_SESSION['cart']);
            $products = $prodModel->getVariantsDetail($variantIds);
            
            $cartItems = [];
            $tempTotal = 0;
            foreach ($products as $p) {
                $qty = $_SESSION['cart'][$p['variant_id']];
                $price = ($p['sale_price'] > 0) ? $p['sale_price'] : $p['price'];
                $subtotal = $price * $qty;
                $tempTotal += $subtotal;
                $cartItems[] = [
                    'variant_id' => $p['variant_id'], 'name' => $p['name'],
                    'size' => $p['size'], 'color' => $p['color'],
                    'qty' => $qty, 'price' => $price, 'subtotal' => $subtotal
                ];
            }
            // 2. XỬ LÝ COUPON (Đoạn mã mới thêm vào)
            $discountAmount = $_POST['discount_amount'] ?? 0;
            $couponCode = $_POST['coupon_code'] ?? null;
            
            // Nên validate lại coupon ở server để bảo mật (tạm thời tin tưởng client)
            $finalTotal = $tempTotal - $discountAmount;
            if ($finalTotal < 0) $finalTotal = 0; // Không được âm tiền

            $customerData = [
                'name' => $_POST['full_name'], 'phone' => $_POST['phone'],
                'email' => $_POST['email'], 'address' => $_POST['address_detail']
            ];
            $paymentMethod = $_POST['payment_method']; 
            $userId = $_SESSION['user_id'] ?? null;

            // 2. TẠO ĐƠN VÀ TRỪ KHO
            $orderModel = $this->model('OrderModel');
            $orderCode = $orderModel->createOrder($userId, $customerData, $cartItems, $finalTotal, $paymentMethod, $discountAmount, $couponCode,$provinceId,$districtId,$wardCode);

            if (is_string($orderCode) && strpos($orderCode, 'DH') === 0) { 
                // Thành công
                unset($_SESSION['cart']); // Xóa giỏ hàng

                // --- 3. PHÂN NHÁNH THANH TOÁN MỚI ---
                if ($paymentMethod == 'COD') {
                    // Thanh toán khi nhận hàng
                    header('Location: ' . BASE_URL . 'checkout/success?code=' . $orderCode);
                } else {
                    // Thanh toán VietQR qua PayOS (Cho mọi trường hợp còn lại)
                    $this->processPayOS($orderCode, $finalTotal);
                }
            } else {
                // Lỗi
                $error_message = is_string($orderCode) ? $orderCode : 'Lỗi hệ thống không xác định.';
                $this->view('client/checkout/index', ['error' => $error_message, 'totalMoney' => $tempTotal]);
            }
        }
    }
        // --- 4. HÀM TẠO LINK THANH TOÁN PAYOS (MỚI) ---
    private function processPayOS($orderCode, $amount) {
        $url = "https://api-merchant.payos.vn/v2/payment-requests";
        
        // PayOS yêu cầu mã đơn là SỐ NGUYÊN (Integer).
        // Ta cắt bỏ chữ "DH" đi. Ví dụ: DH170456 -> 170456
        $orderCodeInt = (int)preg_replace('/\D/', '', $orderCode);

        // Dữ liệu tạo link
        $data = [
            "orderCode" => $orderCodeInt,
            //"amount" => (int)$amount,
            "amount" => 10000,
            "description" => "DH" . $orderCodeInt, 
            "cancelUrl" => BASE_URL . "checkout", // Quay lại trang checkout nếu hủy
            "returnUrl" => BASE_URL . "checkout/payosReturn" // Quay lại đây nếu thành công
        ];

        // Tạo chữ ký (Signature)
        ksort($data);
        $signData = "";
        foreach ($data as $key => $value) {
            $signData .= $key . "=" . $value . "&";
        }
        $signData = rtrim($signData, "&");
        
        $data['signature'] = hash_hmac('sha256', $signData, $this->payos_config['checksum_key']);

        // Gửi API
        $headers = [
            "x-client-id: " . $this->payos_config['client_id'],
            "x-api-key: " . $this->payos_config['api_key'],
            "Content-Type: application/json"
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Bỏ qua SSL (nếu chạy localhost/ngrok bị lỗi SSL)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            $this->model('OrderModel')->cancelOrder($orderCode);
            $this->view('client/checkout/index', ['error' => 'cURL Error: ' . $error]);
            return;
        }
        
        $result = json_decode($response, true);

        if (isset($result['code']) && $result['code'] == '00') {
            // Thành công -> Chuyển hướng sang trang VietQR
            header('Location: ' . $result['data']['checkoutUrl']);
            exit;
        } else {
            // Lỗi từ PayOS
            $this->model('OrderModel')->cancelOrder($orderCode);
            $msg = $result['desc'] ?? 'Lỗi tạo link thanh toán';
            $this->view('client/checkout/index', ['error' => "PayOS Error: " . $msg]);
        }
    }
    
   
    // --- 5. HÀM XỬ LÝ KHI KHÁCH THANH TOÁN XONG (RETURN URL) ---
    public function payosReturn() {
        // Lấy thông tin từ URL
        $status = $_GET['status'] ?? '';
        $orderCodeNumber = $_GET['orderCode'] ?? '';
        
        // Tái tạo lại mã đơn hàng đầy đủ (Thêm chữ DH)
        $originalOrderCode = "DH" . $orderCodeNumber; 

        if ($status == 'PAID') {
            // Cập nhật trạng thái "Đã thanh toán"
            $this->model('OrderModel')->updateOnlinePaymentSuccess($originalOrderCode, time(), 'VietQR');
            
            // Chuyển sang trang báo thành công
            header('Location: ' . BASE_URL . 'checkout/success?code=' . $originalOrderCode);
        } else {
            // Khách hủy hoặc lỗi -> Hủy đơn
            $this->model('OrderModel')->cancelOrder($originalOrderCode);
            $this->view('client/checkout/failure', ['error' => 'Thanh toán chưa hoàn tất hoặc đã bị hủy.']);
        }
    }
    // TRANG THÔNG BÁO THÀNH CÔNG
    public function success() {
        // Lấy mã đơn hàng từ URL (ví dụ: ?code=DH123456)
        $orderCode = $_GET['code'] ?? '';

        if (empty($orderCode)) {
            header('Location: ' . BASE_URL); // Nếu không có mã thì về trang chủ
            exit;
        }

        $data = [
            'title' => 'Đặt hàng thành công',
            'order_code' => $orderCode
        ];

        // Gọi view hiển thị
        $this->view('client/checkout/success', $data);
    }
}
?>