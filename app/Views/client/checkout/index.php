<h3 class="mb-4">Thanh toán đơn hàng</h3>
<?php if(isset($data['error'])): ?>
    <div class="alert alert-danger"><?= $data['error'] ?></div>
<?php endif; ?>

<form action="<?= BASE_URL ?>checkout/submit" method="POST">
    <div class="row">
        
        <div class="col-md-7">
    <div class="card p-4">
        <h5 class="mb-3">Thông tin giao hàng</h5>
        
        <?php 
        // Lấy dữ liệu đã truyền từ Controller
        $addresses = $data['addresses'] ?? [];
        $defaultAddress = $data['defaultAddress'] ?? null;
        $userName = $data['user_name_from_db'] ?? ($_SESSION['user_name'] ?? '');
        $userEmail = $data['user_email'] ?? '';
        
        // Kiểm tra xem có địa chỉ nào đã lưu không
        if (!empty($addresses)): 
        ?>
            <h6 class="mt-3">Chọn địa chỉ đã lưu:</h6>
            <div class="list-group mb-3 address-select-list" id="savedAddresses">
                <?php 
                foreach ($addresses as $addr) {
                    // Dùng key 'recipient_name' (giống trong user/profile)
                    $addrJson = htmlspecialchars(json_encode($addr), ENT_QUOTES, 'UTF-8');
                    $checked = ($addr['is_default'] == 1) ? 'checked' : '';
                    ?>
                    <label class="list-group-item">
                        <input class="form-check-input me-1 select-address-radio" type="radio" 
                               name="address_choice" value="<?= $addr['id'] ?>" 
                               data-address='<?= $addrJson ?>' <?= $checked ?>>
                        <div class="address-details d-inline-block ms-2">
                            
                            <strong><?= htmlspecialchars($addr['recipient_name']) ?></strong> | 
                            
                            <?= htmlspecialchars($addr['phone']) ?> | 
                            <?= htmlspecialchars($addr['address']) ?>
                            <?php if ($addr['is_default'] == 1): ?>
                                <span class="badge bg-primary ms-2">Mặc định</span>
                            <?php endif; ?>
                        </div>
                    </label>
                <?php } ?>
                
                <label class="list-group-item">
                    <input class="form-check-input me-1 select-address-radio" type="radio" 
                           name="address_choice" value="new" <?= ($defaultAddress == null) ? 'checked' : '' ?>>
                    <div class="address-details d-inline-block ms-2">Nhập địa chỉ mới</div>
                </label>
            </div>
            <hr>
        <?php endif; ?>
        
        <h6 class="mb-3">Điền thông tin chi tiết:</h6>
        
        <div class="mb-3">
            <label>Họ và tên *</label>
            <input type="text" name="full_name" class="form-control" id="inputFullName" 
                   value="<?= $defaultAddress['name'] ?? $userName ?>" required>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Số điện thoại *</label>
                <input type="text" name="phone" class="form-control" id="inputPhone" 
                       value="<?= $defaultAddress['phone'] ?? '' ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" id="inputEmail" 
                       value="<?= $userEmail ?>" >
            </div>
        </div>
        <div class="mb-3">
                <select name="province_id" id="province" class="form-control" required>
                    <option value="">-- Chọn Tỉnh/Thành --</option>
                    <?php if(!empty($data['provinces'])): ?>
                        <?php foreach($data['provinces'] as $p): ?>
                            <option value="<?= $p['ProvinceID'] ?>"><?= $p['ProvinceName'] ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Quận/Huyện *</label>
                    <select name="district_id" id="district" class="form-control" required></select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Phường/Xã *</label>
                    <select name="ward_code" id="ward" class="form-control" required></select>
                </div>
            </div>
            <div class="mb-3">
                <label>Địa chỉ chi tiết (Số nhà, tên đường) *</label>
                <textarea name="address_detail" id="inputAddress" class="form-control" required></textarea>
            </div>
                </div>
        </div>

        <div class="col-md-5">
            <div class="card p-4 bg-light">
                <h5 class="mb-3">Đơn hàng của bạn</h5>

                <div class="d-flex justify-content-between mb-2">
                    <span>Tạm tính:</span>
                    <span class="fw-bold" id="tempTotal"><?= number_format($data['totalMoney'] ?? 0) ?>đ</span>
                </div>

                <div class="input-group mb-3">
                    <input type="text" id="couponCode" class="form-control" placeholder="Mã giảm giá">
                    <button class="btn btn-outline-secondary" type="button" onclick="applyCoupon()">Áp dụng</button>
                </div>
                <div id="couponMessage" class="small mb-2"></div>
                
                <div class="d-flex justify-content-between mb-2 text-success" id="discountRow" style="display:none !important;">
                    <span>Giảm giá:</span>
                    <span id="discountAmount">-0đ</span>
                </div>

                <div class="d-flex justify-content-between mb-4 border-top pt-2">
                    <span class="h5">Tổng cộng:</span>
                    <span class="h5 text-danger" id="finalTotal"><?= number_format($data['totalMoney'] ?? 0) ?>đ</span>
                </div>

                <h6 class="mb-3">Phương thức thanh toán</h6>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" value="COD" id="cod" checked>
                        <label class="form-check-label" for="cod">Thanh toán khi nhận hàng (COD)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" value="ZALOPAY" id="zalopay">
                        <label class="form-check-label" for="zalopay">
                            Thanh toán qua ví ZaloPay <img src="https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-ZaloPay-Square.png" height="20">
                        </label>
                    </div>
                </div>
                
                <input type="hidden" name="discount_amount" id="inputDiscount" value="0">
                <input type="hidden" name="coupon_code" id="inputCouponCode" value="">

                <button type="submit" class="btn btn-success w-100 btn-lg">XÁC NHẬN ĐẶT HÀNG</button>
            </div>
        </div>
        
    </div>
</form>
<script>
function applyCoupon() {
    let code = document.getElementById('couponCode').value;
    let total = <?= $data['totalMoney'] ?? 0 ?>; 

    // Gọi AjaxController
    fetch('<?= BASE_URL ?>ajax/checkCoupon', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({code: code, total: total})
    })
    .then(res => res.json())
    .then(data => {
        let msg = document.getElementById('couponMessage');
        if(data.status) {
            msg.className = 'small mb-2 text-success';
            msg.innerText = data.message;
            
            // Hiển thị giảm giá
            document.getElementById('discountRow').style.setProperty('display', 'flex', 'important');
            document.getElementById('discountAmount').innerText = '-' + new Intl.NumberFormat().format(data.discount) + 'đ';
            document.getElementById('finalTotal').innerText = new Intl.NumberFormat().format(total - data.discount) + 'đ';
            
            // Cập nhật input ẩn
            document.getElementById('inputDiscount').value = data.discount;
            document.getElementById('inputCouponCode').value = data.code;
        } else {
            msg.className = 'small mb-2 text-danger';
            msg.innerText = data.message;
            
            // Reset nếu sai
            document.getElementById('discountRow').style.display = 'none';
            document.getElementById('finalTotal').innerText = new Intl.NumberFormat().format(total) + 'đ';
            document.getElementById('inputDiscount').value = 0;
            document.getElementById('inputCouponCode').value = '';
        }
    });
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');
    const addressRadios = document.querySelectorAll('.select-address-radio');

    // --- PHẦN 1: Lắng nghe chọn địa chỉ đã lưu ---
    addressRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'new') {
                fillDeliveryInfo(null);
            } else {
                try {
                    const data = JSON.parse(this.getAttribute('data-address'));
                    fillDeliveryInfo(data);
                } catch (e) {
                    console.error("Lỗi parse địa chỉ:", e);
                }
            }
        });
    });

    // --- PHẦN 2: Tải Quận/Huyện khi Tỉnh thay đổi ---
    provinceSelect.addEventListener('change', function() {
        const pId = this.value;
        districtSelect.innerHTML = '<option value="">-- Đang tải Quận/Huyện --</option>';
        wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';

        if (pId) {
            fetch('<?= BASE_URL ?>address/getDistricts?province_id=' + pId)
                .then(res => res.json())
                .then(data => {
                    let html = '<option value="">-- Chọn Quận/Huyện --</option>';
                    if (Array.isArray(data)) {
                        data.forEach(d => {
                            html += `<option value="${d.DistrictID}">${d.DistrictName}</option>`;
                        });
                    }
                    districtSelect.innerHTML = html;

                    // TỰ ĐỘNG CHỌN HUYỆN (nếu có biến pending từ fillDeliveryInfo)
                    if (window.pendingDistrictId) {
                        districtSelect.value = window.pendingDistrictId;
                        window.pendingDistrictId = null; // Xóa sau khi dùng
                        districtSelect.dispatchEvent(new Event('change'));
                    }
                })
                .catch(err => console.error("Lỗi tải huyện:", err));
        }
    });

    // --- PHẦN 3: Tải Phường/Xã khi Huyện thay đổi ---
    districtSelect.addEventListener('change', function() {
        const dId = this.value;
        wardSelect.innerHTML = '<option value="">-- Đang tải Phường/Xã --</option>';

        if (dId) {
            fetch('<?= BASE_URL ?>address/getWards?district_id=' + dId)
                .then(res => res.json())
                .then(data => {
                    let html = '<option value="">-- Chọn Phường/Xã --</option>';
                    if (Array.isArray(data)) {
                        data.forEach(w => {
                            html += `<option value="${w.WardCode}">${w.WardName}</option>`;
                        });
                    }
                    wardSelect.innerHTML = html;

                    // TỰ ĐỘNG CHỌN XÃ (nếu có biến pending từ fillDeliveryInfo)
                    if (window.pendingWardCode) {
                        wardSelect.value = window.pendingWardCode;
                        window.pendingWardCode = null; // Xóa sau khi dùng
                    }
                })
                .catch(err => console.error("Lỗi tải xã:", err));
        }
    });

    // Kích hoạt tự động điền cho địa chỉ mặc định khi vừa load trang
    const defaultRadio = document.querySelector('.select-address-radio:checked');
    if (defaultRadio && defaultRadio.value !== 'new') {
        defaultRadio.dispatchEvent(new Event('change'));
    }
});

// --- PHẦN 4: Hàm điền thông tin vào Form ---
function fillDeliveryInfo(addressData = null) {
    const nameInput = document.getElementById('inputFullName');
    const phoneInput = document.getElementById('inputPhone');
    const addressTextarea = document.getElementById('inputAddress');
    const provinceSelect = document.getElementById('province');
    const originalName = "<?= $userName ?>"; 

    if (addressData) {
        nameInput.value = addressData.recipient_name || '';
        phoneInput.value = addressData.phone || '';
        addressTextarea.value = addressData.address || '';
        
        if (addressData.province_id) {
            // Lưu lại ID huyện/xã vào biến toàn cục để Phần 2 và 3 sử dụng sau khi AJAX load xong
            window.pendingDistrictId = addressData.district_id;
            window.pendingWardCode = addressData.ward_code;

            // Chọn tỉnh và kích hoạt sự kiện change
            provinceSelect.value = addressData.province_id;
            provinceSelect.dispatchEvent(new Event('change'));
        }
    } else {
        nameInput.value = originalName; 
        phoneInput.value = '';
        addressTextarea.value = '';
        provinceSelect.value = '';
        document.getElementById('district').innerHTML = '<option value="">-- Chọn Quận/Huyện --</option>';
        document.getElementById('ward').innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
    }
}
</script>