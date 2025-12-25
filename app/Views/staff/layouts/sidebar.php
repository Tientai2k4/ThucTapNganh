<style>
    #sidebar-staff {
        min-width: 260px;
        max-width: 260px;
        height: 100vh;
        position: sticky;
        top: 0;
        background: #fff;
        border-right: 1px solid #f0f0f0;
        box-shadow: 4px 0 24px rgba(0,0,0,0.02);
        overflow-y: auto;
        z-index: 1000;
        display: flex;
        flex-direction: column;
    }

    #sidebar-staff::-webkit-scrollbar {
        width: 5px;
    }
    #sidebar-staff::-webkit-scrollbar-thumb {
        background-color: #eee;
        border-radius: 4px;
    }

    .sidebar-brand {
        background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: 1px;
    }

    .menu-item {
        border: none !important;
        color: #555;
        font-weight: 500;
        padding: 12px 20px;
        margin: 4px 15px 4px 0;
        border-radius: 0 50px 50px 0; /* Bo tròn cạnh phải */
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        text-decoration: none;
    }

    .menu-item:hover {
        background-color: #f0f7ff;
        color: #0d6efd;
        padding-left: 25px; /* Hiệu ứng trượt nhẹ */
    }

    .menu-item i {
        width: 24px;
        text-align: center;
        transition: transform 0.2s;
    }

    .menu-item:hover i {
        transform: scale(1.1);
    }

    .menu-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: #adb5bd;
        margin-top: 25px;
        margin-bottom: 10px;
        padding-left: 20px;
        font-weight: 700;
    }
</style>

<nav id="sidebar-staff">
    <div class="sidebar-header p-4 text-center mb-2">
        <h4 class="fw-bold sidebar-brand m-0">Thế Giới Bơi Lội</h4>
        <div class="mt-2">
            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill fw-normal shadow-sm">
                <i class="fas fa-user-circle me-1 text-primary"></i>
                <?php 
                    $roles = [
                        'sales_staff'   => 'Kinh Doanh',
                        'content_staff' => 'Nội Dung',
                        'care_staff'    => 'CSKH',
                        'admin'         => 'Quản Trị Viên'
                    ];
                    echo $roles[$_SESSION['user_role']] ?? 'Nhân viên';
                ?>
            </span>
        </div>
    </div>

    <div class="list-group list-group-flush pb-4">
        
        <a href="<?= BASE_URL ?>staff/dashboard" class="menu-item">
            <i class="fas fa-tachometer-alt me-3 text-secondary"></i> Tổng quan
        </a>

        <?php if (in_array($_SESSION['user_role'], ['admin', 'sales_staff'])): ?>
            <div class="menu-label">Bán hàng & Kho</div>
            <a href="<?= BASE_URL ?>staff/order" class="menu-item">
                <i class="fas fa-shopping-cart me-3 text-warning"></i> Đơn hàng
            </a>
            <a href="<?= BASE_URL ?>staff/product" class="menu-item">
                <i class="fas fa-box me-3 text-success"></i> Sản phẩm & Kho
            </a>
        <?php endif; ?>

        <?php if (in_array($_SESSION['user_role'], ['admin', 'content_staff'])): ?>
            <div class="menu-label">Nội dung Website</div>
            <a href="<?= BASE_URL ?>staff/post" class="menu-item">
                <i class="fas fa-newspaper me-3 text-info"></i> Bài viết Blog
            </a>
            <a href="<?= BASE_URL ?>staff/slider" class="menu-item">
                <i class="fas fa-images me-3 text-primary"></i> Quản lý Sliders
            </a>
        <?php endif; ?>

        <?php if (in_array($_SESSION['user_role'], ['admin', 'care_staff'])): ?>
            <div class="menu-label">Khách hàng</div>
            <a href="<?= BASE_URL ?>staff/contact" class="menu-item">
                <i class="fas fa-envelope me-3 text-danger"></i> Hộp thư liên hệ
            </a>
            <a href="<?= BASE_URL ?>staff/review" class="menu-item">
                <i class="fas fa-star me-3 text-warning"></i> Duyệt đánh giá
            </a>
        <?php endif; ?>

        <?php if ($_SESSION['user_role'] == 'admin'): ?>
            <div class="menu-label text-danger">Hệ thống Admin</div>
            <a href="<?= BASE_URL ?>admin/category" class="menu-item text-danger">
                <i class="fas fa-tags me-3"></i> Quản lý Danh mục
            </a>
            <a href="<?= BASE_URL ?>admin/brand" class="menu-item text-danger">
                <i class="fas fa-copyright me-3"></i> Thương hiệu
            </a>
            <a href="<?= BASE_URL ?>admin/coupon" class="menu-item text-danger">
                <i class="fas fa-ticket-alt me-3"></i> Mã giảm giá
            </a>
            <a href="<?= BASE_URL ?>admin/user" class="menu-item text-danger">
                <i class="fas fa-users-cog me-3"></i> Tài khoản
            </a>
        <?php endif; ?>
        
    </div>
</nav>