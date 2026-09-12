<?php
/**
 * Template 1: Classic Official Tax Standard (صورتحساب رسمی استاندارد مالیاتی)
 * Conforms to Article 19 of VAT law with clean, uncluttered, professional layout.
 */
defined('ABSPATH') || exit;

$theme_color = woo_factor_normalize_color($data['color'] ?? '', '#0F766E');
$seller = $data['seller'] ?? [];
$buyer = $data['buyer'] ?? [];
$totals = $data['totals'] ?? [];
$items = $data['items'] ?? [];
$logo_url = !empty($seller['logo_url']) ? $seller['logo_url'] : '';
$stamp_url = !empty($data['stamp_url']) ? $data['stamp_url'] : '';
$currency = $totals['currency'] ?? 'تومان';
$is_legal_buyer = !empty($buyer['company']) || !empty($buyer['economic_id']) || (($buyer['customer_type'] ?? '') === 'legal');
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>صورتحساب رسمی - <?php echo esc_html($data['invoice_number']); ?></title>
    <style>
        @page {
            size: A4 portrait;
            margin: 8mm 10mm;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        body {
            font-family: 'Vazirmatn', Tahoma, 'Segoe UI', sans-serif;
            background: #f8fafc;
            color: #0f172a;
            direction: rtl;
            text-align: right;
            font-size: 10.5px;
            line-height: 1.5;
            padding: 20px 10px;
        }
        .invoice-box {
            position: relative;
            width: 100%;
            max-width: 820px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            padding: 20px 24px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-28deg);
            font-size: 58px;
            font-weight: 900;
            color: rgba(15, 23, 42, 0.04);
            border: 5px dashed rgba(15, 23, 42, 0.06);
            padding: 12px 36px;
            border-radius: 12px;
            pointer-events: none;
            user-select: none;
            white-space: nowrap;
            z-index: 0;
        }

        /* Header */
        .inv-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 14px;
            margin-bottom: 14px;
            gap: 12px;
        }
        .inv-header-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }
        .inv-logo img {
            max-height: 52px;
            max-width: 140px;
            object-fit: contain;
            display: block;
        }
        .inv-shop-title {
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
        }
        .inv-shop-sub {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }
        .inv-header-center {
            text-align: center;
            flex: 1.2;
        }
        .inv-header-center h1 {
            font-size: 15px;
            font-weight: 900;
            color: #0f172a;
            margin-bottom: 3px;
        }
        .inv-header-center h2 {
            font-size: 10.5px;
            font-weight: 600;
            color: <?php echo esc_attr($theme_color); ?>;
        }
        .inv-header-left {
            flex: 0.9;
            text-align: left;
        }
        .inv-meta-badge {
            display: inline-block;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 10px;
            line-height: 1.6;
            text-align: right;
        }
        .inv-meta-row strong {
            color: #0f172a;
        }

        /* Parties: Seller & Buyer */
        .parties-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 14px;
        }
        .party-card {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            background: #fbfcfe;
            overflow: hidden;
        }
        .party-title-bar {
            background: #f1f5f9;
            border-bottom: 1px solid #e2e8f0;
            padding: 5px 10px;
            font-size: 11px;
            font-weight: 800;
            color: #334155;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .entity-pill {
            font-size: 9px;
            font-weight: bold;
            padding: 1px 7px;
            border-radius: 10px;
            background: <?php echo esc_attr($theme_color); ?>;
            color: #ffffff;
        }
        .party-body {
            padding: 8px 10px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            font-size: 10px;
            color: #334155;
        }
        .party-line {
            display: flex;
            justify-content: space-between;
            gap: 6px;
        }
        .party-line span.label {
            color: #64748b;
            flex-shrink: 0;
        }
        .party-line span.val {
            font-weight: 600;
            color: #0f172a;
            text-align: left;
            word-break: break-word;
        }

        /* Items Table */
        .items-table-wrapper {
            margin-bottom: 14px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            overflow: hidden;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            text-align: center;
        }
        .items-table th {
            background: #f8fafc;
            color: #0f172a;
            font-weight: 800;
            padding: 7px 6px;
            border-bottom: 2px solid #cbd5e1;
            border-left: 1px solid #e2e8f0;
            white-space: nowrap;
        }
        .items-table th:last-child {
            border-left: none;
        }
        .items-table td {
            padding: 7px 6px;
            border-bottom: 1px solid #f1f5f9;
            border-left: 1px solid #f1f5f9;
            vertical-align: middle;
            color: #1e293b;
        }
        .items-table td:last-child {
            border-left: none;
        }
        .items-table tr:nth-child(even) td {
            background: #fafbfc;
        }
        .items-table td.title-cell {
            text-align: right;
            padding-right: 8px;
        }
        .items-table td.num-cell {
            text-align: left;
            padding-left: 8px;
            font-feature-settings: "tnum";
        }
        .item-sku {
            font-size: 8.5px;
            color: #64748b;
            margin-top: 2px;
        }

        /* Summary & Totals */
        .totals-section {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 14px;
            margin-bottom: 14px;
        }
        .terms-card {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 12px;
            background: #fbfcfe;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 8px;
        }
        .words-bar {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        .terms-text {
            font-size: 9.5px;
            color: #475569;
            line-height: 1.5;
        }
        .totals-table-card {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            overflow: hidden;
            background: #ffffff;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        .totals-table td {
            padding: 6px 10px;
            border-bottom: 1px solid #f1f5f9;
        }
        .totals-table td.t-lbl {
            color: #475569;
            width: 50%;
        }
        .totals-table td.t-val {
            text-align: left;
            font-weight: bold;
            color: #0f172a;
            font-feature-settings: "tnum";
        }
        .grand-total-row td {
            background: #f8fafc;
            border-top: 2px solid #0f172a;
            border-bottom: none;
            padding: 8px 10px;
            font-size: 11.5px;
        }
        .grand-total-row td.t-val {
            color: <?php echo esc_attr($theme_color); ?>;
            font-size: 12.5px;
            font-weight: 900;
        }

        /* Signatures & Barcode */
        .bottom-section {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
            border-top: 1px solid #e2e8f0;
            padding-top: 12px;
            align-items: center;
        }
        .sig-card {
            border: 1px dashed #cbd5e1;
            border-radius: 6px;
            height: 90px;
            padding: 6px 10px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            background: #fbfcfe;
        }
        .sig-title {
            font-size: 10px;
            font-weight: bold;
            color: #475569;
        }
        .sig-img {
            max-height: 52px;
            max-width: 120px;
            object-fit: contain;
        }
        .barcode-card {
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 3px;
        }
        .barcode-num {
            font-size: 9px;
            color: #64748b;
            letter-spacing: 1px;
        }
        .footer-note {
            text-align: center;
            margin-top: 10px;
            padding-top: 6px;
            font-size: 9.5px;
            color: #64748b;
            border-top: 1px solid #f1f5f9;
        }

        /* Print Media Styles */
        @media print {
            html, body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                color: #000000 !important;
                font-size: 9.5pt !important;
            }
            .no-print, .woo-factor-toolbar {
                display: none !important;
                height: 0 !important;
                visibility: hidden !important;
            }
            .invoice-box {
                border: 1px solid #94a3b8 !important;
                box-shadow: none !important;
                padding: 10px 14px !important;
                margin: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            .watermark {
                display: none !important;
            }
            tr, td, th {
                page-break-inside: avoid !important;
            }
            .parties-grid, .totals-section, .bottom-section {
                page-break-inside: avoid !important;
            }
        }
    </style>
</head>
<body>

<div class="invoice-box">
    <?php if (!empty($data['show_watermark']) && !empty($data['watermark_text'])): ?>
        <div class="watermark"><?php echo esc_html($data['watermark_text']); ?></div>
    <?php endif; ?>

    <!-- Header Section -->
    <div class="inv-header">
        <div class="inv-header-right">
            <?php if ($logo_url): ?>
                <div class="inv-logo"><img src="<?php echo esc_url($logo_url); ?>" alt="لوگو"></div>
            <?php endif; ?>
            <div>
                <div class="inv-shop-title"><?php echo esc_html($seller['name']); ?></div>
                <?php if (!empty($seller['phone'])): ?>
                    <div class="inv-shop-sub">تلفن: <?php echo woo_factor_fa_digits($seller['phone']); ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="inv-header-center">
            <h1>صورتحساب رسمی فروش کالا و خدمات</h1>
            <h2>(ماده ۱۹ قانون مالیات بر ارزش افزوده سازمان امور مالیاتی کشور)</h2>
        </div>

        <div class="inv-header-left">
            <div class="inv-meta-badge">
                <div class="inv-meta-row">شماره فاکتور: <strong><?php echo woo_factor_fa_digits($data['invoice_number']); ?></strong></div>
                <div class="inv-meta-row">تاریخ صدور: <strong><?php echo woo_factor_fa_digits($data['jalali_date']); ?></strong></div>
                <div class="inv-meta-row">وضعیت سفارش: <strong><?php echo esc_html($data['status_name']); ?></strong></div>
            </div>
        </div>
    </div>

    <!-- Parties Grid -->
    <div class="parties-grid">
        <!-- Seller -->
        <div class="party-card">
            <div class="party-title-bar">
                <span>الف) مشخصات فروشنده (مودی)</span>
                <span style="color: #64748b; font-size: 9.5px;">شناسه/کد اقتصادی</span>
            </div>
            <div class="party-body">
                <div class="party-line">
                    <span class="label">فروشنده:</span>
                    <span class="val"><?php echo esc_html($seller['name']); ?></span>
                </div>
                <div class="party-line">
                    <span class="label">شناسه / کد ملی:</span>
                    <span class="val"><?php echo !empty($seller['national_id']) ? woo_factor_fa_digits($seller['national_id']) : '-'; ?></span>
                </div>
                <div class="party-line">
                    <span class="label">کد اقتصادی:</span>
                    <span class="val"><?php echo !empty($seller['economic_code']) ? woo_factor_fa_digits($seller['economic_code']) : '-'; ?></span>
                </div>
                <div class="party-line">
                    <span class="label">شماره ثبت / مجوز:</span>
                    <span class="val"><?php echo !empty($seller['registration_no']) ? woo_factor_fa_digits($seller['registration_no']) : '-'; ?></span>
                </div>
                <div class="party-line">
                    <span class="label">کد پستی:</span>
                    <span class="val"><?php echo !empty($seller['postal_code']) ? woo_factor_fa_digits($seller['postal_code']) : '-'; ?></span>
                </div>
                <div class="party-line">
                    <span class="label">نشانی:</span>
                    <span class="val" style="font-size: 9.5px;"><?php echo esc_html($seller['address']); ?></span>
                </div>
            </div>
        </div>

        <!-- Buyer -->
        <div class="party-card">
            <div class="party-title-bar">
                <span>ب) مشخصات خریدار</span>
                <span class="entity-pill"><?php echo $is_legal_buyer ? 'شخص حقوقی (شرکت)' : 'شخص حقیقی'; ?></span>
            </div>
            <div class="party-body">
                <div class="party-line">
                    <span class="label"><?php echo $is_legal_buyer ? 'نام شرکت / خریدار:' : 'نام و نام خانوادگی:'; ?></span>
                    <span class="val"><?php echo esc_html($buyer['name']); ?></span>
                </div>
                <div class="party-line">
                    <span class="label"><?php echo $is_legal_buyer ? 'شناسه ملی شرکت:' : 'کد ملی خریدار:'; ?></span>
                    <span class="val"><?php echo !empty($buyer['national_id']) ? woo_factor_fa_digits($buyer['national_id']) : '-'; ?></span>
                </div>
                <div class="party-line">
                    <span class="label">کد اقتصادی خریدار:</span>
                    <span class="val"><?php echo !empty($buyer['economic_id']) ? woo_factor_fa_digits($buyer['economic_id']) : '-'; ?></span>
                </div>
                <div class="party-line">
                    <span class="label">شماره ثبت:</span>
                    <span class="val"><?php echo !empty($buyer['registration_no']) ? woo_factor_fa_digits($buyer['registration_no']) : '-'; ?></span>
                </div>
                <div class="party-line">
                    <span class="label">شماره تماس / همراه:</span>
                    <span class="val"><?php echo !empty($buyer['phone']) ? woo_factor_fa_digits($buyer['phone']) : '-'; ?></span>
                </div>
                <div class="party-line">
                    <span class="label">نشانی تحویل:</span>
                    <span class="val" style="font-size: 9.5px;"><?php echo esc_html($buyer['address']); ?> <?php if (!empty($buyer['postcode'])): ?>- کدپستی: <?php echo woo_factor_fa_digits($buyer['postcode']); ?><?php endif; ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="items-table-wrapper">
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;">ردیف</th>
                    <?php if (!empty($data['show_sku'])): ?>
                        <th style="width: 12%;">شناسه (SKU)</th>
                    <?php endif; ?>
                    <th style="width: 38%; text-align: right; padding-right: 8px;">شرح کالا یا خدمات</th>
                    <th style="width: 8%;">تعداد</th>
                    <th style="width: 14%; text-align: left; padding-left: 8px;">مبلغ واحد (<?php echo esc_html($currency); ?>)</th>
                    <?php if (!empty($data['show_discount_column'])): ?>
                        <th style="width: 11%; text-align: left; padding-left: 8px;">تخفیف</th>
                    <?php endif; ?>
                    <?php if (!empty($data['show_tax_column'])): ?>
                        <th style="width: 11%; text-align: left; padding-left: 8px;">مالیات (۱۰٪)</th>
                    <?php endif; ?>
                    <th style="width: 16%; text-align: left; padding-left: 8px;">مبلغ کل (<?php echo esc_html($currency); ?>)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo woo_factor_fa_digits($item['index']); ?></td>
                        <?php if (!empty($data['show_sku'])): ?>
                            <td style="font-size: 9px;"><?php echo esc_html($item['sku']); ?></td>
                        <?php endif; ?>
                        <td class="title-cell">
                            <strong><?php echo esc_html($item['title']); ?></strong>
                            <?php if (!empty($item['meta'])): ?>
                                <div class="item-sku"><?php echo esc_html($item['meta']); ?></div>
                            <?php endif; ?>
                        </td>
                        <td style="font-weight: bold;"><?php echo woo_factor_fa_digits($item['qty']); ?></td>
                        <td class="num-cell"><?php echo woo_factor_number_format($item['unit_price']); ?></td>
                        <?php if (!empty($data['show_discount_column'])): ?>
                            <td class="num-cell" style="color: #dc2626;"><?php echo $item['discount'] > 0 ? woo_factor_number_format($item['discount']) : '۰'; ?></td>
                        <?php endif; ?>
                        <?php if (!empty($data['show_tax_column'])): ?>
                            <td class="num-cell"><?php echo $item['tax'] > 0 ? woo_factor_number_format($item['tax']) : '۰'; ?></td>
                        <?php endif; ?>
                        <td class="num-cell" style="font-weight: bold;"><?php echo woo_factor_number_format($item['total']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Totals & Notes Section -->
    <div class="totals-section">
        <!-- Left: Words, Notes & Terms -->
        <div class="terms-card">
            <?php if (!empty($totals['grand_total_words'])): ?>
                <div class="words-bar">
                    مبلغ به حروف: <?php echo esc_html($totals['grand_total_words']); ?> <?php echo esc_html($currency); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($data['invoice_terms'])): ?>
                <div class="terms-text">
                    <strong>شرایط و ضوابط:</strong> <?php echo nl2br(esc_html($data['invoice_terms'])); ?>
                </div>
            <?php endif; ?>

            <div style="font-size: 9.5px; color: #64748b;">
                روش پرداخت: <strong><?php echo esc_html($totals['payment_method']); ?></strong>
                <?php if (!empty($totals['transaction_id'])): ?>
                    • شناسه پیگیری پرداخت: <strong><?php echo woo_factor_fa_digits($totals['transaction_id']); ?></strong>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right: Calculated Summary -->
        <div class="totals-table-card">
            <table class="totals-table">
                <tr>
                    <td class="t-lbl">جمع کل اقلام:</td>
                    <td class="t-val"><?php echo woo_factor_number_format($totals['subtotal']); ?> <?php echo esc_html($currency); ?></td>
                </tr>
                <?php if (!empty($totals['discount']) && $totals['discount'] > 0): ?>
                    <tr>
                        <td class="t-lbl">تخفیف کل سفارش:</td>
                        <td class="t-val" style="color: #dc2626;"><?php echo woo_factor_number_format($totals['discount']); ?>- <?php echo esc_html($currency); ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td class="t-lbl">هزینه حمل و نقل / پست:</td>
                    <td class="t-val"><?php echo $totals['shipping'] > 0 ? woo_factor_number_format($totals['shipping']) . ' ' . esc_html($currency) : 'رایگان'; ?></td>
                </tr>
                <?php if (!empty($totals['tax']) && $totals['tax'] > 0): ?>
                    <tr>
                        <td class="t-lbl">مالیات و عوارض ارزش افزوده:</td>
                        <td class="t-val"><?php echo woo_factor_number_format($totals['tax']); ?> <?php echo esc_html($currency); ?></td>
                    </tr>
                <?php endif; ?>
                <tr class="grand-total-row">
                    <td class="t-lbl"><strong>مبلغ کل قابل پرداخت:</strong></td>
                    <td class="t-val"><?php echo woo_factor_number_format($totals['grand_total']); ?> <?php echo esc_html($currency); ?></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Signatures & Barcode Section -->
    <div class="bottom-section">
        <!-- Seller Stamp -->
        <div class="sig-card">
            <div class="sig-title">مهر و امضای فروشنده</div>
            <?php if (!empty($data['show_signature'])): ?>
                <?php if ($stamp_url): ?>
                    <img src="<?php echo esc_url($stamp_url); ?>" alt="مهر فروشگاه" class="sig-img">
                <?php else: ?>
                    <div style="color: #94a3b8; font-size: 9px; margin-top: 8px;"><?php echo esc_html($data['signature_stamp']); ?></div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Buyer Sig -->
        <div class="sig-card">
            <div class="sig-title">امضای خریدار / تحویل‌گیرنده</div>
            <div style="color: #94a3b8; font-size: 9px; margin-top: 18px;">کالاها و خدمات فوق سالم تحویل گرفته شد.</div>
        </div>

        <!-- Barcode -->
        <div class="barcode-card">
            <div style="font-size: 9.5px; font-weight: bold; color: #475569;">بارکد رهگیری سفارش</div>
            <?php if (!empty($data['show_barcode']) && !empty($data['barcode_svg'])): ?>
                <div style="display: inline-block; margin-top: 2px;"><?php echo $data['barcode_svg']; ?></div>
                <div class="barcode-num">#<?php echo woo_factor_fa_digits($data['order_number']); ?></div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer Note -->
    <?php if (!empty($data['footer_note'])): ?>
        <div class="footer-note">
            <?php echo esc_html($data['footer_note']); ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
