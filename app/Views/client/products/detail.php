<div class="container my-5">
    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 mb-3 shadow-sm text-center position-relative">
                <img id="mainImage" 
                     src="<?= BASE_URL ?>public/uploads/<?= $data['product']['image'] ?>" 
                     class="img-fluid p-3 rounded" 
                     alt="<?= htmlspecialchars($data['product']['name']) ?>"
                     style="max-height: 450px; object-fit: contain; cursor: zoom-in;"
                     onclick="openLightbox(this.src)">
                
                <div class="position-absolute bottom-0 end-0 p-3">
                    <button class="btn btn-light rounded-circle shadow-sm" type="button" onclick="openLightbox(document.getElementById('mainImage').src)">
                        <i class="fas fa-expand-alt"></i>
                    </button>
                </div>
            </div>
            
            <div class="d-flex gap-2 overflow-auto pb-2" style="scrollbar-width: thin;">
                <img src="<?= BASE_URL ?>public/uploads/<?= $data['product']['image'] ?>" 
                     class="img-thumbnail thumb-btn border-primary" 
                     style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;" 
                     onclick="changeImage(this.src, this)">
                
                <?php if(!empty($data['gallery'])): ?>
                    <?php foreach($data['gallery'] as $img): ?>
                        <img src="<?= BASE_URL ?>public/uploads/<?= $img['image_url'] ?>" 
                             class="img-thumbnail thumb-btn" 
                             style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;" 
                             onclick="changeImage(this.src, this)">
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-6">
            <h2 class="fw-bold text-dark"><?= htmlspecialchars($data['product']['name']) ?></h2>
            <div class="mb-2">
                <span class="badge bg-info text-dark"><?= $data['product']['brand_name'] ?? 'Chính hãng' ?></span>
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

            <form id="addToCartForm" onsubmit="return handleAddToCart(event)">
                <input type="hidden" name="variant_id" id="selectedVariantId" value="">
                <input type="hidden" name="quantity" id="selectedQuantity" value="1">
                
                <div class="mb-4">
                    <label class="fw-bold mb-2">Chọn kích cỡ & màu sắc:</label>
                    <select id="variantSelect" class="form-select form-select-lg" onchange="updateStockStatus()" required>
                        <option value="">-- Vui lòng chọn --</option>
                        <?php if (!empty($data['variants'])): ?>
                            <?php foreach($data['variants'] as $v): ?>
                                <option value="<?= $v['id'] ?>">
                                    Size <?= $v['size'] ?> - Màu <?= $v['color'] ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>Hiện đang hết hàng</option>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="mb-4">
                    <p class="mb-1 small text-muted">Trình trạng:</p>
                    <div id="stockStatus">
                        <span class="badge bg-secondary">Vui lòng chọn phân loại để xem kho</span>
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col-3">
                        <input type="number" id="inputQty" value="1" min="1" class="form-control form-control-lg text-center" onchange="document.getElementById('selectedQuantity').value = this.value">
                    </div>
                    <div class="col-9">
                        <button type="submit" id="btnBuy" class="btn btn-primary btn-lg w-100 py-2" disabled>
                            <i class="fas fa-shopping-cart me-2"></i> THÊM VÀO GIỎ
                        </button>
                    </div>
                </div>
            </form>
            
            <div class="mt-4 p-3 bg-light rounded border">
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-truck text-primary me-3"></i>
                    <span>Giao hàng nhanh trong 2-4 ngày làm việc.</span>
                </div>
                <div class="d-flex align-items-center">
                    <i class="fas fa-undo text-primary me-3"></i>
                    <span>Đổi trả miễn phí trong 7 ngày nếu lỗi sản xuất.</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5" id="reviews-section">
        <div class="col-12">
            <ul class="nav nav-tabs" id="productTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active fw-bold" data-bs-toggle="tab" data-bs-target="#desc">MÔ TẢ SẢN PHẨM</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-bold" data-bs-toggle="tab" data-bs-target="#review">
                        ĐÁNH GIÁ (<?= $data['rating_summary']['total_review'] ?? 0 ?>)
                    </button>
                </li>
            </ul>
            
            <div class="tab-content border border-top-0 p-4 bg-white shadow-sm">
                <div class="tab-pane fade show active" id="desc">
                    <?= $data['product']['description'] ?>
                </div>

                <div class="tab-pane fade" id="review">
                    <div class="row">
                        <div class="col-md-4 mb-4 border-end">
                            <div class="text-center">
                                <h1 class="display-3 fw-bold text-warning mb-0"><?= $data['rating_summary']['average'] ?? 0 ?></h1>
                                <div class="text-warning mb-2 fs-5">
                                    <?php 
                                        $avg = $data['rating_summary']['average'] ?? 0;
                                        for($i=1; $i<=5; $i++) {
                                            if($i <= $avg) echo '<i class="fas fa-star"></i>';
                                            else if($i - 0.5 <= $avg) echo '<i class="fas fa-star-half-alt"></i>';
                                            else echo '<i class="far fa-star"></i>';
                                        }
                                    ?>
                                </div>
                                <p class="text-muted">Dựa trên <?= $data['rating_summary']['total_review'] ?? 0 ?> đánh giá</p>
                            </div>

                            <div class="mt-4 px-3">
                                <?php 
                                    $total = $data['rating_summary']['total_review'] ?? 0;
                                    // Loop ngược từ 5 về 1
                                    for($star=5; $star>=1; $star--): 
                                        $count = $data['rating_summary']['star_count'][$star] ?? 0;
                                        $percent = $total > 0 ? ($count / $total) * 100 : 0;
                                ?>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="text-muted small me-2"><?= $star ?> <i class="fas fa-star text-secondary"></i></span>
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $percent ?>%"></div>
                                    </div>
                                    <span class="text-muted small ms-2" style="min-width: 30px; text-align: right;"><?= $count ?></span>
                                </div>
                                <?php endfor; ?>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card bg-light border-0 mb-4">
                                <div class="card-body">
                                    <h5 class="card-title mb-3 fw-bold text-uppercase">Viết đánh giá của bạn</h5>
                                    
                                    <?php if(isset($data['can_review']) && $data['can_review'] === true): ?>
                                        <form action="<?= BASE_URL ?>product/postReview" method="POST">
                                            <input type="hidden" name="product_id" value="<?= $data['product']['id'] ?>">
                                            
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">1. Bạn cảm thấy thế nào về sản phẩm?</label>
                                                <div class="star-rating-input">
                                                    <input type="radio" id="star5" name="rating" value="5" checked /><label for="star5" title="Tuyệt vời">★</label>
                                                    <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="Tốt">★</label>
                                                    <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="Bình thường">★</label>
                                                    <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="Tệ">★</label>
                                                    <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="Rất tệ">★</label>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">2. Viết nhận xét chi tiết:</label>
                                                <textarea name="comment" class="form-control" rows="3" placeholder="Chia sẻ cảm nhận về chất liệu, kiểu dáng..." required></textarea>
                                            </div>
                                            
                                            <div class="text-end">
                                                <button class="btn btn-warning text-white fw-bold px-4">
                                                    <i class="fas fa-paper-plane me-2"></i>GỬI ĐÁNH GIÁ
                                                </button>
                                            </div>
                                        </form>
                                    <?php else: ?>
                                        <div class="alert alert-secondary d-flex align-items-center" role="alert">
                                            <i class="fas fa-info-circle fa-2x me-3 text-secondary"></i>
                                            <div>
                                                <h6 class="alert-heading fw-bold mb-1">Thông báo</h6>
                                                <p class="mb-0">
                                                    <?= $data['review_message'] ?>
                                                </p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <hr class="my-4">
                            <h5 class="fw-bold mb-4">Các đánh giá từ khách hàng</h5>

                            <?php if(!empty($data['reviews'])): ?>
                                <?php foreach($data['reviews'] as $review): ?>
                                    <div class="d-flex mb-4 border-bottom pb-3">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold" style="width: 50px; height: 50px; font-size: 20px;">
                                                <?= substr($review['full_name'] ?? 'K', 0, 1) ?>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <h6 class="fw-bold mb-0"><?= htmlspecialchars($review['full_name'] ?? 'Khách hàng') ?></h6>
                                                <span class="text-muted small"><i class="far fa-clock me-1"></i><?= date('d/m/Y', strtotime($review['created_at'])) ?></span>
                                            </div>
                                            
                                            <div class="text-warning small mb-2">
                                                <?php for($k=1; $k<=5; $k++): ?>
                                                    <i class="<?= $k <= $review['rating'] ? 'fas' : 'far' ?> fa-star"></i>
                                                <?php endfor; ?>
                                            </div>
                                            
                                            <p class="mb-2 text-dark">
                                                <?= htmlspecialchars($review['comment']) ?>
                                            </p>

                                            <?php if(!empty($review['reply_content'])): ?>
                                                <div class="bg-light p-3 mt-2 rounded border-start border-4 border-primary">
                                                    <div class="fw-bold text-primary mb-1">
                                                        <i class="fas fa-store me-1"></i> Phản hồi từ Shop:
                                                    </div>
                                                    <p class="mb-0 text-muted small fst-italic">
                                                        <?= htmlspecialchars($review['reply_content']) ?>
                                                    </p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-4 text-muted">
                                    <i class="far fa-comment-dots fa-3x mb-3"></i>
                                    <p>Chưa có đánh giá nào cho sản phẩm này.</p>
                                </div>
                            <?php endif; ?>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0">
            <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
            <div class="modal-body p-0 text-center">
                <img id="lightboxImg" src="" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</div>

<style>
.star-rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}
.star-rating-input input { display: none; }
.star-rating-input label {
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
    padding: 0 5px;
    transition: color 0.2s;
}
.star-rating-input label:hover,
.star-rating-input label:hover ~ label,
.star-rating-input input:checked ~ label {
    color: #ffc107;
}
</style>

<script>
function changeImage(src, el) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('.thumb-btn').forEach(b => b.classList.remove('border-primary'));
    el.classList.add('border-primary');
}

const lbModal = new bootstrap.Modal(document.getElementById('lightboxModal'));
function openLightbox(src) {
    document.getElementById('lightboxImg').src = src;
    lbModal.show();
}

// 1. Hàm kiểm tra kho khi đổi biến thể
function updateStockStatus() {
    const variantId = document.getElementById('variantSelect').value;
    const statusDiv = document.getElementById('stockStatus');
    const btn = document.getElementById('btnBuy');
    const hiddenId = document.getElementById('selectedVariantId');

    if (!variantId) {
        statusDiv.innerHTML = '<span class="badge bg-secondary">Vui lòng chọn phân loại</span>';
        btn.disabled = true;
        hiddenId.value = "";
        return;
    }

    hiddenId.value = variantId;
    statusDiv.innerHTML = '<div class="spinner-border spinner-border-sm text-primary"></div> Kiểm tra kho...';

    fetch('<?= BASE_URL ?>product/checkStock', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ variant_id: variantId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.stock > 0) {
            statusDiv.innerHTML = `<span class="text-success fw-bold"><i class="fas fa-check"></i> Còn ${data.stock} sản phẩm</span>`;
            btn.disabled = false;
        } else {
            statusDiv.innerHTML = '<span class="text-danger fw-bold"><i class="fas fa-times"></i> Hết hàng</span>';
            btn.disabled = true;
        }
    })
    .catch(() => { statusDiv.innerHTML = '<span class="text-danger">Lỗi kết nối!</span>'; });
}

// 2. HÀM XỬ LÝ AJAX THÊM VÀO GIỎ
function handleAddToCart(event) {
    event.preventDefault(); 

    const btn = document.getElementById('btnBuy');
    const originalText = btn.innerHTML;
    
    // Thu thập dữ liệu
    const formData = new FormData();
    formData.append('variant_id', document.getElementById('selectedVariantId').value);
    formData.append('quantity', document.getElementById('selectedQuantity').value);

    // Hiệu ứng Loading
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang thêm...';
    btn.disabled = true;

    // Gửi yêu cầu AJAX
    fetch('<?= BASE_URL ?>cart/add', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        btn.innerHTML = originalText;
        btn.disabled = false;

        if (data.status) {
            alert(data.message); 
            // Cập nhật số lượng trên icon giỏ hàng (Header)
            const cartCountEl = document.getElementById('cart-count');
            if (cartCountEl) {
                cartCountEl.innerText = data.cart_count;
            }
        } else {
            alert('Thông báo: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        btn.innerHTML = originalText;
        btn.disabled = false;
        alert('Đã có lỗi xảy ra. Vui lòng kiểm tra lại!');
    });

    return false;
}
</script>