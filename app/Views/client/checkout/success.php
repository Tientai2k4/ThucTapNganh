<div class="text-center py-5">
    <div class="mb-3 text-success">
        <i class="fas fa-check-circle fa-5x"></i>
    </div>
    <h2 class="text-success">Đặt hàng thành công!</h2>
    <p class="lead">Mã đơn hàng của bạn: <strong><?= $_GET['code'] ?? '' ?></strong></p>
    <p>Cảm ơn bạn đã mua sắm tại Swimming Store. Chúng tôi sẽ liên hệ sớm nhất.</p>
    <a href="<?= BASE_URL ?>" class="btn btn-primary mt-3">Tiếp tục mua sắm</a>
</div>