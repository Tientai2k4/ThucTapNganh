<?php $prefix = $data['role_prefix'] ?? 'admin'; ?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary fw-bold">Đơn hàng #<?= $data['order']['order_code'] ?></h3>
        
        <a href="<?= BASE_URL . $prefix ?>/order" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
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
                            <label class="form-label fw-bold">Mã vận đơn:</label>
                            <input type="text" name="tracking_code" 
                                   class="form-control" 
                                   placeholder="VD: LKV12345"
                                   value="<?= $data['order']['tracking_code'] ?>">
                           
                        </div>

                        <div class="mb-3">
    <label class="form-label fw-bold">Trạng thái đơn hàng:</label>
    
    <?php 
        $currentStatus = $data['order']['status'];
        
        // Logic: Chỉ khóa hẳn (disabled) nếu đơn đã bị HỦY.
        // Nếu đã HOÀN THÀNH, vẫn cho mở để chọn Hủy (nhưng không được quay lại bước trước).
        $selectDisabled = ($currentStatus == 'cancelled') ? 'disabled' : '';
    ?>

    <?php if ($currentStatus == 'cancelled'): ?>
        <input type="hidden" name="status" value="<?= $currentStatus ?>">
    <?php endif; ?>

    <select name="status" class="form-select" <?= $selectDisabled ?>>
        
        <option value="pending" <?= $currentStatus=='pending'?'selected':'' ?> 
                <?= ($currentStatus != 'pending') ? 'disabled class="bg-light text-muted"' : '' ?>>
            Chờ xử lý
        </option>

        <option value="processing" <?= $currentStatus=='processing'?'selected':'' ?>
                <?= ($currentStatus == 'shipping' || $currentStatus == 'completed' || $currentStatus == 'cancelled') ? 'disabled class="bg-light text-muted"' : '' ?>>
            Đang chuẩn bị hàng
        </option>

        <option value="shipping" <?= $currentStatus=='shipping'?'selected':'' ?>
                <?= ($currentStatus == 'completed' || $currentStatus == 'cancelled') ? 'disabled class="bg-light text-muted"' : '' ?>>
            Đang giao hàng
        </option>
        
        <?php if($prefix == 'admin'): ?>
            <option value="completed" <?= $currentStatus=='completed'?'selected':'' ?>
                    <?= ($currentStatus == 'pending' || $currentStatus == 'processing') ? 'disabled class="bg-light text-muted"' : '' ?>>
                Hoàn thành
            </option>
            
            <option value="cancelled" <?= $currentStatus=='cancelled'?'selected':'' ?>>
                Hủy đơn (Admin)
            </option>
        <?php else: ?>
            <option value="completed" <?= $currentStatus=='completed'?'selected':'' ?>
                    <?= ($currentStatus == 'pending' || $currentStatus == 'processing') ? 'disabled class="bg-light text-muted"' : '' ?>>
                Hoàn thành
            </option>
        <?php endif; ?>
    </select>
    
    <?php if ($currentStatus == 'completed'): ?>
        <small class="text-success fw-bold mt-1 d-block"><i class="fas fa-check-circle"></i> Đơn hàng đã hoàn tất.</small>
    <?php elseif ($currentStatus == 'cancelled'): ?>
        <small class="text-danger fw-bold mt-1 d-block"><i class="fas fa-times-circle"></i> Đơn hàng đã bị hủy.</small>
    <?php endif; ?>
</div>

<?php if ($currentStatus != 'cancelled'): ?>
    <button type="submit" class="btn btn-success w-100 fw-bold">
        <i class="fas fa-save me-2"></i>Cập nhật Đơn hàng
    </button>
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