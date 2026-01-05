<div class="container py-5">
    <div class="d-flex justify-content-center">
        <div class="col-lg-9">
            <?php if (isset($_SESSION['alert'])): ?>
                <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show mb-3">
                    <?= $_SESSION['alert']['message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['alert']); ?>
            <?php endif; ?>

            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i>CẬP NHẬT KHO NHANH</h5>
                    <a href="<?= BASE_URL ?>staff/product" class="btn btn-sm btn-light text-primary fw-bold">
                        <i class="fas fa-arrow-left me-1"></i>Quay lại kho
                    </a>
                </div>
                
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded border">
                        <?php $img = !empty($data['product']['image']) ? $data['product']['image'] : 'default.png'; ?>
                        <img src="<?= BASE_URL ?>public/uploads/<?= $img ?>" class="rounded border me-3 bg-white" width="70" height="70" style="object-fit: cover;">
                        <div>
                            <h5 class="fw-bold mb-1 text-primary"><?= htmlspecialchars($data['product']['name']) ?></h5>
                            <div class="text-muted small">SKU: <span class="fw-bold text-dark"><?= $data['product']['sku_code'] ?></span></div>
                        </div>
                    </div>

                    <form action="<?= BASE_URL ?>staff/product/updateStock" method="POST">
                        <input type="hidden" name="product_id" value="<?= $data['product']['id'] ?>">

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Giá bán niêm yết (VNĐ)</label>
                                <div class="input-group">
                                    <input type="number" name="price" class="form-control fw-bold" value="<?= $data['product']['price'] ?>" required>
                                    <span class="input-group-text">đ</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Giá khuyến mãi</label>
                                <div class="input-group">
                                    <input type="number" name="sale_price" class="form-control text-danger fw-bold" value="<?= $data['product']['sale_price'] ?>">
                                    <span class="input-group-text">đ</span>
                                </div>
                                <div class="form-text small">Nhập 0 nếu không giảm giá.</div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-success mb-0"><i class="fas fa-cubes me-2"></i>QUẢN LÝ TỒN KHO</h6>
                        </div>

                        <div class="table-responsive mb-3">
                            <table class="table table-bordered align-middle" id="variantTable">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th style="width: 25%">Size</th>
                                        <th style="width: 35%">Màu sắc</th>
                                        <th style="width: 30%">Tồn kho hiện tại</th>
                                        <th style="width: 10%">TT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['variants'])): ?>
                                        <?php foreach($data['variants'] as $v): ?>
                                        <tr>
                                            <td class="text-center fw-bold bg-light"><?= $v['size'] ?></td>
                                            <td class="text-center bg-light"><?= $v['color'] ?></td>
                                            <td>
                                                <input type="number" name="variants[<?= $v['id'] ?>]" 
                                                       class="form-control text-center fw-bold border-primary" 
                                                       value="<?= $v['stock_quantity'] ?>" min="0">
                                            </td>
                                            <td class="text-center text-muted"><i class="fas fa-lock" title="Đã lưu"></i></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr id="no-variant-msg"><td colspan="4" class="text-center text-muted py-3">Chưa có biến thể nào. Hãy thêm mới bên dưới.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="p-3 bg-light rounded border border-dashed">
                            <h6 class="fw-bold small text-muted mb-2">THÊM BIẾN THỂ MỚI</h6>
                            <div id="newVariantContainer">
                                </div>
                            <button type="button" class="btn btn-outline-primary w-100 fw-bold border-2" onclick="addNewVariantRow()">
                                <i class="fas fa-plus-circle me-2"></i>Thêm dòng biến thể mới
                            </button>
                        </div>

                        <div class="d-grid mt-4 pt-2 border-top">
                            <button type="submit" class="btn btn-success btn-lg fw-bold shadow-sm">
                                <i class="fas fa-save me-2"></i>LƯU THAY ĐỔI
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let newIndex = 0;

function addNewVariantRow() {
    // Xóa thông báo "chưa có biến thể" nếu có
    const noMsg = document.getElementById('no-variant-msg');
    if(noMsg) noMsg.style.display = 'none';

    const container = document.getElementById('newVariantContainer');
    
    // Tạo HTML cho dòng mới (Sử dụng name array: new_variants[index][key])
    const html = `
        <div class="row g-2 mb-2 align-items-center new-row fade-in">
            <div class="col-3">
                <select name="new_variants[${newIndex}][size]" class="form-select form-select-sm" required>
                    <option value="" selected disabled>Chọn Size</option>
                    <option value="S">S</option><option value="M">M</option>
                    <option value="L">L</option><option value="XL">XL</option>
                    <option value="FreeSize">FreeSize</option>
                </select>
            </div>
            <div class="col-4">
                <input type="text" name="new_variants[${newIndex}][color]" class="form-control form-control-sm" placeholder="Nhập màu (VD: Đỏ)..." required>
            </div>
            <div class="col-3">
                <input type="number" name="new_variants[${newIndex}][stock]" class="form-control form-control-sm text-center fw-bold" placeholder="SL" value="10" min="0" required>
            </div>
            <div class="col-2 text-center">
                <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="this.closest('.new-row').remove()">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    
    // Chèn vào cuối container
    container.insertAdjacentHTML('beforeend', html);
    newIndex++;
}
</script>

<style>
    .fade-in { animation: fadeIn 0.3s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
    .border-dashed { border-style: dashed !important; }
</style>