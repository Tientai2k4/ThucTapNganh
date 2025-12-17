<style>
    /* CSS Sidebar giữ nguyên như cũ để đồng bộ giao diện */
    #sidebar {
        min-width: 260px; max-width: 260px; background: #ffffff; color: #333;
        box-shadow: 2px 0 10px rgba(0,0,0,0.05); height: 100vh; position: sticky;
        top: 0; border-right: 1px solid #e9ecef; display: flex; flex-direction: column;
    }
    #sidebar .sidebar-header { background: #f8f9fa; border-bottom: 1px solid #dee2e6; }
    #sidebar .menu-list { overflow-y: auto; flex-grow: 1; padding: 15px 10px; }
    #sidebar .menu-list::-webkit-scrollbar { width: 5px; }
    #sidebar .menu-list::-webkit-scrollbar-thumb { background: #ccc; border-radius: 5px; }
    #sidebar ul li a {
        padding: 12px 15px; font-size: 0.95rem; display: flex; align-items: center;
        text-decoration: none; color: #495057; border-radius: 8px; transition: all 0.2s;
        font-weight: 500; margin-bottom: 5px;
    }
    #sidebar ul li a:hover { background: #e7f1ff; color: #0d6efd; transform: translateX(3px); }
    #sidebar ul.collapse { background: #fafafa; border-radius: 8px; margin-top: 5px; padding-left: 10px !important; }
    #sidebar ul.collapse li a { font-size: 0.9rem; padding: 8px 15px; color: #6c757d; }
    .dropdown-toggle::after { margin-left: auto; transition: transform 0.2s; }
    .dropdown-toggle[aria-expanded="true"]::after { transform: rotate(180deg); }
    .icon-box { width: 30px; text-align: center; }
    .sidebar-footer { padding: 15px; border-top: 1px solid #e9ecef; background: #fff; }
</style>

<nav id="sidebar">
    <div class="sidebar-header p-3 d-flex align-items-center justify-content-center">
        <h3 class="fw-bold text-primary m-0"><i class="fas fa-user-shield me-2"></i>Admin Panel</h3>
    </div>

    <ul class="list-unstyled components menu-list">
        <li>
            <a href="<?= BASE_URL ?>admin/dashboard">
                <span class="icon-box"><i class="fas fa-home text-primary"></i></span> Tổng quan
            </a>
        </li>

        <li>
            <a href="#productSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <span class="icon-box"><i class="fas fa-box text-success"></i></span> Sản phẩm & Kho
            </a>
            <ul class="collapse list-unstyled" id="productSubmenu">
                <li><a href="<?= BASE_URL ?>admin/category"><i class="fas fa-angle-right me-2 small"></i>Danh mục</a></li>
                <li><a href="<?= BASE_URL ?>admin/brand"><i class="fas fa-angle-right me-2 small"></i>Thương hiệu</a></li>
                <li><a href="<?= BASE_URL ?>admin/product"><i class="fas fa-angle-right me-2 small"></i>Tất cả sản phẩm</a></li>
            </ul>
        </li>

        <li>
            <a href="#orderSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <span class="icon-box"><i class="fas fa-shopping-cart text-warning"></i></span> Bán hàng
            </a>
            <ul class="collapse list-unstyled" id="orderSubmenu">
                <li><a href="<?= BASE_URL ?>admin/order"><i class="fas fa-angle-right me-2 small"></i>Đơn hàng</a></li>
                <li><a href="<?= BASE_URL ?>admin/coupon"><i class="fas fa-angle-right me-2 small"></i>Mã giảm giá</a></li>
                <li><a href="<?= BASE_URL ?>admin/review"><i class="fas fa-angle-right me-2 small"></i>Đánh giá</a></li>
            </ul>
        </li>

        <li>
            <a href="#contentSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <span class="icon-box"><i class="fas fa-newspaper text-info"></i></span> Nội dung Web
            </a>
            <ul class="collapse list-unstyled" id="contentSubmenu">
                <li><a href="<?= BASE_URL ?>admin/post"><i class="fas fa-angle-right me-2 small"></i>Tin tức / Bài viết</a></li>
                <li><a href="<?= BASE_URL ?>admin/slider"><i class="fas fa-angle-right me-2 small"></i>Slider / Banner</a></li>
                <li><a href="<?= BASE_URL ?>admin/contact"><i class="fas fa-angle-right me-2 small"></i>Liên hệ</a></li>
            </ul>
        </li>

        <li>
            <a href="<?= BASE_URL ?>admin/user">
                <span class="icon-box"><i class="fas fa-users text-secondary"></i></span> Quản lý Tài khoản
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <ul class="list-unstyled mb-0">
            <li>
                <a href="<?= BASE_URL ?>" class="text-dark" target="_blank">
                    <span class="icon-box"><i class="fas fa-globe text-muted"></i></span> Xem Website
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>client/auth/logout" class="text-danger fw-bold">
                    <span class="icon-box"><i class="fas fa-sign-out-alt"></i></span> Đăng xuất
                </a>
            </li>
        </ul>
    </div>
</nav>

<div id="content" class="flex-grow-1 p-4 bg-light">