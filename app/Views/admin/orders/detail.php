<?php $prefix = $data['role_prefix'] ?? 'admin'; ?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-primary fw-bold">Đơn hàng #<?= $data['order']['order_code'] ?></h3>
            
            <div>
                <a href="<?= BASE_URL . $prefix ?>/order/print/<?= $data['order']['order_code'] ?>" 
                target="_blank" class="btn btn-warning me-2">
                    <i class="fas fa-print me-2"></i>In hóa đơn
                </a>

                <a href="<?= BASE_URL . $prefix ?>/order" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Thông tin khách hàng</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Họ tên:</strong> <?= $data['order']['customer_name'] ?></p>
                    <p class="mb-1"><strong>SĐT:</strong> <?= $data['order']['customer_phone'] ?></p>
                    <p class="mb-1"><strong>Email:</strong> <?= $data['order']['customer_email'] ?></p>
                    <p class="mb-0"><strong>Địa chỉ:</strong> <?= $data['order']['shipping_address'] ?></p>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">Xử lý đơn hàng</h5>
                    
              <form action="<?= BASE_URL . $prefix ?>/order/updateStatus" method="POST">
    <input type="hidden" name="order_id" value="<?= $data['order']['id'] ?>">
    <input type="hidden" name="order_code" value="<?= $data['order']['order_code'] ?>">

    <div class="mb-3">
        <label class="form-label fw-bold">Trạng thái hiện tại:</label>
        <?php 
            $currentStatus = $data['order']['status'];
            $statusVN = '';
            $alertClass = '';
            $icon = '';

            switch($currentStatus) {
                case 'pending_payment': 
                $statusVN = 'Chờ thanh toán (Online)'; 
                $alertClass = 'warning text-dark'; 
                $icon = 'credit-card'; 
                break;
            case 'pending': 
                $statusVN = 'Đang chờ xử lý (COD)'; 
                $alertClass = 'secondary text-white'; 
                $icon = 'clock'; 
                break;
                    }
        ?>
        <div class="alert alert-<?= $alertClass ?> text-center fw-bold mb-3 shadow-sm">
            <i class="fas fa-<?= $icon ?> me-2"></i><?= mb_strtoupper($statusVN, 'UTF-8') ?>
        </div>
    </div>

    <?php if ($currentStatus == 'completed' || $currentStatus == 'cancelled'): ?>
        <div class="mb-3">
            <label class="form-label small fw-bold text-muted">Mã vận đơn</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($data['order']['tracking_code'] ?? 'Không có') ?>" disabled>
        </div>
        <div class="d-grid">
            <button type="button" class="btn btn-secondary disabled">
                <i class="fas fa-lock me-2"></i>Đơn hàng đã kết thúc
            </button>
        </div>

    <?php else: ?>
        <div class="mb-3">
            <label class="form-label fw-bold">Cập nhật Mã vận đơn:</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="fas fa-barcode"></i></span>
                <input type="text" name="tracking_code" class="form-control" 
                       placeholder="VD: GHN12345" 
                       value="<?= htmlspecialchars($data['order']['tracking_code'] ?? '') ?>">
            </div>
        </div>

        <div class="mb-3">
    <label class="form-label fw-bold">Chuyển trạng thái tiếp theo:</label>
    <select name="status" class="form-select border-primary fw-bold shadow-sm" style="background-color: #f8f9fa;">
        <option value="<?= $currentStatus ?>"> -- Giữ nguyên trạng thái -- </option>
        
        <?php if ($currentStatus == 'pending_payment'): ?>
            <option value="pending">➡️ Đã nhận tiền (Xác nhận thủ công)</option>
        <?php endif; ?>

        <?php if ($currentStatus == 'pending'): ?>
            <option value="processing">➡️ Chuyển sang: Đang chuẩn bị hàng</option>
        <?php endif; ?>

        <?php if ($currentStatus == 'processing'): ?>
            <option value="shipping">➡️ Chuyển sang: Đang giao hàng</option> 
        <?php endif; ?>

        <?php if ($currentStatus == 'shipping'): ?> 
            <option value="completed" class="fw-bold text-success">✅ Khách đã nhận hàng (Hoàn thành)</option>
        <?php endif; ?>

        <?php if ($currentStatus != 'completed' && $currentStatus != 'cancelled'): ?>
            <option value="cancelled" class="text-danger">❌ HỦY ĐƠN HÀNG (Hoàn lại kho)</option>
        <?php endif; ?>
    </select>
</div>

        <div class="d-grid">
            <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm">
                <i class="fas fa-save me-2"></i>Lưu cập nhật
            </button>
        </div>
    <?php endif; ?>
</form>

                    <?php if (!empty($data['order']['tracking_code'])): ?>
                        <hr>
                        <div class="d-grid">
                            <a href="https://ghn.vn/blogs/trang-thai-don-hang?order_code=<?= $data['order']['tracking_code'] ?>" 
                               target="_blank" class="btn btn-outline-primary">
                               <i class="fas fa-search me-2"></i>Bấm để tra cứu hành trình (GHN)
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">Chi tiết sản phẩm</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Phân loại</th>
                                <th>Đơn giá</th>
                                <th>SL</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['details'] as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['product_name']) ?></td>
                                <td><span class="badge bg-secondary"><?= $item['size'] ?> - <?= $item['color'] ?></span></td>
                                <td><?= number_format($item['price']) ?>đ</td>
                                <td>x<?= $item['quantity'] ?></td>
                                <td class="text-end fw-bold"><?= number_format($item['total_price']) ?>đ</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="border-top">
                            <tr>
                                <td colspan="4" class="text-end">Phí vận chuyển:</td>
                                <td class="text-end"><?= number_format($data['order']['shipping_fee']) ?>đ</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end">Giảm giá:</td>
                                <td class="text-end">-<?= number_format($data['order']['discount_amount']) ?>đ</td>
                            </tr>
                            <tr class="bg-light">
                                <td colspan="4" class="text-end fw-bold text-danger fs-5">TỔNG THANH TOÁN:</td>
                                <td class="text-end fw-bold text-danger fs-5"><?= number_format($data['order']['total_money']) ?>đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('check-tracking-btn');
    const resultsDiv = document.getElementById('tracking-log-results');

    if (btn) {
        btn.addEventListener('click', function() {
            // 1. Lấy thông tin từ nút bấm
            const orderCode = this.getAttribute('data-order-code');
            const apiUrl = this.getAttribute('data-url');
            
            // 2. Hiển thị trạng thái "Đang tải"
            resultsDiv.style.display = 'block';
            resultsDiv.innerHTML = '<div class="text-center p-3"><i class="fas fa-spinner fa-spin"></i> Đang kết nối GHN...</div>';
            btn.disabled = true;

            // 3. Gọi AJAX lên Server (TrackingController)
            // Lưu ý: apiUrl đã là BASE_URL . 'admin/tracking/getOrderStatus'
            fetch(`${apiUrl}?order_code=${orderCode}`)
                .then(response => response.json())
                .then(data => {
                    btn.disabled = false;
                    
                    if (data.success) {
                        // 4. Vẽ HTML lịch sử hành trình
                        let html = `<div class="alert alert-success mb-2"><strong>Trạng thái hiện tại:</strong> ${data.status}</div>`;
                        html += '<ul class="list-group list-group-flush small">';
                        
                        if (data.log && data.log.length > 0) {
                            data.log.forEach(log => {
                                // Format ngày giờ
                                const date = new Date(log.updated_date);
                                const dateStr = date.toLocaleString('vi-VN');
                                html += `<li class="list-group-item">
                                            <span class="fw-bold">${dateStr}</span>: ${log.status}
                                         </li>`;
                            });
                        } else {
                            html += '<li class="list-group-item">Chưa có lịch sử di chuyển.</li>';
                        }
                        html += '</ul>';
                        resultsDiv.innerHTML = html;
                    } else {
                        resultsDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                    }
                })
                .catch(err => {
                    console.error(err);
                    btn.disabled = false;
                    resultsDiv.innerHTML = '<div class="alert alert-danger">Lỗi kết nối Server! Hãy kiểm tra Console (F12).</div>';
                });
        });
    }
});
</script>