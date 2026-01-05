<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="<?= BASE_URL ?>staff/order" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
        </a>
        <div class="h4 fw-bold text-primary mb-0">ĐƠN HÀNG #<?= $data['order']['order_code'] ?></div>
        <a href="<?= BASE_URL ?>admin/order/print/<?= $data['order']['order_code'] ?>" target="_blank" class="btn btn-warning btn-sm text-dark fw-bold">
            <i class="fas fa-print me-2"></i>In Hóa Đơn
        </a>
    </div>

    <?php if (isset($_SESSION['alert'])): ?>
        <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show shadow-sm">
            <i class="fas fa-info-circle me-2"></i><?= $_SESSION['alert']['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom fw-bold text-uppercase text-secondary">
                    Thông tin chi tiết
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="small text-muted fw-bold">KHÁCH HÀNG</label>
                            <div class="fw-bold"><?= htmlspecialchars($data['order']['customer_name']) ?></div>
                            <div><?= htmlspecialchars($data['order']['customer_phone']) ?></div>
                            <div><?= htmlspecialchars($data['order']['customer_email']) ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted fw-bold">ĐỊA CHỈ GIAO HÀNG</label>
                            <div><?= nl2br(htmlspecialchars($data['order']['shipping_address'])) ?></div>
                        </div>
                    </div>

                    <div class="table-responsive border rounded">
                        <table class="table table-striped mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">SL</th>
                                    <th class="text-end">Đơn giá</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['details'] as $item): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($item['product_name']) ?></div>
                                        <small class="text-muted">Size: <?= $item['size'] ?> | Màu: <?= $item['color'] ?></small>
                                    </td>
                                    <td class="text-center align-middle">x<?= $item['quantity'] ?></td>
                                    <td class="text-end align-middle"><?= number_format($item['price']) ?>đ</td>
                                    <td class="text-end align-middle fw-bold"><?= number_format($item['total_price']) ?>đ</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="bg-white border-top">
                                <tr>
                                    <td colspan="3" class="text-end">Phí ship:</td>
                                    <td class="text-end"><?= number_format($data['order']['shipping_fee']) ?>đ</td>
                                </tr>
                                <?php if($data['order']['discount_amount'] > 0): ?>
                                <tr>
                                    <td colspan="3" class="text-end text-success">Giảm giá:</td>
                                    <td class="text-end text-success">-<?= number_format($data['order']['discount_amount']) ?>đ</td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold fs-5 text-danger">TỔNG CỘNG:</td>
                                    <td class="text-end fw-bold fs-5 text-danger"><?= number_format($data['order']['total_money']) ?>đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">XỬ LÝ ĐƠN HÀNG</h5>
                    
                    <form action="<?= BASE_URL ?>staff/order/updateStatus" method="POST">
                        <input type="hidden" name="order_id" value="<?= $data['order']['id'] ?>">
                        <input type="hidden" name="order_code" value="<?= $data['order']['order_code'] ?>">

                       <?php 
                            $status = $data['order']['status'];
                            $statusVN = '';
                            $alertClass = '';
                            $icon = '';

                            switch($status) {
                                case 'pending': $statusVN = 'Đang chờ xử lý'; $alertClass='warning text-dark'; $icon='clock'; break;
                                case 'processing': $statusVN = 'Đang chuẩn bị hàng'; $alertClass='info text-white'; $icon='box-open'; break;
                                case 'shipping': $statusVN = 'Đang giao hàng'; $alertClass='primary'; $icon='truck'; break; // Đã sửa thành shipping
                                case 'completed': $statusVN = 'Giao thành công'; $alertClass='success'; $icon='check-circle'; break;
                                case 'cancelled': $statusVN = 'Đã hủy đơn'; $alertClass='danger'; $icon='times-circle'; break;
                                default: $statusVN = $status; $alertClass='secondary';
                            }
                        ?>
                        <div class="alert alert-<?= $alertClass ?> text-center fw-bold mb-3 shadow-sm">
                            <i class="fas fa-<?= $icon ?> me-2"></i><?= mb_strtoupper($statusVN, 'UTF-8') ?>
                        </div>

                        <?php if ($status == 'completed' || $status == 'cancelled'): ?>
                            <div class="d-grid">
                                <button type="button" class="btn btn-secondary disabled">
                                    <i class="fas fa-lock me-2"></i>Đơn hàng đã kết thúc
                                </button>
                            </div>

                        <?php else: ?>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Cập nhật Mã Vận Đơn</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-barcode"></i></span>
                                    <input type="text" name="tracking_code" class="form-control" 
                                           placeholder="VD: GHN123..." 
                                           value="<?= htmlspecialchars($data['order']['tracking_code'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Chuyển trạng thái tiếp theo:</label>
                                <select name="status" class="form-select border-primary fw-bold shadow-sm" style="background-color: #f8f9fa;">
                                    <option value="<?= $status ?>">-- Giữ nguyên trạng thái --</option>
                                    
                                    <?php if ($status == 'pending'): ?>
                                        <option value="processing">➡️ Chuyển sang: Đang chuẩn bị hàng</option>
                                        <option value="cancelled" class="text-danger">❌ Hủy đơn hàng</option>
                                    
                                    <?php elseif ($status == 'processing'): ?>
                                        <option value="shipping">➡️ Chuyển sang: Đang giao hàng</option> <option value="cancelled" class="text-danger">❌ Hủy đơn (Hết hàng)</option>

                                    <?php elseif ($status == 'shipping'): ?> <option value="completed" class="fw-bold text-success">✅ KHÁCH ĐÃ NHẬN HÀNG (Hoàn thành)</option>
                                        <option value="cancelled" class="text-danger">❌ Khách bom hàng / Trả hàng</option>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary fw-bold py-2 shadow-sm">
                                    <i class="fas fa-save me-2"></i>Lưu cập nhật
                                </button>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0 mt-3">
                <div class="card-body">
                    <h6 class="fw-bold border-bottom pb-2">Thanh toán</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Hình thức:</span>
                        <span class="badge bg-light text-dark border"><?= $data['order']['payment_method'] ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Trạng thái:</span>
                        <?php if($data['order']['payment_status'] == 1): ?>
                            <span class="badge bg-success rounded-pill"><i class="fas fa-check me-1"></i>Đã thanh toán</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark rounded-pill">Chưa thanh toán</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>