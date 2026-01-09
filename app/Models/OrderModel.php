<?php
namespace App\Models;

use App\Core\Model;
use App\Services\ShippingService;

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
    

    // Hàm bổ trợ lấy thông tin đơn hàng theo ID
    public function getOrderById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updatePaymentStatusByCode($code, $status) {
        $stmt = $this->conn->prepare("UPDATE orders SET payment_status = ? WHERE order_code = ?");
        $stmt->bind_param("is", $status, $code);
        return $stmt->execute();
    }
    
    // --- PHẦN 1: TẠO ĐƠN & KHO ---
    
    public function createOrder($userId, $customerData, $cartItems, $finalTotal, $paymentMethod, $discountAmount = 0, $couponCode = null,$provinceId = null, $districtId = null, $wardCode = null) {
        $this->conn->begin_transaction();
        try {
            $status = ($paymentMethod == 'COD') ? 'pending' : 'pending_payment';
            $paymentStatus = 0; 

            $orderCode = 'DH' . time() . rand(100, 999); 
            $sqlOrder = "INSERT INTO orders (user_id, order_code, customer_name, customer_phone, customer_email, shipping_address,shipping_province_id, shipping_district_id, shipping_ward_code, total_money, discount_amount, coupon_code, payment_method, payment_status, status) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?)";
            
            $stmt = $this->conn->prepare($sqlOrder);
            $stmt->bind_param("isssssiisddssis", 
            $userId, $orderCode, $customerData['name'], $customerData['phone'], 
            $customerData['email'], $customerData['address'], $provinceId,$districtId,$wardCode,
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

    //  Hàm Hủy đơn theo ID (Dùng chung cho cả Admin, Khách và Auto Cancel)
    public function cancelOrderById($orderId, $reason = '') {
        $this->conn->begin_transaction();
        try {
            // 1. Lấy trạng thái hiện tại & Khóa dòng (FOR UPDATE) để tránh xung đột
            $stmt = $this->conn->prepare("SELECT id, status FROM orders WHERE id = ? FOR UPDATE");
            $stmt->bind_param("i", $orderId);
            $stmt->execute();
            $order = $stmt->get_result()->fetch_assoc();

            if (!$order) {
                throw new \Exception("Đơn hàng không tồn tại");
            }
            
            // Nếu đơn đã hủy hoặc hoàn thành rồi thì dừng lại, không làm gì cả
            if ($order['status'] == 'cancelled' || $order['status'] == 'completed') {
                $this->conn->rollback();
                return false; 
            }

            // 2. Lấy danh sách sản phẩm trong đơn để hoàn kho
            // Quan trọng: Phải lấy đúng product_variant_id và quantity
            $stmtDetail = $this->conn->prepare("SELECT product_variant_id, quantity FROM order_details WHERE order_id = ?");
            $stmtDetail->bind_param("i", $orderId);
            $stmtDetail->execute();
            $details = $stmtDetail->get_result()->fetch_all(MYSQLI_ASSOC);

            if (empty($details)) {
                // Trường hợp lạ: Đơn hàng không có sản phẩm? -> Vẫn cho hủy nhưng log lại nếu cần
            }

            // 3. THỰC HIỆN CỘNG KHO (RESTOCK)
            $sqlRestock = "UPDATE product_variants SET stock_quantity = stock_quantity + ? WHERE id = ?";
            $stmtRestock = $this->conn->prepare($sqlRestock);

            foreach ($details as $item) {
                    if ($item['quantity'] > 0 && $item['product_variant_id'] > 0) {
                        $stmtRestock->bind_param("ii", $item['quantity'], $item['product_variant_id']);
                        $stmtRestock->execute();
                        // KIỂM TRA XEM CÓ DÒNG NÀO ĐƯỢC CẬP NHẬT KHÔNG
                        if ($stmtRestock->affected_rows === 0) {
                            // Có thể ID biến thể không tồn tại hoặc sai
                            throw new \Exception("Không tìm thấy biến thể ID: " . $item['product_variant_id'] . " để cộng kho.");
                        }
                    }
                }

            // 4. Cập nhật trạng thái đơn thành 'cancelled'
            $sqlUpdate = "UPDATE orders SET status = 'cancelled', cancel_reason = ? WHERE id = ?";
            $stmtUpdate = $this->conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param("si", $reason, $orderId);
            
            if (!$stmtUpdate->execute()) {
                throw new \Exception("Lỗi SQL khi cập nhật trạng thái đơn.");
            }

            $this->conn->commit();
            return true;

        } catch (\Exception $e) {
            $this->conn->rollback();
            die($e->getMessage());
            // Bạn có thể ghi log lỗi tại đây để debug: error_log($e->getMessage());
            return $e->getMessage(); 
        }
    }

    // [CHUẨN HÓA] Hàm Hủy đơn theo Mã (Dùng cho Khách Hàng / PayOS)
    public function cancelOrder($orderCode) {
        // Chỉ cần tìm ID từ Mã đơn, sau đó gọi lại hàm cancelOrderById ở trên
        // Cách này đảm bảo logic hoàn kho chỉ nằm ở 1 chỗ duy nhất -> Dễ quản lý, ít lỗi
        $stmt = $this->conn->prepare("SELECT id FROM orders WHERE order_code = ?");
        $stmt->bind_param("s", $orderCode);
        $stmt->execute();
        $order = $stmt->get_result()->fetch_assoc();

        if ($order) {
            return $this->cancelOrderById($order['id']);
        }
        return false;
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

     // Hàm cập nhật mã vận đơn vào Database
    public function updateTrackingCode($orderId, $trackingCode) {
        $stmt = $this->conn->prepare("UPDATE orders SET tracking_code = ? WHERE id = ?");
        $stmt->bind_param("si", $trackingCode, $orderId);
        return $stmt->execute();
    }
    public function getRevenueReport($type = 'date', $from = null, $to = null) {
    // 1. Xác định định dạng nhóm (Group By)
    $dateFormat = "%Y-%m-%d"; // Mặc định theo ngày
    if ($type == 'month') $dateFormat = "%Y-%m-01"; // Gộp về ngày đầu tháng
    if ($type == 'year') $dateFormat = "%Y-01-01";  // Gộp về ngày đầu năm

    // 2. Xây dựng câu SQL
    $sql = "SELECT 
                DATE_FORMAT(created_at, '$dateFormat') as date,
                COUNT(id) as total_orders,
                SUM(total_money) as total,
                MIN(total_money) as min_order,
                MAX(total_money) as max_order
            FROM orders 
            WHERE status = 'completed'"; // Chỉ tính đơn hoàn thành

    // 3. Thêm điều kiện lọc thời gian nếu có
    if ($from) {
        $sql .= " AND created_at >= '" . $this->conn->real_escape_string($from) . " 00:00:00'";
    }
    if ($to) {
        $sql .= " AND created_at <= '" . $this->conn->real_escape_string($to) . " 23:59:59'";
    }

    $sql .= " GROUP BY date ORDER BY date DESC";
    
    return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
  }

  //  Hủy tự động các đơn Online treo quá 15 phút
    public function autoCancelExpiredOrders($minutes = 15) {
        // 1. Tìm các đơn pending_payment quá hạn
        $sql = "SELECT id, order_code FROM orders 
                WHERE status = 'pending_payment' 
                AND created_at < DATE_SUB(NOW(), INTERVAL ? MINUTE)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $minutes);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $count = 0;
        while ($order = $result->fetch_assoc()) {
            // Gọi lại hàm hủy đơn có sẵn (đã bao gồm hoàn kho)
            if ($this->cancelOrderById($order['id'])) {
                $count++;
            }
        }
        return $count; // Trả về số đơn đã hủy
    }
        // [MỚI] Hàm kiểm tra User đã dùng mã Coupon này chưa
    public function checkUserUsedCoupon($userId, $couponCode) {
        // Kiểm tra xem user này đã có đơn hàng nào dùng mã này mà chưa bị hủy không
        $sql = "SELECT id FROM orders WHERE user_id = ? AND coupon_code = ? AND status != 'cancelled' LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $userId, $couponCode);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Nếu tìm thấy dòng nào -> Đã dùng -> return true
        return $result->num_rows > 0;
    }
    // Hủy các đơn hàng đang chờ thanh toán của riêng một user (để giải phóng kho khi họ quay lại trang checkout)
    public function cancelMyExpiredOrders($userId) {
        // Tìm các đơn pending_payment của user này
        $sql = "SELECT id FROM orders 
                WHERE user_id = ? 
                AND status = 'pending_payment'";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $count = 0;
        while ($order = $result->fetch_assoc()) {
            // Sử dụng lại hàm cancelOrderById đã có logic hoàn kho
            if ($this->cancelOrderById($order['id'])) {
                $count++;
            }
        }
        return $count;
    }

    // [FIX LỖI ILLEGAL COLLATION]
    // Sử dụng COLLATE utf8mb4_general_ci khi so sánh với bảng ward
    public function getOrderFullAddress($orderId) {
        $sql = "SELECT 
                    o.shipping_address,
                    p.province_name,
                    d.district_name,
                    w.ward_name
                FROM orders o
                LEFT JOIN ghn_provinces p ON o.shipping_province_id = p.province_id
                LEFT JOIN ghn_districts d ON o.shipping_district_id = d.district_id
                -- [QUAN TRỌNG] Ép kiểu bảng mã ở đây để tránh lỗi Illegal mix of collations
                LEFT JOIN ghn_wards w ON o.shipping_ward_code COLLATE utf8mb4_general_ci = w.ward_code
                WHERE o.id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        if ($result) {
            // Nối chuỗi địa chỉ: Số nhà, Xã, Huyện, Tỉnh
            $fullAddress = $result['shipping_address'];
            if (!empty($result['ward_name'])) $fullAddress .= ", " . $result['ward_name'];
            if (!empty($result['district_name'])) $fullAddress .= ", " . $result['district_name'];
            if (!empty($result['province_name'])) $fullAddress .= ", " . $result['province_name'];
            return $fullAddress;
        }
        
        return ""; // Trả về rỗng nếu không tìm thấy
    }
}
?>