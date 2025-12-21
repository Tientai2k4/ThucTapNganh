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

    <div class="row mt-5">
        <div class="col-12">
            <ul class="nav nav-tabs" id="productTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active fw-bold" data-bs-toggle="tab" data-bs-target="#desc">MÔ TẢ SẢN PHẨM</button>
                </li>
            </ul>
            <div class="tab-content border border-top-0 p-4 bg-white shadow-sm">
                <div class="tab-pane fade show active" id="desc">
                    <?= $data['product']['description'] ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-7">
            <h4 class="mb-4">Khách hàng nhận xét</h4>
            <?php if(!empty($data['reviews'])): ?>
                <?php foreach($data['reviews'] as $rev): ?>
                    <div class="card mb-3 border-0 bg-light shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <strong><?= htmlspecialchars($rev['full_name'] ?? 'Người dùng') ?></strong>
                                <span class="text-warning">
                                    <?= str_repeat('★', $rev['rating']) . str_repeat('☆', 5-$rev['rating']) ?>
                                </span>
                            </div>
                            <p class="mb-0 mt-2 small text-muted"><?= $rev['comment'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">Chưa có đánh giá nào cho sản phẩm này.</p>
            <?php endif; ?>
        </div>
        <div class="col-md-5">
            <div class="card p-4">
                <h5>Gửi đánh giá</h5>
                <form action="<?= BASE_URL ?>product/postReview" method="POST">
                    <input type="hidden" name="product_id" value="<?= $data['product']['id'] ?>">
                    <select name="rating" class="form-select mb-3">
                        <option value="5">5 Sao (Tuyệt vời)</option>
                        <option value="4">4 Sao</option>
                        <option value="3">3 Sao</option>
                    </select>
                    <textarea name="comment" class="form-control mb-3" rows="3" placeholder="Chia sẻ trải nghiệm..." required></textarea>
                    <button class="btn btn-dark w-100">GỬI NGAY</button>
                </form>
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
</script>

<script>
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

// 2. HÀM XỬ LÝ AJAX THÊM VÀO GIỎ (CHẶN TUYỆT ĐỐI LOAD TRANG)
function handleAddToCart(event) {
    // NGĂN CHẶN LOAD TRANG (RẤT QUAN TRỌNG)
    event.preventDefault(); 

    const form = document.getElementById('addToCartForm');
    const btn = document.getElementById('btnBuy');
    const originalText = btn.innerHTML;
    
    // Thu thập dữ liệu
    const formData = new FormData();
    formData.append('variant_id', document.getElementById('selectedVariantId').value);
    formData.append('quantity', document.getElementById('selectedQuantity').value);

    // Hiệu ứng Loading
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang thêm...';
    btn.disabled = true;

    // Gửi yêu cầu AJAX bằng Fetch API
    fetch('<?= BASE_URL ?>cart/add', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        // Kiểm tra xem server có trả về JSON không
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        btn.innerHTML = originalText;
        btn.disabled = false;

        if (data.status) {
            // Thông báo thành công
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

    // Trả về false để Form HTML không tự submit
    return false;
}
</script>