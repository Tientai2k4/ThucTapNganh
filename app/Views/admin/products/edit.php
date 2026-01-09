<?php
/**
 * GÁN BIẾN VÀ KIỂM TRA DỮ LIỆU
 */
$product = $data['product'] ?? null;
$categories = $data['categories'] ?? [];
$brands = $data['brands'] ?? [];
$variants = $data['variants'] ?? [];
$gallery = $data['gallery'] ?? [];

if (!$product) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>Không tìm thấy dữ liệu sản phẩm.</div></div>";
    exit;
}

if (!function_exists('is_selected')) {
    function is_selected($val1, $val2) {
        return ($val1 == $val2) ? 'selected' : '';
    }
}
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Cập nhật sản phẩm</h1>
            <p class="text-muted small mb-0">Chỉnh sửa thông tin chi tiết cho: <strong><?= htmlspecialchars($product['name']) ?></strong></p>
        </div>
        <a href="<?= BASE_URL ?>admin/product" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm me-1"></i> Quay lại danh sách
        </a>
    </div>

    <?php if (isset($_SESSION['alert'])): ?>
        <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-info-circle me-2"></i> <?= $_SESSION['alert']['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['alert']); // Xóa session sau khi hiện ?>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>admin/product/update/<?= $product['id'] ?>" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary text-white">
                        <h6 class="m-0 fw-bold"><i class="fas fa-info-circle me-1"></i> Thông tin chung</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Mã SKU <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="sku_code" value="<?= htmlspecialchars($product['sku_code']) ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                             <label class="form-label fw-bold">Mô tả chi tiết</label>
                             <textarea class="form-control" name="description" rows="5"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-white border-bottom">
                         <h6 class="m-0 fw-bold text-primary">Quản lý Biến thể (Size & Màu)</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle" id="variantTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Size</th>
                                    <th>Màu sắc</th>
                                    <th>Tồn kho</th>
                                    <th style="width: 180px;">Giá Riêng (VNĐ)</th>
                                    <th class="text-center" width="50">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $vIdx = 0; ?>
                                <?php foreach ($variants as $variant): ?>
                                <tr>
                                    <td class="bg-light">
                                        <strong><?= htmlspecialchars($variant['size']) ?></strong>
                                        <input type="hidden" name="old_variants[<?= $vIdx ?>][id]" value="<?= $variant['id'] ?>">
                                    </td>
                                    <td class="bg-light"><?= htmlspecialchars($variant['color']) ?></td>
                                    <td>
                                        <span class="badge bg-secondary"><?= $variant['stock_quantity'] ?></span>
                                    </td>
                                    
                                    <td>
                                        <?php if(!empty($variant['price']) && $variant['price'] > 0): ?>
                                            <div class="fw-bold text-success"><?= number_format($variant['price']) ?></div>
                                            <?php if(!empty($variant['sale_price']) && $variant['sale_price'] > 0): ?>
                                                <small class="text-danger">Sale: <?= number_format($variant['sale_price']) ?></small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <small class="text-muted fst-italic">Theo giá gốc</small>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <a href="<?= BASE_URL ?>admin/product/deleteVariant/<?= $variant['id'] ?>" 
                                        class="btn btn-sm btn-outline-danger border-0" 
                                        onclick="return confirm('Xóa biến thể này?')" title="Xóa biến thể cũ">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php $vIdx++; endforeach; ?>
                            </tbody>
                        </table>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm dashed-border w-100 mt-2" onclick="addVariant()">
                            <i class="fas fa-plus-circle"></i> Thêm dòng biến thể mới
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-white border-bottom">
                        <h6 class="m-0 fw-bold text-primary">Phân loại & Giá</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Danh mục</label>
                            <select class="form-select" name="category_id" required>
                                <option value="">-- Chọn danh mục --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= is_selected($cat['id'], $product['category_id']) ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Thương hiệu</label>
                            <select class="form-select" name="brand_id">
                                <option value="">-- Chọn thương hiệu --</option>
                                <?php foreach ($brands as $brand): ?>
                                    <option value="<?= $brand['id'] ?>" <?= is_selected($brand['id'], $product['brand_id']) ?>>
                                        <?= htmlspecialchars($brand['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Giá bán (VNĐ)</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="price" value="<?= $product['price'] ?>" required>
                                <span class="input-group-text">đ</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-danger">Giá Sale (Nếu có)</label>
                            <div class="input-group">
                                <input type="number" class="form-control border-danger" name="sale_price" value="<?= $product['sale_price'] ?>">
                                <span class="input-group-text">đ</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-white border-bottom">
                        <h6 class="m-0 fw-bold text-primary">Ảnh đại diện</h6>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3 position-relative">
                            <?php if (!empty($product['image'])): ?>
                                <img src="<?= BASE_URL ?>public/uploads/<?= $product['image'] ?>" 
                                     class="img-fluid rounded border p-1" style="max-height: 200px;" alt="Main Image">
                            <?php else: ?>
                                <div class="bg-light py-4 rounded text-muted border border-dashed">Chưa có ảnh</div>
                            <?php endif; ?>
                        </div>
                        <input type="file" class="form-control form-control-sm" name="image" accept="image/*">
                        <div class="form-text text-start">Chọn để thay thế ảnh hiện tại.</div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-primary">Album ảnh phụ</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2 mb-3">
                            <?php foreach ($gallery as $img): ?>
                                <div class="col-4 position-relative group-action">
                                    <img src="<?= BASE_URL ?>public/uploads/<?= $img['image_url'] ?>" class="img-thumbnail w-100" style="height: 70px; object-fit: cover;">
                                    <a href="<?= BASE_URL ?>admin/product/deleteGallery/<?= $img['id'] ?>/<?= $product['id'] ?>" 
                                       class="btn btn-danger btn-xs position-absolute top-0 end-0 translate-middle badge rounded-pill"
                                       onclick="return confirm('Xóa ảnh này?');" title="Xóa">
                                        &times;
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <label class="form-label small fw-bold">Tải thêm ảnh:</label>
                        <input type="file" name="gallery[]" class="form-control form-control-sm" multiple accept="image/*">
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-lg border-0 fixed-bottom position-sticky mt-3" style="z-index: 100;">
            <div class="card-body py-2 d-flex justify-content-between align-items-center bg-light border-top">
                <span class="text-muted small"><i class="fas fa-clock"></i> Cập nhật lần cuối: Hôm nay</span>
                <div>
                    <a href="<?= BASE_URL ?>admin/product" class="btn btn-outline-secondary me-2">Hủy bỏ</a>
                    <button type="submit" class="btn btn-success px-4 fw-bold">
                        <i class="fas fa-save me-1"></i> LƯU THAY ĐỔI & THOÁT
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let newVariantIndex = 0; 

function addVariant() {
    const html = `
        <tr class="table-warning">
            <td>
                <select name="variants[${newVariantIndex}][size]" class="form-select form-select-sm">
                    <option value="S">S</option><option value="M">M</option><option value="L">L</option>
                    <option value="XL">XL</option><option value="XXL">XXL</option><option value="FreeSize">FreeSize</option>
                </select>
            </td>
            <td>
                <input type="text" name="variants[${newVariantIndex}][color]" class="form-control form-control-sm" placeholder="Màu (VD: Đỏ)">
            </td>
            <td>
                <input type="number" name="variants[${newVariantIndex}][stock]" class="form-control form-control-sm" value="10">
            </td>
            
            <td>
                <input type="number" name="variants[${newVariantIndex}][price]" class="form-control form-control-sm mb-1" placeholder="Giá gốc (0 = theo SP)">
                <input type="number" name="variants[${newVariantIndex}][sale_price]" class="form-control form-control-sm" placeholder="Giá sale">
            </td>

            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="this.closest('tr').remove()">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>`;
    
    document.querySelector('#variantTable tbody').insertAdjacentHTML('beforeend', html);
    newVariantIndex++;
}
</script>