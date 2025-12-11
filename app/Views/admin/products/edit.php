<?php
/**
 * ĐOẠN CODE FIX LỖI:
 * Kiểm tra và gán biến từ mảng $data.
 * Nếu base Controller của bạn truyền biến tên là $data thì code này sẽ hoạt động.
 */
$product = isset($data['product']) ? $data['product'] : null;
$categories = isset($data['categories']) ? $data['categories'] : [];
$variants = isset($data['variants']) ? $data['variants'] : [];

// Kiểm tra chặn lỗi nếu không có dữ liệu sản phẩm
if (!$product) {
    echo "<div class='alert alert-danger'>Không tìm thấy dữ liệu sản phẩm. Vui lòng kiểm tra lại ID hoặc Database.</div>";
    exit; // Dừng chạy để không báo lỗi bên dưới
}
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Cập nhật sản phẩm: <?= htmlspecialchars($product['name'] ?? '') ?></h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="<?= BASE_URL ?>admin/product/update/<?= $product['id'] ?>" method="POST" enctype="multipart/form-data">
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group mb-3">
                            <label for="name">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?= htmlspecialchars($product['name'] ?? '') ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="sku_code">Mã SKU</label>
                                <input type="text" class="form-control" id="sku_code" name="sku_code" 
                                       value="<?= htmlspecialchars($product['sku_code'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="category_id">Danh mục</label>
                                <select class="form-control" id="category_id" name="category_id" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" 
                                            <?= ($cat['id'] == $product['category_id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['name']) ?>
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
                            <textarea class="form-control" id="description" name="description" rows="5"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-header">Ảnh sản phẩm</div>
                            <div class="card-body text-center">
                                <?php if (!empty($product['image'])): ?>
                                    <img src="<?= BASE_URL ?>public/uploads/<?= $product['image'] ?>" 
                                         class="img-fluid mb-2" style="max-height: 200px; border: 1px solid #ddd;" 
                                         alt="Ảnh sản phẩm">
                                    <p class="text-muted small">Ảnh hiện tại</p>
                                <?php else: ?>
                                    <p class="text-muted small">Chưa có ảnh</p>
                                <?php endif; ?>
                                
                                <label class="form-label">Chọn ảnh mới (nếu muốn thay đổi)</label>
                                <input type="file" class="form-control" name="image" accept="image/*">
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">Biến thể hiện có</div>
                            <ul class="list-group list-group-flush">
                                <?php if (!empty($variants)): ?>
                                    <?php foreach ($variants as $variant): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>
                                                Size: <strong><?= $variant['size'] ?></strong> - 
                                                Màu: <strong><?= $variant['color'] ?></strong>
                                            </span>
                                            <span class="badge bg-primary rounded-pill">Kho: <?= $variant['stock_quantity'] ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li class="list-group-item text-muted">Chưa có biến thể nào</li>
                                <?php endif; ?>
                            </ul>
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