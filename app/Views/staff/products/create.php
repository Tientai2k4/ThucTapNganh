<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-primary mb-0"><i class="fas fa-box-open me-2"></i>NHẬP HÀNG MỚI</h4>
            <small class="text-muted">Tạo sản phẩm và thiết lập kho hàng ban đầu</small>
        </div>
        <a href="<?= BASE_URL ?>staff/product" class="btn btn-secondary shadow-sm">
            <i class="fas fa-times me-1"></i>Hủy bỏ
        </a>
    </div>

    <?php if (isset($_SESSION['alert'])): ?>
        <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show shadow-sm mb-4">
            <i class="fas fa-info-circle me-2"></i><?= $_SESSION['alert']['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>staff/product/store" method="POST" enctype="multipart/form-data">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 fw-bold text-primary"><i class="fas fa-info-circle me-2"></i>Thông tin sản phẩm</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required placeholder="VD: Kính bơi Speedo...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Mã SKU <span class="text-danger">*</span></label>
                                <input type="text" name="sku_code" class="form-control" required placeholder="VD: SP-001">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Danh mục <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    <?php if(!empty($data['categories'])): ?>
                                        <?php foreach($data['categories'] as $cat): ?>
                                            <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Thương hiệu</label>
                                <select name="brand_id" class="form-select">
                                    <option value="">-- Chọn thương hiệu --</option>
                                    <?php if(!empty($data['brands'])): ?>
                                        <?php foreach($data['brands'] as $brand): ?>
                                            <option value="<?= $brand['id'] ?>"><?= $brand['name'] ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Mô tả chi tiết</label>
                                <textarea name="description" class="form-control" rows="4" placeholder="Nhập mô tả sản phẩm..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-success"><i class="fas fa-layer-group me-2"></i>Phân loại hàng (Biến thể)</h6>
                        <button type="button" class="btn btn-sm btn-outline-success fw-bold" onclick="addVariantRow()">
                            <i class="fas fa-plus me-1"></i>Thêm dòng
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0 align-middle" id="variantTable">
                                <thead class="bg-light text-center small text-uppercase">
                                    <tr>
                                        <th style="width: 20%">Size</th>
                                        <th style="width: 25%">Màu sắc</th>
                                        <th style="width: 15%">Số lượng</th>
                                        <th style="width: 30%">Giá riêng (Nếu có)</th>
                                        <th style="width: 10%">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="row_0">
                                        <td>
                                            <select name="variants[0][size]" class="form-select form-select-sm">
                                                <option value="S">S</option><option value="M">M</option>
                                                <option value="L">L</option><option value="XL">XL</option>
                                                <option value="XXL">XXL</option><option value="FreeSize" selected>FreeSize</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="variants[0][color]" class="form-control form-control-sm" placeholder="VD: Đen">
                                        </td>
                                        <td>
                                            <input type="number" name="variants[0][stock]" class="form-control form-control-sm text-center fw-bold" value="10" min="0">
                                        </td>
                                        <td>
                                            <input type="number" name="variants[0][price]" class="form-control form-control-sm mb-1" placeholder="Giá gốc riêng">
                                            <input type="number" name="variants[0][sale_price]" class="form-control form-control-sm border-danger" placeholder="Giá sale riêng">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-light text-muted" disabled><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3 bg-light border-top text-center text-muted small">
                            <i class="fas fa-info-circle me-1"></i> Nếu không nhập "Giá riêng", hệ thống sẽ sử dụng "Giá bán chung".
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 fw-bold text-primary"><i class="fas fa-tags me-2"></i>Giá bán chung</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Giá niêm yết (VNĐ) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="price" class="form-control fw-bold" required placeholder="0">
                                <span class="input-group-text">đ</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-danger">Giá khuyến mãi</label>
                            <div class="input-group">
                                <input type="number" name="sale_price" class="form-control text-danger fw-bold border-danger" placeholder="0">
                                <span class="input-group-text text-danger bg-danger bg-opacity-10">đ</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 fw-bold text-primary"><i class="fas fa-images me-2"></i>Hình ảnh</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ảnh đại diện <span class="text-danger">*</span></label>
                            <input type="file" name="image" class="form-control" accept="image/*" required onchange="previewImage(this, 'mainPreview')">
                            <div class="mt-2 text-center">
                                <img id="mainPreview" src="#" alt="Preview" class="img-thumbnail d-none" style="max-height: 150px;">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Album ảnh phụ</label>
                            <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
                            <div class="form-text small">Nhấn giữ Ctrl để chọn nhiều ảnh.</div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">
                        <i class="fas fa-save me-2"></i> LƯU SẢN PHẨM
                    </button>
                    <a href="<?= BASE_URL ?>staff/product" class="btn btn-light border">Hủy bỏ</a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let vIdx = 1;
function addVariantRow() {
    const html = `
        <tr class="fade-in">
            <td>
                <select name="variants[${vIdx}][size]" class="form-select form-select-sm">
                    <option value="S">S</option><option value="M">M</option>
                    <option value="L">L</option><option value="XL">XL</option>
                    <option value="XXL">XXL</option><option value="FreeSize">FreeSize</option>
                </select>
            </td>
            <td><input type="text" name="variants[${vIdx}][color]" class="form-control form-control-sm" placeholder="Màu..."></td>
            <td><input type="number" name="variants[${vIdx}][stock]" class="form-control form-control-sm text-center fw-bold" value="10" min="0"></td>
            <td>
                <input type="number" name="variants[${vIdx}][price]" class="form-control form-control-sm mb-1" placeholder="Giá gốc riêng">
                <input type="number" name="variants[${vIdx}][sale_price]" class="form-control form-control-sm border-danger" placeholder="Giá sale riêng">
            </td>
            <td class="text-center align-middle">
                <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="this.closest('tr').remove()"><i class="fas fa-trash-alt"></i></button>
            </td>
        </tr>`;
    document.querySelector('#variantTable tbody').insertAdjacentHTML('beforeend', html);
    vIdx++;
}

function previewImage(input, imgId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var img = document.getElementById(imgId);
            img.src = e.target.result;
            img.classList.remove('d-none');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<style>.fade-in { animation: fadeIn 0.3s ease-in; } @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }</style>