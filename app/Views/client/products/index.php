<style>
    /* 1. Thiết lập thẻ Card mặc định */
    .product-card {
        border: 1px solid #dee2e6; /* Viền xám mờ mặc định */
        transition: all 0.3s ease-in-out; /* Hiệu ứng chuyển mượt */
    }

    /* 2. Khi RÊ CHUỘT vào Card: Hiện viền xanh đậm */
    .product-card:hover {
        border: 1px solid #0056b3; /* Màu xanh đậm giống ảnh mẫu */
        box-shadow: 0 4px 12px rgba(0, 86, 179, 0.15); /* Đổ bóng nhẹ cho đẹp */
    }

    /* 3. Thiết lập Nút bấm mặc định (Nền trắng, chữ đen, viền xám) */
    .btn-hover-effect {
        background-color: #fff;
        color: #333;
        border: 1px solid #ced4da;
        text-transform: uppercase; /* Chữ in hoa: XEM CHI TIẾT */
        font-size: 0.9rem;
        font-weight: 600;
        border-radius: 0; /* Vuông vức giống ảnh mẫu (hoặc bỏ dòng này nếu thích bo tròn) */
        transition: all 0.3s;
        width: 100%; /* Nút rộng full card */
    }

    /* 4. Khi RÊ CHUỘT vào Card: Nút biến thành màu xanh */
    .product-card:hover .btn-hover-effect {
        background-color: #0056b3;
        color: #fff;
        border-color: #0056b3;
    }

    /* 5. Nút khi hết hàng (Không đổi màu) */
    .btn-hover-effect:disabled {
        background-color: #e9ecef !important; /* Xám */
        color: #6c757d !important;
        border-color: #dee2e6 !important;
        cursor: not-allowed;
    }
</style>


<div class="container py-4">
    <div class="row">
        <div class="col-md-3" style="position: sticky; top: 90px; height: fit-content; z-index: 100;">
            <form action="<?= BASE_URL ?>product" method="GET" class="card shadow-sm border-0 p-3">
                
                <input type="hidden" name="keyword" value="<?= htmlspecialchars($data['filters']['keyword'] ?? '') ?>">

                <h5 class="fw-bold text-primary border-bottom pb-2 mb-3"><i class="fas fa-filter"></i> BỘ LỌC TÌM KIẾM</h5>
                
                <div class="mb-4">
                    <h6 class="fw-bold">Danh mục sản phẩm</h6>
                    <?php foreach($data['categories'] as $cat): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="cat" value="<?= $cat['id'] ?>" id="cat_<?= $cat['id'] ?>"
                                <?= ($data['filters']['category_id'] == $cat['id']) ? 'checked' : '' ?>
                                onchange="this.form.submit()">
                            <label class="form-check-label" for="cat_<?= $cat['id'] ?>"><?= $cat['name'] ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold">Đối tượng sử dụng</h6>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="target" value="adult" id="target_adult"
                            <?= ($data['filters']['target'] == 'adult') ? 'checked' : '' ?>
                            onchange="this.form.submit()">
                        <label class="form-check-label" for="target_adult">Người lớn</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="target" value="kid" id="target_kid"
                            <?= ($data['filters']['target'] == 'kid') ? 'checked' : '' ?>
                            onchange="this.form.submit()">
                        <label class="form-check-label" for="target_kid">Trẻ em</label>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold">Thương hiệu</h6>
                    <?php foreach($data['brands'] as $brand): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="brand[]" value="<?= $brand['id'] ?>"
                                <?= (in_array($brand['id'], $data['filters']['brands'])) ? 'checked' : '' ?>>
                            <label class="form-check-label"><?= $brand['name'] ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold">Kích thước</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <?php $sizes = ['S', 'M', 'L', 'XL', 'FreeSize']; ?>
                        <?php foreach($sizes as $s): ?>
                            <div>
                                <input type="checkbox" class="btn-check" name="size[]" id="size_<?= $s ?>" value="<?= $s ?>"
                                    <?= (in_array($s, $data['filters']['sizes'])) ? 'checked' : '' ?>>
                                <label class="btn btn-outline-secondary btn-sm" for="size_<?= $s ?>"><?= $s ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold">Khoảng giá</h6>
                    <div class="d-flex align-items-center gap-2">
                        <input type="number" name="min" class="form-control form-control-sm" placeholder="Min" value="<?= $data['filters']['price_min'] ?>">
                        <span>-</span>
                        <input type="number" name="max" class="form-control form-control-sm" placeholder="Max" value="<?= $data['filters']['price_max'] ?>">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-2">Áp dụng lọc</button>
                <a href="<?= BASE_URL ?>product" class="btn btn-outline-secondary w-100 mt-2 btn-sm">Xóa bộ lọc</a>
            </form>
        </div>

        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold text-dark m-0">
                    <?= !empty($data['filters']['keyword']) ? 'Kết quả cho: "' . htmlspecialchars($data['filters']['keyword']) . '"' : 'Tất cả sản phẩm' ?>
                </h4>
                <span class="text-muted small">
                    Hiển thị <?= count($data['products']) ?> / <?= $data['pagination']['total_products'] ?> sản phẩm
                </span>
            </div>

            <?php if(empty($data['products'])): ?>
                <div class="alert alert-warning text-center p-5">
                    <h4><i class="fas fa-search"></i> Không tìm thấy sản phẩm nào!</h4>
                    <p>Vui lòng thử thay đổi tiêu chí lọc.</p>
                </div>
            <?php else: ?>
                <div class="row">
    <?php foreach($data['products'] as $prod): ?>
        <?php 
            // Logic tính tồn kho
            $stock = isset($prod['total_stock']) ? (int)$prod['total_stock'] : ((int)$prod['product_qty'] ?? 0);
            $isOutOfStock = ($stock <= 0);
        ?>

        <div class="col-6 col-md-4 mb-4">
            <div class="card product-card h-100 position-relative bg-white">
                
                <?php if(!$isOutOfStock && $prod['sale_price'] > 0): ?>
                    <div class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1 m-2 fw-bold shadow-sm" style="font-size: 0.8rem; z-index: 10;">
                        -<?= round((($prod['price'] - $prod['sale_price']) / $prod['price']) * 100) ?>%
                    </div>
                <?php endif; ?>

                <a href="<?= BASE_URL ?>product/detail/<?= $prod['id'] ?>" class="position-relative d-block overflow-hidden">
                    <img src="<?= BASE_URL ?>public/uploads/<?= $prod['image'] ?>" 
                         class="card-img-top p-4" 
                         style="height: 220px; object-fit: contain; transition: transform 0.5s; <?= $isOutOfStock ? 'opacity: 0.4;' : '' ?>">
                    
                    <?php if($isOutOfStock): ?>
                        <div class="position-absolute top-50 start-50 translate-middle text-center w-100">
                             <span class="badge bg-danger fs-6 py-2 px-3 shadow">HẾT HÀNG</span>
                        </div>
                    <?php endif; ?>
                </a>
                
                <div class="card-body text-center d-flex flex-column pt-0">
                    <h6 class="card-title text-truncate mb-2">
                        <a href="<?= BASE_URL ?>product/detail/<?= $prod['id'] ?>" class="text-decoration-none text-dark fw-bold">
                            <?= htmlspecialchars($prod['name']) ?>
                        </a>
                    </h6>
                    
                    <div class="mb-3">
                        <?php if($prod['sale_price'] > 0): ?>
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                <span class="text-danger fw-bold fs-5"><?= number_format($prod['sale_price']) ?>đ</span>
                                <small class="text-muted text-decoration-line-through"><?= number_format($prod['price']) ?>đ</small>
                            </div>
                        <?php else: ?>
                            <span class="text-danger fw-bold fs-5"><?= number_format($prod['price']) ?>đ</span>
                        <?php endif; ?>
                    </div>

                    <div class="mt-auto">
                        <?php if($isOutOfStock): ?>
                            <button class="btn btn-hover-effect py-2" disabled>TẠM HẾT HÀNG</button>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>product/detail/<?= $prod['id'] ?>" class="btn btn-hover-effect py-2">
                                XEM CHI TIẾT
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

                <?php if ($data['pagination']['total_pages'] > 1): ?>
                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= ($data['pagination']['current_page'] <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= BASE_URL ?>product?page=<?= $data['pagination']['current_page'] - 1 ?>&<?= http_build_query(array_diff_key($data['filters'], ['limit'=>1, 'offset'=>1])) ?>">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>

                            <?php for ($i = 1; $i <= $data['pagination']['total_pages']; $i++): ?>
                                <li class="page-item <?= ($i == $data['pagination']['current_page']) ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= BASE_URL ?>product?page=<?= $i ?>&<?= http_build_query(array_diff_key($data['filters'], ['limit'=>1, 'offset'=>1])) ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <li class="page-item <?= ($data['pagination']['current_page'] >= $data['pagination']['total_pages']) ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= BASE_URL ?>product?page=<?= $data['pagination']['current_page'] + 1 ?>&<?= http_build_query(array_diff_key($data['filters'], ['limit'=>1, 'offset'=>1])) ?>">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>
</div>