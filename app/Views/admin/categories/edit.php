<div class="d-flex justify-content-between mb-3">
    <h3>Chỉnh sửa: <span class="text-primary"><?= htmlspecialchars($data['category']['name'] ?? '') ?></span></h3>
    <a href="<?= BASE_URL ?>admin/category" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="<?= BASE_URL ?>admin/category/update/<?= $data['category']['id'] ?>" method="POST">
            
            <div class="mb-3">
                <label for="parent_id" class="form-label fw-bold text-primary">
                    <i class="fas fa-sitemap"></i> Danh mục cha
                </label>
                <select class="form-select" id="parent_id" name="parent_id">
                    <option value="" class="fw-bold text-success">-- ĐÂY LÀ DANH MỤC GỐC (ROOT) --</option>
                    
                    <?php if (!empty($data['categories'])): ?>
                        <?php foreach($data['categories'] as $cat): ?>
                            
                            <?php if ($cat['id'] != $data['category']['id'] && empty($cat['parent_id'])): ?>
                                
                                <option value="<?= $cat['id'] ?>" 
                                    <?= ($cat['id'] == $data['category']['parent_id']) ? 'selected' : '' ?>>
                                    📂 <?= htmlspecialchars($cat['name']) ?>
                                </option>

                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <div class="form-text text-muted">
                    Chỉ được phép chọn danh mục Gốc làm cha.
                </div>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label fw-bold">Tên danh mục <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" 
                       required 
                       value="<?= htmlspecialchars($data['category']['name'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-bold">Mô tả</label>
                <textarea class="form-control" id="description" name="description" rows="4"><?= htmlspecialchars($data['category']['description'] ?? '') ?></textarea>
            </div>

            <div class="mt-4 border-top pt-3">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-check-circle"></i> Cập nhật thay đổi
                </button>
                <a href="<?= BASE_URL ?>admin/category" class="btn btn-outline-secondary ms-2">
                    Hủy bỏ
                </a>
            </div>

        </form>
    </div>
</div>