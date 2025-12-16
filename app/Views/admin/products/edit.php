<?php
/**
 * ĐOẠN CODE FIX LỖI & GÁN BIẾN:
 * Kiểm tra và gán biến từ mảng $data.
 */
$product = $data['product'] ?? null;
$categories = $data['categories'] ?? [];
$brands = $data['brands'] ?? []; // Thêm brands
$variants = $data['variants'] ?? [];
$gallery = $data['gallery'] ?? []; // Thêm gallery

// Kiểm tra chặn lỗi nếu không có dữ liệu sản phẩm
if (!$product) {
    echo "<div class='alert alert-danger'>Không tìm thấy dữ liệu sản phẩm. Vui lòng kiểm tra lại ID hoặc Database.</div>";
    exit; // Dừng chạy để không báo lỗi bên dưới
}

// Hàm hỗ trợ: Kiểm tra 'selected'
function is_selected($val1, $val2) {
    return ($val1 == $val2) ? 'selected' : '';
}
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Cập nhật sản phẩm: <?= htmlspecialchars($product['name'] ?? '') ?></h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="<?= BASE_URL ?>admin/product/update/<?= $product['id'] ?>" method="POST" enctype="multipart/form-data">
                
                <div class="row">
                    <div class="col-md-8">
                        
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="name">Tên sản phẩm *</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= htmlspecialchars($product['name'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="sku_code">Mã SKU *</label>
                                <input type="text" class="form-control" id="sku_code" name="sku_code" 
                                       value="<?= htmlspecialchars($product['sku_code'] ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="category_id">Danh mục</label>
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
                                <label for="brand_id">Thương hiệu</label>
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
                                <label for="price">Giá gốc</label>
                                <input type="number" class="form-control" id="price" name="price" 
                                       value="<?= $product['price'] ?? 0 ?>" required>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="sale_price">Giá khuyến mãi</label>
                                <input type="number" class="form-control" id="sale_price" name="sale_price" 
                                       value="<?= $product['sale_price'] ?? 0 ?>">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Mô tả chi tiết</label>
                            <textarea class="form-control" id="description" name="description" rows="5" 
                                      placeholder="Nhập mô tả sản phẩm và bảng quy đổi size tại đây..."><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                        </div>
                        
                        <hr>
                        <h5 class="text-primary">Quản lý Biến thể (Size & Màu)</h5>
                        <small class="text-danger">Lưu ý: Chức năng cập nhật/xóa biến thể hiện tại (ID) cần logic Controller phức tạp hơn. Form này chỉ cho phép bạn xem biến thể cũ và thêm biến thể mới.</small>
                        
                        <table class="table table-bordered" id="variantTable">
                            <thead>
                                <tr>
                                    <th>Size</th>
                                    <th>Màu sắc</th>
                                    <th>Tồn kho</th>
                                    <th><button type="button" class="btn btn-sm btn-success" onclick="addVariant()">+</button></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $vIdx = 0; ?>
                                <?php if (!empty($variants)): ?>
                                    <?php foreach ($variants as $variant): ?>
                                    <tr data-variant-id="<?= $variant['id'] ?>">
                                        <td>
                                            <select name="variants[<?= $vIdx ?>][size]" class="form-control">
                                                <option value="S" <?= is_selected($variant['size'], 'S') ?>>S</option> 
                                                <option value="M" <?= is_selected($variant['size'], 'M') ?>>M</option>
                                                <option value="L" <?= is_selected($variant['size'], 'L') ?>>L</option> 
                                                <option value="XL" <?= is_selected($variant['size'], 'XL') ?>>XL</option>
                                                <option value="FreeSize" <?= is_selected($variant['size'], 'FreeSize') ?>>FreeSize</option>
                                            </select>
                                        </td>
                                        <td><input type="text" name="variants[<?= $vIdx ?>][color]" class="form-control" value="<?= htmlspecialchars($variant['color']) ?>" placeholder="Xanh..."></td>
                                        <td><input type="number" name="variants[<?= $vIdx ?>][stock]" class="form-control" value="<?= $variant['stock_quantity'] ?>"></td> 
                                        <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">Xóa</button></td>
                                    </tr>
                                    <?php $vIdx++; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td>
                                            <select name="variants[0][size]" class="form-control">
                                                <option value="S">S</option> <option value="M">M</option>
                                                <option value="L">L</option> <option value="XL">XL</option>
                                                <option value="FreeSize">FreeSize</option>
                                            </select>
                                        </td>
                                        <td><input type="text" name="variants[0][color]" class="form-control" placeholder="Xanh..."></td>
                                        <td><input type="number" name="variants[0][stock]" class="form-control" value="10"></td>
                                        <td></td>
                                    </tr>
                                    <?php $vIdx = 1; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        
                    </div>

                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-header">Ảnh đại diện chính</div>
                            <div class="card-body text-center">
                                <?php if (!empty($product['image'])): ?>
                                    <img src="<?= BASE_URL ?>public/uploads/<?= $product['image'] ?>" 
                                        class="img-fluid mb-2" style="max-height: 200px; border: 1px solid #ddd;" 
                                        alt="Ảnh sản phẩm">
                                    <p class="text-muted small">Ảnh hiện tại: **<?= htmlspecialchars($product['image']) ?>**</p>
                                <?php else: ?>
                                    <p class="text-muted small">Chưa có ảnh</p>
                                <?php endif; ?>
                                
                                <label class="form-label">Chọn ảnh mới (nếu muốn thay đổi)</label>
                                <input type="file" class="form-control" name="image" accept="image/*">
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header bg-info text-white">Album ảnh phụ (Gallery)</div>
                            <div class="card-body">
                                <h6 class="small mb-2">Ảnh hiện có:</h6>
                                <div class="row mb-3" id="galleryContainer">
                                    <?php if (!empty($gallery)): ?>
                                        <?php foreach ($gallery as $img): ?>
                                            <div class="col-6 mb-2 text-center" id="img-<?= $img['id'] ?>">
                                                <img src="<?= BASE_URL ?>public/uploads/<?= $img['image_url'] ?>" 
                                                     class="img-fluid" style="max-height: 100px; border: 1px solid #ddd;" alt="Ảnh phụ">
                                                <br>
                                                <small><a href="#" data-id="<?= $img['id'] ?>" class="text-danger remove-gallery">Xóa</a></small>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="col-12"><p class="text-muted small">Chưa có ảnh phụ</p></div>
                                    <?php endif; ?>
                                </div>
                                
                                <label class="form-label text-info">Thêm ảnh mới (Chọn nhiều)</label>
                                <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
                                <small class="text-muted">Ảnh mới sẽ được thêm vào bộ sưu tập hiện có.</small>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
                    <a href="<?= BASE_URL ?>admin/product" class="btn btn-secondary">Hủy bỏ</a>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
// Khởi tạo index dựa trên số lượng biến thể đã tồn tại
let vIdx = <?= $vIdx ?? 0 ?>; 

function addVariant() {
    const html = `
        <tr data-variant-id="new-${vIdx}">
            <td>
                <select name="variants[${vIdx}][size]" class="form-control">
                    <option value="S">S</option><option value="M">M</option><option value="L">L</option>
                    <option value="XL">XL</option><option value="FreeSize">FreeSize</option>
                </select>
            </td>
            <td><input type="text" name="variants[${vIdx}][color]" class="form-control" placeholder="Màu..."></td>
            <td><input type="number" name="variants[${vIdx}][stock]" class="form-control" value="10"></td>
            <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">Xóa</button></td>
        </tr>`;
    document.querySelector('#variantTable tbody').insertAdjacentHTML('beforeend', html);
    vIdx++;
}

// [TÙY CHỌN] Logic xóa ảnh Gallery bằng AJAX (cần Controller hỗ trợ route này)
document.addEventListener('DOMContentLoaded', function() {
    const removeLinks = document.querySelectorAll('.remove-gallery');
    
    removeLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const imageId = this.getAttribute('data-id');
            
            if (confirm('Bạn có chắc chắn muốn xóa ảnh phụ này?')) {
                // Đây chỉ là giao diện. Bạn cần thêm code AJAX để gọi đến Controller/Model để xóa thực tế.
                // Ví dụ: fetch(BASE_URL + 'admin/product/deleteGalleryImage/' + imageId)
                
                // Tạm thời xóa khỏi giao diện
                document.getElementById('img-' + imageId).remove();
                alert('Ảnh đã được đánh dấu xóa (cần logic server để xóa vĩnh viễn)');
            }
        });
    });
});
</script>