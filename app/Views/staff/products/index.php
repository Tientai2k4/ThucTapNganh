<div class="container-fluid p-4">
    <h3 class="fw-bold mb-4 text-success"><i class="fas fa-boxes me-2"></i>Kiểm kê Kho hàng</h3>
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá bán</th>
                        <th>Tồn kho</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['products'] as $p): ?>
                    <tr>
                        <td class="ps-3">
                            <div class="d-flex align-items-center">
                                <img src="<?= BASE_URL ?>public/uploads/<?= $p['image'] ?>" class="rounded me-2" width="40" height="40" style="object-fit: cover;">
                                <div>
                                    <div class="fw-bold small"><?= htmlspecialchars($p['name']) ?></div>
                                    <small class="text-muted"><?= $p['sku_code'] ?></small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark border"><?= $p['cat_name'] ?></span></td>
                        <td class="fw-bold text-danger"><?= number_format($p['price']) ?>đ</td>
                        <td><span class="badge bg-info">Xem chi tiết biến thể</span></td>
                        <td class="text-center">
                            <a href="<?= BASE_URL ?>staff/product/edit/<?= $p['id'] ?>" class="btn btn-sm btn-link text-success">
                                <i class="fas fa-edit"></i> Cập nhật kho
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>