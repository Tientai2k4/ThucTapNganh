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
                    <?php if(!empty($data['reviews'])): ?>
                        <?php foreach($data['reviews'] as $review): ?>
                            <div class="d-flex mb-4 border-bottom pb-3">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold" style="width: 50px; height: 50px; font-size: 20px;">
                                        <?= substr($review['full_name'] ?? 'K', 0, 1) ?>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="fw-bold mb-0"><?= htmlspecialchars($review['full_name'] ?? 'Khách hàng') ?></h6>
                                    <div class="text-warning small mb-2">
                                        <?php for($k=1; $k<=5; $k++): ?>
                                            <i class="<?= $k <= $review['rating'] ? 'fas' : 'far' ?> fa-star"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <p class="mb-2 text-dark"><?= htmlspecialchars($review['comment']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center text-muted">Chưa có đánh giá nào.</p>
                    <?php endif; ?>
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
/* CSS cho nút chọn Size/Màu */
.size-btn, .color-btn {
    min-width: 60px;
}
.size-btn.active, .color-btn.active {
    background-color: #0d6efd;
    color: white;
    border-color: #0d6efd;
}
.thumb-btn {
    transition: all 0.2s;
}
.thumb-btn:hover {
    transform: scale(1.05);
}
</style>

<script>
    // Dữ liệu Variants từ PHP sang JS
    const allVariants = <?= json_encode($data['variants'] ?? []) ?>;
    let currentSize = null;
    let currentColor = null;

    // Chọn Size
    function selectSize(btn) {
        document.querySelectorAll('.size-btn').forEach(b => {
            b.classList.remove('active', 'btn-primary');
            b.classList.add('btn-outline-secondary');
        });
        
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('active', 'btn-primary');
        currentSize = btn.getAttribute('data-size');

        // Reset màu
        currentColor = null;
        document.querySelectorAll('.color-btn').forEach(b => {
            b.classList.remove('active', 'btn-primary');
            b.classList.add('btn-outline-secondary', 'disabled');
            b.disabled = true; 
        });

        // Mở khóa màu phù hợp
        const availableColors = allVariants
            .filter(v => v.size == currentSize && v.stock_quantity > 0)
            .map(v => v.color);

        document.querySelectorAll('.color-btn').forEach(b => {
            if (availableColors.includes(b.getAttribute('data-color'))) {
                b.disabled = false;
                b.classList.remove('disabled');
            }
        });

        checkSelection();
    }

    // Chọn Màu
    function selectColor(btn) {
        document.querySelectorAll('.color-btn').forEach(b => {
            b.classList.remove('active', 'btn-primary');
            b.classList.add('btn-outline-secondary');
        });
        
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('active', 'btn-primary');
        currentColor = btn.getAttribute('data-color');

        checkSelection();
    }

    // Kiểm tra kho
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
            statusDiv.innerHTML = '<span class="text-muted small">Vui lòng chọn Size và Màu sắc</span>';
            btnBuy.disabled = true;
        }
    }

    // Tăng giảm số lượng
    function changeQty(delta) {
        const input = document.getElementById('inputQty');
        let val = parseInt(input.value) + delta;
        if (val < 1) val = 1;
        input.value = val;
        document.getElementById('selectedQuantity').value = val;
    }

    // Xử lý giỏ hàng
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

    // Lightbox & Change Image
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
</script>