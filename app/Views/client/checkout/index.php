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
                <div class="alert alert-info">
                    Phương thức thanh toán: <strong>Thanh toán khi nhận hàng (COD)</strong>
                </div>
                <button type="submit" class="btn btn-success w-100 btn-lg">XÁC NHẬN ĐẶT HÀNG</button>
            </div>
        </div>
    </div>
</form>