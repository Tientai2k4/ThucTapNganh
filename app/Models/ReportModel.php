<?php
namespace App\Models;
use App\Core\Model;

class ReportModel extends Model {
    
    public function getRevenueByDate() {
        $sql = "SELECT DATE(created_at) as date, SUM(total_money) as total 
                FROM orders 
                WHERE status = 'completed' 
                GROUP BY DATE(created_at) 
                ORDER BY date DESC LIMIT 7";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function getTopProducts() {
        $sql = "SELECT product_name, SUM(quantity) as total_sold 
                FROM order_details 
                GROUP BY product_name 
                ORDER BY total_sold DESC LIMIT 5";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }
}
?>