<h3 class="mb-4">Lịch sử đơn hàng của tôi</h3>

<?php 
// 1. Tạo hàm hỗ trợ hiển thị trạng thái tiếng Việt và màu sắc
if (!function_exists('renderStatusBadge')) {
    function renderStatusBadge($status) {
        switch ($status) {
            case 'pending':
                return '<span class="badge bg-secondary">Chờ xử lý</span>';
            case 'processing':
                return '<span class="badge bg-info text-dark">Đang chuẩn bị hàng</span>';
            case 'shipping':
                return '<span class="badge bg-warning text-dark">Đang giao hàng</span>';
            case 'completed':
                return '<span class="badge bg-success">Hoàn thành</span>';
            case 'cancelled':
                return '<span class="badge bg-danger">Đã hủy</span>';
            default:
                return '<span class="badge bg-light text-dark">' . $status . '</span>';
        }
    }
}
?>

<?php if(empty($data['orders'])): ?>
    <div class="alert alert-info text-center">
        <p class="mb-2">Bạn chưa có đơn hàng nào.</p> 
        <a href="<?= BASE_URL ?>" class="btn btn-primary btn-sm">Mua sắm ngay</a>
    </div>
<?php else: ?>
    <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover align-middle mb-0 bg-white">
            <thead class="table-light">
                <tr>
                    <th class="py-3">Mã đơn</th>
                    <th class="py-3">Ngày đặt</th>
                    <th class="py-3">Tổng tiền</th>
                    <th class="py-3">Trạng thái</th>
                    <th class="py-3">Chi tiết</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['orders'] as $order): ?>
                <tr>
                    <td class="fw-bold text-primary">#<?= $order['order_code'] ?></td>
                    
                    <td><?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                    
                    <td class="fw-bold text-danger"><?= number_format($order['total_money']) ?>đ</td>
                    
                    <td>
                        <?= renderStatusBadge($order['status']) ?>
                    </td>
                    
                    <td>
                        <a href="<?= BASE_URL ?>user/orderDetail/<?= $order['order_code'] ?>" 
                           class="btn btn-sm btn-outline-primary">
                           <i class="fas fa-eye me-1"></i> Xem
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>