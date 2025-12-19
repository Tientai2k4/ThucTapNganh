<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class CouponController extends Controller {
    public function __construct() {
        // [Kiểm tra chung] Cho phép Admin và Staff quản lý Coupon
AuthMiddleware::isStaffArea();    }

    // Danh sách
    public function index() {
        $model = $this->model('CouponModel');
        $coupons = $model->getAll();
        $this->view('admin/coupons/index', ['coupons' => $coupons]);
    }

    // Form thêm mới
    public function create() {
        // [BẢO VỆ CẤP CAO] Chỉ Admin cấp cao được thêm mã
        AuthMiddleware::onlyAdmin();
        $this->view('admin/coupons/create');
    }

    // Xử lý lưu (Store)
    public function store() {
        // [BẢO VỆ CẤP CAO] Chỉ Admin cấp cao được lưu mã
        AuthMiddleware::onlyAdmin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Kiểm tra tính hợp lệ cơ bản của dữ liệu
            if (empty($_POST['code']) || empty($_POST['discount_value'])) {
                die("Lỗi: Mã code và giá trị giảm giá không được để trống.");
            }

            $data = [
                'code' => strtoupper(trim($_POST['code'])),
                'discount_type' => $_POST['discount_type'],
                'discount_value' => (float)$_POST['discount_value'], // Dùng float cho giá trị chiết khấu
                'min_order_value' => (float)($_POST['min_order_value'] ?? 0),
                'quantity' => (int)($_POST['quantity'] ?? 0),
                'start_date' => $_POST['start_date'],
                'end_date' => $_POST['end_date']
            ];

            $model = $this->model('CouponModel');
            if ($model->add($data)) { 
                header('Location: ' . BASE_URL . 'admin/coupon');
                exit;
            } else {
                echo "Lỗi tạo mã (Có thể trùng mã code hoặc lỗi Database)";
            }
        }
    }

    // Form sửa
    public function edit($id) {
        // [BẢO VỆ CẤP CAO] Chỉ Admin cấp cao được xem form sửa
        AuthMiddleware::onlyAdmin();

        $model = $this->model('CouponModel');
        $coupon = $model->getById($id);

        if (!$coupon) { die("Mã giảm giá không tồn tại."); }

        $this->view('admin/coupons/edit', ['coupon' => $coupon]);
    }

    // Xử lý cập nhật (Update)
    public function update($id) {
        // [BẢO VỆ CẤP CAO] Chỉ Admin cấp cao được cập nhật mã
        AuthMiddleware::onlyAdmin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'code' => strtoupper(trim($_POST['code'])),
                'discount_type' => $_POST['discount_type'],
                'discount_value' => (float)$_POST['discount_value'],
                'min_order_value' => (float)($_POST['min_order_value'] ?? 0),
                'quantity' => (int)($_POST['quantity'] ?? 0),
                'start_date' => $_POST['start_date'],
                'end_date' => $_POST['end_date'],
                'status' => isset($_POST['status']) ? 1 : 0
            ];

            $model = $this->model('CouponModel');
            if ($model->update($id, $data)) {
                header('Location: ' . BASE_URL . 'admin/coupon');
                exit;
            } else {
                echo "Lỗi cập nhật mã (Có thể trùng mã code hoặc lỗi Database)";
            }
        }
    }

    // Xóa
    public function delete($id) {
        // [BẢO VỆ CẤP CAO] Chức năng xóa chỉ dành cho Admin cấp cao
        AuthMiddleware::onlyAdmin();
        
        $model = $this->model('CouponModel');
        $model->delete($id);
        
        header('Location: ' . BASE_URL . 'admin/coupon');
        exit;
    }
}