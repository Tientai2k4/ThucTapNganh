<h3 class="mb-4">Giỏ hàng của bạn</h3>

<?php if (empty($data['cart'])): ?>
    <div class="alert alert-warning text-center">Giỏ hàng đang trống. <a href="<?= BASE_URL ?>">Mua sắm ngay</a></div>
<?php else: ?>
    <div class="row">
        <div class="col-md-8">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['cart'] as $item): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="<?= BASE_URL ?>uploads/<?= $item['image'] ?>" width="60" class="me-3">
                                <div>
                                    <h6 class="mb-0"><?= htmlspecialchars($item['name']) ?></h6>
                                    <small class="text-muted">Size: <?= $item['size'] ?>, Màu: <?= $item['color'] ?></small>
                                </div>
                            </div>
                        </td>
                        <td><?= number_format($item['price']) ?>đ</td>
                        <td>
                            <form action="<?= BASE_URL ?>cart/update" method="POST" class="d-flex">
                                <input type="hidden" name="variant_id" value="<?= $item['variant_id'] ?>">
                                <input type="number" name="qty" value="<?= $item['qty'] ?>" min="1" class="form-control form-control-sm" style="width: 60px" onchange="this.form.submit()">
                            </form>
                        </td>
                        <td class="fw-bold"><?= number_format($item['subtotal']) ?>đ</td>
                        <td>
                            <a href="<?= BASE_URL ?>cart/delete/<?= $item['variant_id'] ?>" class="text-danger"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <h5>Tổng cộng</h5>
                <h3 class="text-danger"><?= number_format($data['total']) ?>đ</h3>
                <hr>
                <a href="<?= BASE_URL ?>checkout" class="btn btn-primary w-100 btn-lg">TIẾN HÀNH THANH TOÁN</a>
                <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary w-100 mt-2">Tiếp tục mua hàng</a>
            </div>
        </div>
    </div>
<?php endif; ?>