<div class="container my-5">
    <div class="row">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <img src="<?= BASE_URL ?>public/uploads/<?= $data['product']['image'] ?>" 
                     class="img-fluid rounded" 
                     alt="<?= htmlspecialchars($data['product']['name']) ?>">
            </div>
        </div>

        <div class="col-md-7">
            <h2 class="fw-bold text-dark"><?= htmlspecialchars($data['product']['name']) ?></h2>
            <div class="mb-3">
                <span class="text-secondary">Mã SP: <?= htmlspecialchars($data['product']['sku_code']) ?></span>
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

            <p class="text-muted"><?= $data['product']['description'] ?></p>
            
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
                
                <div class="d-grid gap-2">
                    <button type="submit" id="btnBuy" class="btn btn-primary btn-lg py-3" disabled>
                        <i class="fas fa-shopping-cart me-2"></i> Thêm vào giỏ hàng
                    </button>
                </div>
            </form>
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
                                        <h6 class="mb-0 fw-bold">
                                            <?= !empty($review['full_name']) ? htmlspecialchars($review['full_name']) : 'Khách vãng lai' ?>
                                        </h6>
                                        <div class="small text-warning">
                                            <?php for($i=1; $i<=5; $i++): ?>
                                                <i class="<?= $i <= $review['rating'] ? 'fas' : 'far' ?> fa-star"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <i class="far fa-clock"></i> <?= date('d/m/Y', strtotime($review['created_at'])) ?>
                                </small>
                            </div>

                            <p class="card-text mt-2"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>

                            <?php if (!empty($review['reply_content'])): ?>
                                <div class="alert alert-white border ms-4 mt-2 mb-0" style="border-left: 4px solid #0d6efd !important;">
                                    <strong class="text-primary"><i class="fas fa-store-alt"></i> Phản hồi từ Shop:</strong>
                                    <p class="mb-0 mt-1 small text-secondary">
                                        <?= nl2br(htmlspecialchars($review['reply_content'])) ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-lg mb-2"></i><br>
                    Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên!
                </div>
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
                            <label class="form-label fw-bold">Bạn cảm thấy thế nào?</label>
                            <select name="rating" class="form-select text-warning fw-bold" required>
                                <option value="5" class="text-dark" selected>⭐⭐⭐⭐⭐ - Rất tuyệt vời</option>
                                <option value="4" class="text-dark">⭐⭐⭐⭐ - Hài lòng</option>
                                <option value="3" class="text-dark">⭐⭐⭐ - Bình thường</option>
                                <option value="2" class="text-dark">⭐⭐ - Không thích</option>
                                <option value="1" class="text-dark">⭐ - Rất tệ</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nội dung đánh giá:</label>
                            <textarea name="comment" class="form-control" rows="4" 
                                      placeholder="Hãy chia sẻ cảm nhận..." required></textarea>
                        </div>

                        <div class="d-grid">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i> Gửi đánh giá
                                </button>
                            <?php else: ?>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i> Gửi đánh giá
                                </button>
                                <small class="text-muted text-center mt-2">
                                    (Bạn đang đánh giá ẩn danh. <a href="<?= BASE_URL ?>auth/login">Đăng nhập</a> để lưu tên)
                                </small>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mt-3 text-center text-muted fst-italic small">
                            * Đánh giá sẽ được kiểm duyệt trước khi hiển thị.
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function checkStock() {
    let selectBox = document.getElementById('variantSelect');
    let variantId = selectBox.value;
    let btn = document.getElementById('btnBuy');
    let statusLabel = document.getElementById('stockStatus');
    
    // Reset trạng thái nếu chưa chọn gì
    if(!variantId) {
        statusLabel.innerHTML = '<span class="badge bg-secondary">Vui lòng chọn phân loại</span>';
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-shopping-cart me-2"></i> Thêm vào giỏ hàng';
        return;
    }

    // Hiển thị trạng thái đang tải
    statusLabel.innerHTML = '<span class="spinner-border spinner-border-sm text-primary"></span> Đang kiểm tra...';
    btn.disabled = true;

    // Gọi API CheckStock
    fetch('<?= BASE_URL ?>product/checkStock', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ variant_id: variantId })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        // Xử lý dữ liệu trả về
        if (data.stock > 0) {
            statusLabel.innerHTML = `<span class="text-success fw-bold"><i class="fas fa-check-circle"></i> Còn ${data.stock} sản phẩm</span>`;
            btn.disabled = false;
            btn.classList.remove('btn-secondary');
            btn.classList.add('btn-primary');
            btn.innerHTML = '<i class="fas fa-shopping-cart me-2"></i> Thêm vào giỏ hàng';
        } else {
            statusLabel.innerHTML = '<span class="text-danger fw-bold"><i class="fas fa-times-circle"></i> Hết hàng</span>';
            btn.disabled = true;
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-secondary');
            btn.innerHTML = 'Hết hàng';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        statusLabel.innerHTML = '<span class="text-danger">Lỗi kiểm tra kho!</span>';
    });
}
</script>

