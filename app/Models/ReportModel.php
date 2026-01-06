<?php
namespace App\Models;
use App\Core\Model;

class ReportModel extends Model {
    
    // --- 1. THỐNG KÊ TỔNG QUAN (COUNTERS) ---
    public function getCounters($date = null) {
        $data = [];
        $dateSql = "";
        if ($date) {
            $date = $this->conn->real_escape_string($date);
            $dateSql = " AND DATE(created_at) = '$date'";
        }

        $data['total_revenue'] = $this->conn->query("SELECT SUM(total_money) FROM orders WHERE status = 'completed' $dateSql")->fetch_row()[0] ?? 0;
        $data['total_orders'] = $this->conn->query("SELECT COUNT(id) FROM orders WHERE status != 'cancelled' $dateSql")->fetch_row()[0] ?? 0;
        $data['total_users'] = $this->conn->query("SELECT COUNT(id) FROM users WHERE role = 'member'")->fetch_row()[0] ?? 0;
        $data['total_products'] = $this->conn->query("SELECT COUNT(id) FROM products WHERE is_active = 1")->fetch_row()[0] ?? 0;
        $data['unread_contacts'] = $this->conn->query("SELECT COUNT(id) FROM contacts WHERE status = 0 $dateSql")->fetch_row()[0] ?? 0;
        
        return $data;
    }

    // --- 2. CÁC HÀM LẤY DỮ LIỆU DASHBOARD (LIMIT) ---

    public function getRecentOrders() {
        // Lấy 10 đơn mới nhất (bất kể trạng thái)
        $sql = "SELECT id, order_code, customer_name, total_money, status, created_at 
                FROM orders 
                ORDER BY created_at DESC LIMIT 10";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function getRecentContacts() {
        $sql = "SELECT id, full_name, email, message, created_at, status 
                FROM contacts 
                ORDER BY created_at DESC LIMIT 5";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }
    
    // [MỚI - QUAN TRỌNG] Hàm lấy danh sách đơn bị hủy để hiển thị bảng
    public function getRecentCancelledOrders() {
        $sql = "SELECT id, order_code, customer_name, total_money, created_at 
                FROM orders 
                WHERE status = 'cancelled' 
                ORDER BY created_at DESC LIMIT 5";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function getLowStockLimit() {
        return $this->getLowStockQuery(10);
    }

    public function getTopCustomersLimit() {
        return $this->getTopCustomersQuery(5);
    }

    // --- 3. CÁC HÀM CHI TIẾT ---

    public function getAllLowStock() {
        return $this->getLowStockQuery(null);
    }

    public function getAllTopCustomers() {
        return $this->getTopCustomersQuery(null);
    }

    public function getRevenueDetail() {
        $sql = "SELECT DATE(created_at) as date, SUM(total_money) as total, COUNT(id) as total_orders, 
                MIN(total_money) as min_order, MAX(total_money) as max_order 
                FROM orders WHERE status = 'completed' GROUP BY DATE(created_at) ORDER BY date DESC LIMIT 30";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    // --- 4. HÀM PRIVATE HỖ TRỢ ---

    private function getTopCustomersQuery($limit = null) {
        $limitSql = $limit ? "LIMIT $limit" : "";
        $sql = "SELECT customer_name, customer_email, customer_phone, COUNT(id) as total_orders, 
                SUM(total_money) as total_spent, MAX(created_at) as last_order_date 
                FROM orders WHERE status = 'completed' GROUP BY customer_email ORDER BY total_spent DESC $limitSql";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    private function getLowStockQuery($limit = null) {
        $limitSql = $limit ? "LIMIT $limit" : "";
        $sql = "SELECT p.id as product_id, p.name, p.image, c.name as cat_name, pv.size, pv.color, pv.stock_quantity 
                FROM product_variants pv JOIN products p ON pv.product_id = p.id 
                JOIN categories c ON p.category_id = c.id WHERE pv.stock_quantity < 10 
                ORDER BY pv.stock_quantity ASC $limitSql";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function getTopSellingProducts($limit = 5) {
        $sql = "SELECT p.id, p.name, p.image, 
                    SUM(od.quantity) as total_sold, 
                    SUM(od.total_price) as total_revenue
                FROM order_details od
                JOIN product_variants pv ON od.product_variant_id = pv.id
                JOIN products p ON pv.product_id = p.id
                JOIN orders o ON od.order_id = o.id
                WHERE o.status = 'completed'
                GROUP BY p.id
                ORDER BY total_sold DESC
                LIMIT $limit";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    // Thống kê số liệu đơn hủy (để hiện ở ô màu đỏ)
    public function getCancelledOrderStats($date = null) {
        $dateSql = "";
        if ($date) {
            $date = $this->conn->real_escape_string($date);
            $dateSql = " AND DATE(created_at) = '$date'";
        }

        $sql = "SELECT COUNT(id) as total_cancelled, 
                    IFNULL(SUM(total_money), 0) as total_lost_revenue 
                FROM orders 
                WHERE status = 'cancelled' $dateSql";
        return $this->conn->query($sql)->fetch_assoc();
    }
}