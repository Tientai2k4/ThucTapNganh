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

            // XỬ LÝ UPLOAD ẢNH (Vào thư mục brands)
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
                // [SỬA LẠI] Đường dẫn lưu trữ vào thư mục con 'brands'
                $targetDir = ROOT_PATH . "/public/uploads/brands/"; 
                
                // Kiểm tra thư mục có tồn tại không, nếu không thì tạo
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                // Tạo tên file ngẫu nhiên để tránh trùng
                $fileExtension = pathinfo($_FILES["logo"]["name"], PATHINFO_EXTENSION);
                $fileName = time() . '_' . uniqid() . '.' . $fileExtension;
                $targetFile = $targetDir . $fileName;

                if (move_uploaded_file($_FILES["logo"]["tmp_name"], $targetFile)) {
                    $logo = $fileName; // Lưu tên file vào DB
                }
            }
            
            $model = $this->model('BrandModel');
            $model->create($name, $logo);
            header('Location: ' . BASE_URL . 'admin/brand');
            exit;
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
            $currentBrand = $model->getById($id); 

            // Kiểm tra xem có upload ảnh mới không
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0 && !empty($_FILES['logo']['tmp_name'])) {
                // [SỬA LẠI] Đường dẫn lưu trữ vào thư mục con 'brands'
                $targetDir = ROOT_PATH . "/public/uploads/brands/";
                if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

                // XÓA ẢNH CŨ TRƯỚC (Nếu có)
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
                    // Nếu upload thất bại, chỉ update tên
                    $model->updateNameOnly($id, $name);
                }
            } else {
                // Không chọn ảnh mới -> Chỉ update tên
                $model->updateNameOnly($id, $name);
            }

            header('Location: ' . BASE_URL . 'admin/brand');
            exit;
        }
    }
    
    public function delete($id) {
        $model = $this->model('BrandModel');
        $brand = $model->getById($id);

        if ($brand) {
             // XÓA ẢNH LIÊN QUAN TRƯỚC
            if (!empty($brand['logo'])) {
                // [SỬA LẠI] Đường dẫn xóa
                $targetDir = ROOT_PATH . "/public/uploads/brands/";
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