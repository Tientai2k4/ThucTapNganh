<div class="container-fluid p-4">
    <h3 class="fw-bold mb-4 text-success"><i class="fas fa-envelope-open-text me-2"></i>Hộp thư phản hồi</h3>
    <div class="row">
        <?php foreach($data['contacts'] as $c): ?>
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm <?= $c['status'] == 0 ? 'border-start border-danger border-4' : '' ?>">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h6 class="fw-bold text-primary"><?= htmlspecialchars($c['full_name']) ?></h6>
                        <small class="text-muted"><?= date('d/m/Y', strtotime($c['created_at'])) ?></small>
                    </div>
                    <p class="small mb-1"><strong>Email:</strong> <?= $c['email'] ?></p>
                    <p class="card-text border-top pt-2 mt-2">"<?= nl2br(htmlspecialchars($c['message'])) ?>"</p>
                    <?php if($c['status'] == 0): ?>
                        <a href="<?= BASE_URL ?>staff/contact/mark/<?= $c['id'] ?>" class="btn btn-sm btn-success w-100 mt-2">
                            Đã liên hệ lại xong
                        </a>
                    <?php else: ?>
                        <span class="badge bg-light text-success w-100 py-2 mt-2"><i class="fas fa-check-circle me-1"></i> Hoàn thành</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>