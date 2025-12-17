<nav id="sidebar">
    <div class="sidebar-header p-3">
        <h3><i class="fas fa-swimmer me-2"></i>Admin</h3>
    </div>

    <ul class="list-unstyled components">
        <li><a href="<?= BASE_URL ?>admin/dashboard" class="nav-link"><i class="fas fa-home me-2"></i> Tổng quan</a></li>
        
        <li><a href="<?= BASE_URL ?>admin/category" class="nav-link"><i class="fas fa-list me-2"></i> Danh mục</a></li>
        <li><a href="<?= BASE_URL ?>admin/brand" class="nav-link"><i class="fas fa-tags me-2"></i> Thương hiệu</a></li>
        <li><a href="<?= BASE_URL ?>admin/product" class="nav-link"><i class="fas fa-box me-2"></i> Sản phẩm</a></li>
        <li><a href="<?= BASE_URL ?>admin/order" class="nav-link"><i class="fas fa-shopping-cart me-2"></i> Đơn hàng</a></li>
        <li><a href="<?= BASE_URL ?>admin/post" class="nav-link"><i class="fas fa-newspaper me-2"></i> Quản lý bài viết</a></li>
        <li><a href="<?= BASE_URL ?>admin/contact" class="nav-link"><i class="fas fa-envelope me-2"></i> Liên hệ khách hàng</a></li>
        <li><a href="<?= BASE_URL ?>admin/slider" class="nav-link"><i class="fas fa-images me-2"></i> Slider / Banner</a></li>
        <li><a href="<?= BASE_URL ?>admin/coupon" class="nav-link"><i class="fas fa-ticket-alt me-2"></i> Mã giảm giá</a></li>
        <li><a href="<?= BASE_URL ?>admin/user" class="nav-link"><i class="fas fa-users me-2"></i> Người dùng</a></li>
        <li><a href="<?= BASE_URL ?>admin/review" class="nav-link"><i class="fas fa-star me-2"></i> Đánh giá</a></li>

        <li><hr class="dropdown-divider"></li>
        <li><a href="<?= BASE_URL ?>client/auth/logout" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
    </ul>
</nav>

<div id="content" class="flex-grow-1">