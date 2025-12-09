<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; flex-direction: column; }
        .wrapper { display: flex; flex: 1; }
        .sidebar { min-width: 250px; background: #343a40; color: #fff; min-height: 100vh; }
        .sidebar a { color: #fff; text-decoration: none; display: block; padding: 10px 20px; }
        .sidebar a:hover { background: #495057; }
        .content { flex: 1; padding: 20px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark px-3">
        <a class="navbar-brand" href="#">Quản Trị Bể Bơi</a>
        <div class="d-flex">
            <span class="text-white me-3">Chào, <?php echo $_SESSION['user_name'] ?? 'Admin'; ?></span>
            <a href="<?= BASE_URL ?>admin/auth/logout" class="btn btn-sm btn-danger">Đăng xuất</a>
        </div>
    </nav>

    <div class="wrapper">
        <div class="sidebar">
            <h4 class="text-center py-3">Menu</h4>
            <a href="<?= BASE_URL ?>admin/dashboard">🏠 Tổng quan</a>
            <a href="<?= BASE_URL ?>admin/category">📂 Quản lý Danh mục</a>
            <a href="<?= BASE_URL ?>admin/product">🩳 Quản lý Sản phẩm</a>
            <a href="<?= BASE_URL ?>admin/order">📦 Quản lý Đơn hàng</a>
            <a href="<?= BASE_URL ?>admin/user">👤 Quản lý Người dùng</a>
        </div>
        
        <div class="content">