<?php
namespace App\Models;

use App\Core\Model;

class OrderModel extends Model {
    protected $table = 'orders';

    // --- CÁC HÀM CŨ (GIỮ NGUYÊN) ---

    public function getAllOrders() {
        $sql = "SELECT * FROM orders ORDER BY created_at DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getOrderByCode($code) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE order_code = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getOrderDetails($orderId) {
        $sql = "SELECT * FROM order_details WHERE order_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function updateStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

    public function updatePaymentStatusByCode($code, $status) {
        $stmt = $this->conn->prepare("UPDATE orders SET payment_status = ? WHERE order_code = ?");
        $stmt->bind_param("is", $status, $code);
        return $stmt->execute();
    }
    
    // --- PHẦN 1: TẠO ĐƠN & KHO ---
    
    public function createOrder($userId, $customerData, $cartItems, $finalTotal, $paymentMethod, $discountAmount = 0, $couponCode = null) {
        $this->conn->begin_transaction();
        try {
            $status = ($paymentMethod == 'COD') ? 'pending' : 'pending_payment';
            $paymentStatus = 0; 

            $orderCode = 'DH' . time() . rand(100, 999); 
            $sqlOrder = "INSERT INTO orders (user_id, order_code, customer_name, customer_phone, customer_email, shipping_address, total_money, discount_amount, coupon_code, payment_method, payment_status, status) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($sqlOrder);
            $stmt->bind_param("isssssddssis", 
            $userId, $orderCode, $customerData['name'], $customerData['phone'], 
            $customerData['email'], $customerData['address'], 
            $finalTotal, $discountAmount, $couponCode,
            $paymentMethod, $paymentStatus, $status
            );
            $stmt->execute();
            $orderId = $this->conn->insert_id;

            $sqlDetail = "INSERT INTO order_details (order_id, product_variant_id, product_name, size, color, quantity, price, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtDetail = $this->conn->prepare($sqlDetail);

            $sqlUpdateStock = "UPDATE product_variants SET stock_quantity = stock_quantity - ? WHERE id = ? AND stock_quantity >= ?";
            $stmtStock = $this->conn->prepare($sqlUpdateStock);

            foreach ($cartItems as $item) {
                $itemTotal = $item['price'] * $item['qty'];
                $stmtDetail->bind_param("iisssidd", $orderId, $item['variant_id'], $item['name'], $item['size'], $item['color'], $item['qty'], $item['price'], $itemTotal);
                $stmtDetail->execute();

                $stmtStock->bind_param("iii", $item['qty'], $item['variant_id'], $item['qty']);
                $stmtStock->execute();

                if ($stmtStock->affected_rows === 0) {
                    throw new \Exception("Sản phẩm {$item['name']} ({$item['size']}/{$item['color']}) không đủ số lượng tồn kho!");
                }
            }
            $this->conn->commit();
            return $orderCode;

        } catch (\Exception $e) {
            $this->conn->rollback();
            return $e->getMessage();
        }
    }

    // --- PHẦN 2: HỦY ĐƠN (QUAN TRỌNG CHO NGÀY 12) ---

    // [MỚI] Hàm Hủy đơn theo ID (Dùng cho Admin)
    public function cancelOrderById($orderId) {
        $this->conn->begin_transaction();
        try {
            // 1. Lấy trạng thái hiện tại
            $stmt = $this->conn->prepare("SELECT status FROM orders WHERE id = ? FOR UPDATE");
            $stmt->bind_param("i", $orderId);
            $stmt->execute();
            $order = $stmt->get_result()->fetch_assoc();

            if (!$order) throw new \Exception("Đơn hàng không tồn tại");
            if ($order['status'] == 'cancelled') throw new \Exception("Đơn hàng đã hủy trước đó");
            if ($order['status'] == 'completed') throw new \Exception("Không thể hủy đơn đã hoàn thành");

            // 2. Lấy chi tiết đơn hàng để biết cần cộng lại bao nhiêu hàng
            $stmtDetail = $this->conn->prepare("SELECT product_variant_id, quantity FROM order_details WHERE order_id = ?");
            $stmtDetail->bind_param("i", $orderId);
            $stmtDetail->execute();
            $details = $stmtDetail->get_result()->fetch_all(MYSQLI_ASSOC);

            // 3. Cộng lại kho (Rollback Inventory)
            $sqlRestock = "UPDATE product_variants SET stock_quantity = stock_quantity + ? WHERE id = ?";
            $stmtRestock = $this->conn->prepare($sqlRestock);

            foreach ($details as $item) {
                $stmtRestock->bind_param("ii", $item['quantity'], $item['product_variant_id']);
                $stmtRestock->execute();
            }

            // 4. Cập nhật trạng thái đơn thành 'cancelled'
            $sqlUpdate = "UPDATE orders SET status = 'cancelled' WHERE id = ?";
            $stmtUpdate = $this->conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param("i", $orderId);
            $stmtUpdate->execute();

            $this->conn->commit();
            return true;
        } catch (\Exception $e) {
            $this->conn->rollback();
            return $e->getMessage(); // Trả về lỗi để controller hiển thị
        }
    }

    // Hàm Hủy đơn theo Mã (Dùng cho Khách) - Giữ nguyên logic cũ
    public function cancelOrder($orderCode) {
        $this->conn->begin_transaction();
        try {
            $sql = "SELECT id, status FROM orders WHERE order_code = ? FOR UPDATE";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $orderCode);
            $stmt->execute();
            $order = $stmt->get_result()->fetch_assoc();
            
            if (!$order) throw new \Exception("Đơn hàng không tồn tại");
            if ($order['status'] == 'cancelled') throw new \Exception("Đơn hàng đã hủy trước đó");

            $orderId = $order['id'];

            $sqlDetails = "SELECT product_variant_id, quantity FROM order_details WHERE order_id = ?";
            $stmtDetails = $this->conn->prepare($sqlDetails);
            $stmtDetails->bind_param("i", $orderId);
            $stmtDetails->execute();
            $details = $stmtDetails->get_result()->fetch_all(MYSQLI_ASSOC);

            $updateStock = $this->conn->prepare("UPDATE product_variants SET stock_quantity = stock_quantity + ? WHERE id = ?");
            foreach ($details as $item) {
                $updateStock->bind_param("ii", $item['quantity'], $item['product_variant_id']);
                $updateStock->execute();
            }

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

    // Các hàm phụ trợ khác (Giữ nguyên)
    public function updateOnlinePaymentSuccess($orderCode, $transId, $gateway = 'VNPAY') {
        $sql = "UPDATE orders SET 
                payment_status = 1, 
                status = 'processing', 
                transaction_id = ? 
                WHERE order_code = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $transId, $orderCode);
        return $stmt->execute();
    }
    
    public function getOrdersByUserId($userId) {
        $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public static function getStatusName($status) {
        $statusMap = [
            'pending_payment' => 'Chờ thanh toán',
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipped' => 'Đang giao hàng',
            'completed' => 'Đã hoàn thành',
            'cancelled' => 'Đã hủy'
        ];
        return $statusMap[$status] ?? $status;
    }
    // Thêm hàm này để sửa lỗi Fatal Error
   public function getOrdersByStatus($status) {
        // Bây giờ $this->table sẽ có giá trị là 'orders'
        $sql = "SELECT * FROM {$this->table} WHERE status = ?";
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            die("Lỗi SQL: " . $this->conn->error);
        }

        $stmt->bind_param("s", $status);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    // [THÊM MỚI] Hàm lấy danh sách đơn hàng có lọc và sắp xếp cho Admin
    public function getFilterOrders($filters = []) {
        $sql = "SELECT * FROM orders WHERE 1=1";
        
        $types = "";
        $values = [];

        // 1. Lọc theo từ khóa (Mã đơn, Tên khách, SĐT)
        if (!empty($filters['keyword'])) {
            $sql .= " AND (order_code LIKE ? OR customer_name LIKE ? OR customer_phone LIKE ?)";
            $types .= "sss";
            $keyword = "%" . $filters['keyword'] . "%";
            $values[] = $keyword;
            $values[] = $keyword;
            $values[] = $keyword;
        }

        // 2. Lọc theo Trạng thái
        if (!empty($filters['status'])) {
            $sql .= " AND status = ?";
            $types .= "s";
            $values[] = $filters['status'];
        }

        // 3. Sắp xếp
        $sort = $filters['sort'] ?? 'newest';
        switch ($sort) {
            case 'oldest':
                $sql .= " ORDER BY created_at ASC"; // Cũ nhất trước
                break;
            case 'total_desc':
                $sql .= " ORDER BY total_money DESC"; // Tiền cao nhất
                break;
            case 'total_asc':
                $sql .= " ORDER BY total_money ASC"; // Tiền thấp nhất
                break;
            case 'newest':
            default:
                $sql .= " ORDER BY created_at DESC"; // Mới nhất (Mặc định)
                break;
        }

        // Thực thi
        $stmt = $this->conn->prepare($sql);
        
        if (!empty($values)) {
            $bind_params = [];
            $params_ref = array_merge([$types], $values);
            foreach ($params_ref as $key => $value) $bind_params[$key] = &$params_ref[$key];
            call_user_func_array([$stmt, 'bind_param'], $bind_params);
        }

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>