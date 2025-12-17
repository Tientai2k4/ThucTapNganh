<?php
// Giả định: Danh sách danh mục chính (Category) đã được truyền từ Controller
$categories = $data['categories'] ?? [
    ['name' => 'Kính Bơi', 'id' => 1],
    ['name' => 'Mũ Bơi', 'id' => 2],
    ['name' => 'Quần Áo Bơi', 'id' => 3]
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Swimming Store - Chuyên đồ bơi chính hãng' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/client.css">
</head>
<body>
    <div class="bg-primary text-white py-1 small">
        <div class="container d-flex justify-content-between align-items-center">
            <span><i class="fas fa-phone-alt me-1"></i> Hotline: 090.123.4567 (8h-21h)</span>
            <div>
                <a href="#" class="text-white me-3"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>

    <header class="main-header border-bottom shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-light bg-white py-2">
            <div class="container">
                <a class="navbar-brand fw-bold text-primary fs-3" href="<?= BASE_URL ?>">
                    <i class="fas fa-swimmer"></i> SWIM STORE
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-semibold">
                        <li class="nav-item"><a class="nav-link px-3" href="<?= BASE_URL ?>">Trang chủ</a></li>
                        <li class="nav-item"><a class="nav-link px-3" href="<?= BASE_URL ?>product">Sản phẩm</a></li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle px-3" href="#" data-bs-toggle="dropdown">Danh mục</a>
                            <div class="dropdown-menu shadow-sm border-0 mt-2 rounded-3">
                                <?php foreach ($categories as $cat): ?>
                                    <a class="dropdown-item py-2" href="<?= BASE_URL ?>product/category/<?= $cat['id'] ?>">
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </a>
                                <?php endforeach; ?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item py-2 text-primary" href="<?= BASE_URL ?>product">Xem tất cả</a>
                            </div>
                        </li>
                        
                        <li class="nav-item"><a class="nav-link px-3" href="<?= BASE_URL ?>blog">Blog</a></li>
                        <li class="nav-item"><a class="nav-link px-3" href="<?= BASE_URL ?>contact">Liên hệ</a></li>
                    </ul>

                    <form action="<?= BASE_URL ?>product" method="GET" class="d-flex me-3 position-relative" style="min-width: 250px;">
                        <div class="input-group">
                            <input type="search" id="searchInput" name="keyword" class="form-control border-end-0 rounded-start-pill ps-3" placeholder="Tìm kiếm..." required autocomplete="off">
                            <button type="submit" class="btn btn-outline-secondary border-start-0 rounded-end-pill pe-3">
                                <i class="fas fa-search text-muted"></i>
                            </button>
                        </div>
                        <div id="searchResults" class="position-absolute bg-white shadow rounded-3 w-100 mt-1 border" style="top: 100%; z-index: 1050; display: none; max-height: 300px; overflow-y: auto;">
                        </div>
                    </form>

                    <div class="d-flex align-items-center gap-3">
                        <a href="<?= BASE_URL ?>cart" class="btn position-relative text-dark border-0 p-0 me-2">
                            <i class="fas fa-shopping-bag fa-xl"></i>
                            <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white">
                                <?= getCartQuantity() ?>
                            </span>
                        </a>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <div class="dropdown">
                                <button class="btn btn-light rounded-pill dropdown-toggle d-flex align-items-center px-3 py-1 border" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php if(!empty($_SESSION['user_avatar'])): ?>
                                        <img src="<?= $_SESSION['user_avatar'] ?>" class="rounded-circle me-2 object-fit-cover" width="28" height="28">
                                    <?php else: ?>
                                        <div class="bg-secondary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                            <i class="fas fa-user small"></i>
                                        </div>
                                    <?php endif; ?>
                                    <span class="small fw-bold text-truncate" style="max-width: 100px;"><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                                </button>
                                
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3" style="min-width: 200px;">
                                    <li class="px-3 py-2 border-bottom mb-2 bg-light rounded-top-3">
                                        <small class="text-muted d-block">Xin chào,</small>
                                        <strong class="text-primary"><?= htmlspecialchars($_SESSION['user_name']) ?></strong>
                                    </li>

                                    <?php if($_SESSION['user_role'] == 'admin'): ?>
                                        <li>
                                            <a class="dropdown-item py-2 text-danger fw-bold" href="<?= BASE_URL ?>admin/dashboard">
                                                <i class="fas fa-cogs me-2 w-25 text-center"></i>Admin Panel
                                            </a>
                                        </li>
                                    <?php elseif($_SESSION['user_role'] == 'staff'): ?>
                                        <li>
                                            <a class="dropdown-item py-2 text-success fw-bold" href="<?= BASE_URL ?>staff/dashboard">
                                                <i class="fas fa-user-tie me-2 w-25 text-center"></i>Staff Panel
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <li>
                                        <a class="dropdown-item py-2" href="<?= BASE_URL ?>user/profile">
                                            <i class="fas fa-id-card me-2 w-25 text-center text-secondary"></i>Hồ sơ của tôi
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="<?= BASE_URL ?>user/history">
                                            <i class="fas fa-box-open me-2 w-25 text-center text-secondary"></i>Đơn mua
                                        </a>
                                    </li>
                                    
                                    <li><hr class="dropdown-divider my-2"></li>
                                    
                                    <li>
                                        <a class="dropdown-item py-2 text-danger" href="<?= BASE_URL ?>client/auth/logout">
                                            <i class="fas fa-sign-out-alt me-2 w-25 text-center"></i>Đăng xuất
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <div class="d-flex gap-2">
                                <a href="<?= BASE_URL ?>client/auth/login" class="btn btn-outline-primary btn-sm rounded-pill px-3">Đăng nhập</a>
                                <a href="<?= BASE_URL ?>client/auth/register" class="btn btn-primary btn-sm rounded-pill px-3">Đăng ký</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="py-4 bg-light" style="min-height: 80vh;">
        <div class="container">

<style>
    .search-item {
        display: flex; align-items: center; padding: 10px; border-bottom: 1px solid #f1f1f1;
        text-decoration: none; color: #333; transition: background 0.2s;
    }
    .search-item:hover { background-color: #f8f9fa; color: #0d6efd; }
    .search-item img { width: 40px; height: 40px; object-fit: cover; margin-right: 10px; border-radius: 4px; }
    .search-info h6 { margin: 0; font-size: 14px; font-weight: 600; line-height: 1.2; }
    .search-info span { font-size: 12px; color: #dc3545; font-weight: bold; }
    .search-item:last-child { border-bottom: none; }
    /* Tùy chỉnh thanh cuộn cho đẹp */
    #searchResults::-webkit-scrollbar { width: 6px; }
    #searchResults::-webkit-scrollbar-thumb { background-color: #ccc; border-radius: 3px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    let timeout = null;

    const formatCurrency = (amount) => {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
    };

    searchInput.addEventListener('input', function() {
        const keyword = this.value.trim();
        clearTimeout(timeout);

        if (keyword.length < 2) {
            searchResults.style.display = 'none';
            searchResults.innerHTML = '';
            return;
        }

        timeout = setTimeout(() => {
            fetch(`<?= BASE_URL ?>product/liveSearch?keyword=${encodeURIComponent(keyword)}`)
                .then(response => response.json())
                .then(res => {
                    let html = '';
                    if (res.success && res.data.length > 0) {
                        res.data.forEach(prod => {
                            let priceDisplay = (prod.sale_price > 0) 
                                ? `${formatCurrency(prod.sale_price)} <del class="text-muted small ms-1">${formatCurrency(prod.price)}</del>`
                                : formatCurrency(prod.price);

                            html += `
                                <a href="<?= BASE_URL ?>product/detail/${prod.id}" class="search-item">
                                    <img src="<?= BASE_URL ?>public/uploads/${prod.image}" alt="${prod.name}">
                                    <div class="search-info">
                                        <h6 class="text-truncate" style="max-width: 200px;">${prod.name}</h6>
                                        <span>${priceDisplay}</span>
                                    </div>
                                </a>`;
                        });
                        html += `<a href="<?= BASE_URL ?>product?keyword=${encodeURIComponent(keyword)}" class="d-block text-center p-2 text-primary small bg-light text-decoration-none border-top fw-bold">Xem tất cả kết quả</a>`;
                    } else {
                        html = '<div class="p-3 text-center text-muted small">Không tìm thấy sản phẩm nào</div>';
                    }
                    searchResults.innerHTML = html;
                    searchResults.style.display = 'block';
                })
                .catch(err => console.error(err));
        }, 300);
    });

    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
});
</script>