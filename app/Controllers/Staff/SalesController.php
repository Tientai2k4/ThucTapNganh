<?php
namespace App\Controllers\Staff;

use App\Core\Controller;
use App\Core\AuthMiddleware;

class SalesController extends Controller {

    public function __construct() {
        // Bảo vệ: Chỉ Sales Staff và Admin mới được vào
        AuthMiddleware::isSales();
    }

    // --- QUẢN LÝ ĐƠN HÀNG (SÂU SẮC) ---
    public function orders() {
        $model = $this->model('OrderModel');
        
        $filters = [
            'keyword' => $_GET['keyword'] ?? '',
            'status'  => $_GET['status'] ?? '',
            'sort'    => $_GET['sort'] ?? 'newest'
        ];

        $orders = $model->getFilterOrders($filters);

        $this->view('staff/sales/orders', [
            'title'   => 'Quản lý Đơn hàng Chuyên nghiệp',
            'orders'  => $orders,
            'filters' => $filters
        ]);
    }

    public function orderDetail($code) {
        $model = $this->model('OrderModel');
        $order = $model->getOrderByCode($code);
        
        if (!$order) {
            $_SESSION['error'] = "Đơn hàng không tồn tại!";
            header('Location: ' . BASE_URL . 'staff/sales/orders');
            exit;
        }

        $this->view('staff/sales/order_detail', [
            'order'   => $order,
            'details' => $model->getOrderDetails($order['id'])
        ]);
    }

    // Cập nhật trạng thái đơn hàng (Có kiểm tra quyền Hủy)
    public function updateOrderStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['order_id'];
            $status = $_POST['status'];
            $code = $_POST['order_code'];
            $model = $this->model('OrderModel');

            // Nghiên cứu sâu: Nhân viên bán hàng chỉ được duyệt, không được hủy đơn nếu không là Admin
            if ($status == 'cancelled' && $_SESSION['user_role'] != 'admin') {
                echo "<script>alert('Bạn không có quyền hủy đơn. Vui lòng gửi yêu cầu cho Admin!'); window.history.back();</script>";
                return;
            }

            if ($status == 'cancelled') {
                $model->cancelOrderById($id);
            } else {
                $model->updateStatus($id, $status);
            }

            header('Location: ' . BASE_URL . 'staff/sales/orderDetail/' . $code);
        }
    }

    // --- QUẢN LÝ KHO & SẢN PHẨM (CHI TIẾT) ---
    public function inventory() {
        $productModel = $this->model('ProductModel');
        
        $filters = [
            'keyword'     => $_GET['keyword'] ?? '',
            'category_id' => $_GET['category_id'] ?? '',
            'sort'        => 'newest'
        ];

        $products = $productModel->getAdminList($filters);
        
        $this->view('staff/sales/inventory', [
            'title'    => 'Kiểm kho & Sản phẩm',
            'products' => $products,
            'categories' => $this->model('CategoryModel')->getAll()
        ]);
    }

    // Cập nhật nhanh số lượng tồn kho (Quick Update)
    public function updateStock() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $variantId = $_POST['variant_id'];
            $newStock = $_POST['stock'];
            
            $sql = "UPDATE product_variants SET stock_quantity = ? WHERE id = ?";
            // Thực thi trực tiếp qua connection của model để nhanh
            $model = $this->model('ProductModel');
            $stmt = $model->getConnection()->prepare($sql);
            $stmt->bind_param("ii", $newStock, $variantId);
            
            if ($stmt->execute()) {
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }
        }
    }
}