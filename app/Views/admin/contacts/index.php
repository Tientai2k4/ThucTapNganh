<div class="d-flex justify-content-between mb-3">
    <h3>Hộp thư liên hệ</h3>
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
                            <a href="<?= BASE_URL ?>admin/contact/mark/<?= $c['id'] ?>" class="btn btn-sm btn-success" title="Đánh dấu đã xong">
                                <i class="fas fa-check"></i>
                            </a>
                        <?php endif; ?>

                        <a href="<?= BASE_URL ?>admin/contact/delete/<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa tin nhắn này?')" title="Xóa">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>