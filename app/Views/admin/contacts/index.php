<?php $prefix = $data['role_prefix'] ?? 'admin'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="h3 mb-0 text-gray-800">Hộp thư liên hệ (<?= ucfirst($prefix) ?>)</h3>
</div>

<div class="card shadow-sm border-0 mb-4 bg-light">
    <div class="card-body py-3">
        <form action="" method="GET" class="row g-3">
            
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="keyword" class="form-control border-start-0 ps-0" 
                           placeholder="Tên, Email hoặc Số điện thoại..." 
                           value="<?= htmlspecialchars($data['filters']['keyword'] ?? '') ?>">
                </div>
            </div>

            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="0" <?= (isset($data['filters']['status']) && $data['filters']['status'] === '0') ? 'selected' : '' ?>>Chưa xem (Mới)</option>
                    <option value="1" <?= (isset($data['filters']['status']) && $data['filters']['status'] === '1') ? 'selected' : '' ?>>Đã xử lý</option>
                </select>
            </div>

            <div class="col-md-2">
                <select name="sort" class="form-select">
                    <option value="newest" <?= ($data['filters']['sort'] == 'newest') ? 'selected' : '' ?>>Mới nhất</option>
                    <option value="oldest" <?= ($data['filters']['sort'] == 'oldest') ? 'selected' : '' ?>>Cũ nhất</option>
                </select>
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 fw-bold">Lọc</button>
                <a href="<?= BASE_URL . $prefix ?>/contact" class="btn btn-outline-secondary" title="Reset">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách tin nhắn</h6>
        <span class="badge bg-secondary rounded-pill">Tổng: <?= count($data['contacts']) ?> tin</span>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="table-light text-uppercase small text-muted">
                    <tr>
                        <th style="width: 25%;">Khách hàng</th>
                        <th>Nội dung tin nhắn</th>
                        <th style="width: 15%;">Ngày gửi</th>
                        <th style="width: 12%;" class="text-center">Trạng thái</th>
                        <th style="width: 15%;" class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['contacts'])): ?>
                        <?php foreach($data['contacts'] as $c): ?>
                        <tr class="<?= $c['status'] == 0 ? 'fw-bold bg-white' : '' ?>">
                            <td>
                                <div class="text-dark"><?= htmlspecialchars($c['full_name']) ?></div>
                                <div class="small text-muted">
                                    <i class="fas fa-envelope me-1"></i><?= htmlspecialchars($c['email']) ?>
                                </div>
                                <div class="small text-primary">
                                    <i class="fas fa-phone me-1"></i><?= htmlspecialchars($c['phone']) ?>
                                </div>
                            </td>

                            <td>
                                <div class="text-break" style="max-height: 100px; overflow-y: auto;">
                                    <?= nl2br(htmlspecialchars($c['message'])) ?>
                                </div>
                            </td>

                            <td class="small text-muted">
                                <?= date('d/m/Y', strtotime($c['created_at'])) ?><br>
                                <?= date('H:i', strtotime($c['created_at'])) ?>
                            </td>

                            <td class="text-center">
                                <?php if($c['status'] == 0): ?>
                                    <span class="badge bg-danger">Chưa xem</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Đã xử lý</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">
                                <a href="mailto:<?= $c['email'] ?>?subject=Phản hồi từ SwimmingStore" 
                                   class="btn btn-sm btn-primary mx-1" title="Gửi email trả lời">
                                    <i class="fas fa-envelope"></i>
                                </a>

                                <?php if($c['status'] == 0): ?>
                                    <?php 
                                        // Xác định link action dựa trên role prefix
                                        // Admin dùng function 'mark', Staff có thể dùng 'updateStatus' tùy logic controller
                                        $action = 'mark'; // Mặc định theo Admin Controller bạn đưa
                                    ?>
                                    <a href="<?= BASE_URL . $prefix ?>/contact/<?= $action ?>/<?= $c['id'] ?>" 
                                       class="btn btn-sm btn-success mx-1" title="Đánh dấu đã xem">
                                        <i class="fas fa-check"></i>
                                    </a>
                                <?php endif; ?>

                                <?php if($prefix == 'admin'): ?>
                                    <a href="<?= BASE_URL ?>admin/contact/delete/<?= $c['id'] ?>" 
                                       class="btn btn-sm btn-outline-danger mx-1" 
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa tin nhắn này không?')" 
                                       title="Xóa">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 text-gray-300"></i><br>
                                    <span class="h5">Không tìm thấy tin nhắn nào!</span>
                                    <p class="mb-0 mt-2">Thử thay đổi bộ lọc tìm kiếm.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>