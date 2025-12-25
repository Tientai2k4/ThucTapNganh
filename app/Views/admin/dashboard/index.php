<div class="container-fluid py-4" style="background-color: #f8f9fa;">
    <div class="mb-4">
        <h2 class="text-primary fw-bold text-uppercase"><i class="fas fa-chart-line me-2"></i>Bảng Điều Khiển</h2>
        <p class="text-muted">Tổng quan tình hình kinh doanh hôm nay</p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <a href="/ThucTapNganh/admin/dashboard/view_revenue" class="text-decoration-none">
                <div class="card border-0 shadow-sm text-white p-3 h-100 bg-primary-gradient">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small opacity-75 fw-bold text-uppercase">Doanh Thu</div>
                            <div class="h3 fw-bold mb-0"><?= number_format($data['counters']['total_revenue']) ?>đ</div>
                        </div>
                        <i class="fas fa-wallet fa-2x opacity-50"></i>
                    </div>
                    <div class="mt-3 small">Xem chi tiết <i class="fas fa-chevron-right ms-1"></i></div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6">
            <a href="/ThucTapNganh/admin/order" class="text-decoration-none">
                <div class="card border-0 shadow-sm text-white p-3 h-100 bg-success-gradient">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small opacity-75 fw-bold text-uppercase">Đơn Hàng</div>
                            <div class="h3 fw-bold mb-0"><?= $data['counters']['total_orders'] ?></div>
                        </div>
                        <i class="fas fa-shopping-cart fa-2x opacity-50"></i>
                    </div>
                    <div class="mt-3 small">Quản lý <i class="fas fa-chevron-right ms-1"></i></div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6">
            <a href="/ThucTapNganh/admin/user" class="text-decoration-none">
                <div class="card border-0 shadow-sm text-white p-3 h-100 bg-info-gradient">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small opacity-75 fw-bold text-uppercase">Thành Viên</div>
                            <div class="h3 fw-bold mb-0"><?= $data['counters']['total_users'] ?></div>
                        </div>
                        <i class="fas fa-user-friends fa-2x opacity-50"></i>
                    </div>
                    <div class="mt-3 small">Xem danh sách <i class="fas fa-chevron-right ms-1"></i></div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6">
            <a href="/ThucTapNganh/admin/contact" class="text-decoration-none">
                <div class="card border-0 shadow-sm text-white p-3 h-100 bg-warning-gradient">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small opacity-75 fw-bold text-uppercase">Liên Hệ Mới</div>
                            <div class="h3 fw-bold mb-0"><?= $data['counters']['unread_contacts'] ?></div>
                        </div>
                        <i class="fas fa-comment-dots fa-2x opacity-50"></i>
                    </div>
                    <div class="mt-3 small">Xem phản hồi <i class="fas fa-chevron-right ms-1"></i></div>
                </div>
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-success"><i class="fas fa-clock me-2"></i>Đơn Hàng Mới Nhất</h6>
                    <a href="/ThucTapNganh/admin/order" class="btn btn-sm btn-outline-success rounded-pill px-3">Tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-3">Mã Đơn</th>
                                    <th>Khách Hàng</th>
                                    <th>Tổng Tiền</th>
                                    <th>Trạng Thái</th>
                                    <th>Ngày Đặt</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($data['recent_orders'])): ?>
                                    <tr><td colspan="5" class="text-center py-3 text-muted">Chưa có đơn hàng nào.</td></tr>
                                <?php else: ?>
                                    <?php 
                                    // Mảng dịch trạng thái sang tiếng Việt
                                    $statusMap = [
                                        'pending_payment' => 'Chờ thanh toán',
                                        'pending'         => 'Chờ xử lý',
                                        'processing'      => 'Đang chuẩn bị',
                                        'shipping'        => 'Đang giao hàng',
                                        'shipped'         => 'Đang giao hàng',
                                        'completed'       => 'Hoàn thành',
                                        'cancelled'       => 'Đã hủy'
                                    ];
                                    ?>
                                    <?php foreach($data['recent_orders'] as $order): ?>
                                    <tr>
                                        <td class="ps-3">
                                            <a href="/ThucTapNganh/admin/order/detail/<?= $order['order_code'] ?>" 
                                               class="fw-bold text-primary text-decoration-none">
                                                #<?= $order['order_code'] ?>
                                            </a>
                                        </td>
                                        
                                        <td><?= htmlspecialchars($order['customer_name']) ?></td>
                                        <td class="fw-bold text-danger"><?= number_format($order['total_money']) ?>đ</td>
                                        
                                        <td>
                                            <?php 
                                            $statusColor = 'secondary';
                                            if($order['status'] == 'pending') $statusColor = 'warning text-dark';
                                            elseif($order['status'] == 'processing') $statusColor = 'info text-dark';
                                            elseif($order['status'] == 'shipping' || $order['status'] == 'shipped') $statusColor = 'primary';
                                            elseif($order['status'] == 'completed') $statusColor = 'success';
                                            elseif($order['status'] == 'cancelled') $statusColor = 'danger';
                                            elseif($order['status'] == 'pending_payment') $statusColor = 'secondary';
                                            
                                            // Lấy tên tiếng Việt từ mảng map, nếu không có thì lấy tên gốc
                                            $statusText = $statusMap[$order['status']] ?? ucfirst($order['status']);
                                            ?>
                                            <span class="badge bg-<?= $statusColor ?>"><?= $statusText ?></span>
                                        </td>
                                        
                                        <td class="small text-muted"><?= date('d/m H:i', strtotime($order['created_at'])) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-warning"><i class="fas fa-envelope me-2"></i>Phản Hồi Mới</h6>
                    <a href="/ThucTapNganh/admin/contact" class="btn btn-sm btn-outline-warning rounded-pill px-3">Tất cả</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <?php if(empty($data['recent_contacts'])): ?>
                            <li class="list-group-item text-center py-3 text-muted">Không có liên hệ mới.</li>
                        <?php else: ?>
                            <?php foreach($data['recent_contacts'] as $contact): ?>
                            <li class="list-group-item p-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="text-dark"><?= htmlspecialchars($contact['full_name']) ?></strong>
                                    <small class="text-muted"><?= date('d/m', strtotime($contact['created_at'])) ?></small>
                                </div>
                                <div class="text-truncate small text-muted mb-1" style="max-width: 250px;">
                                    <?= htmlspecialchars($contact['message']) ?>
                                </div>
                                <?php if($contact['status'] == 0): ?>
                                    <span class="badge bg-danger" style="font-size: 0.65rem;">Chưa xem</span>
                                <?php else: ?>
                                    <span class="badge bg-success" style="font-size: 0.65rem;">Đã xem</span>
                                <?php endif; ?>
                            </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-danger">Cảnh báo tồn kho (< 10)</h6>
                    <a href="/ThucTapNganh/admin/dashboard/view_low_stock" class="btn btn-sm btn-outline-danger rounded-pill px-3">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr><th class="ps-3">Sản phẩm</th><th>Biến thể</th><th class="text-center">Tồn</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['low_stock'] as $item): ?>
                            <tr>
                                <td class="ps-3 fw-medium text-truncate" style="max-width: 200px;"><?= $item['name'] ?></td>
                                <td><span class="badge bg-light text-dark border"><?= $item['size'] ?>/<?= $item['color'] ?></span></td>
                                <td class="text-center fw-bold text-danger"><?= $item['stock_quantity'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary">Top Khách Hàng VIP</h6>
                    <a href="/ThucTapNganh/admin/dashboard/view_customers" class="btn btn-sm btn-outline-primary rounded-pill px-3">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <?php foreach($data['top_customers'] as $cus): ?>
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold"><?= htmlspecialchars($cus['customer_name']) ?></div>
                            <small class="text-muted"><?= $cus['customer_email'] ?></small>
                        </div>
                        <div class="text-end fw-bold text-success"><?= number_format($cus['total_spent']) ?>đ</div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-primary-gradient { background: linear-gradient(45deg, #4e73df, #224abe); }
.bg-success-gradient { background: linear-gradient(45deg, #1cc88a, #13855c); }
.bg-info-gradient { background: linear-gradient(45deg, #36b9cc, #258391); }
.bg-warning-gradient { background: linear-gradient(45deg, #f6c23e, #dda20a); }
</style>