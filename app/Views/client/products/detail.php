<div class="container my-5">
    <div class="row">
        
        <div class="col-md-6">
            <div class="card border-0 mb-3 shadow-sm text-center position-relative group-hover-zoom">
                <img id="mainImage" 
                     src="<?= BASE_URL ?>public/uploads/<?= $data['product']['image'] ?>" 
                     class="img-fluid p-3 rounded" 
                     alt="<?= htmlspecialchars($data['product']['name']) ?>"
                     style="max-height: 450px; object-fit: contain; cursor: zoom-in;"
                     onclick="openLightbox(this.src)">
                
                <div class="position-absolute bottom-0 end-0 p-3">
                    <button class="btn btn-light rounded-circle shadow-sm" onclick="openLightbox(document.getElementById('mainImage').src)">
                        <i class="fas fa-expand-alt"></i>
                    </button>
                </div>
            </div>
            
            <div class="d-flex gap-2 overflow-auto pb-2" style="scrollbar-width: thin;">
                <img src="<?= BASE_URL ?>public/uploads/<?= $data['product']['image'] ?>" 
                     class="img-thumbnail thumbnail-selector border-primary" 
                     style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;" 
                     onclick="changeImage(this.src, this)">
                
                <?php if(!empty($data['gallery'])): ?>
                    <?php foreach($data['gallery'] as $img): ?>
                        <img src="<?= BASE_URL ?>public/uploads/<?= $img['image_url'] ?>" 
                             class="img-thumbnail thumbnail-selector" 
                             style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;" 
                             onclick="changeImage(this.src, this)">
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

            <form id="addToCartForm" action="<?= BASE_URL ?>cart/add" method="POST">
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

<div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 95%; margin: auto;">
        <div class="modal-content bg-transparent border-0 shadow-none">
            
            <div class="text-end mb-2" style="position: absolute; top: -40px; right: 0; z-index: 1060;">
                <button type="button" class="btn btn-light rounded-circle shadow" data-bs-dismiss="modal">
                    <i class="fas fa-times fa-lg"></i>
                </button>
            </div>

            <div class="modal-body text-center d-flex justify-content-center align-items-center" style="height: 85vh; overflow: hidden; padding: 0;">
                <img id="lightboxImg" src="" class="img-fluid shadow-lg" 
                     style="width: 100%; height: 100%; object-fit: contain; transition: transform 0.3s ease-out; cursor: grab;">
            </div>

            <div class="text-center mt-2 fixed-bottom mb-4" style="pointer-events: none;">
                <div class="btn-group bg-white rounded shadow p-1" style="pointer-events: auto;">
                    <button class="btn btn-outline-secondary px-3" onclick="zoomOut()"><i class="fas fa-minus"></i></button>
                    <button class="btn btn-outline-primary px-3" onclick="resetZoom()"><i class="fas fa-sync-alt"></i></button>
                    <button class="btn btn-outline-secondary px-3" onclick="zoomIn()"><i class="fas fa-plus"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// --- LOGIC ĐỔI ẢNH ---
function changeImage(src, element) {
    document.getElementById('mainImage').src = src;
    
    // Xóa border active cũ
    document.querySelectorAll('.thumbnail-selector').forEach(img => img.classList.remove('border-primary'));
    
    // Thêm border active mới
    if(element) element.classList.add('border-primary');
}

// --- LOGIC LIGHTBOX & ZOOM ---
let currentScale = 1;
// Đoạn này sẽ chạy được vì đã có bootstrap.bundle.min.js ở trên
const lightboxModal = new bootstrap.Modal(document.getElementById('lightboxModal'));
const lightboxImg = document.getElementById('lightboxImg');

function openLightbox(src) {
    lightboxImg.src = src;
    resetZoom(); // Reset zoom khi mở ảnh mới
    lightboxModal.show();
}

function updateTransform() {
    lightboxImg.style.transform = `scale(${currentScale})`;
}

function zoomIn() {
    currentScale += 0.2;
    updateTransform();
}

function zoomOut() {
    if (currentScale > 0.4) { // Giới hạn nhỏ nhất
        currentScale -= 0.2;
        updateTransform();
    }
}

function resetZoom() {
    currentScale = 1;
    updateTransform();
}

// Thêm chức năng Zoom bằng lăn chuột
lightboxImg.addEventListener('wheel', function(e) {
    if (e.deltaY < 0) {
        zoomIn();
    } else {
        zoomOut();
    }
    e.preventDefault(); // Ngăn cuộn trang
});

// --- LOGIC GIỎ HÀNG & KHO ---
function checkStock() {
    let selectBox = document.getElementById('variantSelect');
    let variantId = selectBox.value;
    let btn = document.getElementById('btnBuy');
    let statusLabel = document.getElementById('stockStatus');
    
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

document.addEventListener('DOMContentLoaded', function() {
    const cartForm = document.getElementById('addToCartForm');
    
    if (cartForm) {
        cartForm.addEventListener('submit', function(e) {
            e.preventDefault(); 

            const formData = new FormData(this);
            const btn = document.getElementById('btnBuy');
            const originalText = btn.innerHTML;

            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang xử lý...';
            btn.disabled = true;

            fetch('<?= BASE_URL ?>cart/add', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                btn.innerHTML = originalText;
                btn.disabled = false;

                if (data.status) {
                    alert(data.message); 
                    const cartCountEl = document.getElementById('cart-count');
                    if (cartCountEl) {
                        cartCountEl.innerText = data.cart_count;
                    }
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btn.innerHTML = originalText;
                btn.disabled = false;
                alert('Đã có lỗi xảy ra, vui lòng thử lại.');
            });
        });
    }
});
</script>