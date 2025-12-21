<?php
namespace App\Services;

class ShippingService {
    private $api_key = "4a606d00-dd69-11f0-a3d6-dac90fb956b5"; 
    private $shop_id = "198605"; 
    private $api_url = "https://dev-online-gateway.ghn.vn/shiip/public-api/";

    // 1. Sửa hàm callApi để trả về toàn bộ kết quả (bao gồm thông báo lỗi)
    private function callApi($endpoint, $data = [], $method = 'POST') {
        $headers = [
            "Token: " . $this->api_key,
            "Content-Type: application/json",
            "ShopId: " . $this->shop_id
        ];

        $url = $this->api_url . $endpoint;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true); // <-- QUAN TRỌNG: Bỏ ['data'] để lấy full lỗi
    }

    // 2. Các hàm lấy địa chỉ phải thêm ['data'] thủ công
    public function getProvinces() { 
        $res = $this->callApi('master-data/province', [], 'GET'); 
        return $res['data'] ?? [];
    }

    public function getDistricts($pId) { 
        $res = $this->callApi('master-data/district', ["province_id" => (int)$pId], 'POST'); 
        return $res['data'] ?? [];
    }

    public function getWards($dId) { 
        $res = $this->callApi('master-data/ward', ["district_id" => (int)$dId], 'POST'); 
        return $res['data'] ?? [];
    }
    
    // 3. Hàm tạo đơn trả về nguyên response để Controller xử lý lỗi
    public function createGHNOrder($order, $items) {
        $data = [
            "payment_type_id" => 2, // 1: Người nhận trả cước, 2: Người gửi trả cước
            "required_note" => "KHONGCHOXEMHANG",
            "to_name" => $order['customer_name'],
            "to_phone" => $order['customer_phone'],
            "to_address" => $order['shipping_address'],
            "to_ward_code" => $order['shipping_ward_code'],
            "to_district_id" => (int)$order['shipping_district_id'],
            "cod_amount" => ($order['payment_method'] == 'COD') ? (int)$order['total_money'] : 0,
            "weight" => 500, "length" => 20, "width" => 15, "height" => 5,
            "service_type_id" => 2,
            "items" => $items
        ];
        return $this->callApi('v2/shipping-order/create', $data, 'POST');
    }

    // 4. Hàm lấy thông tin đơn hàng
    public function getOrderInfo($ghnOrderCode) {
        $res = $this->callApi('v2/shipping-order/detail', ["order_code" => $ghnOrderCode], 'POST');
        return $res['data'] ?? null; 
    }
}