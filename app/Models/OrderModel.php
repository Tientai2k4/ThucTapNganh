<?php
namespace App\Models;
use App\Core\Model;

class OrderModel extends Model {
    
    // Hàm tạo đơn hàng (Transaction) [cite: 105, 116-120]
    public function createOrder($userId, $customerData, $cartItems, $totalMoney) {
        // 1. Bắt đầu Transaction
        $this->conn->begin_transaction();

        try {
            // 2. Insert bảng orders
            $orderCode = 'DH' . time(); // Mã đơn hàng tự sinh
            $sqlOrder = "INSERT INTO orders (user_id, order_code, customer_name, customer_phone, customer_email, shipping_address, total_money, payment_method, status) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, 'COD', 'pending')";
            
            $stmt = $this->conn->prepare($sqlOrder);
            $stmt->bind_param("isssssd", 
                $userId, 
                $orderCode, 
                $customerData['name'], 
                $customerData['phone'], 
                $customerData['email'], 
                $customerData['address'], 
                $totalMoney
            );
            $stmt->execute();
            $orderId = $this->conn->insert_id; // Lấy ID đơn hàng vừa tạo

            // 3. Insert bảng order_details và Trừ kho
            $sqlDetail = "INSERT INTO order_details (order_id, product_variant_id, product_name, size, color, quantity, price, total_price) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtDetail = $this->conn->prepare($sqlDetail);

            // Câu lệnh trừ kho
            $sqlUpdateStock = "UPDATE product_variants SET stock_quantity = stock_quantity - ? WHERE id = ? AND stock_quantity >= ?";
            $stmtStock = $this->conn->prepare($sqlUpdateStock);

            foreach ($cartItems as $item) {
                // Thêm chi tiết đơn
                $stmtDetail->bind_param("iisssidd", 
                    $orderId, 
                    $item['variant_id'], 
                    $item['name'], 
                    $item['size'], 
                    $item['color'], 
                    $item['qty'], 
                    $item['price'], 
                    $item['subtotal']
                );
                $stmtDetail->execute();

                // Trừ kho [cite: 118, 159]
                $stmtStock->bind_param("iii", $item['qty'], $item['variant_id'], $item['qty']);
                $stmtStock->execute();

                // Kiểm tra nếu trừ lỗi (ví dụ kho không đủ)
                if ($stmtStock->affected_rows === 0) {
                    throw new \Exception("Sản phẩm {$item['name']} (Size: {$item['size']}) không đủ hàng tồn kho!");
                }
            }

            // 4. Commit (Lưu chính thức)
            $this->conn->commit();
            return $orderCode; // Trả về mã đơn hàng thành công

        } catch (\Exception $e) {
            // 5. Rollback (Hoàn tác nếu lỗi)
            $this->conn->rollback();
            return false; // Hoặc return $e->getMessage();
        }
    }
}
?>