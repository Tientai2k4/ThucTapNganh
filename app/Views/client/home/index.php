<div class="mb-5 rounded overflow-hidden shadow-sm">
    <img src="https://yeuboiloi.com/wp-content/uploads/2021/06/banner-kinh-boi-can.jpg" class="w-100" style="height: 400px; object-fit: cover;" alt="Banner">
</div>

<h3 class="text-center mb-4 text-uppercase fw-bold text-primary">Sản phẩm nổi bật</h3>
<div class="row">
    <?php for($i=1; $i<=4; $i++): ?>
    <div class="col-6 col-md-3">
        <div class="card card-product">
            <img src="https://via.placeholder.com/300x300.png?text=Product+<?= $i ?>" class="card-img-top" alt="SP">
            <div class="card-body text-center">
                <a href="#" class="product-title">Kính bơi Phoenix 20<?= $i ?></a>
                <div class="mb-2">
                    <span class="product-price">150,000đ</span>
                    <small class="text-decoration-line-through text-muted ms-2">180,000đ</small>
                </div>
                <button class="btn-buy"><i class="fas fa-cart-plus me-1"></i> Thêm vào giỏ</button>
            </div>
        </div>
    </div>
    <?php endfor; ?>
</div>