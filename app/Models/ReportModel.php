<?php
namespace App\Models;
use App\Core\Model;

class ReportModel extends Model {
    
    // 1. Doanh thu 7 ngày gần nhất
    public function getRevenueByDate() {
        $sql = "SELECT DATE(created_at) as date, SUM(total_money) as total 
                FROM orders 
                WHERE status = 'completed' 
                GROUP BY DATE(created_at) 
                ORDER BY date DESC LIMIT 7";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    // 2. Top 5 Sản phẩm bán chạy
    public function getTopProducts() {
        $sql = "SELECT product_name, SUM(quantity) as total_sold 
                FROM order_details 
                GROUP BY product_name 
                ORDER BY total_sold DESC LIMIT 5";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    // 3. [MỚI] Sản phẩm sắp hết hàng (Stock < 5)
    public function getLowStockProducts() {
        $sql = "SELECT p.name, pv.size, pv.color, pv.stock_quantity 
                FROM product_variants pv
                JOIN products p ON pv.product_id = p.id
                WHERE pv.stock_quantity < 5
                ORDER BY pv.stock_quantity ASC";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    // 4. [MỚI] Top 10 khách hàng mua nhiều nhất
    public function getTopCustomers() {
        $sql = "SELECT customer_name, customer_email, customer_phone, 
                       COUNT(id) as total_orders, SUM(total_money) as total_spent
                FROM orders
                WHERE status = 'completed'
                GROUP BY customer_email
                ORDER BY total_spent DESC LIMIT 10";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    // 5. [MỚI] Thống kê số liệu tổng quan (Giao diện thẻ Card)
    public function getCounters() {
        $data = [];
        $data['total_revenue'] = $this->conn->query("SELECT SUM(total_money) FROM orders WHERE status = 'completed'")->fetch_row()[0] ?? 0;
        $data['total_orders'] = $this->conn->query("SELECT COUNT(id) FROM orders")->fetch_row()[0] ?? 0;
        $data['total_users'] = $this->conn->query("SELECT COUNT(id) FROM users WHERE role = 'member'")->fetch_row()[0] ?? 0;
        $data['total_products'] = $this->conn->query("SELECT COUNT(id) FROM products")->fetch_row()[0] ?? 0;
        return $data;
    }
}