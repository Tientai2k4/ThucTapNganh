
<?php

if (!function_exists('getCartQuantity')) {
    /**
     * Tính tổng số lượng sản phẩm trong giỏ hàng (lưu trong $_SESSION['cart'])
     * @return int Tổng số lượng sản phẩm
     */
    function getCartQuantity() {
        $totalQty = 0;
        // Kiểm tra session giỏ hàng
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            // Lặp qua các cặp [variant_id => quantity] và cộng dồn quantity
            foreach ($_SESSION['cart'] as $qty) {
                // Đảm bảo qty là số nguyên
                $totalQty += (int)$qty; 
            }
        }
        return $totalQty;
    }
}