<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class SliderController extends Controller {
    public function __construct() {
        AuthMiddleware::isAdmin();
    }

    public function index() {
        $model = $this->model('SliderModel');
        $sliders = $model->getAll();
        $this->view('admin/sliders/index', ['sliders' => $sliders]);
    }

    public function create() {
        $this->view('admin/sliders/create');
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Xử lý upload ảnh
            if (!isset($_FILES['image']) || $_FILES['image']['error'] != 0) {
                die("Lỗi: Vui lòng chọn ảnh cho Slider hoặc lỗi upload.");
            }
            
            $targetDir = ROOT_PATH . "/public/uploads/sliders/";
            if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }
            
            $imageName = time() . '_' . basename($_FILES["image"]["name"]);
            
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $imageName)) {
                 die("Lỗi: Không thể di chuyển file ảnh. Kiểm tra quyền ghi (0777) thư mục uploads/sliders.");
            }
            
            // ÉP KIỂU dữ liệu nhận từ POST trước khi truyền vào Model
            $data = [
                'image' => $imageName,
                'link_url' => $_POST['link_url'] ?? '#',
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'status' => isset($_POST['status']) ? 1 : 0
            ];

            $model = $this->model('SliderModel');
            if ($model->add($data)) {
                header('Location: ' . BASE_URL . 'admin/slider');
                exit; // Thêm exit để đảm bảo chuyển hướng
            } else {
                echo "Lỗi: Thêm Slider vào Database không thành công. Kiểm tra log DB.";
            }
        }
    }

    public function edit($id) {
        $model = $this->model('SliderModel');
        $slider = $model->getById($id);
        if (!$slider) { die("Slider không tồn tại"); }
        $this->view('admin/sliders/edit', ['slider' => $slider]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = $this->model('SliderModel');
            $currentSlider = $model->getById($id);
            $imageName = $currentSlider['image']; 

            // Nếu có upload ảnh mới
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = ROOT_PATH . "/public/uploads/sliders/";
                $imageName = time() . '_slider_' . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $imageName);
            }

            $data = [
                'image' => $imageName,
                'link_url' => $_POST['link_url'],
                'sort_order' => (int)$_POST['sort_order'],
                'status' => isset($_POST['status']) ? 1 : 0
            ];

            if ($model->update($id, $data)) {
                header('Location: ' . BASE_URL . 'admin/slider');
                exit;
            } else {
                echo "Lỗi cập nhật slider";
            }
        }
    }

    public function delete($id) {
        $model = $this->model('SliderModel');
        $model->delete($id);
        header('Location: ' . BASE_URL . 'admin/slider');
        exit;
    }
}
?>