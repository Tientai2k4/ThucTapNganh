<h3 class="mb-4">Thanh toán đơn hàng</h3>
<?php if(isset($data['error'])): ?>
    <div class="alert alert-danger"><?= $data['error'] ?></div>
<?php endif; ?>

<form action="<?= BASE_URL ?>checkout/submit" method="POST">
    <div class="row">
        
        <div class="col-md-7">
            <div class="card p-4">
                <h5 class="mb-3">Thông tin giao hàng</h5>
                <div class="mb-3">
                    <label>Họ và tên *</label>
                    <input type="text" name="full_name" class="form-control" value="<?= $_SESSION['user_name'] ?? '' ?>" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Số điện thoại *</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label>Địa chỉ nhận hàng *</label>
                    <textarea name="address" class="form-control" rows="3" required></textarea>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card p-4 bg-light">
                <h5 class="mb-3">Đơn hàng của bạn</h5>

                <div class="d-flex justify-content-between mb-2">
                    <span>Tạm tính:</span>
                    <span class="fw-bold" id="tempTotal"><?= number_format($data['totalMoney'] ?? 0) ?>đ</span>
                </div>

                <div class="input-group mb-3">
                    <input type="text" id="couponCode" class="form-control" placeholder="Mã giảm giá">
                    <button class="btn btn-outline-secondary" type="button" onclick="applyCoupon()">Áp dụng</button>
                </div>
                <div id="couponMessage" class="small mb-2"></div>
                
                <div class="d-flex justify-content-between mb-2 text-success" id="discountRow" style="display:none !important;">
                    <span>Giảm giá:</span>
                    <span id="discountAmount">-0đ</span>
                </div>

                <div class="d-flex justify-content-between mb-4 border-top pt-2">
                    <span class="h5">Tổng cộng:</span>
                    <span class="h5 text-danger" id="finalTotal"><?= number_format($data['totalMoney'] ?? 0) ?>đ</span>
                </div>

                <h6 class="mb-3">Phương thức thanh toán</h6>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" value="COD" id="cod" checked>
                        <label class="form-check-label" for="cod">Thanh toán khi nhận hàng (COD)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" value="ZALOPAY" id="zalopay">
                        <label class="form-check-label" for="zalopay">
                            Thanh toán qua ví ZaloPay <img src="https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-ZaloPay-Square.png" height="20">
                        </label>
                    </div>
                </div>
                
                <input type="hidden" name="discount_amount" id="inputDiscount" value="0">
                <input type="hidden" name="coupon_code" id="inputCouponCode" value="">

                <button type="submit" class="btn btn-success w-100 btn-lg">XÁC NHẬN ĐẶT HÀNG</button>
            </div>
        </div>
        
    </div>
</form>
<script>
function applyCoupon() {
    let code = document.getElementById('couponCode').value;
    let total = <?= $data['totalMoney'] ?? 0 ?>; 

    // Gọi AjaxController
    fetch('<?= BASE_URL ?>ajax/checkCoupon', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({code: code, total: total})
    })
    .then(res => res.json())
    .then(data => {
        let msg = document.getElementById('couponMessage');
        if(data.status) {
            msg.className = 'small mb-2 text-success';
            msg.innerText = data.message;
            
            // Hiển thị giảm giá
            document.getElementById('discountRow').style.setProperty('display', 'flex', 'important');
            document.getElementById('discountAmount').innerText = '-' + new Intl.NumberFormat().format(data.discount) + 'đ';
            document.getElementById('finalTotal').innerText = new Intl.NumberFormat().format(total - data.discount) + 'đ';
            
            // Cập nhật input ẩn
            document.getElementById('inputDiscount').value = data.discount;
            document.getElementById('inputCouponCode').value = data.code;
        } else {
            msg.className = 'small mb-2 text-danger';
            msg.innerText = data.message;
            
            // Reset nếu sai
            document.getElementById('discountRow').style.display = 'none';
            document.getElementById('finalTotal').innerText = new Intl.NumberFormat().format(total) + 'đ';
            document.getElementById('inputDiscount').value = 0;
            document.getElementById('inputCouponCode').value = '';
        }
    });
}
</script>