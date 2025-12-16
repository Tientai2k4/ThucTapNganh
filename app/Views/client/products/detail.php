<div class="container my-5">
    <div class="row">
        
        <div class="col-md-6">
            <div class="card border-0 mb-3 shadow-sm text-center">
                <img id="mainImage" 
                     src="<?= BASE_URL ?>public/uploads/<?= $data['product']['image'] ?>" 
                     class="img-fluid p-3 rounded" 
                     alt="<?= htmlspecialchars($data['product']['name']) ?>"
                     style="max-height: 450px; object-fit: contain;">
            </div>
            
            <div class="d-flex gap-2 overflow-auto pb-2">
                <img src="<?= BASE_URL ?>public/uploads/<?= $data['product']['image'] ?>" 
                     class="img-thumbnail thumbnail-selector border-primary" 
                     style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;" 
                     onclick="changeImage(this.src)">
                
                <?php if(!empty($data['gallery'])): ?>
                    <?php foreach($data['gallery'] as $img): ?>
                        <img src="<?= BASE_URL ?>public/uploads/<?= $img['image_url'] ?>" 
                             class="img-thumbnail thumbnail-selector" 
                             style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;" 
                             onclick="changeImage(this.src)">
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-6">
            <h2 class="fw-bold text-dark"><?= htmlspecialchars($data['product']['name']) ?></h2>
            <div class="mb-2">
                <span class="badge bg-info text-dark"><?= $data['product']['brand_name'] ?? 'Brand' ?></span>
                <span class="text-muted ms-2">Mã SP: <?= htmlspecialchars($data['product']['sku_code']) ?></span>
            </div>

            <h3 class="text-danger fw-bold my-3">
                <?php if ($data['product']['sale_price'] > 0): ?>
                    <?= number_format($data['product']['sale_price']) ?>đ
                    <small class="text-muted text-decoration-line-through fs-6 ms-2">
                        <?= number_format($data['product']['price']) ?>đ
                    </small>
                <?php else: ?>
                    <?= number_format($data['product']['price']) ?>đ
                <?php endif; ?>
            </h3>
            
            <hr>

            <form action="<?= BASE_URL ?>cart/add" method="POST">
                <input type="hidden" name="product_id" value="<?= $data['product']['id'] ?>">
                
                <div class="mb-4">
                    <label class="fw-bold mb-2">Chọn Phân Loại (Size - Màu):</label>
                    <select name="variant_id" id="variantSelect" class="form-select form-select-lg" onchange="checkStock()" required>
                        <option value="">-- Vui lòng chọn --</option>
                        <?php if (!empty($data['variants'])): ?>
                            <?php foreach($data['variants'] as $v): ?>
                                <option value="<?= $v['id'] ?>">
                                    Size <?= $v['size'] ?> - Màu <?= $v['color'] ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>Sản phẩm này hiện hết hàng</option>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="mb-4">
                   <p class="mb-1">Tình trạng kho:</p>
                   <h5 id="stockStatus">
                    <span class="badge bg-secondary">Vui lòng chọn phân loại</span>
                   </h5>
                 </div>

                <div class="d-flex align-items-center mt-4">
                    <input type="number" name="quantity" value="1" min="1" class="form-control me-3 text-center" style="width: 80px;">
                    
                    <button type="submit" id="btnBuy" class="btn btn-primary btn-lg py-3 flex-grow-1" disabled>
                        <i class="fas fa-shopping-cart me-2"></i> Thêm vào giỏ hàng
                    </button>
                    
                    <button type="button" 
                            class="btn btn-lg ms-3" 
                            id="wishlistBtn"
                            data-product-id="<?= $data['product']['id'] ?>"
                            onclick="toggleWishlist(<?= $data['product']['id'] ?>)">
                        <?php if ($data['is_wished']): ?>
                            <i class="fas fa-heart text-danger"></i> Đã thích
                        <?php else: ?>
                            <i class="far fa-heart"></i> Yêu thích
                        <?php endif; ?>
                    </button>
             </div>
            </form>
            
            <div class="alert alert-light border-start border-4 border-success mt-4 small">
                <p class="mb-1 fw-bold"><i class="fas fa-truck text-success me-2"></i> Giao hàng toàn quốc</p>
                <p class="mb-1 fw-bold"><i class="fas fa-check-circle text-success me-2"></i> Cam kết hàng chính hãng</p>
            </div>
        </div>
    </div>
    
    <div class="row mt-5">
        <div class="col-12">
            <h4 class="text-uppercase border-bottom mb-4 pb-2 text-primary">
                <i class="fas fa-info-circle"></i> Mô tả & Hướng dẫn Size
            </h4>
            <div class="bg-white p-4 rounded shadow-sm">
                <?= $data['product']['description'] ?>
            </div>
        </div>
    </div>
    
    <div class="row mt-5">
        <div class="col-12">
            <h4 class="text-uppercase border-bottom mb-4 pb-2">
                <i class="fas fa-comments text-primary"></i> Đánh giá khách hàng
            </h4>
        </div>

        <div class="col-md-7">
            <?php if (!empty($data['reviews'])): ?>
                <?php foreach ($data['reviews'] as $review): ?>
                    <div class="card mb-3 border-0 shadow-sm bg-light">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center me-2" style="width: 40px; height: 40px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold"><?= !empty($review['full_name']) ? htmlspecialchars($review['full_name']) : 'Khách vãng lai' ?></h6>
                                        <div class="small text-warning">
                                            <?php for($i=1; $i<=5; $i++): ?>
                                                <i class="<?= $i <= $review['rating'] ? 'fas' : 'far' ?> fa-star"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted"><i class="far fa-clock"></i> <?= date('d/m/Y', strtotime($review['created_at'])) ?></small>
                            </div>
                            <p class="card-text mt-2"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                            <?php if (!empty($review['reply_content'])): ?>
                                <div class="alert alert-white border ms-4 mt-2 mb-0" style="border-left: 4px solid #0d6efd !important;">
                                    <strong class="text-primary"><i class="fas fa-store-alt"></i> Phản hồi từ Shop:</strong>
                                    <p class="mb-0 mt-1 small text-secondary"><?= nl2br(htmlspecialchars($review['reply_content'])) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info text-center">Chưa có đánh giá nào.</div>
            <?php endif; ?>
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-pen-nib"></i> Viết đánh giá của bạn</h5>
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>product/postReview" method="POST">
                        <input type="hidden" name="product_id" value="<?= $data['product']['id'] ?>">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Số sao:</label>
                            <select name="rating" class="form-select text-warning fw-bold" required>
                                <option value="5" class="text-dark" selected>⭐⭐⭐⭐⭐ - Rất tuyệt vời</option>
                                <option value="4" class="text-dark">⭐⭐⭐⭐ - Hài lòng</option>
                                <option value="3" class="text-dark">⭐⭐⭐ - Bình thường</option>
                                <option value="2" class="text-dark">⭐⭐ - Không thích</option>
                                <option value="1" class="text-dark">⭐ - Rất tệ</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nội dung:</label>
                            <textarea name="comment" class="form-control" rows="4" placeholder="Hãy chia sẻ cảm nhận..." required></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i> Gửi đánh giá
                            </button>
                            <?php if (!isset($_SESSION['user_id'])): ?>
                                <small class="text-muted text-center mt-2">(Đánh giá ẩn danh. Đăng nhập để lưu tên)</small>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <?php if(!empty($data['related'])): ?>
    <div class="row mt-5">
        <div class="col-12">
            <h4 class="border-bottom pb-2 text-primary">Có thể bạn thích</h4>
        </div>
        <?php foreach($data['related'] as $rel): ?>
            <div class="col-6 col-md-3 mt-3">
                <div class="card h-100 border-0 shadow-sm">
                    <a href="<?= BASE_URL ?>product/detail/<?= $rel['id'] ?>">
                        <img src="<?= BASE_URL ?>public/uploads/<?= $rel['image'] ?>" class="card-img-top p-2" style="height: 150px; object-fit: contain;">
                    </a>
                    <div class="card-body text-center">
                        <h6 class="text-truncate">
                            <a href="<?= BASE_URL ?>product/detail/<?= $rel['id'] ?>" class="text-decoration-none text-dark"><?= $rel['name'] ?></a>
                        </h6>
                        <span class="text-danger fw-bold"><?= number_format($rel['price']) ?>đ</span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</div>

<script>
// Chức năng đổi ảnh chính
function changeImage(src) {
    document.getElementById('mainImage').src = src;
    // Tùy chọn: Thêm hiệu ứng border cho ảnh đang chọn
    document.querySelectorAll('.thumbnail-selector').forEach(img => img.classList.remove('border-primary'));
    event.target.classList.add('border-primary');
}

// Chức năng kiểm tra tồn kho (Giữ nguyên)
function checkStock() {
    let selectBox = document.getElementById('variantSelect');
    let variantId = selectBox.value;
    let btn = document.getElementById('btnBuy');
    let statusLabel = document.getElementById('stockStatus');
    
    // Logic AJAX đã kiểm tra và chạy đúng
    if(!variantId) { 
        statusLabel.innerHTML = '<span class="badge bg-secondary">Vui lòng chọn phân loại</span>';
        btn.disabled = true;
        return; 
    }

    statusLabel.innerHTML = '<span class="spinner-border spinner-border-sm text-primary"></span> Đang kiểm tra...';
    btn.disabled = true;

    fetch('<?= BASE_URL ?>product/checkStock', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ variant_id: variantId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.stock > 0) {
            statusLabel.innerHTML = `<span class="text-success fw-bold"><i class="fas fa-check-circle"></i> Còn ${data.stock} sản phẩm</span>`;
            btn.disabled = false;
        } else {
            statusLabel.innerHTML = '<span class="text-danger fw-bold"><i class="fas fa-times-circle"></i> Hết hàng</span>';
            btn.disabled = true;
        }
    })
    .catch(error => { statusLabel.innerHTML = '<span class="text-danger">Lỗi kiểm tra kho!</span>'; });
}

// Chức năng Wishlist (Giữ nguyên)
function toggleWishlist(productId) {
    if (!<?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>) {
        alert('Vui lòng đăng nhập để thêm sản phẩm yêu thích.');
        window.location.href = '<?= BASE_URL ?>client/auth/login';
        return;
    }

    const btn = document.getElementById('wishlistBtn');
    
    fetch('<?= BASE_URL ?>wishlist/toggle', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            if (data.action === 'added') {
                btn.innerHTML = '<i class="fas fa-heart"></i> Đã thích';
                btn.classList.remove('btn-outline-danger');
                btn.classList.add('btn-danger');
            } else if (data.action === 'removed') {
                btn.innerHTML = '<i class="far fa-heart"></i> Yêu thích';
                btn.classList.remove('btn-danger');
                btn.classList.add('btn-outline-danger');
            }
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Lỗi AJAX:', error));
}


</script>