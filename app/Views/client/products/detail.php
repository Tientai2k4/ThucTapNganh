<div class="row">
    <div class="col-md-5">
        <img src="<?= BASE_URL ?>uploads/<?= $data['product']['image'] ?>" class="img-fluid border rounded">
    </div>
    <div class="col-md-7">
        <h2 class="fw-bold"><?= htmlspecialchars($data['product']['name']) ?></h2>
        <h3 class="text-danger my-3"><?= number_format($data['product']['price']) ?>đ</h3>
        <p><?= $data['product']['description'] ?></p>
        
        <form action="<?= BASE_URL ?>cart/add" method="POST" class="mt-4 p-4 bg-light rounded">
            <input type="hidden" name="product_id" value="<?= $data['product']['id'] ?>">
            
            <div class="mb-3">
                <label class="fw-bold">Chọn Loại (Size - Màu):</label>
                <select name="variant_id" id="variantSelect" class="form-select" onchange="checkStock()" required>
                    <option value="">-- Chọn Size & Màu --</option>
                    <?php foreach($data['variants'] as $v): ?>
                        <option value="<?= $v['id'] ?>">Size <?= $v['size'] ?> - Màu <?= $v['color'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label>Tồn kho: <span id="stockLabel" class="fw-bold text-muted">---</span></label>
            </div>
            
            <button type="submit" id="btnBuy" class="btn btn-primary btn-lg w-100" disabled>
                Thêm vào giỏ hàng
            </button>
        </form>
    </div>
</div>

<script>
function checkStock() {
    let id = document.getElementById('variantSelect').value;
    let btn = document.getElementById('btnBuy');
    let label = document.getElementById('stockLabel');
    
    if(!id) {
        label.innerText = "---";
        btn.disabled = true;
        return;
    }

    fetch('<?= BASE_URL ?>product/checkStock', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({variant_id: id})
    })
    .then(res => res.json())
    .then(data => {
        label.innerText = data.stock + " sản phẩm";
        if(data.stock > 0) {
            btn.disabled = false;
            btn.innerText = "Thêm vào giỏ hàng";
        } else {
            btn.disabled = true;
            btn.innerText = "Hết hàng";
        }
    });
}
</script>