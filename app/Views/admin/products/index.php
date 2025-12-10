<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="h3 mb-0 text-gray-800">Quản lý Sản phẩm</h3>
    <a href="<?= BASE_URL ?>admin/product/create" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50 me-1"></i> Thêm sản phẩm
    </a>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center" style="width: 80px;">Ảnh</th>
                        <th style="width: 25%;">Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá bán</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['products'])): ?>
                        <?php foreach($data['products'] as $item): ?>
                        <tr>
                            <td class="text-center text-muted">#<?= $item['id'] ?></td>
                            
                            <td class="text-center">
                                <?php if (!empty($item['image'])): ?>
                                    <img src="<?= BASE_URL ?>public/uploads/<?= $item['image'] ?>" 
                                         class="rounded border" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                <?php else: ?>
                                    <span class="badge bg-secondary">No Image</span>
                                <?php endif; ?>
                            </td>
                            
                            <td class="fw-bold">
                                <?= htmlspecialchars($item['name']) ?>
                                <br>
                                <small class="text-muted">SKU: <?= htmlspecialchars($item['sku_code']) ?></small>
                            </td>

                            <td>
                                <span class="badge bg-info text-dark">
                                    <?= htmlspecialchars($item['cat_name'] ?? 'Chưa phân loại') ?>
                                </span>
                            </td>
                            
                            <td>
                                <?php if ($item['sale_price'] > 0 && $item['sale_price'] < $item['price']): ?>
                                    <div class="text-danger fw-bold"><?= number_format($item['sale_price']) ?> đ</div>
                                    <small class="text-muted text-decoration-line-through"><?= number_format($item['price']) ?> đ</small>
                                <?php else: ?>
                                    <div class="fw-bold"><?= number_format($item['price']) ?> đ</div>
                                <?php endif; ?>
                            </td>
                            
                            <td class="text-center">
                                <a href="<?= BASE_URL ?>admin/product/edit/<?= $item['id'] ?>" class="btn btn-sm btn-outline-warning mx-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= BASE_URL ?>admin/product/delete/<?= $item['id'] ?>" 
                                   class="btn btn-sm btn-outline-danger mx-1"
                                   onclick="return confirm('Xóa sản phẩm này?')">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-box-open fa-3x mb-3"></i><br>
                                Chưa có sản phẩm nào.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>