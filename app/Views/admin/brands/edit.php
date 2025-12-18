<div class="d-flex justify-content-between mb-4 align-items-center">
    <h3 class="h3 mb-0 text-gray-800">Chỉnh sửa Thương hiệu</h3>
    <a href="<?= BASE_URL ?>admin/brand" class="btn btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm me-1"></i> Quay lại
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="<?= BASE_URL ?>admin/brand/update/<?= $data['brand']['id'] ?>" method="POST" enctype="multipart/form-data">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label fw-bold">Tên thương hiệu <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-lg" id="name" name="name" 
                           required value="<?= htmlspecialchars($data['brand']['name']) ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="logo" class="form-label fw-bold">Logo (Để trống nếu không đổi)</label>
                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                    
                    <?php $currentLogo = $data['brand']['logo']; ?>
                    
                    <div class="mt-3 d-flex gap-4">
                        <div id="current-logo-display" style="<?= !empty($currentLogo) ? 'display: block;' : 'display: none;' ?>">
                            <p class="mb-1 small text-muted">Logo hiện tại:</p>
                            <div class="p-2 border rounded bg-light">
                                <img id="current-logo-img"
                                    src="<?= !empty($currentLogo) ? BASE_URL . 'public/uploads/brands/' . $currentLogo : '' ?>" 
                                    style="height: 80px; width: auto; object-fit: contain;" 
                                    alt="Logo hiện tại">
                            </div>
                        </div>

                        <div id="new-logo-preview-container" style="display: none;">
                            <p class="mb-1 small text-muted">Xem trước logo MỚI:</p>
                            <div class="p-2 border rounded bg-white">
                                <img id="new-logo-preview" src="" 
                                    style="height: 80px; width: auto; object-fit: contain;" 
                                    alt="Logo mới">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-warning px-4">
                    <i class="fas fa-save me-1"></i> Cập nhật thay đổi
                </button>
                <a href="<?= BASE_URL ?>admin/brand" class="btn btn-outline-secondary ms-2">Hủy bỏ</a>
            </div>

        </form>
    </div>
</div>

<script>
    document.getElementById('logo').addEventListener('change', function(event) {
        const currentLogoDisplay = document.getElementById('current-logo-display');
        const newPreviewContainer = document.getElementById('new-logo-preview-container');
        const newPreviewImage = document.getElementById('new-logo-preview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                newPreviewImage.src = e.target.result;
                newPreviewContainer.style.display = 'block';
                // Ẩn logo cũ đi cho đỡ rối mắt
                if(currentLogoDisplay) currentLogoDisplay.style.opacity = '0.5'; 
            }
            reader.readAsDataURL(file);
        } else {
            newPreviewImage.src = '';
            newPreviewContainer.style.display = 'none';
            if(currentLogoDisplay) currentLogoDisplay.style.opacity = '1';
        }
    });
</script>