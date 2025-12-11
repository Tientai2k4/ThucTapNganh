<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý Slider / Banner</h1>
        <a href="<?= BASE_URL ?>admin/slider/create" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Thêm Slider Mới
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Thứ tự</th>
                            <th>Hình ảnh</th>
                            <th>Link liên kết</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($sliders)): ?>
                            <?php foreach ($sliders as $slider): ?>
                                <tr>
                                    <td class="text-center align-middle"><?= $slider['sort_order'] ?></td>
                                    <td class="text-center">
                                        <img src="<?= BASE_URL ?>public/uploads/sliders/<?= $slider['image'] ?>" 
                                             alt="Slider" style="height: 80px; object-fit: cover; border: 1px solid #ddd;">
                                    </td>
                                    <td class="align-middle">
                                        <a href="<?= $slider['link_url'] ?>" target="_blank"><?= htmlspecialchars($slider['link_url']) ?></a>
                                    </td>
                                    <td class="align-middle text-center">
                                        <?php if ($slider['status'] == 1): ?>
                                            <span class="badge bg-success">Hiển thị</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Đang ẩn</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="<?= BASE_URL ?>admin/slider/edit/<?= $slider['id'] ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>admin/slider/delete/<?= $slider['id'] ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Bạn có chắc muốn xóa slider này?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center">Chưa có slider nào.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>