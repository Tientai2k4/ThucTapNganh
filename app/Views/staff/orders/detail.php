<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Chi tiết đơn hàng #<?= $data['order']['order_code'] ?></h3>
    <a href="<?= BASE_URL ?>staff/order" class="btn btn-secondary btn-sm">Quay lại</a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">Sản phẩm trong đơn</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Sản phẩm</th>
                            <th>SL</th>
                            <th class="text-end pe-3">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['details'] as $item): ?>
                        <tr>
                            <td class="ps-3"><?= $item['product_name'] ?> (<?= $item['size'] ?>/<?= $item['color'] ?>)</td>
                            <td>x<?= $item['quantity'] ?></td>
                            <td class="text-end pe-3"><?= number_format($item['total_price']) ?>đ</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-light mb-3">
            <div class="card-body">
                <h6 class="fw-bold">Khách hàng</h6>
                <p class="mb-1"><?= $data['order']['customer_name'] ?></p>
                <p class="small text-muted"><?= $data['order']['customer_phone'] ?></p>
                <hr>
                <h6 class="fw-bold">Trạng thái đơn hàng</h6>
                <form action="<?= BASE_URL ?>staff/order/updateStatus" method="POST">
                    <input type="hidden" name="order_id" value="<?= $data['order']['id'] ?>">
                    <input type="hidden" name="order_code" value="<?= $data['order']['order_code'] ?>">
                    <select name="status" class="form-select mb-3">
                        <option value="pending" <?= $data['order']['status'] == 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                        <option value="shipping" <?= $data['order']['status'] == 'shipping' ? 'selected' : '' ?>>Đang giao hàng</option>
                        <option value="completed" <?= $data['order']['status'] == 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                    </select>
                    <button type="submit" class="btn btn-success w-100">Cập nhật trạng thái</button>
                </form>
            </div>
        </div>
    </div>
</div>