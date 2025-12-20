<?php
namespace App\Controllers\Client;
use App\Core\Controller;

class CartController extends Controller {

    public function index() {
        $cartData = [];
        $totalPrice = 0;

        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            $prodModel = $this->model('ProductModel');
            $variantIds = array_keys($_SESSION['cart']);
            $products = $prodModel->getVariantsDetail($variantIds);

            foreach ($products as $p) {
                $vId = $p['variant_id'];
                $qty = $_SESSION['cart'][$vId];
                $price = ($p['sale_price'] > 0) ? $p['sale_price'] : $p['price'];
                $subtotal = $price * $qty;

                $cartData[] = [
                    'product_id' => $p['product_id'],
                    'variant_id' => $vId,
                    'name' => $p['name'],
                    'image' => $p['image'],
                    'size' => $p['size'],
                    'color' => $p['color'],
                    'price' => $price,
                    'qty' => $qty,
                    'subtotal' => $subtotal
                ];
                $totalPrice += $subtotal;
            }
        }

        $this->view('client/cart/index', [
            'cart' => $cartData,
            'total' => $totalPrice
        ]);
    }

 public function add() {
    // Xóa bỏ mọi ký tự thừa phát sinh trước đó (khoảng trắng, dòng trống)
    if (ob_get_length()) ob_clean();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $variantId = $_POST['variant_id'] ?? null;
        $qty = (int)($_POST['quantity'] ?? 1);

        if ($variantId && $qty > 0) {
            $prodModel = $this->model('ProductModel');
            $stock = $prodModel->getVariantStock($variantId);
            
            if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

            $currentInCart = $_SESSION['cart'][$variantId] ?? 0;
            $newQty = $currentInCart + $qty;

            // Kiểm tra kho
            if ($newQty > $stock) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['status' => false, 'message' => "Kho không đủ! Hiện còn: $stock"]);
                exit;
            }

            // Lưu vào session
            $_SESSION['cart'][$variantId] = $newQty;

            // Tính tổng số lượng hiển thị trên Header
            $totalCount = 0;
            foreach($_SESSION['cart'] as $q) $totalCount += $q;

            // TRẢ VỀ JSON
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status' => true, 
                'message' => 'Đã thêm sản phẩm vào giỏ hàng thành công.', 
                'cart_count' => $totalCount
            ]);
            exit; // Bắt buộc phải có exit
        }
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['status' => false, 'message' => 'Lỗi dữ liệu!']);
    exit;
}
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $variantId = $_POST['variant_id'];
            $qty = (int)$_POST['qty'];
            if ($qty > 0) {
                $_SESSION['cart'][$variantId] = $qty;
            } else {
                unset($_SESSION['cart'][$variantId]);
            }
        }
        header('Location: ' . BASE_URL . 'cart');
        exit;
    }

    public function delete($id) {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header('Location: ' . BASE_URL . 'cart');
        exit;
    }
}