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
                       value="<?= $defaultAddress['phone'] ?? '' ?>"
                       maxlength="10"required>
                       <small id="phone-error-checkout" class="text-danger fw-bold" style="display: none;"></small>
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

                <div class="cart-list mb-3" style="max-height: 300px; overflow-y: auto; padding-right: 5px;">
            <?php if (!empty($data['cart_items'])): ?>
                <?php foreach ($data['cart_items'] as $item): ?>
                    <div class="d-flex align-items-center mb-3 border-bottom pb-2 item-row" data-variant="<?= $item['variant_id'] ?>" data-price="<?= ($item['sale_price'] > 0) ? $item['sale_price'] : $item['price'] ?>">
                        <div class="me-3" style="width: 60px;">
                        <?php 
                           $imgName = $item['thumbnail'] ?? ($item['image'] ?? '');
        
                                    // Tạo đường dẫn ảnh
                                    if (!empty($imgName)) {
                                        $imgUrl = BASE_URL . 'public/uploads/' . $imgName;
                                    } else {
                                        // Nếu không có tên ảnh thì dùng ảnh giữ chỗ
                                        $imgUrl = 'https://placehold.co/60x60?text=No+Img';
                                    }
        
                        ?>
        
                            <img src="<?= $imgUrl ?>" 
                                class="img-fluid rounded border" 
                                style="width: 100%; height: 100%; object-fit: cover;"
                                alt="<?= htmlspecialchars($item['name']) ?>"
                                onerror="this.onerror=null; this.src='https://placehold.co/60x60?text=No+Img';">
                        </div>
                        
                        <div class="flex-grow-1">
                            <h6 class="mb-0 small fw-bold"><?= htmlspecialchars($item['name']) ?></h6>
                            <div class="text-muted" style="font-size: 0.85rem;">
                                Size: <?= $item['size'] ?> | Màu: <?= $item['color'] ?>
                            </div>
                            <div class="text-primary fw-bold small">
                                <?= number_format(($item['sale_price'] > 0) ? $item['sale_price'] : $item['price']) ?>đ
                            </div>
                        </div>

                        <div style="width: 70px;">
                            <input type="number" 
                                   class="form-control form-control-sm text-center input-qty" 
                                   value="<?= $item['qty'] ?>" 
                                   min="1" 
                                   onchange="updateCartItem(this, <?= $item['variant_id'] ?>)">
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

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
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment_method" value="VIETQR" id="vietqr">
                        <label class="form-check-label d-flex align-items-center" for="vietqr">
                            Chuyển khoản Ngân hàng (VietQR) 
                            <span class="badge bg-success ms-2" style="font-size: 0.7rem;">Xác nhận tự động</span>
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
    function getCurrentTotal() {
    let tempTotal = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        let price = parseFloat(row.getAttribute('data-price'));
        let qty = parseInt(row.querySelector('.input-qty').value);
        tempTotal += price * qty;
    });
    return tempTotal;
}
// Hàm áp dụng coupon (đã sửa để nhận tham số totalDynamic)

function applyCoupon(totalDynamic = null) {
    let code = document.getElementById('couponCode').value;
    
    // SỬA LỖI: 
    // Nếu hàm được gọi từ việc sửa số lượng (có totalDynamic) -> dùng totalDynamic
    // Nếu hàm được gọi từ nút Bấm (totalDynamic là null) -> Tự tính lại bằng getCurrentTotal()
    let total = (totalDynamic !== null) ? totalDynamic : getCurrentTotal();

    if(code.trim() === "") {
        // Nếu xóa mã thì tính lại tiền bình thường (không giảm giá)
        recalculateTotal();
        return; 
    }

    // Gọi Ajax check mã
    fetch('<?= BASE_URL ?>ajax/checkCoupon', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({code: code, total: total})
    })
    .then(res => res.json())
    .then(data => {
        let msg = document.getElementById('couponMessage');
        
        if(data.status) {
            // --- TRƯỜNG HỢP THÀNH CÔNG ---
            msg.className = 'small mb-2 text-success';
            msg.innerText = data.message;
            
            // Hiển thị dòng giảm giá
            document.getElementById('discountRow').style.setProperty('display', 'flex', 'important');
            document.getElementById('discountAmount').innerText = '-' + new Intl.NumberFormat('vi-VN').format(data.discount) + 'đ';
            
            // Tính lại Tổng cộng cuối cùng
            let final = total - data.discount;
            if(final < 0) final = 0;
            
            document.getElementById('finalTotal').innerText = new Intl.NumberFormat('vi-VN').format(final) + 'đ';
            
            // Cập nhật input ẩn để gửi form đi
            document.getElementById('inputDiscount').value = data.discount;
            document.getElementById('inputCouponCode').value = data.code;
        } else {
            // --- TRƯỜNG HỢP LỖI (Mã sai hoặc chưa đủ tiền) ---
            msg.className = 'small mb-2 text-danger';
            msg.innerText = data.message;
            
            // Ẩn dòng giảm giá
            document.getElementById('discountRow').style.setProperty('display', 'none', 'important');
            // Reset tổng cộng về bằng tạm tính
            document.getElementById('finalTotal').innerText = new Intl.NumberFormat('vi-VN').format(total) + 'đ';
            
            // Reset input ẩn
            document.getElementById('inputDiscount').value = 0;
            document.getElementById('inputCouponCode').value = '';
        }
    });
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('inputPhone');
    const errorSpan = document.getElementById('phone-error-checkout');

    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            const start = this.selectionStart;
            const originalVal = this.value;

            // Chặn nhập chữ ngay lập tức
            const cleanVal = originalVal.replace(/[^0-9]/g, '');

            if (originalVal !== cleanVal) {
                this.value = cleanVal;
                const diff = originalVal.length - cleanVal.length;
                this.setSelectionRange(start - diff, start - diff);
            }

            // Kiểm tra định dạng 10 số
            const regexPhone = /^0[0-9]{9}$/;
            if (this.value.length > 0 && !regexPhone.test(this.value)) {
                if (this.value.length !== 10) {
                    errorSpan.innerText = "SĐT phải đủ 10 chữ số.";
                } else if (!this.value.startsWith('0')) {
                    errorSpan.innerText = "SĐT phải bắt đầu bằng số 0.";
                }
                errorSpan.style.display = 'block';
                this.classList.add('is-invalid');
            } else {
                errorSpan.style.display = 'none';
                this.classList.remove('is-invalid');
            }
        });
    }
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


// Hàm xử lý cập nhật số lượng ngay tại trang Checkout
function updateCartItem(input, variantId) {
    let newQty = parseInt(input.value);
    if (newQty < 1) {
        input.value = 1;
        newQty = 1;
    }

    // 1. Gọi AJAX để cập nhật Session Giỏ hàng (Backend)
    fetch('<?= BASE_URL ?>ajax/updateCart', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ variant_id: variantId, qty: newQty })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status) {
            // 2. Nếu update Session thành công, tính lại tổng tiền trên giao diện (Frontend)
            recalculateTotal();
        } else {
            alert('Lỗi cập nhật giỏ hàng!');
        }
    })
    .catch(err => console.error(err));
}

// Hàm tính lại tổng tiền hiển thị
function recalculateTotal() {
    // Sử dụng hàm getCurrentTotal để tránh lặp code
    let tempTotal = getCurrentTotal();

    // Cập nhật text Tạm tính
    document.getElementById('tempTotal').innerText = new Intl.NumberFormat('vi-VN').format(tempTotal) + 'đ';

    // Kiểm tra xem có đang nhập mã không để tính lại giảm giá
    let currentCoupon = document.getElementById('couponCode').value;
    
    // Nếu có mã, gọi check lại mã với giá tiền MỚI
    if (currentCoupon.trim() !== "") {
        applyCoupon(tempTotal);
    } else {
        // Không có mã -> Tổng = Tạm tính
        document.getElementById('finalTotal').innerText = new Intl.NumberFormat('vi-VN').format(tempTotal) + 'đ';
        // Reset giảm giá
        document.getElementById('inputDiscount').value = 0;
        document.getElementById('discountRow').style.setProperty('display', 'none', 'important');
        // Xóa thông báo lỗi nếu có
        document.getElementById('couponMessage').innerText = '';
    }
}
</script>