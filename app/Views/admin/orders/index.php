<h3>Quản lý Đơn Hàng</h3>
<table class="table table-bordered table-hover bg-white">
    <thead class="table-dark">
        <tr>
            <th>Mã Đơn</th>
            <th>Khách hàng</th>
            <th>Tổng tiền</th>
            <th>Thanh toán</th>
            <th>Trạng thái</th>
            <th>Ngày đặt</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($data['orders'] as $order): ?>
        <tr>
            <td><strong><?= $order['order_code'] ?></strong></td>
            <td>
                <?= htmlspecialchars($order['customer_name']) ?><br>
                <small><?= $order['customer_phone'] ?></small>
            </td>
            <td class="text-danger fw-bold"><?= number_format($order['total_money']) ?>đ</td>
            <td>
                <span class="badge bg-<?= $order['payment_method'] == 'COD' ? 'secondary' : 'info' ?>">
                    <?= $order['payment_method'] ?>
                </span>
                <?php if($order['payment_status'] == 1): ?>
                    <span class="badge bg-success">Đã TT</span>
                <?php endif; ?>
            </td>
            <td>
                <?php 
                    $colors = ['pending'=>'warning', 'shipping'=>'primary', 'completed'=>'success', 'cancelled'=>'danger'];
                    $stt = $order['status'];
                ?>
                <span class="badge bg-<?= $colors[$stt] ?? 'secondary' ?>"><?= strtoupper($stt) ?></span>
            </td>
            <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
            <td>
                <a href="<?= BASE_URL ?>admin/order/detail/<?= $order['order_code'] ?>" class="btn btn-sm btn-primary">Xem</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>