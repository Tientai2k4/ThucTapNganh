<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Trị - Swimming Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { min-height: 100vh; display: flex; flex-direction: column; }
        .wrapper { display: flex; flex: 1; }
        #sidebar { min-width: 250px; max-width: 250px; background: #343a40; color: #fff; transition: all 0.3s; }
        #sidebar .nav-link { color: rgba(255,255,255,0.8); padding: 15px 20px; }
        #sidebar .nav-link:hover { color: #fff; background: rgba(255,255,255,0.1); }
        #content { width: 100%; padding: 20px; background: #f8f9fa; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-secondary px-4">
        <a class="navbar-brand" href="<?= BASE_URL ?>admin">ADMIN DASHBOARD</a>
        <div class="collapse navbar-collapse justify-content-end">
            <span class="navbar-text text-white me-3">Xin chào, Admin</span>
            <a href="<?= BASE_URL ?>admin/auth/logout" class="btn btn-sm btn-outline-danger">Đăng xuất</a>
        </div>
    </nav>
    <div class="wrapper">