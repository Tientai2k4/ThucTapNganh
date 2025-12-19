<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-danger"><i class="fas fa-exclamation-circle me-2"></i>Chi Tiết Tồn Kho Cảnh Báo</h3>
        <a href="<?= BASE_URL ?>admin/dashboard" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>Quay lại Dashboard
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body">
            <div class="alert alert-warning border-0 shadow-sm mb-4">
                <i class="fas fa-info-circle me-2"></i> Danh sách các sản phẩm có số lượng <b>dưới 10</b>. Hãy nhấn <b>Nhập kho</b> để cập nhật số lượng.
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="bg-danger text-white">
                        <tr>
                            <th>Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Phân loại</th>
                            <th>Biến thể (Size/Màu)</th>
                            <th class="text-center">Tồn kho</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['products'] as $p): ?>
                        <tr>
                            <td class="text-center" style="width: 80px;">
                                <img src="<?= BASE_URL ?>public/uploads/<?= $p['image'] ?>" class="rounded border shadow-sm" width="60" height="60" style="object-fit: cover;">
                            </td>
                            <td class="fw-bold text-dark">
                                <?= htmlspecialchars($p['name']) ?>
                                <div class="small text-muted">Mã SP: #<?= $p['product_id'] ?></div>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= $p['cat_name'] ?></span></td>
                            <td>
                                <span class="badge bg-info text-dark"><?= $p['size'] ?></span>
                                <span class="badge bg-secondary"><?= $p['color'] ?></span>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold text-danger fs-5"><?= $p['stock_quantity'] ?></span>
                            </td>
                            <td class="text-center">
                                <a href="<?= BASE_URL ?>admin/product/edit/<?= $p['product_id'] ?>" class="btn btn-primary btn-sm shadow-sm rounded-pill px-3">
                                    <i class="fas fa-truck-loading me-1"></i> Nhập kho
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>