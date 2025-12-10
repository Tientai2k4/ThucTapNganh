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

    <hr>
    <h4 class="mb-3">Quản lý Biến thể (Size & Màu sắc)</h4>
    <table class="table table-bordered table-striped" id="variantTable">
        <thead class="thead-light">
            <tr>
                <th width="20%">Size</th>
                <th width="40%">Màu sắc</th>
                <th width="30%">Số lượng tồn kho</th>
                <th width="10%" class="text-center">
                    <button type="button" class="btn btn-sm btn-success" onclick="addVariantRow()">
                        <i class="fa fa-plus"></i> +
                    </button>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <select name="variants[0][size]" class="form-control">
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                        <option value="XXL">XXL</option>
                        <option value="FreeSize">FreeSize</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="variants[0][color]" class="form-control" placeholder="Ví dụ: Xanh, Đỏ..." required>
                </td>
                <td>
                    <input type="number" name="variants[0][stock]" class="form-control" value="0" min="0" required>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">Xóa</button>
                </td>
            </tr>
        </tbody>
    </table>
    <hr>
    <div class="mt-4">
        <button type="submit" class="btn btn-success">Lưu sản phẩm</button>
        <a href="<?= BASE_URL ?>admin/product" class="btn btn-secondary">Hủy</a>
    </div>
</form>

<script>
let variantCount = 1;

function addVariantRow() {
    const table = document.getElementById('variantTable').getElementsByTagName('tbody')[0];
    const newRow = table.insertRow();
    
    // Sử dụng backtick (`) để tạo template string cho dễ nhìn
    newRow.innerHTML = `
        <td>
            <select name="variants[${variantCount}][size]" class="form-control">
                <option value="S">S</option>
                <option value="M">M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
                <option value="XXL">XXL</option>
                <option value="FreeSize">FreeSize</option>
            </select>
        </td>
        <td>
            <input type="text" name="variants[${variantCount}][color]" class="form-control" placeholder="Màu sắc" required>
        </td>
        <td>
            <input type="number" name="variants[${variantCount}][stock]" class="form-control" value="0" min="0" required>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">Xóa</button>
        </td>
    `;
    variantCount++;
}

function removeRow(btn) {
    const row = btn.parentNode.parentNode;
    // Kiểm tra nếu còn nhiều hơn 1 dòng thì mới cho xóa (tùy chọn)
    // const rowCount = document.getElementById('variantTable').tBodies[0].rows.length;
    // if(rowCount > 1) { row.parentNode.removeChild(row); }
    
    row.parentNode.removeChild(row);
}
</script>