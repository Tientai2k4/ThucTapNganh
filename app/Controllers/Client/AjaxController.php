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
    // API Cập nhật giỏ hàng
    public function updateCart() {
        // Nhận dữ liệu JSON
        $input = json_decode(file_get_contents('php://input'), true);
        $variantId = $input['variant_id'] ?? 0;
        $qty = $input['qty'] ?? 1;

        // Kiểm tra xem sản phẩm có trong giỏ hàng (Session) không
        if ($variantId && isset($_SESSION['cart'][$variantId])) {
            // Cập nhật số lượng mới vào session
            $_SESSION['cart'][$variantId] = $qty;
            
            // Trả về kết quả thành công
            echo json_encode(['status' => true, 'message' => 'Cập nhật thành công']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ hàng']);
        }
    }
}
?>