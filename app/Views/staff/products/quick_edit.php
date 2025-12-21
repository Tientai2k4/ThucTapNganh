<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Cập nhật kho: <?= htmlspecialchars($data['product']['name']) ?></h5>
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>staff/product/updateStock" method="POST">
                        <input type="hidden" name="product_id" value="<?= $data['product']['id'] ?>">

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Giá niêm yết</label>
                                <input type="number" name="price" class="form-control" value="<?= $data['product']['price'] ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Giá khuyến mãi (0 nếu không có)</label>
                                <input type="number" name="sale_price" class="form-control" value="<?= $data['product']['sale_price'] ?>">
                            </div>
                        </div>

                        <h6 class="border-bottom pb-2 mb-3 fw-bold text-success">Quản lý biến thể (Màu / Size)</h6>
                        
                        <?php if (!empty($data['variants'])): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Size</th>
                                            <th>Màu sắc</th>
                                            <th width="150">Số lượng tồn</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($data['variants'] as $v): ?>
                                        <tr>
                                            <td class="align-middle fw-bold"><?= $v['size'] ?></td>
                                            <td class="align-middle">
                                                <span class="badge bg-secondary"><?= $v['color'] ?></span>
                                            </td>
                                            <td>
                                                <input type="number" name="variants[<?= $v['id'] ?>]" class="form-control text-center fw-bold" value="<?= $v['stock_quantity'] ?>" min="0">
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">Sản phẩm này chưa có biến thể nào.</p>
                        <?php endif; ?>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?= BASE_URL ?>staff/product" class="btn btn-secondary">Hủy bỏ</a>
                            <button type="submit" class="btn btn-primary fw-bold px-4">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>