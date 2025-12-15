<?php
namespace App\Controllers\Client;
use App\Core\Controller;

class CartController extends Controller {

    public function index() {
        $cartData = [];
        $totalPrice = 0;

        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            $prodModel = $this->model('ProductModel');
            
            // Lấy danh sách ID biến thể từ session
            $variantIds = array_keys($_SESSION['cart']);
            
            // Lấy thông tin chi tiết từ DB (để đảm bảo giá luôn đúng)
            // Bạn cần thêm hàm getCartDetails trong ProductModel
            $products = $prodModel->getVariantsDetail($variantIds);

            foreach ($products as $p) {
                $qty = $_SESSION['cart'][$p['variant_id']];
                $subtotal = $p['price'] * $qty;
                if ($p['sale_price'] > 0) {
                    $subtotal = $p['sale_price'] * $qty;
                }

                $cartData[] = [
                    'product_id' => $p['product_id'],
                    'variant_id' => $p['variant_id'],
                    'name' => $p['name'],
                    'image' => $p['image'],
                    'size' => $p['size'],
                    'color' => $p['color'],
                    'price' => ($p['sale_price'] > 0) ? $p['sale_price'] : $p['price'],
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

    // Thêm vào giỏ [cite: 28, 106-107]
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $variantId = $_POST['variant_id'];
            $qty = (int)$_POST['quantity'];

            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            if (isset($_SESSION['cart'][$variantId])) {
                $_SESSION['cart'][$variantId] += $qty;
            } else {
                $_SESSION['cart'][$variantId] = $qty;
            }

            // Chuyển hướng về giỏ hàng
            header('Location: ' . BASE_URL . 'cart');
        }
    }

    // Cập nhật số lượng
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $variantId = $_POST['variant_id'];
            $qty = (int)$_POST['qty'];

            if ($qty > 0) {
                $_SESSION['cart'][$variantId] = $qty;
            } else {
                unset($_SESSION['cart'][$variantId]);
            }
            header('Location: ' . BASE_URL . 'cart');
        }
    }

    // Xóa sản phẩm
    public function delete($id) {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header('Location: ' . BASE_URL . 'cart');
    }
}
?>