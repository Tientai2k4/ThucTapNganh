<style>
    /* CSS y hệt Admin để đồng bộ trải nghiệm */
    #sidebar {
        min-width: 260px; max-width: 260px; background: #ffffff; color: #333;
        box-shadow: 2px 0 10px rgba(0,0,0,0.05); height: 100vh; position: sticky;
        top: 0; border-right: 1px solid #e9ecef; display: flex; flex-direction: column;
    }
    #sidebar .sidebar-header { background: #f0fdf4; border-bottom: 1px solid #dcfce7; } /* Màu nền header hơi xanh lá để phân biệt Staff */
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
    .icon-box { width: 30px; text-align: center; }
    .sidebar-footer { padding: 15px; border-top: 1px solid #e9ecef; background: #fff; }
</style>

<nav id="sidebar">
    <div class="sidebar-header p-3 d-flex align-items-center justify-content-center">
        <h3 class="fw-bold text-success m-0"><i class="fas fa-user-tie me-2"></i>Staff Panel</h3>
    </div>

    <ul class="list-unstyled components menu-list">
        <li>
            <a href="<?= BASE_URL ?>staff/dashboard">
                <span class="icon-box"><i class="fas fa-tachometer-alt text-primary"></i></span> Tổng quan
            </a>
        </li>

        <li>
            <a href="<?= BASE_URL ?>staff/order">
                <span class="icon-box"><i class="fas fa-shopping-cart text-warning"></i></span> Quản lý Đơn hàng
            </a>
        </li>

        <li>
            <a href="#customerMenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <span class="icon-box"><i class="fas fa-headset text-info"></i></span> CSKH & Phản hồi
            </a>
            <ul class="collapse list-unstyled" id="customerMenu">
                <li><a href="<?= BASE_URL ?>staff/contact"><i class="fas fa-angle-right me-2 small"></i>Hộp thư liên hệ</a></li>
                <li><a href="<?= BASE_URL ?>staff/review"><i class="fas fa-angle-right me-2 small"></i>Duyệt đánh giá</a></li>
            </ul>
        </li>

        <li>
            <a href="<?= BASE_URL ?>staff/post">
                <span class="icon-box"><i class="fas fa-pen-nib text-danger"></i></span> Viết bài Blog
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