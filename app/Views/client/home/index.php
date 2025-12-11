<?php 
// 1. Phía trên cùng: Khối chứa thông báo flash
if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show container mt-3" role="alert">
        <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['flash_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); // Xóa thông báo sau khi hiện xong ?>
<?php endif; ?>

<?php 
// FIX LỖI: Gỡ biến $sliders từ $data
$sliders = $data['sliders'] ?? []; 
?>

<?php if (!empty($sliders)): ?>
<div class="mb-5 shadow-sm">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <?php foreach ($sliders as $index => $slider): ?>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>" aria-current="<?= $index === 0 ? 'true' : 'false' ?>"></button>
            <?php endforeach; ?>
        </div>

        <div class="carousel-inner">
            <?php foreach ($sliders as $index => $slider): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>" data-bs-interval="5000">
                    <a href="<?= htmlspecialchars($slider['link_url'] ?? '#') ?>">
                        <img src="<?= BASE_URL ?>public/uploads/sliders/<?= $slider['image'] ?>" 
                             class="d-block w-100" 
                             alt="Banner <?= $index + 1 ?>"
                             style="max-height: 450px; object-fit: cover;">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>
<?php else: ?>
    <div class="container mb-5">
        <div class="alert alert-info text-center">Chưa có banner khuyến mãi nào được kích hoạt.</div>
    </div>
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