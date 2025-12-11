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