<?php
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
        <?php unset($_SESSION['alert']); ?>
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
                    <div class="card-header py-3 bg-white border-bottom d-flex justify-content-between align-items-center">
                         <h6 class="m-0 fw-bold text-primary">Quản lý Biến thể (Size & Màu)</h6>
                         <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addVariantModal">
                            <i class="fas fa-plus"></i> Thêm nhanh biến thể
                         </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th width="15%">Size</th>
                                    <th width="20%">Màu sắc</th>
                                    <th width="15%">Tồn kho</th>
                                    <th width="35%">Giá Riêng (VNĐ)</th>
                                    <th width="15%">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($variants as $variant): ?>
                                <tr>
                                    <td>
                                        <select name="old_variants[<?= $variant['id'] ?>][size]" class="form-select form-select-sm">
                                            <option value="S" <?= is_selected($variant['size'], 'S') ?>>S</option>
                                            <option value="M" <?= is_selected($variant['size'], 'M') ?>>M</option>
                                            <option value="L" <?= is_selected($variant['size'], 'L') ?>>L</option>
                                            <option value="XL" <?= is_selected($variant['size'], 'XL') ?>>XL</option>
                                            <option value="XXL" <?= is_selected($variant['size'], 'XXL') ?>>XXL</option>
                                            <option value="FreeSize" <?= is_selected($variant['size'], 'FreeSize') ?>>FreeSize</option>
                                        </select>
                                    </td>
                                    
                                    <td>
                                        <input type="text" name="old_variants[<?= $variant['id'] ?>][color]" 
                                               class="form-control form-control-sm" 
                                               value="<?= htmlspecialchars($variant['color']) ?>">
                                    </td>
                                    
                                    <td>
                                        <input type="number" name="old_variants[<?= $variant['id'] ?>][stock]" 
                                               class="form-control form-control-sm text-center" 
                                               value="<?= $variant['stock_quantity'] ?>">
                                    </td>
                                    
                                    <td>
                                        <div class="input-group input-group-sm mb-1">
                                            <span class="input-group-text bg-light text-muted">Gốc</span>
                                            <input type="number" name="old_variants[<?= $variant['id'] ?>][price]" 
                                                   class="form-control" 
                                                   value="<?= (float)$variant['price'] ?>" placeholder="0">
                                        </div>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light text-danger">Sale</span>
                                            <input type="number" name="old_variants[<?= $variant['id'] ?>][sale_price]" 
                                                   class="form-control" 
                                                   value="<?= (float)$variant['sale_price'] ?>" placeholder="0">
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <a href="<?= BASE_URL ?>admin/product/deleteVariant/<?= $variant['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger border-0" 
                                           onclick="return confirm('Bạn có chắc muốn xóa biến thể này?')" 
                                           title="Xóa biến thể">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                
                                <?php if(empty($variants)): ?>
                                    <tr><td colspan="5" class="text-center text-muted py-3">Chưa có biến thể nào.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-white border-bottom">
                        <h6 class="m-0 fw-bold text-primary">Phân loại & Giá Chung</h6>
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
                            <label class="form-label fw-bold">Giá bán gốc (VNĐ)</label>
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
                    <div class="card-header py-3 bg-white border-bottom">
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
                        <i class="fas fa-save me-1"></i> LƯU THAY ĐỔI
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="addVariantModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thêm Biến Thể Mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= BASE_URL ?>admin/product/addVariant" method="POST">
          <div class="modal-body">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            
            <div class="mb-3">
                <label class="form-label">Size</label>
                <select name="size" class="form-select">
                    <option value="S">S</option><option value="M">M</option><option value="L">L</option>
                    <option value="XL">XL</option><option value="XXL">XXL</option><option value="FreeSize">FreeSize</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Màu sắc</label>
                <input type="text" name="color" class="form-control" placeholder="VD: Xanh, Đỏ..." required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Số lượng tồn kho</label>
                <input type="number" name="stock" class="form-control" value="10" required>
            </div>

            <div class="row">
                <div class="col-6 mb-3">
                    <label class="form-label">Giá Riêng (Gốc)</label>
                    <input type="number" name="price" class="form-control" placeholder="Để 0 lấy giá SP">
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label">Giá Sale (Nếu có)</label>
                    <input type="number" name="sale_price" class="form-control" placeholder="Giá khuyến mãi">
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            <button type="submit" class="btn btn-primary">Lưu Biến Thể</button>
          </div>
      </form>
    </div>
  </div>
</div>