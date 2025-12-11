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

    // Hiển thị form thêm mới
    public function create() {
        $this->view('admin/sliders/create');
    }

    // Xử lý thêm mới (Store)
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $imageName = '';
            
            // Xử lý upload ảnh
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = ROOT_PATH . "/public/uploads/sliders/";
                // Tạo thư mục nếu chưa có
                if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }
                
                $imageName = time() . '_slider_' . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $imageName);
            } else {
                die("Vui lòng chọn ảnh cho Slider");
            }

            $data = [
                'image' => $imageName,
                'link_url' => $_POST['link_url'] ?? '#',
                'sort_order' => $_POST['sort_order'] ?? 0,
                'status' => isset($_POST['status']) ? 1 : 0
            ];

            $model = $this->model('SliderModel');
            if ($model->add($data)) {
                header('Location: ' . BASE_URL . 'admin/slider');
            } else {
                echo "Lỗi thêm slider";
            }
        }
    }

    // Hiển thị form sửa
    public function edit($id) {
        $model = $this->model('SliderModel');
        $slider = $model->getById($id);
        if (!$slider) { die("Slider không tồn tại"); }
        $this->view('admin/sliders/edit', ['slider' => $slider]);
    }

    // Xử lý cập nhật (Update)
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = $this->model('SliderModel');
            $currentSlider = $model->getById($id);
            $imageName = $currentSlider['image']; // Giữ ảnh cũ mặc định

            // Nếu có upload ảnh mới
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = ROOT_PATH . "/public/uploads/sliders/";
                $imageName = time() . '_slider_' . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $imageName);
            }

            $data = [
                'image' => $imageName,
                'link_url' => $_POST['link_url'],
                'sort_order' => $_POST['sort_order'],
                'status' => isset($_POST['status']) ? 1 : 0
                ];

            if ($model->update($id, $data)) {
                header('Location: ' . BASE_URL . 'admin/slider');
            } else {
                echo "Lỗi cập nhật slider";
            }
        }
    }

    // Xóa slider
    public function delete($id) {
        $model = $this->model('SliderModel');
        // (Tùy chọn: Xóa file ảnh trong thư mục uploads trước khi xóa DB)
        $model->delete($id);
        header('Location: ' . BASE_URL . 'admin/slider');
    }
}
?>