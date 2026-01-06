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
        if (isset($_SESSION['user_id'])) {
        $this->model('OrderModel')->cancelMyExpiredOrders($_SESSION['user_id']);
        }
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

        $data = ['totalMoney' => $totalMoney, 'provinces' => [],'cart_items' => $cartItems]; 
        
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
            
            // --- FIX LỖI TẠI ĐÂY: LẤY BIẾN PHONE TRƯỚC ---
            $phone = $_POST['phone'] ?? ''; // Lấy số điện thoại từ form
            
            // LẤY DỮ LIỆU ĐỊA CHỈ TỪ FORM
            $provinceId = $_POST['province_id'] ?? null;
            $districtId = $_POST['district_id'] ?? null;
            $wardCode   = $_POST['ward_code'] ?? null;

            // KIỂM TRA SỐ ĐIỆN THOẠI
            if (!preg_match('/^0[0-9]{9}$/', $phone)) {
                // Lấy lại dữ liệu cũ để hiển thị lại view
                $addressModel = $this->model('AddressModel');
                
                // Cần lấy lại thông tin giỏ hàng để view không bị lỗi thiếu biến $totalMoney hay $cart_items
                $prodModel = $this->model('ProductModel');
                // Kiểm tra nếu giỏ hàng trống thì redirect luôn
                if (empty($_SESSION['cart'])) { header('Location: ' . BASE_URL . 'cart'); exit; }
                
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

                $data = [
                    'error' => 'Số điện thoại không hợp lệ (phải gồm 10 chữ số và bắt đầu bằng số 0).',
                    'provinces' => $addressModel->getAllProvinces(),
                    'totalMoney' => $totalMoney,
                    'cart_items' => $cartItems
                ];
                $this->view('client/checkout/index', $data);
                return; // Dừng hàm tại đây
            }

            // --- KIỂM TRA GIỎ HÀNG RỖNG ---
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                header('Location: ' . BASE_URL . 'cart'); 
                exit;
            }
            
            // 1. CHUẨN BỊ DỮ LIỆU SẢN PHẨM
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

            // 2. XỬ LÝ COUPON
            $discountAmount = $_POST['discount_amount'] ?? 0;
            $couponCode = $_POST['coupon_code'] ?? null;
            $userId = $_SESSION['user_id'] ?? null;

            // --- [THÊM MỚI] KIỂM TRA XEM USER ĐÃ DÙNG MÃ NÀY CHƯA ---
            // Nếu có mã coupon và user đã đăng nhập
            if (!empty($couponCode) && $userId) {
                $orderModel = $this->model('OrderModel');
                $isUsed = $orderModel->checkUserUsedCoupon($userId, $couponCode);
                
                if ($isUsed) {
                     // Nếu đã dùng -> Báo lỗi và trả về View Checkout
                     $addressModel = $this->model('AddressModel');
                     
                     // Tái tạo lại danh sách hiển thị để view không lỗi
                     $displayItems = [];
                     foreach ($products as $p) {
                        $qty = $_SESSION['cart'][$p['variant_id']];
                        $price = ($p['sale_price'] > 0) ? $p['sale_price'] : $p['price'];
                        $p['qty'] = $qty;
                        $p['line_total'] = $price * $qty;
                        $displayItems[] = $p;
                    }

                     $data = [
                        'error' => "Bạn đã sử dụng mã giảm giá '$couponCode' này rồi, vui lòng dùng mã khác hoặc bỏ trống.", 
                        'totalMoney' => $tempTotal,
                        'provinces' => $addressModel->getAllProvinces(),
                        'cart_items' => $displayItems 
                    ];
                    $this->view('client/checkout/index', $data);
                    return; // Dừng việc tạo đơn
                }
            }
            // --- HẾT PHẦN KIỂM TRA USER DÙNG MÃ ---
            
            $finalTotal = $tempTotal - $discountAmount;
            if ($finalTotal < 0) $finalTotal = 0; 

            $customerData = [
                'name' => $_POST['full_name'], 
                'phone' => $phone, // Sử dụng biến $phone đã lấy ở trên
                'email' => $_POST['email'], 
                'address' => $_POST['address_detail']
            ];
            $paymentMethod = $_POST['payment_method']; 
            

            // 3. TẠO ĐƠN VÀ TRỪ KHO
            $orderModel = $this->model('OrderModel');
            $orderCode = $orderModel->createOrder($userId, $customerData, $cartItems, $finalTotal, $paymentMethod, $discountAmount, $couponCode, $provinceId, $districtId, $wardCode);

            if (is_string($orderCode) && strpos($orderCode, 'DH') === 0) { 
                // Thành công
                unset($_SESSION['cart']); // Xóa giỏ hàng

                // --- 4. PHÂN NHÁNH THANH TOÁN ---
                if ($paymentMethod == 'COD') {
                    // Thanh toán khi nhận hàng
                    header('Location: ' . BASE_URL . 'checkout/success?code=' . $orderCode);
                } else {
                    // Thanh toán VietQR qua PayOS
                    $this->processPayOS($orderCode, $finalTotal);
                }
            } else {
                // Lỗi khi tạo đơn
                $error_message = is_string($orderCode) ? $orderCode : 'Lỗi hệ thống không xác định.';
                
                // Cần load lại data để hiển thị lỗi
                $addressModel = $this->model('AddressModel');
                
                // Tái tạo lại danh sách hiển thị
                $displayItems = [];
                foreach ($products as $p) {
                   $qty = $_SESSION['cart'][$p['variant_id']];
                   $price = ($p['sale_price'] > 0) ? $p['sale_price'] : $p['price'];
                   $p['qty'] = $qty;
                   $p['line_total'] = $price * $qty;
                   $displayItems[] = $p;
               }

                $data = [
                    'error' => $error_message, 
                    'totalMoney' => $tempTotal,
                    'provinces' => $addressModel->getAllProvinces(),
                    'cart_items' => $displayItems // Tái sử dụng biến displayItems cho view đỡ lỗi
                ];
                $this->view('client/checkout/index', $data);
            }
        }
    }

    // --- 4. HÀM TẠO LINK THANH TOÁN PAYOS ---
    private function processPayOS($orderCode, $amount) {
        $url = "https://api-merchant.payos.vn/v2/payment-requests";
        
        // PayOS yêu cầu mã đơn là SỐ NGUYÊN (Integer).
        $orderCodeInt = (int)preg_replace('/\D/', '', $orderCode);

        // Dữ liệu tạo link
        $data = [
            "orderCode" => $orderCodeInt,
            "amount" => 10000, // Đang hardcode 10k để test, khi chạy thật hãy đổi thành (int)$amount
            "description" => "DH" . $orderCodeInt, 
            "cancelUrl" => BASE_URL . "checkout", 
            "returnUrl" => BASE_URL . "checkout/payosReturn" 
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
        
        // Bỏ qua SSL (khi dev localhost)
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
            // Cần load lại view đầy đủ nếu có lỗi
            $this->view('client/checkout/index', ['error' => "PayOS Error: " . $msg]);
        }
    }
    
    // --- 5. HÀM XỬ LÝ KHI KHÁCH THANH TOÁN XONG (RETURN URL) ---
    public function payosReturn() {
        $status = $_GET['status'] ?? '';
        $orderCodeNumber = $_GET['orderCode'] ?? '';
        
        $originalOrderCode = "DH" . $orderCodeNumber; 

        if ($status == 'PAID') {
            $this->model('OrderModel')->updateOnlinePaymentSuccess($originalOrderCode, time(), 'VietQR');
            header('Location: ' . BASE_URL . 'checkout/success?code=' . $originalOrderCode);
        } else {
            $this->model('OrderModel')->cancelOrder($originalOrderCode);
            $this->view('client/checkout/failure', ['error' => 'Thanh toán chưa hoàn tất hoặc đã bị hủy.']);
        }
    }

    // TRANG THÔNG BÁO THÀNH CÔNG
    public function success() {
        $orderCode = $_GET['code'] ?? '';

        if (empty($orderCode)) {
            header('Location: ' . BASE_URL); 
            exit;
        }

        $data = [
            'title' => 'Đặt hàng thành công',
            'order_code' => $orderCode
        ];

        $this->view('client/checkout/success', $data);
    }
}
?>