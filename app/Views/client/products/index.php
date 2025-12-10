<div class="container my-4">
    <div class="row">
        
        <div class="col-md-3">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom-0">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-filter text-primary"></i> Bộ Lọc Tìm Kiếm</h5>
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>product" method="GET">
                        
                        <div class="mb-3">
                            <label class="fw-bold mb-2">Thương hiệu</label>
                            <select name="brand" class="form-select">
                                <option value="">-- Tất cả --</option>
                                <?php foreach($data['brands'] as $b): ?>
                                    <option value="<?= $b['id'] ?>" <?= (isset($_GET['brand']) && $_GET['brand'] == $b['id']) ? 'selected' : '' ?>>
                                        <?= $b['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold mb-2">Mức giá</label>
                            <select name="price" class="form-select">
                                <option value="">-- Tất cả --</option>
                                <option value="duoi_500" <?= (isset($_GET['price']) && $_GET['price'] == 'duoi_500') ? 'selected' : '' ?>>Dưới 500k</option>
                                <option value="500_1tr" <?= (isset($_GET['price']) && $_GET['price'] == '500_1tr') ? 'selected' : '' ?>>500k - 1 Triệu</option>
                                <option value="tren_1tr" <?= (isset($_GET['price']) && $_GET['price'] == 'tren_1tr') ? 'selected' : '' ?>>Trên 1 Triệu</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mt-2">Lọc sản phẩm</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-uppercase text-dark m-0">Danh sách sản phẩm</h3>
                <span class="text-muted">Tìm thấy <?= count($data['products']) ?> sản phẩm</span>
            </div>

            <div class="row">
                <?php if(!empty($data['products'])): ?>
                    <?php foreach($data['products'] as $prod): ?>
                        <div class="col-6 col-md-4 mb-4">
                            <div class="card h-100 shadow-sm border-0 product-card">
                                <div style="height: 200px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #fff;">
                                    <img src="<?= BASE_URL ?>uploads/<?= $prod['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($prod['name']) ?>" style="max-height: 100%; width: auto; object-fit: contain;">
                                </div>
                                
                                <div class="card-body text-center d-flex flex-column">
                                    <h6 class="card-title text-truncate" title="<?= htmlspecialchars($prod['name']) ?>">
                                        <?= htmlspecialchars($prod['name']) ?>
                                    </h6>
                                    
                                    <div class="mt-auto">
                                        <p class="text-danger fw-bold fs-5 mb-2">
                                            <?= number_format($prod['price']) ?>đ
                                        </p>
                                        <a href="<?= BASE_URL ?>product/detail/<?= $prod['id'] ?>" class="btn btn-primary btn-sm w-100">
                                            Xem chi tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-warning text-center">
                            Không tìm thấy sản phẩm nào phù hợp với bộ lọc.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>