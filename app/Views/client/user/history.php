<h3 class="mb-4">Lịch sử đơn hàng của tôi</h3>

<?php if(empty($data['orders'])): ?>
    <p>Bạn chưa có đơn hàng nào. <a href="<?= BASE_URL ?>">Mua sắm ngay</a></p>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Mã đơn</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Chi tiết</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['orders'] as $order): ?>
                <tr>
                    <td>#<?= $order['order_code'] ?></td>
                    <td><?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                    <td><?= number_format($order['total_money']) ?>đ</td>
                    <td>
                        <span class="badge bg-secondary"><?= $order['status'] ?></span>
                    </td>
                    <td>
                        <a href="<?= BASE_URL ?>user/orderDetail/<?= $order['order_code'] ?>" class="btn btn-sm btn-outline-primary">Xem</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>