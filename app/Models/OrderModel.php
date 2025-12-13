<?php
namespace App\Models;
use App\Core\Model;

class OrderModel extends Model {
    
    // Cập nhật hàm createOrder để nhận payment_method
    public function createOrder($userId, $customerData, $cartItems, $totalMoney, $paymentMethod) {
        $this->conn->begin_transaction();
        try {
            // 1. Xác định trạng thái dựa trên phương thức thanh toán
            // Nếu COD: Pending (Chờ xử lý). Nếu ZALOPAY: Pending Payment (Chờ thanh toán)
            $status = ($paymentMethod == 'COD') ? 'pending' : 'pending_payment';

            // 2. Insert Order
            $orderCode = 'DH' . time();
            $sqlOrder = "INSERT INTO orders (user_id, order_code, customer_name, customer_phone, customer_email, shipping_address, total_money, payment_method, status) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($sqlOrder);
            $stmt->bind_param("isssssdss", 
                $userId, $orderCode, $customerData['name'], $customerData['phone'], 
                $customerData['email'], $customerData['address'], $totalMoney, 
                $paymentMethod, $status
            );
            $stmt->execute();
            $orderId = $this->conn->insert_id;

            // 3. Insert Detail & TRỪ KHO (Giữ hàng ngay lập tức)
            $sqlDetail = "INSERT INTO order_details (order_id, product_variant_id, product_name, size, color, quantity, price, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtDetail = $this->conn->prepare($sqlDetail);

            $sqlUpdateStock = "UPDATE product_variants SET stock_quantity = stock_quantity - ? WHERE id = ? AND stock_quantity >= ?";
            $stmtStock = $this->conn->prepare($sqlUpdateStock);

            foreach ($cartItems as $item) {
                // Lưu chi tiết
                $stmtDetail->bind_param("iisssidd", $orderId, $item['variant_id'], $item['name'], $item['size'], $item['color'], $item['qty'], $item['price'], $item['subtotal']);
                $stmtDetail->execute();

                // Trừ kho (Reservation)
                $stmtStock->bind_param("iii", $item['qty'], $item['variant_id'], $item['qty']);
                $stmtStock->execute();
                if ($stmtStock->affected_rows === 0) throw new \Exception("Sản phẩm {$item['name']} hết hàng!");
            }

            $this->conn->commit();
            return $orderCode;
        } catch (\Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    // Hủy đơn & Hoàn kho (Rollback)
    public function cancelOrder($orderCode) {
        // Lấy thông tin đơn hàng để biết sản phẩm nào cần hoàn
        $sql = "SELECT id FROM orders WHERE order_code = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $orderCode);
        $stmt->execute();
        $order = $stmt->get_result()->fetch_assoc();
        
        if (!$order) return false;
        $orderId = $order['id'];

        // Lấy chi tiết đơn hàng
        $sqlDetails = "SELECT product_variant_id, quantity FROM order_details WHERE order_id = ?";
        $stmtDetails = $this->conn->prepare($sqlDetails);
        $stmtDetails->bind_param("i", $orderId);
        $stmtDetails->execute();
        $details = $stmtDetails->get_result()->fetch_all(MYSQLI_ASSOC);

        // Cộng lại kho
        foreach ($details as $item) {
            $update = $this->conn->prepare("UPDATE product_variants SET stock_quantity = stock_quantity + ? WHERE id = ?");
            $update->bind_param("ii", $item['quantity'], $item['product_variant_id']);
            $update->execute();
        }

        // Cập nhật trạng thái đơn thành Cancelled
        $updateOrder = $this->conn->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
        $updateOrder->bind_param("i", $orderId);
        return $updateOrder->execute();
    }

    // Cập nhật trạng thái thanh toán khi ZaloPay báo về (Success)
    public function updatePaymentStatus($orderCode, $zalopayTransId, $status = 'processing') {
        // processing: Đã thanh toán, đang chờ shop giao hàng
        // Lưu mã giao dịch ZaloPay để đối soát sau này
        $sql = "UPDATE orders SET 
                    zalopay_trans_id = ?, 
                    status = ? 
                WHERE order_code = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $zalopayTransId, $status, $orderCode);
        return $stmt->execute();
    }
}
?>