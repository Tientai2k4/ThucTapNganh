<?php
namespace App\Controllers\Admin;
use App\Core\Controller;

class BrandController extends Controller {

    public function index() {
        $model = $this->model('BrandModel');
        $brands = $model->getAll();
        $this->view('admin/brands/index', ['brands' => $brands]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $logo = null;

            // XỬ LÝ UPLOAD ẢNH
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
                // Định nghĩa đường dẫn vật lý và đường dẫn công khai (ROOT_PATH và BASE_URL cần được định nghĩa ở đâu đó như index.php hoặc config)
                $targetDir = ROOT_PATH . "/uploads/"; 
                
                // Kiểm tra thư mục có tồn tại không, nếu không thì tạo
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                // Tạo tên file ngẫu nhiên để tránh trùng
                $fileExtension = pathinfo($_FILES["logo"]["name"], PATHINFO_EXTENSION);
                $fileName = time() . '_' . uniqid() . '.' . $fileExtension; // Tạo tên file ngẫu nhiên hơn
                $targetFile = $targetDir . $fileName;

                if (move_uploaded_file($_FILES["logo"]["tmp_name"], $targetFile)) {
                    $logo = $fileName; // Lưu tên file vào DB
                }
            }
            
            $model = $this->model('BrandModel');
            $model->create($name, $logo);
            // Có thể thêm session flash message ở đây
            header('Location: ' . BASE_URL . 'admin/brand');
            exit; // Thêm exit để đảm bảo không có code nào chạy tiếp
        } else {
            $this->view('admin/brands/create');
        }
    }

    public function edit($id) {
        $model = $this->model('BrandModel');
        $brand = $model->getById($id);

        if (!$brand) {
            header('Location: ' . BASE_URL . 'admin/brand');
            exit;
        }

        $this->view('admin/brands/edit', ['brand' => $brand]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $model = $this->model('BrandModel');
            $currentBrand = $model->getById($id); // Lấy thông tin cũ để xử lý xóa ảnh

            // Kiểm tra xem có upload ảnh mới không
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0 && !empty($_FILES['logo']['tmp_name'])) {
                $targetDir = ROOT_PATH . "/uploads/";

                // XÓA ẢNH CŨ TRƯỚC
                if ($currentBrand && !empty($currentBrand['logo'])) {
                    $oldFilePath = $targetDir . $currentBrand['logo'];
                    if (file_exists($oldFilePath) && is_file($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
                
                // UPLOAD ẢNH MỚI
                $fileExtension = pathinfo($_FILES["logo"]["name"], PATHINFO_EXTENSION);
                $fileName = time() . '_' . uniqid() . '.' . $fileExtension;
                $targetFile = $targetDir . $fileName;

                if (move_uploaded_file($_FILES["logo"]["tmp_name"], $targetFile)) {
                    // Update cả tên và logo mới
                    $model->updateWithLogo($id, $name, $fileName);
                } else {
                    // Nếu upload thất bại, chỉ update tên, giữ logo cũ
                    $model->updateNameOnly($id, $name);
                }
            } else {
                // Không chọn ảnh mới -> Chỉ update tên, giữ nguyên ảnh cũ
                $model->updateNameOnly($id, $name);
            }

            header('Location: ' . BASE_URL . 'admin/brand');
            exit;
        } else {
            header('Location: ' . BASE_URL . 'admin/brand/edit/' . $id);
            exit;
        }
    }
    
    // Thêm logic xóa an toàn hơn, xóa cả ảnh
    public function delete($id) {
        $model = $this->model('BrandModel');
        $brand = $model->getById($id);

        if ($brand) {
             // XÓA ẢNH LIÊN QUAN TRƯỚC
            if (!empty($brand['logo'])) {
                $targetDir = ROOT_PATH . "/uploads/";
                $filePath = $targetDir . $brand['logo'];
                if (file_exists($filePath) && is_file($filePath)) {
                    unlink($filePath);
                }
            }
            // XÓA RECORD TRONG DB
            $model->delete($id);
        }
        
        header('Location: ' . BASE_URL . 'admin/brand');
        exit;
    }
}
?>