<?php
/**
 * Template 2: Modern Standard (قالب مدرن و استاندارد WooFactor)
 * Ultra-clean, border-perfect, card-based layout without QR code.
 */
defined('ABSPATH') || exit;

$brand = woo_factor_normalize_color($data['color'] ?? '', '#0284c7');
$brand_dark = woo_factor_color_shade($brand, -25, '#0369a1');
$brand_light = woo_factor_color_shade($brand, 90, '#f0f9ff');
$seller = $data['seller'] ?? [];
$buyer = $data['buyer'] ?? [];
$totals = $data['totals'] ?? [];
$items = $data['items'] ?? [];
$logo_url = !empty($seller['logo_url']) ? $seller['logo_url'] : '';
$stamp_url = !empty($data['stamp_url']) ? $data['stamp_url'] : '';
$currency = $totals['currency'] ?? 'تومان';

$status_color = '#10b981';
if (in_array($data['status'], ['pending', 'on-hold'], true)) {
    $status_color = '#f59e0b';
} elseif (in_array($data['status'], ['cancelled', 'failed'], true)) {
    $status_color = '#ef4444';
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>فاکتور خرید - <?php echo esc_html($data['invoice_number']); ?></title>
    <style>
        @page { size: A4 portrait; margin: 8mm; }
        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
            -webkit-print-color-adjust: exact !important; 
            print-color-adjust: exact !important;
        }
        body {
            font-family: 'Vazirmatn', 'Tahoma', 'Segoe UI', sans-serif;
            background: #f8fafc;
            color: #1e293b;
            direction: rtl;
            text-align: right;
            font-size: 11px;
            line-height: 1.5;
            padding: 15px;
        }
        .invoice-card {
            position: relative;
            width: 100%;
            max-width: 820px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-28deg);
            font-size: 64px;
            font-weight: 900;
            color: rgba(2, 132, 199, 0.05);
            border: 6px dashed rgba(2, 132, 199, 0.08);
            padding: 15px 40px;
            border-radius: 16px;
            pointer-events: none;
            user-select: none;
            white-space: nowrap;
            z-index: 0;
        }
        .header-banner {
            background: linear-gradient(135deg, <?php echo esc_attr($brand); ?> 0%, <?php echo esc_attr($brand_dark); ?> 100%);
            border-radius: 8px;
            color: #ffffff;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .brand-section {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .logo-wrapper {
            background: #ffffff;
            border-radius: 6px;
            padding: 6px 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            max-height: 52px;
        }
        .logo-wrapper img {
            max-height: 40px;
            max-width: 120px;
            object-fit: contain;
        }
        .brand-text h1 {
            font-size: 17px;
            font-weight: 900;
            margin-bottom: 2px;
            color: #ffffff;
        }
        .brand-text p {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.85);
        }
        .header-meta {
            text-align: left;
            direction: ltr;
        }
        .status-badge {
            display: inline-block;
            background: <?php echo esc_attr($status_color); ?>;
            color: #ffffff;
            font-size: 10.5px;
            font-weight: bold;
            padding: 3px 12px;
            border-radius: 20px;
            margin-bottom: 5px;
            direction: rtl;
        }
        .meta-line {
            font-size: 10.5px;
            color: rgba(255, 255, 255, 0.9);
            direction: rtl;
            text-align: left;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 16px;
        }
        .card-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 14px;
        }
        .card-title {
            font-size: 11.5px;
            font-weight: bold;
            color: <?php echo esc_attr($brand); ?>;
            margin-bottom: 8px;
            border-bottom: 1.5px solid #e2e8f0;
            padding-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            font-size: 10.5px;
        }
        .info-row .lbl { color: #64748b; }
        .info-row .val { font-weight: bold; color: #1e293b; text-align: left; max-width: 65%; word-break: break-word; }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            font-size: 10.5px;
        }
        .items-table th {
            background: <?php echo esc_attr($brand_light); ?>;
            color: <?php echo esc_attr($brand_dark); ?>;
            padding: 9px 8px;
            font-weight: 800;
            text-align: center;
            border-bottom: 2px solid #cbd5e1;
        }
        .items-table td {
            padding: 8px 8px;
            border-bottom: 1px solid #e2e8f0;
            text-align: center;
            vertical-align: middle;
        }
        .items-table tr:nth-child(even) {
            background: #fafafa;
        }
        .product-cell {
            display: flex;
            align-items: center;
            gap: 10px;
            text-align: right;
        }
        .product-thumb {
            width: 36px;
            height: 36px;
            border-radius: 4px;
            object-fit: cover;
            border: 1px solid #e2e8f0;
            flex-shrink: 0;
        }
        .sku-badge {
            display: inline-block;
            background: #f1f5f9;
            color: #475569;
            padding: 1px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            margin-top: 2px;
        }
        .bottom-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 16px;
            margin-bottom: 14px;
            align-items: start;
        }
        .totals-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 14px;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 11px;
            color: #475569;
            border-bottom: 1px dashed #e2e8f0;
        }
        .totals-row:last-child {
            border-bottom: none;
        }
        .grand-total-box {
            background: <?php echo esc_attr($brand_light); ?>;
            border: 1.5px solid <?php echo esc_attr($brand); ?>;
            border-radius: 6px;
            padding: 10px 12px;
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .grand-total-box .grand-lbl {
            font-size: 12px;
            font-weight: bold;
            color: <?php echo esc_attr($brand_dark); ?>;
        }
        .grand-total-box .grand-val {
            font-size: 14.5px;
            font-weight: 900;
            color: <?php echo esc_attr($brand_dark); ?>;
            direction: ltr;
        }
        .extra-box {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .barcode-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .stamp-box {
            border: 1px dashed #cbd5e1;
            border-radius: 6px;
            padding: 8px;
            min-height: 75px;
            min-width: 130px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #fff;
        }
        .stamp-box img {
            max-height: 60px;
            max-width: 120px;
            object-fit: contain;
        }
        .footer-bar {
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            font-size: 10px;
            color: #64748b;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        @media print {
            body { background: #fff; padding: 0; }
            .invoice-card { border: none; box-shadow: none; padding: 0; max-width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<?php if (!empty($data['show_watermark']) && !empty($data['watermark_text'])): ?>
    <div class="watermark"><?php echo esc_html($data['watermark_text']); ?></div>
<?php endif; ?>

<div class="invoice-card">
    <!-- Header Banner -->
    <div class="header-banner">
        <div class="brand-section">
            <?php if ($logo_url): ?>
                <div class="logo-wrapper">
                    <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($seller['name']); ?>">
                </div>
            <?php endif; ?>
            <div class="brand-text">
                <h1><?php echo esc_html($seller['name']); ?></h1>
                <p>صورتحساب خرید سفارش #<?php echo woo_factor_fa_digits($data['order_number']); ?></p>
            </div>
        </div>
        <div class="header-meta">
            <div><span class="status-badge"><?php echo esc_html($data['status_name']); ?></span></div>
            <div class="meta-line"><strong>شماره فاکتور:</strong> <?php echo woo_factor_fa_digits($data['invoice_number']); ?></div>
            <div class="meta-line"><strong>تاریخ:</strong> <?php echo woo_factor_fa_digits($data['jalali_date']); ?> - <?php echo woo_factor_fa_digits($data['jalali_time']); ?></div>
        </div>
    </div>

    <!-- Parties Grid -->
    <div class="info-grid">
        <div class="card-box">
            <div class="card-title">🏢 مشخصات فروشگاه</div>
            <div class="info-row"><span class="lbl">فروشگاه:</span><span class="val"><?php echo esc_html($seller['name']); ?></span></div>
            <?php if (!empty($seller['national_id'])): ?>
                <div class="info-row"><span class="lbl">شناسه ملی / کد اقتصادی:</span><span class="val"><?php echo woo_factor_fa_digits($seller['national_id']); ?></span></div>
            <?php endif; ?>
            <?php if (!empty($seller['phone'])): ?>
                <div class="info-row"><span class="lbl">تلفن تماس:</span><span class="val"><?php echo woo_factor_fa_digits($seller['phone']); ?></span></div>
            <?php endif; ?>
            <?php if (!empty($seller['address'])): ?>
                <div class="info-row"><span class="lbl">آدرس:</span><span class="val"><?php echo esc_html($seller['address']); ?></span></div>
            <?php endif; ?>
        </div>

        <div class="card-box">
            <div class="card-title">👤 مشخصات خریدار</div>
            <div class="info-row"><span class="lbl">نام خریدار:</span><span class="val"><?php echo esc_html($buyer['company'] ?: $buyer['name']); ?></span></div>
            <?php if (!empty($buyer['national_id'])): ?>
                <div class="info-row"><span class="lbl">کد ملی / شناسه:</span><span class="val"><?php echo woo_factor_fa_digits($buyer['national_id']); ?></span></div>
            <?php endif; ?>
            <?php if (!empty($buyer['phone'])): ?>
                <div class="info-row"><span class="lbl">شماره تماس:</span><span class="val"><?php echo woo_factor_fa_digits($buyer['phone']); ?></span></div>
            <?php endif; ?>
            <div class="info-row"><span class="lbl">نشانی تحویل:</span><span class="val"><?php echo esc_html($buyer['full_address']); ?></span></div>
        </div>
    </div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 6%;">ردیف</th>
                <th style="width: 44%; text-align: right; padding-right: 12px;">شرح محصول</th>
                <th style="width: 8%;">تعداد</th>
                <th style="width: 14%;">قیمت واحد</th>
                <?php if (!empty($data['show_discount_column'])): ?>
                    <th style="width: 12%;">تخفیف</th>
                <?php endif; ?>
                <th style="width: 16%;">جمع کل</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo woo_factor_fa_digits($item['index']); ?></td>
                    <td>
                        <div class="product-cell">
                            <?php if (!empty($data['show_product_image']) && !empty($item['thumbnail_url'])): ?>
                                <img src="<?php echo esc_url($item['thumbnail_url']); ?>" alt="" class="product-thumb">
                            <?php endif; ?>
                            <div>
                                <div style="font-weight: bold; color: #0f172a;"><?php echo esc_html($item['title']); ?></div>
                                <?php if (!empty($data['show_sku']) && !empty($item['sku']) && $item['sku'] !== '-'): ?>
                                    <span class="sku-badge">کد: <?php echo esc_html($item['sku']); ?></span>
                                <?php endif; ?>
                                <?php if (!empty($item['meta'])): ?>
                                    <div style="font-size: 9px; color: #64748b; margin-top: 2px;"><?php echo esc_html($item['meta']); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td style="font-weight: bold;"><?php echo woo_factor_fa_digits($item['qty']); ?></td>
                    <td><?php echo woo_factor_number_format($item['unit_price']); ?> <?php echo esc_html($currency); ?></td>
                    <?php if (!empty($data['show_discount_column'])): ?>
                        <td style="color: #ef4444;"><?php echo $item['discount'] > 0 ? woo_factor_number_format($item['discount']) . ' ' . esc_html($currency) : '-'; ?></td>
                    <?php endif; ?>
                    <td style="font-weight: 800; color: #0f172a;"><?php echo woo_factor_number_format($item['total']); ?> <?php echo esc_html($currency); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Bottom Section: Totals & Verification -->
    <div class="bottom-grid">
        <div class="extra-box">
            <!-- Terms & Note -->
            <?php if (!empty($data['invoice_terms'])): ?>
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 12px; font-size: 10px; color: #475569;">
                    <strong style="color: <?php echo esc_attr($brand); ?>;">شرایط و قوانین:</strong> <?php echo nl2br(esc_html($data['invoice_terms'])); ?>
                </div>
            <?php endif; ?>

            <!-- Barcode & Stamp Section -->
            <div class="barcode-card">
                <?php if (!empty($data['show_barcode']) && !empty($data['barcode_svg'])): ?>
                    <div style="text-align: right;">
                        <div><?php echo $data['barcode_svg']; ?></div>
                        <div style="font-size: 9px; color: #64748b; margin-top: 3px;">بارکد پیگیری سفارش #<?php echo woo_factor_fa_digits($data['order_number']); ?></div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($data['show_signature'])): ?>
                    <div class="stamp-box">
                        <?php if ($stamp_url): ?>
                            <img src="<?php echo esc_url($stamp_url); ?>" alt="مهر فروشگاه">
                        <?php else: ?>
                            <span style="font-weight: bold; color: #64748b; font-size: 10px;"><?php echo esc_html($data['signature_stamp']); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Totals Card -->
        <div class="totals-card">
            <div class="totals-row">
                <span>جمع کل اقلام:</span>
                <strong><?php echo woo_factor_number_format($totals['subtotal']); ?> <?php echo esc_html($currency); ?></strong>
            </div>
            <?php if ($totals['discount'] > 0): ?>
                <div class="totals-row" style="color: #ef4444;">
                    <span>مجموع تخفیف:</span>
                    <strong><?php echo woo_factor_number_format($totals['discount']); ?>- <?php echo esc_html($currency); ?></strong>
                </div>
            <?php endif; ?>
            <div class="totals-row">
                <span>حمل و نقل (<?php echo esc_html($totals['shipping_method']); ?>):</span>
                <strong><?php echo $totals['shipping'] > 0 ? woo_factor_number_format($totals['shipping']) . ' ' . esc_html($currency) : 'رایگان'; ?></strong>
            </div>
            <?php if ($totals['tax'] > 0): ?>
                <div class="totals-row">
                    <span>مالیات بر ارزش افزوده:</span>
                    <strong><?php echo woo_factor_number_format($totals['tax']); ?> <?php echo esc_html($currency); ?></strong>
                </div>
            <?php endif; ?>
            <div class="grand-total-box">
                <span class="grand-lbl">مبلغ کل پرداختی:</span>
                <span class="grand-val"><?php echo woo_factor_number_format($totals['grand_total']); ?> <?php echo esc_html($currency); ?></span>
            </div>
            <?php if (!empty($totals['grand_total_words'])): ?>
                <div style="font-size: 9.5px; color: #64748b; margin-top: 6px; text-align: center;">
                    به حروف: <?php echo esc_html($totals['grand_total_words']); ?> <?php echo esc_html($currency); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer Bar -->
    <div class="footer-bar">
        <span><?php echo esc_html($data['footer_note']); ?></span>
        <span>روش پرداخت: <?php echo esc_html($totals['payment_method']); ?></span>
    </div>
</div>

</body>
</html>
