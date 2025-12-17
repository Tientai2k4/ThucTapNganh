<nav id="sidebar" class="bg-dark text-white">
    <div class="sidebar-header p-3 bg-secondary">
        <h3><i class="fas fa-user-tie me-2"></i>Staff Panel</h3>
        <small>Nhân viên: <?= htmlspecialchars($_SESSION['user_name']) ?></small>
    </div>

    <ul class="list-unstyled components">
        <li class="p-2 text-muted small uppercase">Nghiệp vụ bán hàng</li>
        <li><a href="<?= BASE_URL ?>staff/dashboard" class="nav-link text-white"><i class="fas fa-tachometer-alt me-2"></i> Trình làm việc</a></li>
        <li><a href="<?= BASE_URL ?>staff/order" class="nav-link text-white"><i class="fas fa-shopping-cart me-2"></i> Quản lý Đơn hàng</a></li>
        <li><a href="<?= BASE_URL ?>staff/contact" class="nav-link text-white"><i class="fas fa-envelope me-2"></i> Hộp thư Liên hệ</a></li>
        
        <li class="p-2 text-muted small uppercase mt-3">Nội dung & Cộng đồng</li>
        <li><a href="<?= BASE_URL ?>staff/post" class="nav-link text-white"><i class="fas fa-newspaper me-2"></i> Viết bài Blog</a></li>
        <li><a href="<?= BASE_URL ?>staff/review" class="nav-link text-white"><i class="fas fa-star me-2"></i> Duyệt Đánh giá</a></li>
        <li><a href="<?= BASE_URL ?>staff/product" class="nav-link text-white"><i class="fas fa-box me-2"></i> Xem kho sản phẩm</a></li>

        <li><hr class="dropdown-divider"></li>
        <li><a href="<?= BASE_URL ?>client/auth/logout" class="nav-link text-danger fw-bold"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
    </ul>
</nav>
<div id="content" class="flex-grow-1">