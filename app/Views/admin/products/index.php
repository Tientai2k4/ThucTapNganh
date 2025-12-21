<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="h3 mb-0 text-gray-800">Quản lý Sản phẩm</h3>
    <a href="<?= BASE_URL ?>admin/product/create" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50 me-1"></i> Thêm sản phẩm
    </a>
</div>

<div class="card shadow-sm border-0 mb-4 bg-light">
    <div class="card-body py-3">
        <form action="" method="GET" class="row g-3">
            
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="keyword" class="form-control border-start-0 ps-0" 
                           placeholder="Tên sản phẩm hoặc mã SKU..." 
                           value="<?= htmlspecialchars($data['filters']['keyword'] ?? '') ?>">
                </div>
            </div>

            <div class="col-md-3">
                <select name="category_id" class="form-select">
                    <option value="">-- Tất cả danh mục --</option>
                    <?php if (!empty($data['categories'])): ?>
                        <?php foreach($data['categories'] as $cat): ?>
                            <option value="<?= $cat['id'] ?>" 
                                <?= (isset($data['filters']['category_id']) && $data['filters']['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="col-md-3">
                <select name="sort" class="form-select">
                    <option value="newest" <?= (isset($data['filters']['sort']) && $data['filters']['sort'] == 'newest') ? 'selected' : '' ?>>
                        Mới nhất
                    </option>
                    <option value="oldest" <?= (isset($data['filters']['sort']) && $data['filters']['sort'] == 'oldest') ? 'selected' : '' ?>>
                        Cũ nhất
                    </option>
                    <option value="price_asc" <?= (isset($data['filters']['sort']) && $data['filters']['sort'] == 'price_asc') ? 'selected' : '' ?>>
                        Giá tăng dần (Thấp -> Cao)
                    </option>
                    <option value="price_desc" <?= (isset($data['filters']['sort']) && $data['filters']['sort'] == 'price_desc') ? 'selected' : '' ?>>
                        Giá giảm dần (Cao -> Thấp)
                    </option>
                </select>
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 fw-bold">
                    <i class="fas fa-filter me-1"></i> Lọc
                </button>
                <a href="<?= BASE_URL ?>admin/product" class="btn btn-outline-secondary w-50" title="Xóa bộ lọc">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách sản phẩm</h6>
        <span class="badge bg-secondary rounded-pill">
            Tổng: <?= count($data['products']) ?> sản phẩm
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="table-light text-uppercase small text-muted">
                    <tr>
                        <th class="text-center" style="width: 60px;">ID</th>
                        <th class="text-center" style="width: 80px;">Ảnh</th>
                        <th style="width: 30%;">Tên sản phẩm / SKU</th>
                        <th>Danh mục</th>
                        <th>Giá bán</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['products'])): ?>
                        <?php foreach($data['products'] as $item): ?>
                        <tr>
                            <td class="text-center text-muted fw-bold">#<?= $item['id'] ?></td>
                            
                            <td class="text-center">
                                <?php if (!empty($item['image'])): ?>
                                    <img src="<?= BASE_URL ?>public/uploads/<?= $item['image'] ?>" 
                                         class="rounded border shadow-sm" 
                                         style="width: 50px; height: 50px; object-fit: cover;"
                                         alt="Product Image">
                                <?php else: ?>
                                    <div class="bg-light rounded border d-flex align-items-center justify-content-center text-muted" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            
                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($item['name']) ?></div>
                                <small class="text-muted">
                                    <i class="fas fa-barcode me-1"></i><?= htmlspecialchars($item['sku_code']) ?>
                                </small>
                            </td>

                            <td>
                                <span class="badge bg-info text-dark bg-opacity-25 border border-info px-2 py-1">
                                    <?= htmlspecialchars($item['cat_name'] ?? 'Chưa phân loại') ?>
                                </span>
                                <?php if(!empty($item['brand_name'])): ?>
                                    <br><small class="text-muted">Hãng: <?= htmlspecialchars($item['brand_name']) ?></small>
                                <?php endif; ?>
                            </td>
                            
                            <td>
                                <?php if ($item['sale_price'] > 0 && $item['sale_price'] < $item['price']): ?>
                                    <div class="text-danger fw-bold"><?= number_format($item['sale_price']) ?> đ</div>
                                    <small class="text-muted text-decoration-line-through"><?= number_format($item['price']) ?> đ</small>
                                <?php else: ?>
                                    <div class="fw-bold text-dark"><?= number_format($item['price']) ?> đ</div>
                                <?php endif; ?>
                            </td>
                            
                            <td class="text-center">
                                <a href="<?= BASE_URL ?>admin/product/edit/<?= $item['id'] ?>" 
                                   class="btn btn-sm btn-outline-warning mx-1" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= BASE_URL ?>admin/product/delete/<?= $item['id'] ?>" 
                                   class="btn btn-sm btn-outline-danger mx-1"
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm: <?= htmlspecialchars($item['name']) ?> không?')"
                                   title="Xóa">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-search fa-3x mb-3 text-gray-300"></i><br>
                                    <span class="h5">Không tìm thấy sản phẩm nào!</span>
                                    <p class="mb-0 mt-2">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>