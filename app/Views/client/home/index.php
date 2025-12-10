<?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show container mt-3" role="alert">
        <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['flash_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); // Xóa thông báo sau khi hiện xong ?>
<?php endif; ?>

<h3 class="text-center mb-4 text-uppercase fw-bold text-primary">Sản phẩm nổi bật</h3>
<div class="row">
    <div class="col-md-3">
        <div class="list-group mb-4 shadow-sm">
            <a href="#" class="list-group-item list-group-item-action active bg-primary border-primary">
                <i class="fas fa-bars me-2"></i> DANH MỤC SẢN PHẨM
            </a>
            
            <?php if (!empty($data['categories'])): ?>
                <?php foreach($data['categories'] as $cat): ?>
                <a href="<?= BASE_URL ?>product/category/<?= $cat['id'] ?>" class="list-group-item list-group-item-action">
                    <?= htmlspecialchars($cat['name']) ?>
                </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="p-3 text-muted">Chưa có danh mục</div>
            <?php endif; ?>
        </div>
        
        <img src="https://yeuboiloi.com/wp-content/uploads/2021/06/mu-boi.jpg" class="img-fluid rounded mb-3" alt="Banner Left">
    </div>

    <div class="col-md-9">
        <div class="mb-4 rounded overflow-hidden shadow-sm">
             <img src="https://yeuboiloi.com/wp-content/uploads/2021/06/banner-kinh-boi-can.jpg" class="w-100" style="height: 350px; object-fit: cover;" alt="Banner">
        </div>
        
        <h4 class="text-uppercase fw-bold text-primary mb-3">Sản phẩm mới</h4>
        <div class="row">
             <?php for($i=1; $i<=6; $i++): ?>
                <div class="col-6 col-md-4">
                    <div class="card card-product">
                         </div>
                </div>
             <?php endfor; ?>
        </div>
    </div>
</div>