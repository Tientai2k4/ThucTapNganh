<?php
namespace App\Controllers\Client;
use App\Core\Controller;

class ApiController extends Controller {

    private $key2 = "kLtgPl8HHhfvMuDHPwKfgfsY4Ydm9eIz"; // Key2 Sandbox

    // URL Callback này sẽ là: BASE_URL . 'api/zalopay_callback'
    // Bạn cần cấu hình đường dẫn này trong Dashboard ZaloPay Sandbox nếu deploy thật
    // Nhưng trên Localhost, ZaloPay không gọi vào máy bạn được, nên hàm này để dành khi up host.
    
    public function zalopay_callback() {
        $result = [];

        try {
            // 1. Nhận dữ liệu JSON từ ZaloPay
            $postdata = file_get_contents('php://input');
            $postdatajson = json_decode($postdata, true);
            
            if (!isset($postdatajson['data']) || !isset($postdatajson['mac'])) {
                 throw new \Exception("Dữ liệu không hợp lệ");
            }

            $mac = $postdatajson["mac"];
            $dataStr = $postdatajson["data"];
            
            // 2. Kiểm tra chữ ký
            $reqMac = hash_hmac("sha256", $dataStr, $this->key2);

            if ($reqMac !== $mac) {
                // Chữ ký sai
                $result["return_code"] = -1;
                $result["return_message"] = "mac not equal";
            } else {
                // 3. Chữ ký đúng -> Giải mã dữ liệu
                $dataInfo = json_decode($dataStr, true);
                
                // Lấy Order Code từ description
                // Format lúc tạo: "Thanh toan don hang #DH123456"
                $description = $dataInfo['description'];
                
                // Regex bắt mã đơn hàng bắt đầu bằng DH
                if (preg_match('/#(DH\d+)/', $description, $matches)) {
                    $orderCode = $matches[1];
                    $zp_trans_id = $dataInfo['zp_trans_id'];
                    
                    // 4. Gọi Model cập nhật
                    $orderModel = $this->model('OrderModel');
                    $orderModel->updatePaymentStatus($orderCode, $zp_trans_id, 'processing');
                    
                    $result["return_code"] = 1;
                    $result["return_message"] = "success";
                } else {
                    $result["return_code"] = 0;
                    $result["return_message"] = "Order Code not found";
                }
            }
        } catch (\Exception $e) {
            $result["return_code"] = 0;
            $result["return_message"] = $e->getMessage();
        }

        // Trả về JSON
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }
}
?>