<h3>Thêm Sản Phẩm Mới</h3>
<form action="<?= BASE_URL ?>admin/product/store" method="POST" enctype="multipart/form-data" class="bg-white p-4 shadow-sm rounded">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Tên sản phẩm *</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
            <label>Mã SKU (Mã kho) *</label>
            <input type="text" name="sku_code" class="form-control" required>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4 mb-3">
            <label>Giá gốc *</label>
            <input type="number" name="price" class="form-control" required>
        </div>
        <div class="col-md-4 mb-3">
            <label>Giá khuyến mãi</label>
            <input type="number" name="sale_price" class="form-control" value="0">
        </div>
        <div class="col-md-4 mb-3">
            <label>Danh mục</label>
            <select name="category_id" class="form-control">
                <?php foreach($data['categories'] as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label>Ảnh đại diện</label>
        <input type="file" name="image" class="form-control">
    </div>

    <div class="mb-3">
        <label>Mô tả chi tiết</label>
        <textarea name="description" class="form-control" rows="5"></textarea>
    </div>

    <button type="submit" class="btn btn-success">Lưu sản phẩm</button>
    <a href="<?= BASE_URL ?>admin/product" class="btn btn-secondary">Hủy</a>
</form>