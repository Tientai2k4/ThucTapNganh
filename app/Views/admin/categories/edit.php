<div class="d-flex justify-content-between mb-3">
    <h3>Chỉnh sửa Danh mục: <?= htmlspecialchars($data['category']['name']) ?></h3>
    <a href="<?= BASE_URL ?>admin/category" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="<?= BASE_URL ?>admin/category/update/<?= $data['category']['id'] ?>" method="POST">
            
            <div class="mb-3">
                <label for="name" class="form-label fw-bold">Tên danh mục <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" 
                       required 
                       value="<?= htmlspecialchars($data['category']['name']) ?>" 
                       placeholder="Nhập tên danh mục...">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-bold">Mô tả</label>
                <textarea class="form-control" id="description" name="description" rows="4" 
                          placeholder="Nhập mô tả danh mục (không bắt buộc)..."><?= htmlspecialchars($data['category']['description']) ?></textarea>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Cập nhật
                </button>
                <a href="<?= BASE_URL ?>admin/category" class="btn btn-outline-secondary ms-2">
                    Hủy bỏ
                </a>
            </div>

        </form>
    </div>
</div>