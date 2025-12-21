<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Core\AuthMiddleware; // Nhúng Middleware

class SliderController extends Controller {
  public function __construct() {
        // Sử dụng phương thức đã định nghĩa ở Middleware mới để cho phép cả Admin và Staff
        AuthMiddleware::isStaffArea(); 
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
        // [Bảo vệ] Chỉ Admin cấp cao được thêm mới (Tùy chọn)
        // AuthMiddleware::onlyAdmin(); 
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Xử lý upload ảnh: Kiểm tra lỗi chi tiết hơn
            if (!isset($_FILES['image']) || $_FILES['image']['error'] != UPLOAD_ERR_OK) {
                die("Lỗi: Vui lòng chọn ảnh cho Slider hoặc lỗi upload.");
            }
            
            // Kiểm tra và tạo thư mục
            $targetDir = ROOT_PATH . "/public/uploads/sliders/";
            if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }
            
            $imageName = time() . '_' . basename($_FILES["image"]["name"]);
            
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $imageName)) {
                 die("Lỗi: Không thể di chuyển file ảnh. Kiểm tra quyền ghi (0777) thư mục.");
            }
            
            // ÉP KIỂU dữ liệu nhận từ POST
            $data = [
                'image' => $imageName,
                'link_url' => filter_var($_POST['link_url'] ?? '#', FILTER_SANITIZE_URL), // Lọc URL
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'status' => isset($_POST['status']) ? 1 : 0
            ];

            $model = $this->model('SliderModel');
            if ($model->add($data)) {
                header('Location: ' . BASE_URL . 'admin/slider');
                exit; 
            } else {
                // [Nên dùng] Lưu flash message
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
        // [Bảo vệ] Chỉ Admin cấp cao được cập nhật (Tùy chọn)
        // AuthMiddleware::onlyAdmin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = $this->model('SliderModel');
            $currentSlider = $model->getById($id);
            if (!$currentSlider) { die("Slider không tồn tại"); }
            $imageName = $currentSlider['image']; 

            // Nếu có upload ảnh mới
            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $targetDir = ROOT_PATH . "/public/uploads/sliders/";
                $imageName = time() . '_slider_' . basename($_FILES["image"]["name"]);
                
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $imageName)) {
                    // [Tùy chọn] Xóa ảnh cũ
                    // if ($currentSlider['image'] && file_exists($targetDir . $currentSlider['image'])) {
                    //     unlink($targetDir . $currentSlider['image']);
                    // }
                } else {
                    die("Lỗi: Không thể upload ảnh mới.");
                }
            }

            $data = [
                'image' => $imageName,
                'link_url' => filter_var($_POST['link_url'] ?? '#', FILTER_SANITIZE_URL),
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
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
        // [BẢO VỆ CẤP CAO] Chức năng xóa thường chỉ dành cho Admin cấp cao
        AuthMiddleware::onlyAdmin(); 
        
        $model = $this->model('SliderModel');
        $slider = $model->getById($id);
        
        if ($model->delete($id)) {
            // [Tùy chọn] Xóa file ảnh vật lý trên server
            // $filePath = ROOT_PATH . "/public/uploads/sliders/" . $slider['image'];
            // if ($slider['image'] && file_exists($filePath)) {
            //     unlink($filePath);
            // }
        }
        
        header('Location: ' . BASE_URL . 'admin/slider');
        exit;
    }
}