<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800">Quản lý Danh mục</h2>
        <p class="text-muted small mb-0">Quản lý phân loại sản phẩm của hệ thống</p>
    </div>
    <a href="<?= BASE_URL ?>admin/category/create" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50 me-1"></i> Thêm mới
    </a>
</div>

<div class="card shadow-sm border-0 mb-4 bg-light">
    <div class="card-body py-3">
        <form action="" method="GET" class="row g-3 align-items-center">
            
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="keyword" class="form-control border-start-0 ps-0" 
                           placeholder="Tìm kiếm tên danh mục..." 
                           value="<?= htmlspecialchars($data['filters']['keyword'] ?? '') ?>">
                </div>
            </div>

            <div class="col-md-4">
                <select name="parent_id" class="form-select cursor-pointer" onchange="this.form.submit()">
                    <option value="">-- Tất cả cấp độ --</option>
                    <option value="root" <?= ($data['filters']['parent_id'] === 'root') ? 'selected' : '' ?>>
                        📁 Chỉ xem Danh mục Gốc (Root)
                    </option>
                    <option disabled>──────────</option>
                    <?php foreach($data['root_categories'] as $root): ?>
                        <option value="<?= $root['id'] ?>" <?= ($data['filters']['parent_id'] == $root['id']) ? 'selected' : '' ?>>
                            ↳ Con của: <?= htmlspecialchars($root['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2">
                <a href="<?= BASE_URL ?>admin/category" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-sync-alt me-1"></i> Làm mới
                </a>
            </div>
            
            <div class="col-md-2 text-end text-muted small">
                Tìm thấy: <strong><?= count($data['categories']) ?></strong> kết quả
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-list me-2"></i>Danh sách hiển thị</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light text-secondary small text-uppercase">
                    <tr>
                        <th class="text-center" style="width: 60px;">ID</th>
                        <th style="width: 30%;">Tên danh mục</th>
                        <th style="width: 25%;">Thuộc danh mục (Cha)</th>
                        <th>Mô tả</th>
                        <th class="text-center" style="width: 120px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['categories'])): ?>
                        <?php foreach($data['categories'] as $cat): ?>
                        <tr>
                            <td class="text-center text-muted fw-bold">#<?= $cat['id'] ?></td>
                            
                            <td>
                                <?php if(empty($cat['parent_id'])): ?>
                                    <span class="text-primary fw-bold">
                                        <i class="fas fa-folder me-2"></i><?= htmlspecialchars($cat['name']) ?>
                                    </span>
                                <?php else: ?>
                                    <div class="ms-4 border-start ps-3 border-primary" style="border-width: 2px !important;">
                                        <span class="text-dark">
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </td>
                            
                            <td>
                                <?php if(!empty($cat['parent_name'])): ?>
                                    <span class="badge bg-info text-dark bg-opacity-10 border border-info">
                                        <i class="fas fa-level-up-alt me-1"></i> <?= htmlspecialchars($cat['parent_name']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success bg-opacity-75">
                                        <i class="fas fa-star me-1"></i> Danh mục Gốc
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="text-secondary small">
                                <?= htmlspecialchars($cat['description'] ?? '---') ?>
                            </td>
                            
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="<?= BASE_URL ?>admin/category/edit/<?= $cat['id'] ?>" class="btn btn-sm btn-light text-warning" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>admin/category/delete/<?= $cat['id'] ?>" class="btn btn-sm btn-light text-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')" title="Xóa">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="64" alt="Empty" class="mb-3 opacity-50">
                                <p class="text-muted mb-0">Không tìm thấy danh mục nào phù hợp.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>