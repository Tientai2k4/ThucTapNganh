<div class="container my-5 py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm text-center py-5">
                <div class="card-body">
                    <div class="mb-4">
                        <div style="width: 80px; height: 80px; background: #d4edda; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
                            <i class="fas fa-check fa-3x text-success"></i>
                        </div>
                    </div>

                    <h2 class="text-success fw-bold mb-3">Đặt hàng thành công!</h2>
                    <p class="text-muted mb-4">Cảm ơn bạn đã mua sắm tại Thế Giới Bơi Lội.</p>

                    <div class="alert alert-light border d-inline-block px-4 py-2 mb-4">
                        Mã đơn hàng của bạn: <strong class="text-primary fs-5"><?= htmlspecialchars($data['order_code']) ?></strong>
                    </div>

                    <p class="mb-4 small text-secondary">
                        Thông tin đơn hàng đã được gửi tới email của bạn.<br>
                        Chúng tôi sẽ sớm liên hệ để xác nhận và giao hàng.
                    </p>

                    <div class="d-flex justify-content-center gap-3">
                        <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-home me-1"></i> Về trang chủ
                        </a>
                        <a href="<?= BASE_URL ?>product" class="btn btn-primary">
                            <i class="fas fa-shopping-bag me-1"></i> Tiếp tục mua sắm
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>