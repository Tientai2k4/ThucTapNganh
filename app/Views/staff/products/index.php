<div class="container-fluid px-4 py-4">
   <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-success mb-0"><i class="fas fa-boxes me-2"></i>Kho hàng & Giá</h3>
        <small class="text-muted">Quản lý tồn kho nhanh chóng</small>
    </div>
    
    <a href="<?= BASE_URL ?>staff/product/create" class="btn btn-primary fw-bold shadow-sm">
        <i class="fas fa-plus me-2"></i>Nhập hàng mới
    </a>
</div>

    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body bg-light rounded">
            <form action="" method="GET" class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="keyword" class="form-control" placeholder="Tên sản phẩm hoặc mã SKU..." value="<?= htmlspecialchars($data['filters']['keyword']) ?>">
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-select">
                        <option value="">-- Danh mục --</option>
                        <?php foreach($data['categories'] as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $data['filters']['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success w-100 fw-bold">Tìm kiếm</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-success">
                        <tr>
                            <th class="ps-4">Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Mã SKU</th>
                            <th>Giá bán</th>
                            <th class="text-center">Tổng tồn kho</th>
                            <th class="text-end pe-4">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['products'])): ?>
                            <?php foreach ($data['products'] as $p): ?>
                            <tr>
                                <td class="ps-4">
                                    <img src="<?= BASE_URL ?>public/uploads/<?= $p['image'] ?>" class="rounded border" width="50" height="50" style="object-fit: cover;">
                                </td>
                                <td>
                                    <div class="fw-bold"><?= htmlspecialchars($p['name']) ?></div>
                                    <small class="text-muted"><?= $p['cat_name'] ?></small>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= $p['sku_code'] ?></span></td>
                                <td>
                                    <?php if($p['sale_price'] > 0): ?>
                                        <div class="text-danger fw-bold"><?= number_format($p['sale_price']) ?>đ</div>
                                        <small class="text-decoration-line-through text-muted"><?= number_format($p['price']) ?>đ</small>
                                    <?php else: ?>
                                        <div class="fw-bold"><?= number_format($p['price']) ?>đ</div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($p['total_stock'] == 0): ?>
                                        <span class="badge bg-danger">Hết hàng</span>
                                    <?php elseif($p['total_stock'] < 10): ?>
                                        <span class="badge bg-warning text-dark">Sắp hết: <?= $p['total_stock'] ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-success"><?= $p['total_stock'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?= BASE_URL ?>staff/product/quickEdit/<?= $p['id'] ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit me-1"></i>Sửa kho
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center py-5">Không tìm thấy sản phẩm.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>