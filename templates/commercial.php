<?php
/**
 * Template 3: Commercial & Luxury (قالب تجاری، رسمی و لوکس)
 * Tailored for commercial companies, wholesale distributors, and luxury brands.
 */
defined('ABSPATH') || exit;

$accent_color = woo_factor_normalize_color($data['color'] ?? '', '#0f172a');
$seller = $data['seller'] ?? [];
$buyer = $data['buyer'] ?? [];
$totals = $data['totals'] ?? [];
$items = $data['items'] ?? [];
$logo_url = !empty($seller['logo_url']) ? $seller['logo_url'] : '';
$stamp_url = !empty($data['stamp_url']) ? $data['stamp_url'] : '';
$currency = $totals['currency'] ?? 'تومان';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاکتور تجاری - <?php echo esc_html($data['invoice_number']); ?></title>
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
            background: #e2e8f0;
            color: #0f172a;
            direction: rtl;
            text-align: right;
            font-size: 11px;
            line-height: 1.5;
            padding: 15px;
        }
        .wrapper {
            position: relative;
            width: 100%;
            max-width: 820px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.07);
        }
        .watermark {
            position: absolute;
            top: 52%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-28deg);
            font-size: 68px;
            font-weight: 900;
            color: rgba(15, 23, 42, 0.05);
            border: 6px dashed rgba(15, 23, 42, 0.08);
            padding: 15px 40px;
            border-radius: 16px;
            pointer-events: none;
            user-select: none;
            white-space: nowrap;
            z-index: 0;
        }
        .header-bg {
            background: #0f172a;
            color: #ffffff;
            padding: 18px 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 4px solid <?php echo esc_attr($accent_color !== '#0f172a' ? $accent_color : '#0284c7'); ?>;
        }
        .header-brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .logo-box {
            background: #ffffff;
            padding: 6px 12px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            max-height: 52px;
        }
        .logo-box img {
            max-height: 42px;
            max-width: 130px;
            object-fit: contain;
        }
        .header-title h1 {
            font-size: 18px;
            font-weight: 900;
            color: #f8fafc;
            margin-bottom: 2px;
        }
        .header-title p {
            font-size: 11px;
            color: #94a3b8;
        }
        .header-meta-badge {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 10.5px;
            line-height: 1.6;
            text-align: right;
        }
        .body-content {
            padding: 18px 20px;
        }
        .parties-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 16px;
        }
        .party-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 14px;
            background: #f8fafc;
        }
        .party-title {
            font-weight: 800;
            font-size: 12px;
            color: #0f172a;
            border-bottom: 2px solid #cbd5e1;
            padding-bottom: 5px;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
        }
        .party-row {
            display: flex;
            margin-bottom: 4px;
            font-size: 10.5px;
        }
        .party-lbl { color: #64748b; width: 35%; flex-shrink: 0; }
        .party-val { color: #0f172a; font-weight: bold; width: 65%; word-break: break-word; }

        .comm-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
            margin-bottom: 16px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            overflow: hidden;
        }
        .comm-table thead tr {
            background: #0f172a;
            color: #ffffff;
        }
        .comm-table th {
            padding: 8px 6px;
            font-weight: bold;
            text-align: center;
        }
        .comm-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        .comm-table td {
            padding: 8px 6px;
            border-bottom: 1px solid #e2e8f0;
            text-align: center;
            vertical-align: middle;
        }
        .comm-table td.align-r {
            text-align: right;
            padding-right: 12px;
        }
        .thumb-box {
            display: flex;
            align-items: center;
            gap: 8px;
            text-align: right;
        }
        .thumb-img {
            width: 34px;
            height: 34px;
            border-radius: 4px;
            border: 1px solid #cbd5e1;
            object-fit: cover;
            flex-shrink: 0;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 310px;
            gap: 16px;
            align-items: start;
            margin-bottom: 16px;
        }
        .summary-box {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            overflow: hidden;
            background: #ffffff;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 7px 14px;
            font-size: 11px;
            border-bottom: 1px solid #f1f5f9;
        }
        .summary-row.grand {
            background: #0f172a;
            color: #ffffff;
            font-weight: 900;
            font-size: 13px;
            padding: 10px 14px;
        }
        .summary-row.grand span:last-child {
            color: #38bdf8;
            font-size: 14px;
            direction: ltr;
        }
        .payment-badge-box {
            background: #f0fdf4;
            border: 1px solid #86efac;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 10.5px;
            color: #166534;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .auth-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px;
            margin-top: 10px;
        }
        .auth-box {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 8px;
            min-height: 90px;
            background: #f8fafc;
            text-align: center;
        }
        .auth-box-title {
            font-size: 10px;
            font-weight: bold;
            color: #475569;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 3px;
            margin-bottom: 6px;
        }
        .auth-stamp-img {
            max-height: 60px;
            max-width: 120px;
            object-fit: contain;
            margin: 0 auto;
        }
        .footer-terms {
            background: #f8fafc;
            border-top: 1px solid #cbd5e1;
            padding: 10px 20px;
            font-size: 10px;
            color: #64748b;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        @media print {
            body { background: #fff; padding: 0; }
            .wrapper { border: none; box-shadow: none; max-width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<?php if (!empty($data['show_watermark']) && !empty($data['watermark_text'])): ?>
    <div class="watermark"><?php echo esc_html($data['watermark_text']); ?></div>
<?php endif; ?>

<div class="wrapper">
    <!-- Header -->
    <div class="header-bg">
        <div class="header-brand">
            <?php if ($logo_url): ?>
                <div class="logo-box">
                    <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($seller['name']); ?>">
                </div>
            <?php endif; ?>
            <div class="header-title">
                <h1><?php echo esc_html($seller['name']); ?></h1>
                <p>صورتحساب رسمی فروش کالا و خدمات تجاری</p>
            </div>
        </div>
        <div class="header-meta-badge">
            <div>شماره فاکتور: <strong><?php echo woo_factor_fa_digits($data['invoice_number']); ?></strong></div>
            <div>شماره سفارش: <strong><?php echo woo_factor_fa_digits($data['order_number']); ?></strong></div>
            <div>تاریخ ثبت: <strong><?php echo woo_factor_fa_digits($data['jalali_date']); ?></strong></div>
            <div>وضعیت سفارش: <strong><?php echo esc_html($data['status_name']); ?></strong></div>
        </div>
    </div>

    <!-- Body -->
    <div class="body-content">
        <!-- Parties -->
        <div class="parties-grid">
            <div class="party-card">
                <div class="party-title">
                    <span>🏢 مشخصات فروشنده</span>
                    <span style="font-size: 10px; color: #64748b;">اصلی</span>
                </div>
                <div class="party-row"><span class="party-lbl">نام فروشگاه:</span><span class="party-val"><?php echo esc_html($seller['name']); ?></span></div>
                <?php if (!empty($seller['national_id'])): ?>
                    <div class="party-row"><span class="party-lbl">شناسه ملی:</span><span class="party-val"><?php echo woo_factor_fa_digits($seller['national_id']); ?></span></div>
                <?php endif; ?>
                <?php if (!empty($seller['economic_code'])): ?>
                    <div class="party-row"><span class="party-lbl">کد اقتصادی:</span><span class="party-val"><?php echo woo_factor_fa_digits($seller['economic_code']); ?></span></div>
                <?php endif; ?>
                <div class="party-row"><span class="party-lbl">تلفن پشتیبانی:</span><span class="party-val"><?php echo !empty($seller['phone']) ? woo_factor_fa_digits($seller['phone']) : '-'; ?></span></div>
                <div class="party-row"><span class="party-lbl">نشانی:</span><span class="party-val"><?php echo !empty($seller['address']) ? esc_html($seller['address']) : '-'; ?></span></div>
            </div>

            <div class="party-card">
                <div class="party-title">
                    <span>👤 مشخصات خریدار</span>
                    <span style="font-size: 10px; color: #64748b;">تحویل‌گیرنده</span>
                </div>
                <div class="party-row"><span class="party-lbl">نام شخص / شرکت:</span><span class="party-val"><?php echo esc_html($buyer['name']); ?></span></div>
                <?php if (!empty($buyer['national_id'])): ?>
                    <div class="party-row"><span class="party-lbl">کد ملی / شناسه:</span><span class="party-val"><?php echo woo_factor_fa_digits($buyer['national_id']); ?></span></div>
                <?php endif; ?>
                <div class="party-row"><span class="party-lbl">شماره تماس:</span><span class="party-val"><?php echo !empty($buyer['phone']) ? woo_factor_fa_digits($buyer['phone']) : '-'; ?></span></div>
                <?php if (!empty($buyer['postcode'])): ?>
                    <div class="party-row"><span class="party-lbl">کد پستی:</span><span class="party-val"><?php echo woo_factor_fa_digits($buyer['postcode']); ?></span></div>
                <?php endif; ?>
                <div class="party-row"><span class="party-lbl">نشانی تحویل:</span><span class="party-val"><?php echo esc_html($buyer['full_address']); ?></span></div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="comm-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 44%; text-align: right; padding-right: 12px;">شرح کالا یا خدمات</th>
                    <th style="width: 7%;">تعداد</th>
                    <th style="width: 14%;">مبلغ واحد (<?php echo esc_html($currency); ?>)</th>
                    <?php if (!empty($data['show_discount_column'])): ?>
                        <th style="width: 12%;">تخفیف</th>
                    <?php endif; ?>
                    <th style="width: 18%;">مبلغ نهایی (<?php echo esc_html($currency); ?>)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo woo_factor_fa_digits($item['index']); ?></td>
                        <td class="align-r">
                            <div class="thumb-box">
                                <?php if (!empty($data['show_product_image']) && !empty($item['thumbnail_url'])): ?>
                                    <img src="<?php echo esc_url($item['thumbnail_url']); ?>" alt="" class="thumb-img">
                                <?php endif; ?>
                                <div>
                                    <strong style="color: #0f172a;"><?php echo esc_html($item['title']); ?></strong>
                                    <?php if (!empty($data['show_sku']) && !empty($item['sku']) && $item['sku'] !== '-'): ?>
                                        <div style="font-size: 9px; color: #64748b;">کد کالا: <?php echo esc_html($item['sku']); ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($item['meta'])): ?>
                                        <div style="font-size: 9px; color: #64748b; margin-top: 1px;"><?php echo esc_html($item['meta']); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td style="font-weight: bold;"><?php echo woo_factor_fa_digits($item['qty']); ?></td>
                        <td><?php echo woo_factor_number_format($item['unit_price']); ?></td>
                        <?php if (!empty($data['show_discount_column'])): ?>
                            <td style="color: #ef4444;"><?php echo $item['discount'] > 0 ? woo_factor_number_format($item['discount']) : '-'; ?></td>
                        <?php endif; ?>
                        <td style="font-weight: 800;"><?php echo woo_factor_number_format($item['total']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Summary & Bottom Grid -->
        <div class="summary-grid">
            <div>
                <!-- Payment details -->
                <div class="payment-badge-box">
                    <span>روش پرداخت: <strong><?php echo esc_html($totals['payment_method']); ?></strong></span>
                    <?php if (!empty($totals['transaction_id'])): ?>
                        <span>شناسه تراکنش: <strong><?php echo woo_factor_fa_digits($totals['transaction_id']); ?></strong></span>
                    <?php endif; ?>
                </div>

                <?php if (!empty($data['invoice_terms'])): ?>
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 8px 12px; font-size: 10px; color: #475569; margin-bottom: 10px;">
                        <strong style="color: #0f172a;">شرایط و مقررات فروشگاه:</strong> <?php echo nl2br(esc_html($data['invoice_terms'])); ?>
                    </div>
                <?php endif; ?>

                <!-- Signatures & Verification -->
                <div class="auth-grid">
                    <div class="auth-box">
                        <div class="auth-box-title">مهر و امضای فروشگاه</div>
                        <?php if (!empty($data['show_signature'])): ?>
                            <?php if ($stamp_url): ?>
                                <img src="<?php echo esc_url($stamp_url); ?>" alt="مهر فروشگاه" class="auth-stamp-img">
                            <?php else: ?>
                                <div style="margin-top: 14px; font-weight: bold; color: #64748b; font-size: 10px;"><?php echo esc_html($data['signature_stamp']); ?></div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <div class="auth-box">
                        <div class="auth-box-title">تأیید تحویل خریدار</div>
                        <div style="margin-top: 22px; font-size: 9.5px; color: #94a3b8;">امضا و اثر انگشت</div>
                    </div>

                    <div class="auth-box" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                        <div class="auth-box-title" style="width: 100%;">اعتبارسنجی آنلاین</div>
                        <?php if (!empty($data['show_qrcode']) && !empty($data['qrcode_svg'])): ?>
                            <div><?php echo $data['qrcode_svg']; ?></div>
                        <?php elseif (!empty($data['show_barcode']) && !empty($data['barcode_svg'])): ?>
                            <div><?php echo $data['barcode_svg']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Financial Summary Box -->
            <div class="summary-box">
                <div class="summary-row">
                    <span>جمع کل اقلام:</span>
                    <strong><?php echo woo_factor_number_format($totals['subtotal']); ?> <?php echo esc_html($currency); ?></strong>
                </div>
                <?php if ($totals['discount'] > 0): ?>
                    <div class="summary-row" style="color: #ef4444;">
                        <span>تخفیف کل:</span>
                        <strong><?php echo woo_factor_number_format($totals['discount']); ?>- <?php echo esc_html($currency); ?></strong>
                    </div>
                <?php endif; ?>
                <div class="summary-row">
                    <span>حمل و نقل (<?php echo esc_html($totals['shipping_method']); ?>):</span>
                    <strong><?php echo $totals['shipping'] > 0 ? woo_factor_number_format($totals['shipping']) . ' ' . esc_html($currency) : 'رایگان'; ?></strong>
                </div>
                <?php if ($totals['tax'] > 0): ?>
                    <div class="summary-row">
                        <span>مالیات و عوارض:</span>
                        <strong><?php echo woo_factor_number_format($totals['tax']); ?> <?php echo esc_html($currency); ?></strong>
                    </div>
                <?php endif; ?>
                <div class="summary-row grand">
                    <span>مبلغ قابل پرداخت:</span>
                    <span><?php echo woo_factor_number_format($totals['grand_total']); ?> <?php echo esc_html($currency); ?></span>
                </div>
                <?php if (!empty($totals['grand_total_words'])): ?>
                    <div style="padding: 8px 12px; font-size: 9.5px; color: #64748b; background: #f8fafc; text-align: center;">
                        به حروف: <?php echo esc_html($totals['grand_total_words']); ?> <?php echo esc_html($currency); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer Terms -->
    <div class="footer-terms">
        <span><?php echo esc_html($data['footer_note']); ?></span>
        <span>سامانه فاکتورساز رسمی ووکامرس</span>
    </div>
</div>

</body>
</html>
