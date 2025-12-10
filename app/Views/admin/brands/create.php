<div class="d-flex justify-content-between mb-4">
    <h3 class="h3 mb-0 text-gray-800">Thêm mới Thương hiệu</h3>
    <a href="<?= BASE_URL ?>admin/brand" class="btn btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm me-1"></i> Quay lại
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="<?= BASE_URL ?>admin/brand/create" method="POST" enctype="multipart/form-data">
            
            <div class="mb-3">
                <label for="name" class="form-label fw-bold">Tên thương hiệu <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" required placeholder="Nhập tên thương hiệu...">
            </div>

            <div class="mb-3">
                <label for="logo" class="form-label fw-bold">Logo (Ảnh)</label>
                <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                
                <div class="mt-3" id="logo-preview-container" style="display: none;">
                    <p class="mb-1 small text-muted">Xem trước logo:</p>
                    <img id="logo-preview" 
                        src="" 
                        style="height: 60px; width: auto; border: 1px solid #ddd; padding: 5px; border-radius: 4px; object-fit: contain;" 
                        alt="Logo Preview">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Lưu lại
                </button>
                <a href="<?= BASE_URL ?>admin/brand" class="btn btn-outline-secondary ms-2">Hủy</a>
            </div>

        </form>
    </div>
</div>

<script>
    document.getElementById('logo').addEventListener('change', function(event) {
        const previewContainer = document.getElementById('logo-preview-container');
        const previewImage = document.getElementById('logo-preview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            previewImage.src = '';
            previewContainer.style.display = 'none';
        }
    });
</script>