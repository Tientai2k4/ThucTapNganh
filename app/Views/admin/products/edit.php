<?php
/**
 * GÁN BIẾN VÀ KIỂM TRA DỮ LIỆU
 */
$product = $data['product'] ?? null;
$categories = $data['categories'] ?? [];
$brands = $data['brands'] ?? [];
$variants = $data['variants'] ?? [];
$gallery = $data['gallery'] ?? [];

// Chặn lỗi nếu không có dữ liệu sản phẩm
if (!$product) {
    echo "<div class='alert alert-danger'>Không tìm thấy dữ liệu sản phẩm. Vui lòng kiểm tra lại Database.</div>";
    exit;
}

// Hàm hỗ trợ: Kiểm tra 'selected'
if (!function_exists('is_selected')) {
    function is_selected($val1, $val2) {
        return ($val1 == $val2) ? 'selected' : '';
    }
}
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Cập nhật sản phẩm: <?= htmlspecialchars($product['name'] ?? '') ?></h1>
        <a href="<?= BASE_URL ?>admin/product" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Quay lại danh sách
        </a>
    </div>

    <div class="card shadow mb-4 border-0">
        <div class="card-body">
            <form action="<?= BASE_URL ?>admin/product/update/<?= $product['id'] ?>" method="POST" enctype="multipart/form-data">
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label class="fw-bold" for="name">Tên sản phẩm *</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= htmlspecialchars($product['name'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label class="fw-bold" for="sku_code">Mã SKU *</label>
                                <input type="text" class="form-control" id="sku_code" name="sku_code" 
                                       value="<?= htmlspecialchars($product['sku_code'] ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label class="fw-bold" for="category_id">Danh mục</label>
                                <select class="form-control" id="category_id" name="category_id" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" 
                                            <?= is_selected($cat['id'], $product['category_id'] ?? '') ?>>
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label class="fw-bold" for="brand_id">Thương hiệu</label>
                                <select class="form-control" id="brand_id" name="brand_id">
                                    <option value="">-- Chọn thương hiệu --</option>
                                    <?php foreach ($brands as $brand): ?>
                                        <option value="<?= $brand['id'] ?>" 
                                            <?= is_selected($brand['id'], $product['brand_id'] ?? '') ?>>
                                            <?= htmlspecialchars($brand['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label class="fw-bold" for="price">Giá gốc (đ)</label>
                                <input type="number" class="form-control" id="price" name="price" 
                                       value="<?= $product['price'] ?? 0 ?>" required>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label class="fw-bold" for="sale_price">Giá khuyến mãi (đ)</label>
                                <input type="number" class="form-control" id="sale_price" name="sale_price" 
                                       value="<?= $product['sale_price'] ?? 0 ?>">
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="fw-bold" for="description">Mô tả chi tiết</label>
                            <textarea class="form-control" id="description" name="description" rows="8"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                        </div>
                        
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="text-primary mb-0 fw-bold">Quản lý Biến thể (Size & Màu)</h5>
                            <button type="button" class="btn btn-sm btn-success" onclick="addVariant()">
                                <i class="fas fa-plus"></i> Thêm biến thể mới
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="variantTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Size</th>
                                        <th>Màu sắc</th>
                                        <th>Tồn kho</th>
                                        <th width="80">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $vIdx = 0; ?>
                                    <?php if (!empty($variants)): ?>
                                        <?php foreach ($variants as $variant): ?>
                                        <tr>
                                            <td>
                                                <select name="variants[<?= $vIdx ?>][size]" class="form-control">
                                                    <option value="S" <?= is_selected($variant['size'], 'S') ?>>S</option> 
                                                    <option value="M" <?= is_selected($variant['size'], 'M') ?>>M</option>
                                                    <option value="L" <?= is_selected($variant['size'], 'L') ?>>L</option> 
                                                    <option value="XL" <?= is_selected($variant['size'], 'XL') ?>>XL</option>
                                                    <option value="FreeSize" <?= is_selected($variant['size'], 'FreeSize') ?>>FreeSize</option>
                                                </select>
                                            </td>
                                            <td><input type="text" name="variants[<?= $vIdx ?>][color]" class="form-control" value="<?= htmlspecialchars($variant['color']) ?>"></td>
                                            <td><input type="number" name="variants[<?= $vIdx ?>][stock]" class="form-control" value="<?= $variant['stock_quantity'] ?>"></td> 
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php $vIdx++; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header fw-bold">Ảnh đại diện chính</div>
                            <div class="card-body text-center">
                                <?php if (!empty($product['image'])): ?>
                                    <img src="<?= BASE_URL ?>public/uploads/<?= $product['image'] ?>" 
                                         class="img-thumbnail mb-3" style="max-height: 250px;" alt="Ảnh chính">
                                    <p class="text-muted small">Tên file: <?= htmlspecialchars($product['image']) ?></p>
                                <?php else: ?>
                                    <div class="bg-light py-5 mb-3 rounded">Chưa có ảnh chính</div>
                                <?php endif; ?>
                                
                                <input type="file" class="form-control" name="image" accept="image/*">
                                <small class="text-muted d-block mt-2">Chọn ảnh mới để thay thế ảnh hiện tại.</small>
                            </div>
                        </div>

                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-info text-white fw-bold">Album ảnh phụ (Gallery)</div>
                            <div class="card-body">
                                <div class="row g-2 mb-3">
                                    <?php if (!empty($gallery)): ?>
                                        <?php foreach ($gallery as $img): ?>
                                            <div class="col-6 text-center">
                                                <div class="border rounded p-1 position-relative">
                                                    <img src="<?= BASE_URL ?>public/uploads/<?= $img['image_url'] ?>" 
                                                         class="img-fluid rounded" style="height: 100px; object-fit: cover;">
                                                    <a href="<?= BASE_URL ?>admin/product/deleteGallery/<?= $img['id'] ?>/<?= $product['id'] ?>" 
                                                       class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                                       onclick="return confirm('Xóa ảnh này vĩnh viễn?');"
                                                       title="Xóa ảnh">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="col-12"><p class="text-muted small text-center italic">Chưa có ảnh phụ</p></div>
                                    <?php endif; ?>
                                </div>
                                
                                <label class="form-label fw-bold text-info">Tải lên ảnh phụ mới</label>
                                <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
                                <small class="text-muted">Chọn nhiều tệp để thêm vào bộ sưu tập.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white border-top-0 d-flex justify-content-end gap-2 px-0">
                    <a href="<?= BASE_URL ?>admin/product" class="btn btn-light border">Hủy bỏ</a>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        <i class="fas fa-save me-1"></i> Lưu thay đổi
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
let vIdx = <?= $vIdx ?>; 

function addVariant() {
    const html = `
        <tr>
            <td>
                <select name="variants[${vIdx}][size]" class="form-control">
                    <option value="S">S</option><option value="M">M</option><option value="L">L</option>
                    <option value="XL">XL</option><option value="FreeSize">FreeSize</option>
                </select>
            </td>
            <td><input type="text" name="variants[${vIdx}][color]" class="form-control" placeholder="Màu sắc..."></td>
            <td><input type="number" name="variants[${vIdx}][stock]" class="form-control" value="10"></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>`;
    document.querySelector('#variantTable tbody').insertAdjacentHTML('beforeend', html);
    vIdx++;
}
</script>