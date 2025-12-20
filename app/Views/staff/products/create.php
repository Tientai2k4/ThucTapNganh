<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-primary mb-0"><i class="fas fa-plus-circle me-2"></i>Nhập Sản Phẩm Mới</h4>
            <small class="text-muted">Nhập thông tin sản phẩm và số lượng tồn kho ban đầu</small>
        </div>
        <a href="<?= BASE_URL ?>staff/product" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Quay lại Kho
        </a>
    </div>

    <form action="<?= BASE_URL ?>staff/product/store" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="m-0 fw-bold text-success"><i class="fas fa-info-circle me-2"></i>Thông tin chung</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="Ví dụ: Kính bơi Speedo Biofuse..." required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Mã SKU <span class="text-danger">*</span></label>
                                <input type="text" name="sku_code" class="form-control" placeholder="VD: SP-001" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Danh mục</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    <?php foreach($data['categories'] as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Thương hiệu</label>
                                <select name="brand_id" class="form-select">
                                    <option value="">-- Chọn thương hiệu --</option>
                                    <?php foreach($data['brands'] as $brand): ?>
                                        <option value="<?= $brand['id'] ?>"><?= $brand['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Mô tả sản phẩm</label>
                                <textarea name="description" class="form-control" rows="5" placeholder="Mô tả chi tiết chất liệu, công dụng..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-primary"><i class="fas fa-boxes me-2"></i>Quản lý Biến thể & Tồn kho</h6>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addVariantRow()">
                            <i class="fas fa-plus me-1"></i>Thêm dòng
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0 align-middle" id="variantTable">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 25%">Size</th>
                                        <th style="width: 35%">Màu sắc</th>
                                        <th style="width: 25%">Số lượng nhập</th>
                                        <th style="width: 15%" class="text-center">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select name="variants[0][size]" class="form-select">
                                                <option value="FreeSize">FreeSize</option>
                                                <option value="S">S</option>
                                                <option value="M">M</option>
                                                <option value="L">L</option>
                                                <option value="XL">XL</option>
                                                <option value="XXL">XXL</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="variants[0][color]" class="form-control" placeholder="VD: Xanh...">
                                        </td>
                                        <td>
                                            <input type="number" name="variants[0][stock]" class="form-control fw-bold text-center" value="10" min="0">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-light text-muted" disabled><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="m-0 fw-bold text-danger"><i class="fas fa-tag me-2"></i>Thiết lập Giá</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control fw-bold" placeholder="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Giá khuyến mãi</label>
                            <input type="number" name="sale_price" class="form-control" placeholder="Để trống nếu không giảm">
                            <small class="text-muted fst-italic">Nhập 0 hoặc để trống nếu bán đúng giá.</small>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="m-0 fw-bold text-info"><i class="fas fa-images me-2"></i>Hình ảnh</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ảnh đại diện chính <span class="text-danger">*</span></label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Album ảnh phụ</label>
                            <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
                            <small class="text-muted d-block mt-1"><i class="fas fa-info-circle"></i> Giữ phím <strong>Ctrl</strong> để chọn nhiều ảnh.</small>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">
                        <i class="fas fa-save me-2"></i>LƯU SẢN PHẨM
                    </button>
                    <a href="<?= BASE_URL ?>staff/product" class="btn btn-light">Hủy bỏ</a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let vIndex = 1;
function addVariantRow() {
    const html = `
        <tr>
            <td>
                <select name="variants[${vIndex}][size]" class="form-select">
                    <option value="FreeSize">FreeSize</option>
                    <option value="S">S</option><option value="M">M</option>
                    <option value="L">L</option><option value="XL">XL</option><option value="XXL">XXL</option>
                </select>
            </td>
            <td>
                <input type="text" name="variants[${vIndex}][color]" class="form-control" placeholder="Màu sắc...">
            </td>
            <td>
                <input type="number" name="variants[${vIndex}][stock]" class="form-control fw-bold text-center" value="10" min="0">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;
    document.querySelector('#variantTable tbody').insertAdjacentHTML('beforeend', html);
    vIndex++;
}
</script>