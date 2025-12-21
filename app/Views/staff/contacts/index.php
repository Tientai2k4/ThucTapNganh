<div class="container-fluid p-4 bg-light" style="min-height: 100vh;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-danger m-0"><i class="fas fa-envelope-open-text me-2"></i>Hòm thư Khách hàng</h4>
            <small class="text-muted">Bạn có <strong class="text-danger"><?= $data['unread'] ?></strong> tin nhắn chưa đọc.</small>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-3 col-xl-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-2">
                    <div class="d-grid gap-1">
                        <a href="<?= BASE_URL ?>staff/contact" class="btn btn-light text-start fw-bold <?= ($data['filters']['status'] === '') ? 'bg-danger text-white' : 'text-secondary' ?>">
                            <i class="fas fa-inbox me-2"></i>Tất cả
                        </a>
                        <a href="<?= BASE_URL ?>staff/contact?status=0" class="btn btn-light text-start fw-bold d-flex justify-content-between align-items-center <?= ($data['filters']['status'] === '0') ? 'bg-danger text-white' : 'text-secondary' ?>">
                            <span><i class="fas fa-envelope me-2"></i>Chưa đọc</span>
                            <?php if($data['unread'] > 0): ?>
                                <span class="badge bg-white text-danger rounded-pill shadow-sm" style="font-size: 0.7rem;"><?= $data['unread'] ?></span>
                            <?php endif; ?>
                        </a>
                        <a href="<?= BASE_URL ?>staff/contact?status=1" class="btn btn-light text-start fw-bold <?= ($data['filters']['status'] === '1') ? 'bg-danger text-white' : 'text-secondary' ?>">
                            <i class="fas fa-check-double me-2"></i>Đã xử lý
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9 col-xl-10">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-secondary">
                                <tr>
                                    <th class="ps-4 py-3">Người gửi</th>
                                    <th class="py-3">Nội dung tin nhắn</th>
                                    <th class="py-3" style="width: 150px;">Thời gian</th>
                                    <th class="text-end pe-4 py-3">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($data['contacts'])): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="text-muted mb-2"><i class="far fa-envelope-open fa-3x text-light"></i></div>
                                            <span class="text-muted">Hòm thư trống.</span>
                                        </td>
                                    </tr>
                                <?php endif; ?>

                                <?php foreach($data['contacts'] as $c): ?>
                                <tr class="<?= $c['status'] == 0 ? 'bg-white fw-bold border-start border-4 border-danger' : 'bg-light text-muted' ?>">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                <span class="small fw-bold"><?= substr($c['full_name'], 0, 1) ?></span>
                                            </div>
                                            <div>
                                                <div class="text-dark"><?= htmlspecialchars($c['full_name']) ?></div>
                                                <small class="d-block <?= $c['status'] == 0 ? 'text-primary' : 'text-muted' ?>" style="font-size: 0.75rem;">
                                                    <?= $c['email'] ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 400px;">
                                            <?= htmlspecialchars($c['message']) ?>
                                        </div>
                                    </td>
                                    <td class="small text-muted">
                                        <?= date('d/m H:i', strtotime($c['created_at'])) ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <?php if($c['status'] == 0): ?>
                                            <a href="<?= BASE_URL ?>staff/contact/mark/<?= $c['id'] ?>" class="btn btn-outline-success btn-sm rounded-circle me-1" style="width: 32px; height: 32px; padding: 0; line-height: 30px;" title="Đánh dấu đã xem">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <a href="mailto:<?= $c['email'] ?>" class="btn btn-outline-primary btn-sm rounded-circle me-1" style="width: 32px; height: 32px; padding: 0; line-height: 30px;" title="Trả lời Email">
                                            <i class="fas fa-reply"></i>
                                        </a>
                                        
                                        <a href="<?= BASE_URL ?>staff/contact/delete/<?= $c['id'] ?>" class="btn btn-outline-secondary btn-sm rounded-circle" style="width: 32px; height: 32px; padding: 0; line-height: 30px;" onclick="return confirm('Xóa vĩnh viễn tin nhắn này?')" title="Xóa">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>