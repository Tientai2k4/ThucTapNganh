<style>
    #sidebar-staff {
        min-width: 260px; background: #ffffff; height: 100vh; position: sticky;
        top: 0; border-right: 1px solid #dee2e6; display: flex; flex-direction: column;
    }
    #sidebar-staff .sidebar-header { background: #f0fdf4; border-bottom: 1px solid #dcfce7; padding: 20px; }
    #sidebar-staff .nav-link {
        padding: 12px 20px; color: #495057; font-weight: 500; display: flex; align-items: center; border-radius: 8px; margin: 2px 10px; text-decoration: none;
    }
    #sidebar-staff .nav-link:hover { background: #e8f5e9; color: #2e7d32; }
    #sidebar-staff .nav-link i { width: 25px; color: #2e7d32; }
</style>

<nav id="sidebar-staff">
    <div class="sidebar-header text-center">
        <h4 class="fw-bold text-success mb-1">STAFF PANEL</h4>
        <span class="badge bg-success">Nhân viên</span>
    </div>

    <div class="menu-list py-3 overflow-auto flex-grow-1">
        <a href="<?= BASE_URL ?>staff/dashboard" class="nav-link">
            <i class="fas fa-chart-line"></i> Tổng quan
        </a>
        <a href="<?= BASE_URL ?>staff/order" class="nav-link">
            <i class="fas fa-shopping-cart"></i> Quản lý đơn hàng
        </a>
        <a href="<?= BASE_URL ?>staff/contact" class="nav-link">
            <i class="fas fa-envelope-open-text"></i> Hộp thư liên hệ
        </a>
        <a href="<?= BASE_URL ?>staff/review" class="nav-link">
            <i class="fas fa-comment-dots"></i> Duyệt đánh giá
        </a>
        <a href="<?= BASE_URL ?>staff/post" class="nav-link">
            <i class="fas fa-pen-fancy"></i> Viết bài Blog
        </a>
    </div>

    <div class="sidebar-footer p-3 border-top bg-light">
        <a href="<?= BASE_URL ?>client/auth/logout" class="nav-link p-2 text-danger"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
    </div>
</nav>