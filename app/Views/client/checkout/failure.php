<div class="text-center py-5">
    <div class="mb-3 text-danger">
        <i class="fas fa-times-circle fa-5x"></i>
    </div>
    <h2 class="text-danger">Thanh toán thất bại</h2>
    <p class="lead"><?= $data['error'] ?? 'Có lỗi xảy ra trong quá trình thanh toán.' ?></p>
    <p>Hệ thống đã hoàn trả sản phẩm vào kho.</p>
    <a href="<?= BASE_URL ?>cart" class="btn btn-warning mt-3">Quay lại Giỏ hàng</a>
    <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary mt-3">Về trang chủ</a>
</div>