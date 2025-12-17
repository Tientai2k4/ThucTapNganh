<?php $prefix = $data['role_prefix'] ?? 'admin'; ?>

<div class="d-flex justify-content-between mb-3">
    <h3>Hộp thư liên hệ (<?= ucfirst($prefix) ?>)</h3>
</div>

<div class="card shadow">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Khách hàng</th>
                    <th>Nội dung</th>
                    <th>Ngày gửi</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data['contacts'])): ?>
                    <?php foreach($data['contacts'] as $c): ?>
                    <tr class="<?= $c['status'] == 0 ? 'fw-bold bg-light' : '' ?>">
                        <td style="width: 20%;">
                            <?= htmlspecialchars($c['full_name']) ?><br>
                            <small class="text-muted"><?= htmlspecialchars($c['email']) ?></small><br>
                            <small class="text-primary"><?= htmlspecialchars($c['phone']) ?></small>
                        </td>
                        <td>
                            <?= nl2br(htmlspecialchars($c['message'])) ?>
                        </td>
                        <td style="width: 15%; font-size: 0.9rem;">
                            <?= date('d/m/Y H:i', strtotime($c['created_at'])) ?>
                        </td>
                        <td style="width: 10%;">
                            <?php if($c['status'] == 0): ?>
                                <span class="badge bg-danger">Chưa xem</span>
                            <?php else: ?>
                                <span class="badge bg-success">Đã xử lý</span>
                            <?php endif; ?>
                        </td>
                        <td style="width: 15%;">
                            <a href="mailto:<?= $c['email'] ?>?subject=Phản hồi từ SwimmingStore" class="btn btn-sm btn-primary" title="Gửi mail trả lời">
                                <i class="fas fa-envelope"></i>
                            </a>

                            <?php if($c['status'] == 0): ?>
                                <?php 
                                    // Tùy vào controller bạn đặt tên hàm là mark hay updateStatus
                                    // Ở StaffController ta đặt là updateStatus, AdminController là mark (theo code cũ của bạn)
                                    // Để đồng bộ, tốt nhất nên sửa Controller Admin cho giống Staff, hoặc dùng if ở đây.
                                    $action = ($prefix == 'staff') ? 'updateStatus' : 'mark'; 
                                ?>
                                <a href="<?= BASE_URL . $prefix ?>/contact/<?= $action ?>/<?= $c['id'] ?>" class="btn btn-sm btn-success" title="Đánh dấu đã xong">
                                    <i class="fas fa-check"></i>
                                </a>
                            <?php endif; ?>

                            <?php if($prefix == 'admin'): ?>
                                <a href="<?= BASE_URL ?>admin/contact/delete/<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa tin nhắn này?')" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Chưa có liên hệ nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>