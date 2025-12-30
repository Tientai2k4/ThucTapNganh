<div class="container my-5">
    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 mb-3 shadow-sm text-center position-relative">
                <img id="mainImage" src="<?= BASE_URL ?>public/uploads/<?= $data['product']['image'] ?>" 
                     class="img-fluid p-3 rounded" alt="<?= htmlspecialchars($data['product']['name']) ?>" 
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
                <?= number_format($data['product']['sale_price'] > 0 ? $data['product']['sale_price'] : $data['product']['price']) ?>đ
                <?php if ($data['product']['sale_price'] > 0): ?>
                    <small class="text-muted text-decoration-line-through fs-6 ms-2">
                        <?= number_format($data['product']['price']) ?>đ
                    </small>
                <?php endif; ?>
            </h3>
            
            <hr>

            <form id="addToCartForm" onsubmit="return handleAddToCart(event)">
                <input type="hidden" name="variant_id" id="selectedVariantId" value="">
                <input type="hidden" name="quantity" id="selectedQuantity" value="1">
                
                <?php if (!empty($data['variants'])): ?>
                    <div class="mb-3">
                        <label class="fw-bold mb-2">Kích cỡ:</label>
                        <div class="d-flex flex-wrap gap-2" id="sizeOptions">
                            <?php 
                                $sizes = array_unique(array_column($data['variants'], 'size'));
                                foreach($sizes as $size): 
                            ?>
                                <button type="button" class="btn btn-outline-secondary size-btn" 
                                        data-size="<?= $size ?>" onclick="selectSize(this)">
                                    <?= $size ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold mb-2">Màu sắc:</label>
                        <div class="d-flex flex-wrap gap-2" id="colorOptions">
                            <?php 
                                $colors = array_unique(array_column($data['variants'], 'color'));
                                foreach($colors as $color): 
                            ?>
                                <button type="button" class="btn btn-outline-secondary color-btn disabled" 
                                        data-color="<?= $color ?>" onclick="selectColor(this)" disabled>
                                    <?= $color ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div id="stockStatus" class="mt-2">
                            <span class="text-muted small">Vui lòng chọn Size và Màu sắc</span>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">Sản phẩm này tạm thời hết hàng.</div>
                <?php endif; ?>

                <div class="row g-2 align-items-center">
                    <div class="col-auto">
                        <label class="text-muted small me-2">Số lượng:</label>
                    </div>
                    <div class="col-auto">
                        <div class="input-group" style="width: 130px;">
                            <button class="btn btn-outline-secondary" type="button" onclick="changeQty(-1)">-</button>
                            <input type="text" id="inputQty" value="1" class="form-control text-center" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="changeQty(1)">+</button>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" id="btnBuy" class="btn btn-primary btn-lg w-100 py-3 text-uppercase fw-bold" disabled>
                            <i class="fas fa-cart-plus me-2"></i> Thêm vào giỏ hàng
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
                                                <p class="mb-0"><?= $data['review_message'] ?></p>
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
                                            
                                            <p class="mb-2 text-dark"><?= htmlspecialchars($review['comment']) ?></p>

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


    <div class="container my-5 pt-4 border-top">
      <h3 class="fw-bold text-dark mb-4 text-uppercase">Sản phẩm liên quan</h3>
    
       <div class="row g-4">
        <?php if (!empty($data['related'])): ?>
            <?php foreach ($data['related'] as $item): ?>
                <div class="col-6 col-md-3">
                    <div class="card h-100 border-0 shadow-sm hover-lift product-card">
                        <?php if ($item['sale_price'] > 0): ?>
                            <span class="badge bg-danger position-absolute m-2" style="z-index: 10;">
                                -<?= round((($item['price'] - $item['sale_price']) / $item['price']) * 100) ?>%
                            </span>
                        <?php endif; ?>

                        <a href="<?= BASE_URL ?>product/detail/<?= $item['id'] ?>" class="text-decoration-none">
                            <div class="text-center p-3">
                                <img src="<?= BASE_URL ?>public/uploads/<?= $item['image'] ?>" 
                                     class="card-img-top object-fit-contain" 
                                     alt="<?= htmlspecialchars($item['name']) ?>"
                                     style="height: 180px;">
                            </div>
                            <div class="card-body pt-0">
                                <h6 class="card-title text-dark text-truncate mb-2"><?= htmlspecialchars($item['name']) ?></h6>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if ($item['sale_price'] > 0): ?>
                                        <span class="text-danger fw-bold"><?= number_format($item['sale_price']) ?>đ</span>
                                        <small class="text-muted text-decoration-line-through"><?= number_format($item['price']) ?>đ</small>
                                    <?php else: ?>
                                        <span class="text-primary fw-bold"><?= number_format($item['price']) ?>đ</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="text-muted fst-italic text-center">Chưa có sản phẩm liên quan nào.</p>
            </div>
        <?php endif; ?>
      </div>
   </div>
</div>

<div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0 position-relative" style="height: 90vh;">
            
            <div class="position-absolute top-0 end-0 m-3 d-flex gap-2" style="z-index: 2000;">
                <button type="button" class="btn btn-light rounded-circle shadow-sm" onclick="zoomImage(0.2)">
                    <i class="fas fa-plus"></i>
                </button>
                <button type="button" class="btn btn-light rounded-circle shadow-sm" onclick="zoomImage(-0.2)">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-danger rounded-circle shadow-sm" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body p-0 d-flex align-items-center justify-content-center overflow-hidden" 
                 id="imageContainer" 
                 style="height: 100%; width: 100%; cursor: grab;">
                <img id="lightboxImg" src="" 
                     class="rounded shadow" 
                     style="transition: transform 0.1s ease-out; max-height: 90vh; max-width: 90vw; user-select: none; -webkit-user-drag: none;">
            </div>
        </div>
    </div>
</div>

<style>
/* CSS cho nút chọn Size/Màu */
.size-btn, .color-btn { min-width: 60px; }
.size-btn.active, .color-btn.active { background-color: #0d6efd; color: white; border-color: #0d6efd; }
.thumb-btn { transition: all 0.2s; }
.thumb-btn:hover { transform: scale(1.05); }

/* CSS cho Star Rating (Giữ nguyên) */
.star-rating-input { display: flex; flex-direction: row-reverse; justify-content: flex-end; }
.star-rating-input input { display: none; }
.star-rating-input label { font-size: 2rem; color: #ddd; cursor: pointer; padding: 0 5px; transition: color 0.2s; }
.star-rating-input label:hover, .star-rating-input label:hover ~ label, .star-rating-input input:checked ~ label { color: #ffc107; }
/* Hiệu ứng di chuột cho card sản phẩm liên quan */
    .product-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .product-card img {
        transition: transform 0.3s ease;
    }
    .product-card:hover img {
        transform: scale(1.05);
    }
</style>

<script>
    // --- 1. DỮ LIỆU JSON TỪ PHP SANG JS ---
    const allVariants = <?= json_encode($data['variants'] ?? []) ?>;
    let currentSize = null;
    let currentColor = null;

    // --- 2. XỬ LÝ CHỌN SIZE ---
    function selectSize(btn) {
        const clickedSize = btn.getAttribute('data-size');

        // Logic Toggle (Bấm lần 2 để bỏ chọn)
        if (currentSize === clickedSize) {
            currentSize = null;
            btn.classList.remove('active', 'btn-primary');
            btn.classList.add('btn-outline-secondary');
            updateColorButtonsState(); // Reset màu
        } else {
            document.querySelectorAll('.size-btn').forEach(b => {
                b.classList.remove('active', 'btn-primary');
                b.classList.add('btn-outline-secondary');
            });
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('active', 'btn-primary');
            currentSize = clickedSize;
            updateColorButtonsState();
        }
        checkSelection();
    }

    // --- 3. XỬ LÝ CHỌN MÀU ---
    function selectColor(btn) {
        const clickedColor = btn.getAttribute('data-color');

        // Logic Toggle
        if (currentColor === clickedColor) {
            currentColor = null;
            btn.classList.remove('active', 'btn-primary');
            btn.classList.add('btn-outline-secondary');
            updateSizeButtonsState(); // Reset size
        } else {
            document.querySelectorAll('.color-btn').forEach(b => {
                b.classList.remove('active', 'btn-primary');
                b.classList.add('btn-outline-secondary');
            });
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('active', 'btn-primary');
            currentColor = clickedColor;
            updateSizeButtonsState();
        }
        checkSelection();
    }

    // --- 4. CẬP NHẬT TRẠNG THÁI MÀU ---
    function updateColorButtonsState() {
        document.querySelectorAll('.color-btn').forEach(btn => {
            const color = btn.getAttribute('data-color');
            if (currentSize) {
                const exists = allVariants.some(v => v.size == currentSize && v.color == color && v.stock_quantity > 0);
                if (exists) {
                    btn.disabled = false;
                    btn.classList.remove('disabled');
                } else {
                    btn.disabled = true;
                    btn.classList.add('disabled');
                    if (currentColor == color) {
                        currentColor = null;
                        btn.classList.remove('active', 'btn-primary');
                        btn.classList.add('btn-outline-secondary');
                    }
                }
            } else {
                btn.disabled = false;
                btn.classList.remove('disabled');
            }
        });
    }

    // --- 5. CẬP NHẬT TRẠNG THÁI SIZE ---
    function updateSizeButtonsState() {
        document.querySelectorAll('.size-btn').forEach(btn => {
            const size = btn.getAttribute('data-size');
            if (currentColor) {
                const exists = allVariants.some(v => v.color == currentColor && v.size == size && v.stock_quantity > 0);
                if (exists) {
                    btn.disabled = false;
                    btn.classList.remove('disabled');
                } else {
                    btn.disabled = true;
                    btn.classList.add('disabled');
                    if (currentSize == size) {
                        currentSize = null;
                        btn.classList.remove('active', 'btn-primary');
                        btn.classList.add('btn-outline-secondary');
                    }
                }
            } else {
                btn.disabled = false;
                btn.classList.remove('disabled');
            }
        });
    }

    // --- 6. KIỂM TRA KHO ---
    function checkSelection() {
        const statusDiv = document.getElementById('stockStatus');
        const btnBuy = document.getElementById('btnBuy');
        const hiddenId = document.getElementById('selectedVariantId');

        if (currentSize && currentColor) {
            const variant = allVariants.find(v => v.size == currentSize && v.color == currentColor);
            if (variant) {
                hiddenId.value = variant.id;
                statusDiv.innerHTML = `<span class="text-success fw-bold"><i class="fas fa-check"></i> Kho còn: ${variant.stock_quantity} sản phẩm</span>`;
                btnBuy.disabled = false;
            } else {
                statusDiv.innerHTML = '<span class="text-danger fw-bold">Hết hàng</span>';
                btnBuy.disabled = true;
            }
        } else {
            statusDiv.innerHTML = '<span class="text-muted small">Vui lòng chọn đủ Size và Màu sắc</span>';
            btnBuy.disabled = true;
        }
    }

    // --- 7. TĂNG GIẢM SỐ LƯỢNG ---
    function changeQty(delta) {
        const input = document.getElementById('inputQty');
        let val = parseInt(input.value) + delta;
        if (val < 1) val = 1;
        input.value = val;
        document.getElementById('selectedQuantity').value = val;
    }

    // --- 8. SUBMIT GIỎ HÀNG ---
    function handleAddToCart(event) {
        event.preventDefault(); 
        const btn = document.getElementById('btnBuy');
        const originalText = btn.innerHTML;
        const formData = new FormData();
        formData.append('variant_id', document.getElementById('selectedVariantId').value);
        formData.append('quantity', document.getElementById('selectedQuantity').value);

        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang xử lý...';
        btn.disabled = true;

        fetch('<?= BASE_URL ?>cart/add', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            if(data.status) {
                alert(data.message);
                const cartCountEl = document.getElementById('cart-count');
                if (cartCountEl) cartCountEl.innerText = data.cart_count;
            } else {
                alert(data.message);
            }
        })
        .catch(e => {
            console.error(e);
            btn.innerHTML = originalText;
            btn.disabled = false;
            alert('Lỗi kết nối!');
        });
    }

    // --- 9. HÀM HỖ TRỢ ẢNH ---
    function changeImage(src, el) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.thumb-btn').forEach(b => b.classList.remove('border-primary'));
        el.classList.add('border-primary');
    }
    
    
    // Init state
    updateColorButtonsState();
    updateSizeButtonsState();

    // --- 10. XỬ LÝ ZOOM ẢNH (MỚI) ---
    // Khai báo biến
    let currentScale = 1;
    let isDragging = false;
    let startX, startY;
    let translateX = 0;
    let translateY = 0;

    const imgEl = document.getElementById('lightboxImg');
    const containerEl = document.getElementById('imageContainer');

    function openLightbox(src) {
        imgEl.src = src;
        currentScale = 1;
        translateX = 0;
        translateY = 0;
        updateTransform();
        const lbModal = new bootstrap.Modal(document.getElementById('lightboxModal'));
        lbModal.show();
    }

    function zoomImage(factor) {
        currentScale += factor;
        if (currentScale < 0.5) currentScale = 0.5;
        if (currentScale > 5) currentScale = 5;
        updateTransform();
    }

    function updateTransform() {
        // Sử dụng translate3d để tận dụng tăng tốc phần cứng
        imgEl.style.transform = `translate3d(${translateX}px, ${translateY}px, 0) scale(${currentScale})`;
    }

    // Xử lý kéo thả
    containerEl.addEventListener('mousedown', (e) => {
        if (currentScale > 1) {
            isDragging = true;
            containerEl.style.cursor = 'grabbing';
            startX = e.clientX - translateX;
            startY = e.clientY - translateY;
        }
    });

    window.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        e.preventDefault();
        translateX = e.clientX - startX;
        translateY = e.clientY - startY;
        updateTransform();
    });

    window.addEventListener('mouseup', () => {
        isDragging = false;
        containerEl.style.cursor = 'grab';
    });

    // Zoom bằng lăn chuột (Wheel)
    containerEl.addEventListener('wheel', (e) => {
        e.preventDefault();
        const delta = e.deltaY > 0 ? -0.2 : 0.2;
        zoomImage(delta);
    }, { passive: false });
</script>