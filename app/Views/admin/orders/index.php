<?php 
    $prefix = $data['role_prefix'] ?? 'admin'; 
    // Mảng trạng thái để tạo Dropdown lọc
    $statusList = [
        'pending_payment' => 'Chờ thanh toán',
        'pending'         => 'Chờ xử lý',
        'processing'      => 'Đang xử lý',
        'shipped'         => 'Đang giao hàng',
        'completed'       => 'Hoàn thành',
        'cancelled'       => 'Đã hủy'
    ];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="h3 mb-0 text-gray-800">Quản lý Đơn hàng</h3>
</div>

<div class="card shadow-sm border-0 mb-4 bg-light">
    <div class="card-body py-3">
        <form action="" method="GET" class="row g-3">
            
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="keyword" class="form-control border-start-0 ps-0" 
                           placeholder="Nhập mã đơn, tên khách hoặc SĐT..." 
                           value="<?= htmlspecialchars($data['filters']['keyword'] ?? '') ?>">
                </div>
            </div>

            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    <?php foreach($statusList as $key => $label): ?>
                        <option value="<?= $key ?>" 
                            <?= (isset($data['filters']['status']) && $data['filters']['status'] == $key) ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <select name="sort" class="form-select">
                    <option value="newest" <?= ($data['filters']['sort'] == 'newest') ? 'selected' : '' ?>>Mới nhất</option>
                    <option value="oldest" <?= ($data['filters']['sort'] == 'oldest') ? 'selected' : '' ?>>Cũ nhất</option>
                    <option value="total_desc" <?= ($data['filters']['sort'] == 'total_desc') ? 'selected' : '' ?>>Tổng tiền cao nhất</option>
                    <option value="total_asc" <?= ($data['filters']['sort'] == 'total_asc') ? 'selected' : '' ?>>Tổng tiền thấp nhất</option>
                </select>
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="fas fa-filter"></i> Lọc</button>
                <a href="<?= BASE_URL . $prefix ?>/order" class="btn btn-outline-secondary w-50" title="Xóa lọc">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách đơn hàng</h6>
        <span class="badge bg-secondary rounded-pill">Tổng: <?= count($data['orders']) ?> đơn</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="table-light text-uppercase small text-muted">
                    <tr>
                        <th>Mã Đơn</th>
                        <th style="width: 25%;">Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Thanh toán</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['orders'])): ?>
                        <?php foreach($data['orders'] as $order): ?>
                        <tr>
                            <td>
                                <a href="<?= BASE_URL . $prefix ?>/order/detail/<?= $order['order_code'] ?>" class="fw-bold text-primary text-decoration-none">
                                    <?= $order['order_code'] ?>
                                </a>
                            </td>

                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($order['customer_name']) ?></div>
                                <div class="small text-muted">
                                    <i class="fas fa-phone-alt me-1" style="font-size: 0.8em;"></i><?= $order['customer_phone'] ?>
                                </div>
                            </td>

                            <td class="text-danger fw-bold">
                                <?= number_format($order['total_money']) ?> đ
                            </td>

                            <td>
                                <span class="badge bg-<?= $order['payment_method'] == 'COD' ? 'secondary' : 'info' ?>">
                                    <?= $order['payment_method'] ?>
                                </span>
                            </td>

                            <td>
                                <?php 
                                    $colors = [
                                        'pending_payment' => 'warning', // Vàng
                                        'pending'         => 'secondary', // Xám
                                        'processing'      => 'info', // Xanh dương nhạt
                                        'shipped'         => 'primary', // Xanh dương đậm
                                        'completed'       => 'success', // Xanh lá
                                        'cancelled'       => 'danger' // Đỏ
                                    ];
                                    $stt = $order['status'];
                                    $sttLabel = $statusList[$stt] ?? $stt;
                                ?>
                                <span class="badge bg-<?= $colors[$stt] ?? 'secondary' ?>">
                                    <?= $sttLabel ?>
                                </span>
                            </td>

                            <td>
                                <span class="small text-muted">
                                    <?= date('d/m/Y', strtotime($order['created_at'])) ?>
                                </span>
                                <br>
                                <span class="small text-muted fw-bold">
                                    <?= date('H:i', strtotime($order['created_at'])) ?>
                                </span>
                            </td>

                            <td class="text-center">
                                <a href="<?= BASE_URL . $prefix ?>/order/detail/<?= $order['order_code'] ?>" 
                                   class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i> Xem
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-clipboard-list fa-3x mb-3 text-gray-300"></i><br>
                                    <span class="h5">Không tìm thấy đơn hàng nào!</span>
                                    <p class="mb-0 mt-2">Thử thay đổi từ khóa hoặc trạng thái lọc.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>