<?php
// Gỡ biến từ mảng data để code gọn hơn
$slider = isset($data['slider']) ? $data['slider'] : null;
if (!$slider) { die('Không tìm thấy dữ liệu slider'); }
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Cập nhật Slider</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="<?= BASE_URL ?>admin/slider/update/<?= $slider['id'] ?>" method="POST" enctype="multipart/form-data">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Hình ảnh hiện tại</label>
                            <div class="mb-2">
                                <img src="<?= BASE_URL ?>public/uploads/sliders/<?= $slider['image'] ?>" 
                                     class="img-fluid border" style="max-height: 150px;">
                            </div>
                            <label>Thay đổi ảnh (Nếu cần)</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>

                        <div class="form-group mb-3">
                            <label>Đường dẫn liên kết</label>
                            <input type="text" class="form-control" name="link_url" 
                                   value="<?= htmlspecialchars($slider['link_url']) ?>">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Thứ tự hiển thị</label>
                            <input type="number" class="form-control" name="sort_order" 
                                   value="<?= $slider['sort_order'] ?>">
                        </div>

                        <div class="form-group mb-3">
                            <label>Trạng thái</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="status" name="status" 
                                       <?= $slider['status'] == 1 ? 'checked' : '' ?>>
                                <label class="form-check-label" for="status">Đang hiển thị</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Cập nhật</button>
                    <a href="<?= BASE_URL ?>admin/slider" class="btn btn-secondary">Quay lại</a>
                </div>

            </form>
        </div>
    </div>
</div>