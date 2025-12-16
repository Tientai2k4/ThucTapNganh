<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
            <form action="<?= BASE_URL ?>product" method="GET" class="card shadow-sm border-0 p-3">
                <h5 class="fw-bold text-primary border-bottom pb-2 mb-3"><i class="fas fa-filter"></i> BỘ LỌC TÌM KIẾM</h5>
                
                <div class="mb-4">
                    <h6 class="fw-bold">Đối tượng</h6>
                    <?php foreach($data['categories'] as $cat): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="cat" value="<?= $cat['id'] ?>"
                                <?= ($data['filters']['category_id'] == $cat['id']) ? 'checked' : '' ?>
                                onchange="this.form.submit()">
                            <label class="form-check-label"><?= $cat['name'] ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold">Thương hiệu</h6>
                    <?php foreach($data['brands'] as $brand): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="brand[]" value="<?= $brand['id'] ?>"
                                <?= (in_array($brand['id'], $data['filters']['brands'])) ? 'checked' : '' ?>>
                            <label class="form-check-label"><?= $brand['name'] ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold">Kích thước</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <?php $sizes = ['S', 'M', 'L', 'XL', 'FreeSize']; ?>
                        <?php foreach($sizes as $s): ?>
                            <div>
                                <input type="checkbox" class="btn-check" name="size[]" id="size_<?= $s ?>" value="<?= $s ?>"
                                    <?= (in_array($s, $data['filters']['sizes'])) ? 'checked' : '' ?>>
                                <label class="btn btn-outline-secondary btn-sm" for="size_<?= $s ?>"><?= $s ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold">Khoảng giá</h6>
                    <div class="d-flex align-items-center gap-2">
                        <input type="number" name="min" class="form-control form-control-sm" placeholder="Min" value="<?= $data['filters']['price_min'] ?>">
                        <span>-</span>
                        <input type="number" name="max" class="form-control form-control-sm" placeholder="Max" value="<?= $data['filters']['price_max'] ?>">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-2">Áp dụng lọc</button>
                <a href="<?= BASE_URL ?>product" class="btn btn-outline-secondary w-100 mt-2 btn-sm">Xóa bộ lọc</a>
            </form>
        </div>

        <div class="col-md-9">
            <h4 class="mb-3 fw-bold text-dark">
                <?= !empty($data['filters']['keyword']) ? 'Kết quả cho: "' . htmlspecialchars($data['filters']['keyword']) . '"' : 'Tất cả sản phẩm' ?>
                <span class="fs-6 text-muted fw-normal">(<?= count($data['products']) ?> sản phẩm)</span>
            </h4>

            <?php if(empty($data['products'])): ?>
                <div class="alert alert-warning text-center p-5">
                    <h4><i class="fas fa-search"></i> Không tìm thấy sản phẩm nào!</h4>
                    <p>Vui lòng thử thay đổi tiêu chí lọc.</p>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach($data['products'] as $prod): ?>
                        <div class="col-6 col-md-4 mb-4">
                            <div class="card product-card h-100 border-0 shadow-sm">
                                <a href="<?= BASE_URL ?>product/detail/<?= $prod['id'] ?>">
                                    <img src="<?= BASE_URL ?>public/uploads/<?= $prod['image'] ?>" class="card-img-top p-3" style="height: 200px; object-fit: contain;">
                                </a>
                                <div class="card-body text-center d-flex flex-column">
                                    <h6 class="card-title text-truncate">
                                        <a href="<?= BASE_URL ?>product/detail/<?= $prod['id'] ?>" class="text-decoration-none text-dark">
                                            <?= htmlspecialchars($prod['name']) ?>
                                        </a>
                                    </h6>
                                    <div class="mt-auto">
                                        <span class="text-danger fw-bold fs-5"><?= number_format($prod['price']) ?>đ</span>
                                    </div>
                                    <a href="<?= BASE_URL ?>product/detail/<?= $prod['id'] ?>" class="btn btn-sm btn-primary mt-2">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>