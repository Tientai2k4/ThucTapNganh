<?php
namespace App\Controllers\Staff;
use App\Core\Controller;
use App\Core\AuthMiddleware;

class SliderController extends Controller {
    
    public function __construct() {
        // [QUAN TRỌNG] Cho phép Content Staff và Admin
        AuthMiddleware::isContent(); 
    }

    public function index() {
        $model = $this->model('SliderModel');
        $sliders = $model->getAll();
        $this->view('staff/sliders/index', ['sliders' => $sliders]);
    }

    // Form thêm mới
    public function create() {
        $this->view('staff/sliders/create');
    }

    // Xử lý thêm mới
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $targetDir = ROOT_PATH . "/public/uploads/sliders/";
            if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

            $imageName = 'default_slider.jpg';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $imageName = time() . '_' . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $imageName);
            }

            $data = [
                'image'      => $imageName,
                'link_url'   => $_POST['link_url'],
                'sort_order' => (int)$_POST['sort_order'],
                'status'     => isset($_POST['status']) ? 1 : 0
            ];

            $this->model('SliderModel')->add($data);
            header('Location: ' . BASE_URL . 'staff/slider?msg=created');
        }
    }

    // Xóa Slider
    public function delete($id) {
        // Content Staff được phép xóa slider
        $this->model('SliderModel')->delete($id);
        header('Location: ' . BASE_URL . 'staff/slider?msg=deleted');
    }
}
?>