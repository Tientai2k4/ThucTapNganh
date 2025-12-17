<?php
// Giả định: Danh sách danh mục chính (Category) đã được truyền từ Controller
$categories = $data['categories'] ?? [
    ['name' => 'Kính Bơi', 'id' => 1, 'sub' => [['name'=>'Kính Cận'], ['name'=>'Kính Thi Đấu']]],
    ['name' => 'Mũ Bơi', 'id' => 2, 'sub' => [['name'=>'Mũ Silicone'], ['name'=>'Mũ Vải']]],
    ['name' => 'Quần Áo Bơi', 'id' => 3, 'sub' => [['name'=>'Đồ Nam'], ['name'=>'Đồ Nữ']]]
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

    <header class="main-header">
        <nav class="navbar navbar-expand-lg navbar-light bg-white py-0">
            <div class="container">
                <a class="navbar-brand fw-bold text-primary fs-3" href="<?= BASE_URL ?>">
                    <i class="fas fa-swimmer"></i> SWIM STORE
                    
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>">Trang chủ</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>product">Sản phẩm</a></li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Danh mục</a>
                            <div class="dropdown-menu mega-menu">
                                <div class="container">
                                    <div class="row">
                                        
                                        <div class="col-md-3">
                                            <span class="mega-title">TẤT CẢ DANH MỤC</span>
                                            <?php foreach ($categories as $cat): ?>
                                                <a class="mega-item" href="<?= BASE_URL ?>product/category/<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></a>
                                            <?php endforeach; ?>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <span class="mega-title">PHÂN LOẠI NỔI BẬT</span>
                                            <a class="mega-item" href="#">Đồ bơi Nam</a>
                                            <a class="mega-item" href="#">Đồ bơi Nữ</a>
                                            <a class="mega-item" href="#">Dụng cụ lặn</a>
                                        </div>

                                        <div class="col-md-3">
                                            <span class="mega-title">THƯƠNG HIỆU HÀNG ĐẦU</span>
                                            <a class="mega-item" href="#">Speedo</a>
                                            <a class="mega-item" href="#">Arena</a>
                                            <a class="mega-item" href="#">Phoenix</a>
                                        </div>
                                        
                                       
                                    </div>
                                </div>
                            </div>
                        </li>
                        
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>blog">Blog</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>contact">Liên hệ</a></li>
                    </ul>

                   <form action="<?= BASE_URL ?>product" method="GET" class="d-flex me-3 position-relative">
    <div class="header-search-form w-100">
        <input type="search" id="searchInput" name="keyword" placeholder="Tìm sản phẩm..." required autocomplete="off">
        <button type="submit" class="btn-search-icon">
            <i class="fas fa-search"></i>
        </button>
    </div>

    <div id="searchResults" class="position-absolute bg-white shadow rounded start-0 end-0" style="top: 100%; z-index: 1000; display: none; overflow: hidden;">
        </div>
</form>

<style>
    /* CSS cho từng dòng kết quả */
    .search-item {
        display: flex;
        align-items: center;
        padding: 10px;
        border-bottom: 1px solid #f1f1f1;
        text-decoration: none;
        color: #333;
        transition: background 0.2s;
    }
    .search-item:hover {
        background-color: #f8f9fa;
        color: #0d6efd; /* Màu xanh primary */
    }
    .search-item img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        margin-right: 10px;
        border-radius: 4px;
    }
    .search-info h6 {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.2;
    }
    .search-info span {
        font-size: 12px;
        color: #dc3545; /* Màu đỏ cho giá */
        font-weight: bold;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    let timeout = null;

    // Hàm định dạng tiền tệ
    const formatCurrency = (amount) => {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
    };

    searchInput.addEventListener('input', function() {
        const keyword = this.value.trim();

        // Xóa timeout cũ để tránh gọi server quá nhiều (Debounce)
        clearTimeout(timeout);

        if (keyword.length < 2) {
            searchResults.style.display = 'none';
            searchResults.innerHTML = '';
            return;
        }

        // Đợi 300ms sau khi ngừng gõ mới gọi API
        timeout = setTimeout(() => {
            fetch(`<?= BASE_URL ?>product/liveSearch?keyword=${encodeURIComponent(keyword)}`)
                .then(response => response.json())
                .then(res => {
                    if (res.success && res.data.length > 0) {
                        let html = '';
                        res.data.forEach(prod => {
                            // Xử lý giá hiển thị
                            let priceDisplay = '';
                            if (prod.sale_price > 0) {
                                priceDisplay = `${formatCurrency(prod.sale_price)} <del class="text-muted small ms-1">${formatCurrency(prod.price)}</del>`;
                            } else {
                                priceDisplay = formatCurrency(prod.price);
                            }

                            html += `
                                <a href="<?= BASE_URL ?>product/detail/${prod.id}" class="search-item">
                                    <img src="<?= BASE_URL ?>public/uploads/${prod.image}" alt="${prod.name}">
                                    <div class="search-info">
                                        <h6 class="text-truncate" style="max-width: 200px;">${prod.name}</h6>
                                        <span>${priceDisplay}</span>
                                    </div>
                                </a>
                            `;
                        });
                        // Thêm nút xem tất cả nếu cần
                        html += `<a href="<?= BASE_URL ?>product?keyword=${encodeURIComponent(keyword)}" class="d-block text-center p-2 text-primary small bg-light text-decoration-none">Xem tất cả kết quả cho "${keyword}"</a>`;
                        
                        searchResults.innerHTML = html;
                        searchResults.style.display = 'block';
                    } else {
                        searchResults.innerHTML = '<div class="p-3 text-center text-muted small">Không tìm thấy sản phẩm nào</div>';
                        searchResults.style.display = 'block';
                    }
                })
                .catch(err => console.error(err));
        }, 300);
    });

    // Ẩn kết quả khi click ra ngoài
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
});
</script>

                    <div class="d-flex align-items-center">
                        <a href="<?= BASE_URL ?>cart" class="btn position-relative text-primary me-3 border-0">
                            <i class="fas fa-shopping-bag fa-lg"></i>
                            <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?= getCartQuantity() ?>
                            </span>
                        </a>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <div class="dropdown">
                                <button class="btn btn-outline-primary dropdown-toggle btn-sm d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                                    <?php if(!empty($_SESSION['user_avatar'])): ?>
                                        <img src="<?= $_SESSION['user_avatar'] ?>" class="rounded-circle me-2" width="25" height="25">
                                    <?php else: ?>
                                        <i class="fas fa-user-circle me-2"></i>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($_SESSION['user_name']) ?>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <?php if($_SESSION['user_role'] == 'admin'): ?>
                                        <li><a class="dropdown-item" href="<?= BASE_URL ?>admin/dashboard"><i class="fas fa-cog me-2"></i>Quản trị</a></li>
                                    <?php endif; ?>
                                    <li><a class="dropdown-item" href="<?= BASE_URL ?>user/profile"><i class="fas fa-address-book me-2"></i>Sổ địa chỉ</a></li>
                                    <li><a class="dropdown-item" href="<?= BASE_URL ?>user/history"><i class="fas fa-box me-2"></i>Đơn hàng</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>client/auth/logout"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a></li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>client/auth/login" class="btn btn-primary btn-sm px-3 fw-bold">Đăng nhập</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main class="py-4">
        <div class="container">