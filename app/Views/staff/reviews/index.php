<div class="container-fluid p-4">
    <h3 class="fw-bold mb-4 text-success"><i class="fas fa-star-half-alt me-2"></i>Kiểm duyệt Đánh giá</h3>
    <div class="row">
        <?php foreach($data['reviews'] as $r): ?>
        <div class="col-md-4 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-warning mb-2">
                        <?php for($i=1; $i<=5; $i++) echo ($i <= $r['rating']) ? '★' : '☆'; ?>
                    </div>
                    <p class="small"><strong>Sản phẩm:</strong> <?= $r['product_name'] ?></p>
                    <p class="fst-italic text-muted">"<?= htmlspecialchars($r['comment']) ?>"</p>
                    <hr>
                    <form action="<?= BASE_URL ?>staff/review/updateStatus" method="POST">
                        <input type="hidden" name="review_id" value="<?= $r['id'] ?>">
                        <div class="input-group">
                            <select name="status" class="form-select form-select-sm">
                                <option value="0" <?= $r['status'] == 0 ? 'selected' : '' ?>>Ẩn (Chờ duyệt)</option>
                                <option value="1" <?= $r['status'] == 1 ? 'selected' : '' ?>>Hiện công khai</option>
                            </select>
                            <button class="btn btn-sm btn-primary" type="submit">Lưu</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-white border-0 py-2">
                    <small class="text-muted">Bởi: <?= htmlspecialchars($r['full_name'] ?? 'Khách') ?></small>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>