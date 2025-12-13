<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Chi tiết đơn hàng: #<?= $data['order']['order_code'] ?></h3>
    <a href="<?= BASE_URL ?>admin/order" class="btn btn-secondary">Quay lại</a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card p-3 mb-3">
            <h5>Thông tin khách hàng</h5>
            <p><strong>Họ tên:</strong> <?= $data['order']['customer_name'] ?></p>
            <p><strong>SĐT:</strong> <?= $data['order']['customer_phone'] ?></p>
            <p><strong>Địa chỉ:</strong> <?= $data['order']['shipping_address'] ?></p>
            <p><strong>Email:</strong> <?= $data['order']['customer_email'] ?></p>
        </div>
        
        <div class="card p-3 bg-light">
            <h5>Cập nhật trạng thái</h5>
            <form action="<?= BASE_URL ?>admin/order/updateStatus" method="POST">
                <input type="hidden" name="order_id" value="<?= $data['order']['id'] ?>">
                <input type="hidden" name="order_code" value="<?= $data['order']['order_code'] ?>">
                <select name="status" class="form-select mb-2">
                    <option value="pending" <?= $data['order']['status']=='pending'?'selected':'' ?>>Chờ xử lý</option>
                    <option value="processing" <?= $data['order']['status']=='processing'?'selected':'' ?>>Đang chuẩn bị</option>
                    <option value="shipping" <?= $data['order']['status']=='shipping'?'selected':'' ?>>Đang giao hàng</option>
                    <option value="completed" <?= $data['order']['status']=='completed'?'selected':'' ?>>Hoàn thành</option>
                    <option value="cancelled" <?= $data['order']['status']=='cancelled'?'selected':'' ?>>Hủy đơn</option>
                </select>
                <button type="submit" class="btn btn-success w-100">Cập nhật</button>
            </form>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card p-3">
            <h5>Danh sách sản phẩm</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Phân loại</th>
                        <th>Giá</th>
                        <th>SL</th>
                        <th>Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['details'] as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td><?= $item['size'] ?> / <?= $item['color'] ?></td>
                        <td><?= number_format($item['price']) ?>đ</td>
                        <td>x<?= $item['quantity'] ?></td>
                        <td><?= number_format($item['total_price']) ?>đ</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Phí vận chuyển:</th>
                        <th><?= number_format($data['order']['shipping_fee']) ?>đ</th>
                    </tr>
                    <tr>
                        <th colspan="4" class="text-end">Giảm giá:</th>
                        <th>-<?= number_format($data['order']['discount_amount']) ?>đ</th>
                    </tr>
                    <tr>
                        <th colspan="4" class="text-end text-danger h5">TỔNG CỘNG:</th>
                        <th class="text-danger h5"><?= number_format($data['order']['total_money']) ?>đ</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>