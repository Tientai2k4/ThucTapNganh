<?php
// =================================================================
// XỬ LÝ LẤY DANH MỤC TỪ DATABASE VÀ PHÂN CẤP CHA - CON
// =================================================================

if (isset($data['categories'])) {
    $categories = $data['categories'];
} else {
    $cateModel = new \App\Models\CategoryModel();
    $categories = $cateModel->getAll();
}

// --- LOGIC MỚI: Sắp xếp danh mục thành cây (Tree) ---
$menuTree = [];
// Bước 1: Lấy tất cả danh mục Cha (Root) trước
foreach ($categories as $cat) {
    if (empty($cat['parent_id'])) {
        $menuTree[$cat['id']] = $cat;
        $menuTree[$cat['id']]['children'] = []; // Tạo mảng rỗng để chứa con
    }
}
// Bước 2: Duyệt lại lần nữa để gán Con vào Cha tương ứng
foreach ($categories as $cat) {
    if (!empty($cat['parent_id']) && isset($menuTree[$cat['parent_id']])) {
        $menuTree[$cat['parent_id']]['children'][] = $cat;
    }
}
// ---------------------------------------------------
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Thế Giới Bơi Lội - Chuyên đồ bơi chính hãng' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/client.css">
    
    <style>
        /* Hiển thị menu con khi hover trên Desktop */
        @media (min-width: 992px) {
            .dropend:hover > .dropdown-menu {
                display: block;
                margin-top: -10px;
                margin-left: 0;
            }
            .dropend .dropdown-toggle::after {
                float: right;
                margin-top: 7px;
            }
        }
        /* Style cho thanh tìm kiếm */
        .search-item {
            display: flex; align-items: center; padding: 10px; border-bottom: 1px solid #f1f1f1;
            text-decoration: none; color: #333; transition: background 0.2s;
        }
        .search-item:hover { background-color: #f8f9fa; color: #0d6efd; }
        .search-item img { width: 40px; height: 40px; object-fit: cover; margin-right: 10px; border-radius: 4px; }
        .search-info h6 { margin: 0; font-size: 14px; font-weight: 600; line-height: 1.2; }
        .search-info span { font-size: 12px; color: #dc3545; font-weight: bold; }
        .search-item:last-child { border-bottom: none; }
        #searchResults::-webkit-scrollbar { width: 6px; }
        #searchResults::-webkit-scrollbar-thumb { background-color: #ccc; border-radius: 3px; }
    </style>
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

   <header class="main-header border-bottom shadow-sm sticky-top bg-white" style="z-index: 1020;">
        <nav class="navbar navbar-expand-lg navbar-light bg-white py-2">
            <div class="container">
                <a class="navbar-brand p-0" href="<?= BASE_URL ?>">
    <img src="<?= BASE_URL ?>public/uploads/logo1.jpg" alt="Thế Giới Bơi Lội" style="height: 50px; width: auto; object-fit: contain;">
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
                           <ul class="dropdown-menu shadow-sm border-0 mt-2 rounded-3">
    <?php if (!empty($menuTree)): ?>
        <?php foreach ($menuTree as $parent): ?>
            
            <?php if (!empty($parent['children'])): ?>
                <li class="dropend">
                    <a class="dropdown-item dropdown-toggle py-2" href="<?= BASE_URL ?>product?category_id=<?= $parent['id'] ?>">
                        <?= htmlspecialchars($parent['name']) ?>
                    </a>
                    <ul class="dropdown-menu shadow-sm border-0">
                        <li>
                          
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        
                        <?php foreach ($parent['children'] as $child): ?>
                            <li>
                                <a class="dropdown-item py-2" href="<?= BASE_URL ?>product?category_id=<?= $child['id'] ?>">
                                    <?= htmlspecialchars($child['name']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php else: ?>
                <li>
                    <a class="dropdown-item py-2" href="<?= BASE_URL ?>product?category_id=<?= $parent['id'] ?>">
                        <?= htmlspecialchars($parent['name']) ?>
                    </a>
                </li>
            <?php endif; ?>

        <?php endforeach; ?>
    <?php endif; ?>
    <li><hr class="dropdown-divider"></li>
    <li><a class="dropdown-item py-2 text-primary" href="<?= BASE_URL ?>product">Xem tất cả sản phẩm</a></li>
</ul>
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
                                
                               <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3" style="min-width: 250px;">
    <li>
        <a href="<?= BASE_URL ?>user/profile" class="dropdown-item px-3 py-2 border-bottom mb-2 bg-light rounded-top-3">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <?php if(!empty($_SESSION['user_avatar'])): ?>
                        <img src="<?= $_SESSION['user_avatar'] ?>" class="rounded-circle object-fit-cover" width="40" height="40">
                    <?php else: ?>
                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fas fa-user small"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div>
                    <small class="text-muted d-block" style="font-size: 11px;">Xin chào,</small>
                    <div class="fw-bold text-primary text-truncate" style="max-width: 140px;">
                        <?= htmlspecialchars($_SESSION['user_name']) ?>
                    </div>
                    <span class="badge bg-info text-dark" style="font-size: 9px;">
                        <?= strtoupper(str_replace('_', ' ', $_SESSION['user_role'])) ?>
                    </span>
                </div>
            </div>
        </a>
    </li>

    <?php if($_SESSION['user_role'] == 'admin'): ?>
        <li>
            <a class="dropdown-item py-2 text-danger fw-bold" href="<?= BASE_URL ?>admin/dashboard">
                <i class="fas fa-user-shield me-2 w-25 text-center"></i>Admin Panel
            </a>
        </li>
    <?php elseif(in_array($_SESSION['user_role'], ['sales_staff', 'content_staff', 'care_staff'])): ?>
        <li>
            <a class="dropdown-item py-2 text-success fw-bold" href="<?= BASE_URL ?>staff/dashboard">
                <i class="fas fa-user-tie me-2 w-25 text-center"></i>Staff Panel
            </a>
        </li>
    <?php endif; ?>

    <li>
        <a class="dropdown-item py-2" href="<?= BASE_URL ?>user/profile">
            <i class="fas fa-id-card me-2 w-25 text-center text-secondary"></i>Hồ sơ cá nhân
        </a>
    </li>
    <li>
        <a class="dropdown-item py-2" href="<?= BASE_URL ?>user/history">
            <i class="fas fa-box-open me-2 w-25 text-center text-secondary"></i>Lịch sử đơn mua
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