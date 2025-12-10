<?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show container mt-3" role="alert">
        <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['flash_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); // Xóa thông báo sau khi hiện xong ?>
<?php endif; ?>

<h3 class="text-center mb-4 text-uppercase fw-bold text-primary">Sản phẩm nổi bật</h3>
<div class="row">
    <?php if(!empty($data['products'])): ?>
        <?php foreach($data['products'] as $prod): ?>
        <div class="col-6 col-md-4 mb-4">
            <div class="card card-product h-100">
                <div style="height: 220px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    <img src="<?= BASE_URL ?>uploads/<?= $prod['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($prod['name']) ?>" style="max-height: 100%; width: auto;">
                </div>
                
                <div class="card-body text-center d-flex flex-column">
                    <a href="<?= BASE_URL ?>product/detail/<?= $prod['id'] ?>" class="product-title text-truncate">
                        <?= htmlspecialchars($prod['name']) ?>
                    </a>
                    
                    <div class="mb-2 mt-auto">
                        <?php if($prod['sale_price'] > 0 && $prod['sale_price'] < $prod['price']): ?>
                            <span class="product-price text-danger"><?= number_format($prod['sale_price']) ?>đ</span>
                            <small class="text-decoration-line-through text-muted ms-2"><?= number_format($prod['price']) ?>đ</small>
                        <?php else: ?>
                            <span class="product-price"><?= number_format($prod['price']) ?>đ</span>
                        <?php endif; ?>
                    </div>
                    
                    <a href="<?= BASE_URL ?>product/detail/<?= $prod['id'] ?>" class="btn-buy">
                        <i class="fas fa-eye me-1"></i> Xem chi tiết
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12 text-center text-muted py-5">
            <p>Hiện chưa có sản phẩm nào được cập nhật.</p>
        </div>
    <?php endif; ?>
</div>