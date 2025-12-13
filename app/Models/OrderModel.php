<?php
namespace App\Models;

use App\Core\Model;

class OrderModel extends Model {

    // --- PHẦN 1: QUẢN LÝ ĐƠN HÀNG ADMIN (Ngày 11) ---

    // Lấy tất cả đơn hàng (Admin)
    public function getAllOrders() {
        // Sắp xếp đơn mới nhất lên đầu
        $sql = "SELECT * FROM orders ORDER BY created_at DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy thông tin đơn hàng theo Mã Code (Dùng cho cả Admin xem chi tiết và tra cứu)
    public function getOrderByCode($code) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE order_code = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Lấy danh sách sản phẩm trong đơn hàng (Chi tiết đơn)
    public function getOrderDetails($orderId) {
        $sql = "SELECT * FROM order_details WHERE order_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Cập nhật trạng thái xử lý đơn hàng (Admin: Duyệt, Giao, Hoàn thành, Hủy)
    public function updateStatus($id, $status) {
        // Nếu chuyển sang trạng thái 'cancelled', cần gọi hàm cancelOrder để hoàn kho
        // Tuy nhiên hàm updateStatus này chỉ cập nhật trạng thái đơn thuần
        $stmt = $this->conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

    // Cập nhật trạng thái thanh toán (Dùng cho VNPAY/ZaloPay IPN)
    public function updatePaymentStatusByCode($code, $status) {
        // 1: Đã thanh toán, 0: Chưa thanh toán
        $stmt = $this->conn->prepare("UPDATE orders SET payment_status = ? WHERE order_code = ?");
        $stmt->bind_param("is", $status, $code);
        return $stmt->execute();
    }

    // --- PHẦN 2: LOGIC TẠO ĐƠN VÀ XỬ LÝ KHO (Checkout & Transaction) ---
    
    // Hàm tạo đơn hàng (Transaction + Inventory Reservation)
    public function createOrder($userId, $customerData, $cartItems, $totalMoney, $paymentMethod) {
        // Bắt đầu transaction để đảm bảo toàn vẹn dữ liệu
        $this->conn->begin_transaction();
        try {
            // 1. Xác định trạng thái ban đầu
            // Nếu COD: 'pending' (Chờ xử lý)
            // Nếu Online (VNPAY/Zalo): 'pending_payment' (Chờ thanh toán)
            $status = ($paymentMethod == 'COD') ? 'pending' : 'pending_payment';
            $paymentStatus = 0; // Mặc định chưa thanh toán

            // 2. Insert vào bảng orders
            $orderCode = 'DH' . time() . rand(100, 999); // Mã đơn hàng duy nhất
            $sqlOrder = "INSERT INTO orders (user_id, order_code, customer_name, customer_phone, customer_email, shipping_address, total_money, payment_method, payment_status, status) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($sqlOrder);
            $stmt->bind_param("isssssdsis", 
                $userId, $orderCode, $customerData['name'], $customerData['phone'], 
                $customerData['email'], $customerData['address'], $totalMoney, 
                $paymentMethod, $paymentStatus, $status
            );
            $stmt->execute();
            $orderId = $this->conn->insert_id;

            // 3. Insert chi tiết đơn hàng & TRỪ KHO NGAY LẬP TỨC
            $sqlDetail = "INSERT INTO order_details (order_id, product_variant_id, product_name, size, color, quantity, price, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtDetail = $this->conn->prepare($sqlDetail);

            // Câu lệnh trừ kho an toàn (Tránh Race Condition)
            $sqlUpdateStock = "UPDATE product_variants SET stock_quantity = stock_quantity - ? WHERE id = ? AND stock_quantity >= ?";
            $stmtStock = $this->conn->prepare($sqlUpdateStock);

            foreach ($cartItems as $item) {
                // Tính thành tiền từng món
                $itemTotal = $item['price'] * $item['qty'];

                // Lưu vào bảng order_details
                $stmtDetail->bind_param("iisssidd", $orderId, $item['variant_id'], $item['name'], $item['size'], $item['color'], $item['qty'], $item['price'], $itemTotal);
                $stmtDetail->execute();

                // Thực hiện trừ kho (Giữ hàng)
                $stmtStock->bind_param("iii", $item['qty'], $item['variant_id'], $item['qty']);
                $stmtStock->execute();

                // Kiểm tra nếu không trừ được dòng nào (nghĩa là hết hàng hoặc stock < qty)
                if ($stmtStock->affected_rows === 0) {
                    throw new \Exception("Sản phẩm {$item['name']} ({$item['size']}/{$item['color']}) không đủ số lượng tồn kho!");
                }
            }

            // Nếu mọi thứ ok, commit transaction
            $this->conn->commit();
            return $orderCode;

        } catch (\Exception $e) {
            // Nếu có lỗi, rollback toàn bộ (Không tạo đơn, không trừ kho)
            $this->conn->rollback();
            return false; // Hoặc ném ngoại lệ để Controller xử lý thông báo lỗi
        }
    }

    // --- PHẦN 3: XỬ LÝ HỦY ĐƠN & HOÀN KHO (Rollback) ---

    // Hủy đơn hàng và tự động cộng lại kho
    public function cancelOrder($orderCode) {
        $this->conn->begin_transaction();
        try {
            // 1. Lấy ID đơn hàng
            $sql = "SELECT id, status FROM orders WHERE order_code = ? FOR UPDATE"; // Khóa dòng dữ liệu để xử lý
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $orderCode);
            $stmt->execute();
            $order = $stmt->get_result()->fetch_assoc();
            
            if (!$order) throw new \Exception("Đơn hàng không tồn tại");
            if ($order['status'] == 'cancelled') throw new \Exception("Đơn hàng đã hủy trước đó");

            $orderId = $order['id'];

            // 2. Lấy danh sách sản phẩm trong đơn để hoàn kho
            $sqlDetails = "SELECT product_variant_id, quantity FROM order_details WHERE order_id = ?";
            $stmtDetails = $this->conn->prepare($sqlDetails);
            $stmtDetails->bind_param("i", $orderId);
            $stmtDetails->execute();
            $details = $stmtDetails->get_result()->fetch_all(MYSQLI_ASSOC);

            // 3. Cộng lại số lượng tồn kho (Rollback Stock)
            $updateStock = $this->conn->prepare("UPDATE product_variants SET stock_quantity = stock_quantity + ? WHERE id = ?");
            
            foreach ($details as $item) {
                $updateStock->bind_param("ii", $item['quantity'], $item['product_variant_id']);
                $updateStock->execute();
            }

            // 4. Cập nhật trạng thái đơn hàng thành 'cancelled'
            $updateOrder = $this->conn->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
            $updateOrder->bind_param("i", $orderId);
            $updateOrder->execute();

            $this->conn->commit();
            return true;
        } catch (\Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    // Cập nhật thông tin giao dịch thanh toán Online (Khi thanh toán thành công)
    public function updateOnlinePaymentSuccess($orderCode, $transId, $gateway = 'VNPAY') {
        // Cập nhật payment_status = 1 (Đã thanh toán)
        // Cập nhật status = 'processing' (Đã thanh toán, chờ giao hàng)
        $sql = "UPDATE orders SET 
                payment_status = 1, 
                status = 'processing', 
                transaction_id = ? 
                WHERE order_code = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $transId, $orderCode);
        return $stmt->execute();
    }
}
?>