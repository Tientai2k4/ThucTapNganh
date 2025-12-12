<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class CouponController extends Controller {
    public function __construct() {
        AuthMiddleware::isAdmin();
    }

    // Danh sách
    public function index() {
        $model = $this->model('CouponModel');
        $coupons = $model->getAll();
        $this->view('admin/coupons/index', ['coupons' => $coupons]);
    }

    // Form thêm mới
    public function create() {
        $this->view('admin/coupons/create');
    }

    // Xử lý lưu (Store)
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'code' => strtoupper($_POST['code']),
                'discount_type' => $_POST['discount_type'],
                'discount_value' => (int)$_POST['discount_value'],
                'min_order_value' => (int)($_POST['min_order_value'] ?? 0),
                'quantity' => (int)$_POST['quantity'],
                'start_date' => $_POST['start_date'],
                'end_date' => $_POST['end_date']
            ];

            $model = $this->model('CouponModel');
            // Dùng hàm add() trong Model
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
        $model = $this->model('CouponModel');
        $coupon = $model->getById($id);

        if (!$coupon) { die("Mã giảm giá không tồn tại."); }

        $this->view('admin/coupons/edit', ['coupon' => $coupon]);
    }

    // Xử lý cập nhật (Update)
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'code' => strtoupper($_POST['code']),
                'discount_type' => $_POST['discount_type'],
                'discount_value' => (int)$_POST['discount_value'],
                'min_order_value' => (int)($_POST['min_order_value'] ?? 0),
                'quantity' => (int)$_POST['quantity'],
                'start_date' => $_POST['start_date'],
                'end_date' => $_POST['end_date'],
                'status' => isset($_POST['status']) ? 1 : 0 // Trạng thái cho phép bật/tắt
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
        $model = $this->model('CouponModel');
        $model->delete($id);
        header('Location: ' . BASE_URL . 'admin/coupon');
        exit;
    }
}
?>