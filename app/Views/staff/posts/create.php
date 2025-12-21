<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-info"><i class="fas fa-pen-nib me-2"></i>Viết bài mới</h4>
            <small class="text-muted">Tạo nội dung hấp dẫn cho khách hàng</small>
        </div>
        <a href="<?= BASE_URL ?>staff/post" class="btn btn-light border shadow-sm hover-lift">
            <i class="fas fa-arrow-left me-1"></i>Quay lại danh sách
        </a>
    </div>

    <form action="<?= BASE_URL ?>staff/post/store" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold text-uppercase small text-muted">Tiêu đề bài viết <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-lg fw-bold" placeholder="Nhập tiêu đề hấp dẫn..." required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-uppercase small text-muted">Tóm tắt ngắn (Sapo)</label>
                            <textarea name="excerpt" class="form-control" rows="3" placeholder="Mô tả ngắn gọn nội dung bài viết để hiện ngoài trang chủ..."></textarea>
                            <div class="form-text">Nên viết khoảng 2-3 câu ngắn gọn.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-uppercase small text-muted">Nội dung chi tiết <span class="text-danger">*</span></label>
                            <textarea name="content" id="editor" class="form-control" rows="15" placeholder="Viết nội dung tại đây..." required></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold text-info">
                        <i class="fas fa-cog me-2"></i>Thiết lập
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="status" id="statusSwitch" checked>
                            <label class="form-check-label fw-bold" for="statusSwitch">Công khai ngay</label>
                        </div>
                        <div class="text-muted small mb-3">
                            <i class="fas fa-info-circle me-1"></i>
                            Nếu tắt, bài viết sẽ ở trạng thái <strong>Nháp</strong> và không hiển thị với khách hàng.
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-info text-white fw-bold">
                                <i class="fas fa-save me-2"></i>Lưu bài viết
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold text-info">
                        <i class="fas fa-image me-2"></i>Ảnh đại diện (Thumbnail)
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3 position-relative">
                            <div class="ratio ratio-16x9 bg-light border rounded overflow-hidden mb-2" id="previewContainer" style="border-style: dashed !important; cursor: pointer;" onclick="document.getElementById('thumbInput').click()">
                                <div class="d-flex align-items-center justify-content-center h-100 text-muted flex-column" id="placeholder">
                                    <i class="fas fa-cloud-upload-alt fa-3x mb-2"></i>
                                    <span>Chọn ảnh</span>
                                </div>
                                <img id="preview" src="" class="w-100 h-100 object-fit-cover d-none">
                            </div>
                            
                            <input type="file" name="thumbnail" id="thumbInput" class="d-none" accept="image/*" onchange="previewImage(this)">
                            
                            <button type="button" class="btn btn-outline-secondary btn-sm w-100" onclick="document.getElementById('thumbInput').click()">
                                Chọn ảnh khác
                            </button>
                        </div>
                        <div class="form-text text-start">
                            Hỗ trợ: JPG, PNG. Kích thước tối ưu: 800x450px.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Script xem trước ảnh khi upload
function previewImage(input) {
    const preview = document.getElementById('preview');
    const placeholder = document.getElementById('placeholder');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            placeholder.classList.add('d-none');
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = "";
        preview.classList.add('d-none');
        placeholder.classList.remove('d-none');
    }
}
</script>