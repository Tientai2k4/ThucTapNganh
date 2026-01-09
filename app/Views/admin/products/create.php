<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Thêm Sản Phẩm Mới</h6>
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>admin/product/store" method="POST" enctype="multipart/form-data">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Tên sản phẩm *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Mã SKU *</label>
                    <input type="text" name="sku_code" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label>Danh mục</label>
                    <select name="category_id" class="form-control" required>
                        <?php foreach($data['categories'] as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Thương hiệu</label>
                    <select name="brand_id" class="form-control">
                        <option value="">-- Chọn --</option>
                        <?php foreach($data['brands'] as $brand): ?>
                            <option value="<?= $brand['id'] ?>"><?= $brand['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Giá gốc</label>
                    <input type="number" name="price" class="form-control" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Giá khuyến mãi</label>
                    <input type="number" name="sale_price" class="form-control" value="0">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="fw-bold">Ảnh đại diện chính *</label>
                    <input type="file" name="image" class="form-control" accept="image/*" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="fw-bold text-info">Album ảnh phụ (Chọn nhiều ảnh)</label>
                    <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
                    <small class="text-muted">Nhấn giữ Ctrl để chọn nhiều ảnh</small>
                </div>
            </div>

            <div class="mb-3">
                <label>Mô tả chi tiết (Bao gồm hướng dẫn chọn Size)</label>
                <textarea name="description" class="form-control" rows="6" placeholder="Nhập mô tả sản phẩm và bảng quy đổi size tại đây..."></textarea>
            </div>

            <hr>
            <h5 class="text-primary">Quản lý Biến thể (Size & Màu)</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="variantTable">
                    <thead class="bg-light text-center">
                        <tr>
                            <th style="width: 15%">Size</th>
                            <th style="width: 20%">Màu sắc</th>
                            <th style="width: 15%">Tồn kho</th>
                            <th style="width: 40%">Giá riêng (VNĐ)</th>
                            <th style="width: 10%">
                                <button type="button" class="btn btn-sm btn-success" onclick="addVariant()">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="variants[0][size]" class="form-control">
                                    <option value="S">S</option> <option value="M">M</option>
                                    <option value="L">L</option> <option value="XL">XL</option>
                                    <option value="XXL">XXL</option> <option value="FreeSize">FreeSize</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="variants[0][color]" class="form-control" placeholder="Xanh...">
                            </td>
                            <td>
                                <input type="number" name="variants[0][stock]" class="form-control text-center" value="10">
                            </td>
                            <td>
                                <div class="input-group input-group-sm mb-1">
                                    <span class="input-group-text">Gốc</span>
                                    <input type="number" name="variants[0][price]" class="form-control" placeholder="0 = Giá chung">
                                </div>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text text-danger">Sale</span>
                                    <input type="number" name="variants[0][sale_price]" class="form-control" placeholder="Giá KM">
                                </div>
                            </td>
                            <td class="text-center align-middle"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-primary mt-3 w-100">Lưu Sản Phẩm</button>
        </form>
    </div>
</div>

<script>
let vIdx = 1;
function addVariant() {
    const html = `
        <tr>
            <td>
                <select name="variants[${vIdx}][size]" class="form-control">
                    <option value="S">S</option><option value="M">M</option><option value="L">L</option>
                    <option value="XL">XL</option><option value="XXL">XXL</option><option value="FreeSize">FreeSize</option>
                </select>
            </td>
            <td>
                <input type="text" name="variants[${vIdx}][color]" class="form-control" placeholder="Màu...">
            </td>
            <td>
                <input type="number" name="variants[${vIdx}][stock]" class="form-control text-center" value="10">
            </td>
            <td>
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text">Gốc</span>
                    <input type="number" name="variants[${vIdx}][price]" class="form-control" placeholder="0 = Giá chung">
                </div>
                <div class="input-group input-group-sm">
                    <span class="input-group-text text-danger">Sale</span>
                    <input type="number" name="variants[${vIdx}][sale_price]" class="form-control" placeholder="Giá KM">
                </div>
            </td>
            <td class="text-center align-middle">
                <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>`;
    
    document.querySelector('#variantTable tbody').insertAdjacentHTML('beforeend', html);
    vIdx++;
}
</script>