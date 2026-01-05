<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-primary mb-0"><i class="fas fa-box-open me-2"></i>NHẬP HÀNG MỚI</h4>
        <a href="<?= BASE_URL ?>staff/product" class="btn btn-secondary">
            <i class="fas fa-times me-1"></i>Hủy bỏ
        </a>
    </div>

    <form action="<?= BASE_URL ?>staff/product/store" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold text-success border-bottom">Thông tin sản phẩm</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required placeholder="VD: Áo thun nam...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Mã SKU <span class="text-danger">*</span></label>
                                <input type="text" name="sku_code" class="form-control" required placeholder="VD: AT-001">
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
                                <label class="form-label">Mô tả chi tiết</label>
                                <textarea name="description" class="form-control" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light fw-bold text-primary d-flex justify-content-between align-items-center">
                        <span>Nhập kho (Biến thể)</span>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addVariantRow()">+ Thêm dòng</button>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered mb-0" id="variantTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Size</th>
                                    <th>Màu sắc</th>
                                    <th width="150">Số lượng nhập</th>
                                    <th width="50"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="variants[0][size]" class="form-select">
                                            <option value="S">S</option><option value="M">M</option>
                                            <option value="L">L</option><option value="XL">XL</option>
                                            <option value="FreeSize">FreeSize</option>
                                        </select>
                                    </td>
                                    <td><input type="text" name="variants[0][color]" class="form-control" placeholder="Màu..."></td>
                                    <td><input type="number" name="variants[0][stock]" class="form-control fw-bold text-center" value="10" min="1"></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold">Thiết lập Giá</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control fw-bold" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Giá khuyến mãi</label>
                            <input type="number" name="sale_price" class="form-control" placeholder="0">
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold">Hình ảnh</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ảnh đại diện <span class="text-danger">*</span></label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Album ảnh phụ</label>
                            <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                    <i class="fas fa-save me-2"></i> LƯU VÀO KHO
                </button>
            </div>
        </div>
    </form>
</div>

<script>
let vIdx = 1;
function addVariantRow() {
    const html = `
        <tr>
            <td>
                <select name="variants[${vIdx}][size]" class="form-select">
                    <option value="S">S</option><option value="M">M</option>
                    <option value="L">L</option><option value="XL">XL</option>
                    <option value="FreeSize">FreeSize</option>
                </select>
            </td>
            <td><input type="text" name="variants[${vIdx}][color]" class="form-control" placeholder="Màu..."></td>
            <td><input type="number" name="variants[${vIdx}][stock]" class="form-control fw-bold text-center" value="10" min="1"></td>
            <td class="text-center"><button type="button" class="btn btn-sm text-danger" onclick="this.closest('tr').remove()"><i class="fas fa-trash"></i></button></td>
        </tr>`;
    document.querySelector('#variantTable tbody').insertAdjacentHTML('beforeend', html);
    vIdx++;
}
</script>