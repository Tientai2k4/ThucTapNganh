<?php
namespace App\Controllers\Client;
use App\Core\Controller;

class AddressController extends Controller {

    public function getDistricts() {
        header('Content-Type: application/json');
        $province_id = isset($_GET['province_id']) ? (int)$_GET['province_id'] : 0;
        
        if ($province_id > 0) {
            // Gọi Model xử lý
            $model = $this->model('AddressModel');
            $data = $model->getDistrictsByProvince($province_id);
            echo json_encode($data);
        } else {
            echo json_encode([]);
        }
    }

    public function getWards() {
        header('Content-Type: application/json');
        $district_id = isset($_GET['district_id']) ? (int)$_GET['district_id'] : 0;
        
        if ($district_id > 0) {
            // Gọi Model xử lý
            $model = $this->model('AddressModel');
            $data = $model->getWardsByDistrict($district_id);
            echo json_encode($data);
        } else {
            echo json_encode([]);
        }
    }
}