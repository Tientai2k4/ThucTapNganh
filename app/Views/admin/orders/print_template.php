<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hóa đơn #<?= $data['order']['order_code'] ?></title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 14px; line-height: 1.5; color: #333; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; border: 1px solid #eee; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; }
        .info-section { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .info-box { width: 48%; }
        .info-box h3 { border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f9f9f9; font-weight: bold; }
        .text-end { text-align: right; }
        .footer { text-align: center; margin-top: 50px; font-style: italic; font-size: 12px; }
        .print-btn { text-align: center; margin-bottom: 20px; }
        .print-btn button { padding: 10px 20px; background: #007bff; color: #fff; border: none; cursor: pointer; font-size: 16px; border-radius: 5px; }
        
        @media print {
            .print-btn, .container { border: none; }
            .print-btn { display: none; }
            body { margin: 0; padding: 0; }
        }
    </style>
</head>
<body>

    <div class="print-btn">
        <button onclick="window.print()">🖨️ In Hóa Đơn Ngay</button>
    </div>

    <div class="container">
        <div class="header">
            <h1>CỬA HÀNG BÁN ĐỒ BƠI LỘI</h1>
            <p>Địa chỉ: 123 Đường Bơi Lội, Nha Trang, Khánh Hòa</p>
            <p>Hotline: 090.123.4567 - Website: www.doboi.com</p>
        </div>

        <div class="info-section">
            <div class="info-box">
                <h3>Thông tin khách hàng</h3>
                <p><strong>Người nhận:</strong> <?= htmlspecialchars($data['order']['customer_name']) ?></p>
                <p><strong>SĐT:</strong> <?= htmlspecialchars($data['order']['customer_phone']) ?></p>
                <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($data['order']['shipping_address']) ?></p>
            </div>
            <div class="info-box">
                <h3>Thông tin đơn hàng</h3>
                <p><strong>Mã đơn hàng:</strong> #<?= $data['order']['order_code'] ?></p>
                <p><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($data['order']['created_at'])) ?></p>
                <p><strong>Thanh toán:</strong> <?= $data['order']['payment_method'] ?></p>
                <?php if(!empty($data['order']['tracking_code'])): ?>
                    <p><strong>Mã vận đơn:</strong> <?= $data['order']['tracking_code'] ?></p>
                <?php endif; ?>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Sản phẩm</th>
                    <th>Phân loại</th>
                    <th class="text-end">Đơn giá</th>
                    <th class="text-end">SL</th>
                    <th class="text-end">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $i = 1;
                foreach($data['details'] as $item): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                    <td><?= $item['size'] ?> - <?= $item['color'] ?></td>
                    <td class="text-end"><?= number_format($item['price']) ?>đ</td>
                    <td class="text-end"><?= $item['quantity'] ?></td>
                    <td class="text-end"><?= number_format($item['total_price']) ?>đ</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-end"><strong>Tổng tiền hàng:</strong></td>
                    <td class="text-end"><?= number_format($data['order']['total_money'] + $data['order']['discount_amount'] - $data['order']['shipping_fee']) ?>đ</td>
                </tr>
                <tr>
                    <td colspan="5" class="text-end">Phí vận chuyển:</td>
                    <td class="text-end"><?= number_format($data['order']['shipping_fee']) ?>đ</td>
                </tr>
                <?php if($data['order']['discount_amount'] > 0): ?>
                <tr>
                    <td colspan="5" class="text-end">Giảm giá:</td>
                    <td class="text-end">-<?= number_format($data['order']['discount_amount']) ?>đ</td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td colspan="5" class="text-end"><strong>TỔNG THANH TOÁN:</strong></td>
                    <td class="text-end"><strong><?= number_format($data['order']['total_money']) ?>đ</strong></td>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            <p>Cảm ơn quý khách đã mua hàng!</p>
            <p>Vui lòng giữ lại hóa đơn để đổi trả trong vòng 7 ngày nếu có lỗi.</p>
        </div>
    </div>

</body>
</html>