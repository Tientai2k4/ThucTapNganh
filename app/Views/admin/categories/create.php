<div class="d-flex justify-content-between mb-3">
    <h3>Thêm mới Danh mục</h3>
    <a href="<?= BASE_URL ?>admin/category" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="<?= BASE_URL ?>admin/category/create" method="POST">
            
            <div class="mb-3">
                <label for="parent_id" class="form-label fw-bold text-primary">
                    <i class="fas fa-sitemap"></i> Thuộc nhóm nào?
                </label>
                <select class="form-select" id="parent_id" name="parent_id">
                    <option value="" class="fw-bold text-success">-- ĐÂY LÀ DANH MỤC GỐC (ROOT) --</option>
                    
                    <?php if (!empty($data['categories'])): ?>
                        <?php foreach($data['categories'] as $cat): ?>
                            
                            <?php if (empty($cat['parent_id'])): ?>
                                <option value="<?= $cat['id'] ?>">
                                    📂 <?= htmlspecialchars($cat['name']) ?> (Gốc)
                                </option>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <div class="form-text text-muted">
                    * Nếu ô này để trống: Bạn đang tạo danh mục lớn nhất.<br>
                    * Nếu chọn 1 mục: Bạn đang tạo danh mục con cho mục đó.<br>
                    <span class="text-danger">* Lưu ý: Danh mục con không thể chứa thêm danh mục khác.</span>
                </div>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label fw-bold">Tên danh mục mới <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" required placeholder="Ví dụ: Đồ bơi nam, Kính bơi...">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-bold">Mô tả</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Mô tả ngắn gọn..."></textarea>
            </div>

            <div class="mt-4 border-top pt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu Danh Mục
                </button>
                <button type="reset" class="btn btn-outline-secondary ms-2">
                    <i class="fas fa-eraser"></i> Nhập lại
                </button>
            </div>

        </form>
    </div>
</div>