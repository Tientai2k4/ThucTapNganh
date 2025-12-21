<?php
namespace App\Controllers\Admin;
use App\Core\Controller;

class TrackingController extends Controller {
    public function getOrderStatus() {
        $code = $_GET['order_code'] ?? '';
        
        // Nạp Service
        require_once dirname(dirname(dirname(__DIR__))) . '/app/Services/ShippingService.php';
        $service = new \App\Services\ShippingService();
        
        // Gọi hàm mới trong ShippingService (Bạn nhớ thêm hàm getOrderInfo vào file Service nhé)
        $info = $service->getOrderInfo($code);
        
        if ($info) {
            echo json_encode(['success' => true, 'status' => $info['status'], 'log' => $info['logs'] ?? []]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy đơn hàng bên GHN']);
        }
    }
}