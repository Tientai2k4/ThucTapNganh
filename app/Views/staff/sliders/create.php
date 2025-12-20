<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="m-0 fw-bold text-primary"><i class="fas fa-plus-circle me-2"></i>Thêm Banner Mới</h5>
                </div>
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>staff/slider/store" method="POST" enctype="multipart/form-data">
                        
                        <div class="mb-4 text-center">
                            <label for="sliderImage" class="form-label fw-bold d-block text-start mb-2">Hình ảnh Banner <span class="text-danger">*</span></label>
                            
                            <div class="border rounded bg-light p-4 position-relative" id="previewBox" style="border-style: dashed !important; cursor: pointer;">
                                <i class="fas fa-cloud-upload-alt fa-3x text-secondary mb-2"></i>
                                <p class="text-muted small m-0">Nhấn để tải ảnh lên (JPG, PNG)</p>
                                <img id="preview" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover rounded d-none">
                                <input type="file" name="image" id="sliderImage" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" accept="image/*" required onchange="previewImage(this)">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Đường dẫn khi click (Link URL)</label>
                            <input type="url" name="link_url" class="form-control" placeholder="https://...">
                            <div class="form-text">Để trống nếu không cần chuyển trang.</div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label fw-bold">Thứ tự hiển thị</label>
                                <input type="number" name="sort_order" class="form-control" value="0" min="0">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">Trạng thái</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="status" id="statusSwitch" checked>
                                    <label class="form-check-label" for="statusSwitch">Hiển thị ngay</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-2">
                            <a href="<?= BASE_URL ?>staff/slider" class="btn btn-light border">Hủy bỏ</a>
                            <button type="submit" class="btn btn-primary px-4 fw-bold">Lưu Banner</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('preview').classList.remove('d-none');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>