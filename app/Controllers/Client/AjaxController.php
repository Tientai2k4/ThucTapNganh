<?php
// app/Controllers/Client/AjaxController.php
namespace App\Controllers\Client;
use App\Core\Controller;

class AjaxController extends Controller {
    
    // API Check Coupon
    public function checkCoupon() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            $code = $data['code'] ?? '';
            $total = $data['total'] ?? 0;

            $couponModel = $this->model('CouponModel');
            $coupon = $couponModel->findByCode($code);

            if ($coupon) {
                if ($total < $coupon['min_order_value']) {
                    echo json_encode(['status' => false, 'message' => 'Đơn hàng chưa đạt giá trị tối thiểu để dùng mã này.']);
                    return;
                }

                // Tính số tiền giảm
                $discount = 0;
                if ($coupon['discount_type'] == 'percent') {
                    $discount = ($total * $coupon['discount_value']) / 100;
                } else {
                    $discount = $coupon['discount_value'];
                }

                echo json_encode([
                    'status' => true,
                    'message' => 'Áp dụng mã thành công!',
                    'discount' => $discount,
                    'code' => $coupon['code']
                ]);
            } else {
                echo json_encode(['status' => false, 'message' => 'Mã giảm giá không tồn tại hoặc đã hết hạn.']);
            }
        }
    }
}
?>