<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <?php if (isset($_SESSION['alert'])): ?>
                <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show mb-4 shadow-sm" role="alert">
                    <i class="fas fa-info-circle me-2"></i><?= $_SESSION['alert']['message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['alert']); ?>
            <?php endif; ?>

            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i>QUẢN LÝ KHO & GIÁ</h5>
                    <a href="<?= BASE_URL ?>staff/product" class="btn btn-sm btn-light text-primary fw-bold">
                        <i class="fas fa-arrow-left me-1"></i>Quay lại
                    </a>
                </div>
                
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded border">
                        <?php $img = !empty($data['product']['image']) ? $data['product']['image'] : 'default.png'; ?>
                        <div class="position-relative me-3">
                            <img src="<?= BASE_URL ?>public/uploads/<?= $img ?>" class="rounded border bg-white" width="80" height="80" style="object-fit: contain;">
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1 text-primary"><?= htmlspecialchars($data['product']['name']) ?></h4>
                            <div class="text-muted small mb-1">Mã SKU: <span class="fw-bold text-dark"><?= $data['product']['sku_code'] ?></span></div>
                            <div class="badge bg-secondary"><?= $data['product']['cat_name'] ?? 'Chưa phân loại' ?></div>
                        </div>
                    </div>

                    <form action="<?= BASE_URL ?>staff/product/updateStock" method="POST">
                        <input type="hidden" name="product_id" value="<?= $data['product']['id'] ?>">

                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fas fa-tag me-2"></i>CẬP NHẬT GIÁ BÁN CHUNG</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Giá niêm yết</label>
                                <div class="input-group">
                                    <input type="number" name="price" class="form-control fw-bold" value="<?= $data['product']['price'] ?>" required>
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Giá khuyến mãi</label>
                                <div class="input-group">
                                    <input type="number" name="sale_price" class="form-control text-danger fw-bold" value="<?= $data['product']['sale_price'] ?>">
                                    <span class="input-group-text text-danger bg-danger bg-opacity-10">VNĐ</span>
                                </div>
                                <div class="form-text small fst-italic">Để 0 nếu không giảm giá.</div>
                            </div>
                        </div>

                        <h6 class="fw-bold text-success border-bottom pb-2 mb-3 mt-4">
                            <i class="fas fa-cubes me-2"></i>DANH SÁCH BIẾN THỂ HIỆN CÓ
                        </h6>
                        
                        <div class="table-responsive mb-4">
                            <table class="table table-hover table-bordered align-middle">
                                <thead class="table-light text-center small text-uppercase">
                                    <tr>
                                        <th style="width: 20%">Size</th>
                                        <th style="width: 30%">Màu sắc</th>
                                        <th style="width: 30%">Tồn kho thực tế</th>
                                        <th style="width: 20%">Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['variants'])): ?>
                                        <?php foreach($data['variants'] as $v): ?>
                                            <tr>
                                                <td class="text-center fw-bold bg-light"><?= htmlspecialchars($v['size']) ?></td>
                                                <td class="text-center"><?= htmlspecialchars($v['color']) ?></td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <button class="btn btn-outline-secondary" type="button" onclick="adjustStock(<?= $v['id'] ?>, -1)"><i class="fas fa-minus"></i></button>
                                                        <input type="number" id="stock_<?= $v['id'] ?>" name="variants[<?= $v['id'] ?>]" 
                                                               class="form-control text-center fw-bold" value="<?= $v['stock_quantity'] ?>" min="0">
                                                        <button class="btn btn-outline-secondary" type="button" onclick="adjustStock(<?= $v['id'] ?>, 1)"><i class="fas fa-plus"></i></button>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <?php if($v['stock_quantity'] > 0): ?>
                                                        <span class="badge bg-success">Còn hàng</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Hết hàng</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="4" class="text-center text-muted py-3">Sản phẩm này chưa có biến thể nào.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="card bg-light border-dashed mb-4">
                            <div class="card-body">
                                <h6 class="fw-bold text-primary small mb-3 text-uppercase">
                                    <i class="fas fa-plus-circle me-1"></i>Thêm biến thể mới (Nếu cần)
                                </h6>
                                <div id="newVariantContainer"></div>
                                <button type="button" class="btn btn-outline-primary w-100 fw-bold border-2" onclick="addNewVariantRow()">
                                    + Thêm dòng biến thể mới
                                </button>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg fw-bold shadow-sm py-3">
                                <i class="fas fa-check-circle me-2"></i>LƯU CẬP NHẬT
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

// Hàm thêm dòng biến thể mới
function addNewVariantRow() {
    const container = document.getElementById('newVariantContainer');
    
    // Tạo HTML cho dòng mới
    const html = `
        <div class="row g-2 mb-3 align-items-center new-row fade-in bg-white p-2 rounded border shadow-sm">
            <div class="col-3">
                <select name="new_variants[${newIndex}][size]" class="form-select form-select-sm" required>
                    <option value="" selected disabled>-- Size --</option>
                    <option value="S">S</option><option value="M">M</option>
                    <option value="L">L</option><option value="XL">XL</option>
                    <option value="XXL">XXL</option><option value="FreeSize">FreeSize</option>
                </select>
            </div>
            <div class="col-4">
                <input type="text" name="new_variants[${newIndex}][color]" class="form-control form-control-sm" placeholder="Màu sắc..." required>
            </div>
            <div class="col-3">
                <input type="number" name="new_variants[${newIndex}][stock]" class="form-control form-control-sm text-center fw-bold" placeholder="SL" value="10" min="0" required>
            </div>
            <div class="col-2 text-center">
                <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="this.closest('.new-row').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', html);
    newIndex++;
}

// Hàm tăng giảm số lượng tồn kho nhanh
function adjustStock(id, amount) {
    const input = document.getElementById('stock_' + id);
    let currentVal = parseInt(input.value) || 0;
    let newVal = currentVal + amount;
    if (newVal < 0) newVal = 0;
    input.value = newVal;
}
</script>

<style>
    .fade-in { animation: fadeIn 0.3s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
    .border-dashed { border-style: dashed !important; border-width: 2px !important; border-color: #dee2e6 !important; }
</style>