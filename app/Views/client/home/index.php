<?php 
// 1. Flash Message
if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show container mt-3" role="alert">
        <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['flash_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>

<?php 
// Nhận biến
$sliders = $data['sliders'] ?? []; 
$products = $data['products'] ?? [];
$brands = $data['brands'] ?? [];
$posts = $data['posts'] ?? [];
$coupons = $data['coupons'] ?? [];
?>

<?php if (!empty($sliders)): ?>
<div class="shadow-sm mb-4">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <?php foreach ($sliders as $index => $slider): ?>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>"></button>
            <?php endforeach; ?>
        </div>
        <div class="carousel-inner">
            <?php foreach ($sliders as $index => $slider): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>" data-bs-interval="4000">
                    <a href="<?= htmlspecialchars($slider['link_url'] ?? '#') ?>">
                        <img src="<?= BASE_URL ?>public/uploads/sliders/<?= $slider['image'] ?>" class="d-block w-100" alt="Banner" style="max-height: 500px; object-fit: cover;">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
    </div>
</div>
<?php endif; ?>

<div class="container py-4">
    <div class="row g-4">
        <div class="col-6 col-md-3">
            <div class="d-flex align-items-center p-3 border rounded shadow-sm h-100 bg-white">
                <div class="flex-shrink-0 text-primary"><i class="fas fa-medal fa-2x"></i></div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="mb-0 fw-bold">Đảm bảo chính hãng</h6>
                    <small class="text-muted">100% Sản phẩm thật</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="d-flex align-items-center p-3 border rounded shadow-sm h-100 bg-white">
                <div class="flex-shrink-0 text-primary"><i class="fas fa-sync-alt fa-2x"></i></div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="mb-0 fw-bold">Chính sách đổi trả</h6>
                    <small class="text-muted">Linh hoạt trong 7 ngày</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="d-flex align-items-center p-3 border rounded shadow-sm h-100 bg-white">
                <div class="flex-shrink-0 text-primary"><i class="fas fa-mouse-pointer fa-2x"></i></div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="mb-0 fw-bold">Đặt hàng Online</h6>
                    <small class="text-muted">Thao tác dễ dàng</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="d-flex align-items-center p-3 border rounded shadow-sm h-100 bg-white">
                <div class="flex-shrink-0 text-primary"><i class="fas fa-shipping-fast fa-2x"></i></div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="mb-0 fw-bold">Giao hàng nhanh</h6>
                    <small class="text-muted">Thanh toán khi nhận</small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(!empty($coupons)): ?>
<div class="container py-4">
    <div class="d-flex align-items-center mb-3">
        <h4 class="fw-bold text-uppercase text-danger mb-0"><i class="fas fa-ticket-alt me-2"></i>Mã giảm giá hot</h4>
    </div>
    <div class="row g-3">
        <?php foreach($coupons as $coupon): ?>
        <div class="col-md-4 col-sm-6">
            <div class="d-flex bg-white border border-danger rounded overflow-hidden position-relative coupon-card shadow-sm h-100">
                <div class="bg-danger text-white p-3 d-flex flex-column justify-content-center align-items-center text-center" style="min-width: 100px;">
                    <span class="fw-bold fs-4">
                        <?= $coupon['discount_type'] == 'percent' ? $coupon['discount_value'] . '%' : number_format($coupon['discount_value']/1000) . 'k' ?>
                    </span>
                    <span class="small">OFF</span>
                </div>
                <div class="p-2 flex-grow-1 d-flex flex-column justify-content-center">
                    <div class="fw-bold text-dark mb-1">Mã: <span class="text-danger select-all"><?= htmlspecialchars($coupon['code']) ?></span></div>
                    <small class="text-muted d-block lh-sm mb-2">Đơn từ <?= number_format($coupon['min_order_value']) ?>đ</small>
                    <div class="d-flex justify-content-between align-items-end">
                        <small class="text-secondary" style="font-size: 11px;">HSD: <?= date('d/m', strtotime($coupon['end_date'])) ?></small>
                        <button class="btn btn-sm btn-outline-danger py-0 px-2" onclick="copyToClipboard('<?= $coupon['code'] ?>')">Copy</button>
                    </div>
                </div>
                <div class="position-absolute bg-light rounded-circle" style="width: 20px; height: 20px; top: 50%; left: -10px; transform: translateY(-50%);"></div>
                <div class="position-absolute bg-light rounded-circle" style="width: 20px; height: 20px; top: 50%; right: -10px; transform: translateY(-50%);"></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<div class="bg-light py-5 mt-4">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-uppercase text-primary position-relative d-inline-block section-title">
                Sản phẩm nổi bật
            </h2>
            <p class="text-muted mt-2">Tuyển tập những dụng cụ bơi lội tốt nhất dành cho bạn</p>
        </div>

        <div class="row g-4">
            <?php if(!empty($products)): ?>
                <?php foreach($products as $prod): ?>
                <div class="col-6 col-md-4">
                    <div class="card h-100 border-0 shadow-sm product-card position-relative overflow-hidden">
                        
                        <?php if($prod['sale_price'] > 0 && $prod['sale_price'] < $prod['price']): ?>
                            <?php $percent = round((($prod['price'] - $prod['sale_price']) / $prod['price']) * 100); ?>
                            <div class="position-absolute top-0 start-0 bg-danger text-white px-2 py-1 small fw-bold shadow-sm" 
                                 style="z-index: 2; border-bottom-right-radius: 10px;">
                                <i class="fas fa-bolt me-1"></i> SALE -<?= $percent ?>%
                            </div>
                        <?php endif; ?>

                        <a href="<?= BASE_URL ?>product/detail/<?= $prod['id'] ?>" class="overflow-hidden bg-white d-flex align-items-center justify-content-center position-relative" style="height: 280px;">
                            <img src="<?= BASE_URL ?>public/uploads/<?= $prod['image'] ?>" 
                                 class="card-img-top transition-transform" 
                                 alt="<?= htmlspecialchars($prod['name']) ?>" 
                                 style="max-height: 85%; width: auto; transition: transform 0.4s ease;">
                            
                            <div class="hover-overlay position-absolute w-100 h-100 d-flex justify-content-center align-items-center" 
                                 style="background: rgba(255,255,255,0.3); opacity: 0; transition: opacity 0.3s;">
                                <span class="btn btn-primary rounded-pill shadow-sm">Xem ngay</span>
                            </div>
                        </a>
                        
                        <div class="card-body text-center d-flex flex-column pt-3">
                            <h5 class="card-title mb-2">
                                <a href="<?= BASE_URL ?>product/detail/<?= $prod['id'] ?>" class="text-decoration-none text-dark fw-bold text-truncate d-block">
                                    <?= htmlspecialchars($prod['name']) ?>
                                </a>
                            </h5>
                            
                            <div class="mt-auto mb-3">
                                <?php if($prod['sale_price'] > 0 && $prod['sale_price'] < $prod['price']): ?>
                                    <div class="d-flex justify-content-center align-items-baseline gap-2">
                                        <span class="text-danger fw-bold fs-5"><?= number_format($prod['sale_price']) ?>đ</span>
                                        <span class="text-muted text-decoration-line-through small"><?= number_format($prod['price']) ?>đ</span>
                                    </div>
                                <?php else: ?>
                                    <span class="fw-bold fs-5 text-dark"><?= number_format($prod['price']) ?>đ</span>
                                <?php endif; ?>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="<?= BASE_URL ?>product/detail/<?= $prod['id'] ?>" class="btn btn-outline-primary btn-sm rounded-pill">
                                    <i class="fas fa-info-circle me-1"></i> Chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted py-5">
                    <i class="fas fa-box-open fa-3x mb-3 text-secondary"></i>
                    <p class="fs-5">Hiện chưa có sản phẩm nào nổi bật.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="<?= BASE_URL ?>product" class="btn btn-primary btn-lg px-5 py-2 rounded-pill shadow-lg hover-up">
                Xem thêm sản phẩm <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</div>

<div class="container py-5 border-bottom">
    <div class="text-center mb-4">
        <h4 class="fw-bold text-uppercase text-secondary">Thương hiệu đồng hành</h4>
    </div>
    <div class="row align-items-center justify-content-center g-4">
        <?php if(!empty($brands)): ?>
            <?php foreach($brands as $brand): ?>
                <div class="col-4 col-md-2 text-center">
                    <div class="p-3 border rounded shadow-sm h-100 brand-item bg-white" title="<?= htmlspecialchars($brand['name']) ?>">
                        <img src="<?= BASE_URL ?>public/uploads/brands/<?= $brand['logo'] ?>" 
                             alt="<?= htmlspecialchars($brand['name']) ?>" 
                             class="img-fluid mb-2" 
                             style="max-height: 50px; object-fit: contain;">
                        <div class="small fw-bold text-dark"><?= htmlspecialchars($brand['name']) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-muted small">Đang cập nhật...</p>
        <?php endif; ?>
    </div>
</div>

<div class="bg-white py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
            <h3 class="fw-bold text-uppercase mb-0 text-primary border-start border-4 border-primary ps-3">
                Tin tức bơi lội
            </h3>
            <a href="<?= BASE_URL ?>blog" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                Xem tất cả <i class="fas fa-angle-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            <?php if(!empty($posts)): ?>
                <?php foreach($posts as $post): ?>
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100 border-0 shadow-sm hover-shadow transition-all bg-white rounded-3 overflow-hidden">
                        
                        <a href="<?= BASE_URL ?>blog/detail/<?= $post['slug'] ?? $post['id'] ?>" class="overflow-hidden d-block position-relative">
                            <?php 
                                $imgSrc = !empty($post['thumbnail']) 
                                    ? BASE_URL . 'public/uploads/posts/' . $post['thumbnail'] 
                                    : 'https://placehold.co/600x400?text=News';
                            ?>
                            <img src="<?= $imgSrc ?>" 
                                 class="card-img-top" 
                                 alt="<?= htmlspecialchars($post['title']) ?>"
                                 style="height: 180px; width: 100%; object-fit: cover; transition: transform 0.5s ease;"
                                 onerror="this.onerror=null; this.src='https://placehold.co/600x400?text=No+Image';">
                            
                            <div class="position-absolute top-0 start-0 bg-primary text-white px-2 py-1 small m-2 rounded shadow-sm">
                                <i class="far fa-calendar-alt"></i> <?= date('d/m', strtotime($post['created_at'])) ?>
                            </div>
                        </a>
                        
                        <div class="card-body p-3 d-flex flex-column">
                            <h6 class="card-title lh-base mb-2" style="min-height: 2.5em;">
                                <a href="<?= BASE_URL ?>blog/detail/<?= $post['slug'] ?? $post['id'] ?>" class="text-decoration-none text-dark fw-bold hover-link">
                                    <?= htmlspecialchars($post['title']) ?>
                                </a>
                            </h6>
                            <p class="card-text small text-muted text-truncate-3 mb-3">
                                <?= htmlspecialchars(substr(strip_tags($post['excerpt'] ?? $post['content']), 0, 90)) ?>...
                            </p>
                            
                            <div class="mt-auto">
                                <a href="<?= BASE_URL ?>blog/detail/<?= $post['slug'] ?? $post['id'] ?>" class="text-primary small fw-bold text-decoration-none">
                                    Đọc tiếp <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted py-5">
                    <p>Chưa có bài viết nào mới.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    /* Hiệu ứng Zoom ảnh SP */
    .product-card:hover img.card-img-top { transform: scale(1.08); }
    .product-card:hover .hover-overlay { opacity: 1 !important; }
    .product-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    
    /* Thương hiệu: Luôn sáng, có khung */
    .brand-item { transition: all 0.3s; }
    .brand-item:hover { border-color: var(--bs-primary) !important; transform: translateY(-3px); }
    
    /* Cắt dòng văn bản */
    .text-truncate-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    
    /* Hover link */
    .hover-link:hover { color: var(--bs-primary) !important; }
    .hover-up:hover { transform: translateY(-3px); }

    /* Coupon Card */
    .coupon-card { transition: transform 0.2s; }
    .coupon-card:hover { transform: scale(1.02); }
    .select-all { user-select: all; cursor: pointer; }
</style>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Đã copy mã: ' + text);
    }, function(err) {
        console.error('Không thể copy: ', err);
    });
}
</script>