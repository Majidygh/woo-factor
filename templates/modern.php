<?php
/**
 * Template 2: Modern Minimalist (قالب مدرن، شیک و مینیمال فاکتورساز ووفاکتور)
 * Inspired by modern fintech minimalist aesthetic with perfect print support.
 */
defined('ABSPATH') || exit;

$brand = woo_factor_normalize_color($data['color'] ?? '', '#0284C7');
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
    <meta name="robots" content="noindex, nofollow">
    <title>فاکتور سفارش - <?php echo esc_html($data['invoice_number']); ?></title>
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
            font-size: 11px;
            line-height: 1.5;
            padding: 20px 10px;
        }
        .invoice-card {
            position: relative;
            width: 100%;
            max-width: 820px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px 26px;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);
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

        /* Modern Top Header */
        .mod-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
            gap: 16px;
        }
        .mod-header-right {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .mod-brand-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .mod-logo img {
            max-height: 48px;
            max-width: 130px;
            object-fit: contain;
            display: block;
        }
        .mod-logo-placeholder {
            width: 44px;
            height: 44px;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .mod-store-name {
            font-size: 16px;
            font-weight: 900;
            color: #0f172a;
        }
        .mod-title {
            font-size: 26px;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: -0.5px;
            margin-top: 4px;
        }
        .mod-header-left {
            text-align: left;
            font-size: 10.5px;
            color: #475569;
            display: flex;
            flex-direction: column;
            gap: 3px;
            align-items: flex-end;
        }
        .mod-contact-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            direction: ltr;
        }

        /* 2-Column Metadata Rounded Cards */
        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 14px;
        }
        .meta-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 10.5px;
        }
        .meta-lbl {
            color: #64748b;
        }
        .meta-val {
            font-weight: 800;
            color: #0f172a;
        }

        /* Parties: Symmetrical Modern Cards */
        .parties-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
        }
        .party-card {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 14px;
            background: #ffffff;
        }
        .party-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 6px;
        }
        .party-badge-seller {
            background: #0f766e;
            color: #ffffff;
            font-size: 10.5px;
            font-weight: 800;
            padding: 3px 12px;
            border-radius: 14px;
        }
        .party-badge-buyer {
            background: <?php echo esc_attr($brand); ?>;
            color: #ffffff;
            font-size: 10.5px;
            font-weight: 800;
            padding: 3px 12px;
            border-radius: 14px;
        }
        .party-content {
            display: flex;
            flex-direction: column;
            gap: 5px;
            font-size: 10.5px;
        }
        .party-line {
            display: flex;
            gap: 4px;
        }
        .p-lbl {
            color: #64748b;
            flex-shrink: 0;
        }
        .p-val {
            font-weight: 700;
            color: #0f172a;
            word-break: break-word;
        }

        /* Items Table with Dark Slate Header */
        .items-wrapper {
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 14px;
            background: #ffffff;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
            text-align: center;
        }
        .items-table th {
            background: #0f172a;
            color: #ffffff;
            font-weight: 800;
            padding: 9px 8px;
            white-space: nowrap;
        }
        .items-table td {
            padding: 9px 8px;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
            vertical-align: middle;
        }
        .items-table tr:nth-child(even) td {
            background: #f8fafc;
        }
        .items-table td.title-col {
            text-align: right;
            padding-right: 12px;
            font-weight: 600;
        }
        .items-table td.num-col {
            text-align: left;
            padding-left: 10px;
            font-feature-settings: "tnum";
        }
        .item-sku-tag {
            font-size: 9px;
            color: #64748b;
            margin-top: 2px;
        }

        /* Totals & Bottom Section */
        .bottom-section {
            display: grid;
            grid-template-columns: 1.1fr 1.2fr;
            gap: 16px;
            margin-bottom: 12px;
            align-items: start;
        }
        .sig-box {
            border: 1.5px dashed #cbd5e1;
            border-radius: 8px;
            min-height: 140px;
            padding: 10px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            background: #fbfcfe;
        }
        .sig-title {
            font-size: 10.5px;
            font-weight: 800;
            color: #475569;
        }
        .sig-img {
            max-height: 75px;
            max-width: 140px;
            object-fit: contain;
            margin: auto;
        }
        .totals-card {
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            background: #ffffff;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
        }
        .totals-table td {
            padding: 7px 12px;
            border-bottom: 1px solid #f1f5f9;
        }
        .totals-table td.t-lbl {
            color: #475569;
            width: 50%;
        }
        .totals-table td.t-val {
            text-align: left;
            font-weight: 700;
            color: #0f172a;
            font-feature-settings: "tnum";
        }
        .grand-row td {
            background: <?php echo esc_attr($brand); ?>;
            color: #ffffff !important;
            font-size: 12px;
            font-weight: 900;
            border-bottom: none;
            padding: 9px 12px;
        }
        .grand-row td.t-val {
            color: #ffffff !important;
            font-size: 13px;
            font-weight: 900;
        }

        /* Persian words strip */
        .words-strip {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
            padding: 7px 12px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        /* Bottom Status & Barcode Strip */
        .footer-strip {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            gap: 12px;
            flex-wrap: wrap;
        }
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
            padding: 4px 12px;
            border-radius: 14px;
            font-size: 10px;
            font-weight: 800;
        }
        .status-dot {
            width: 6px;
            height: 6px;
            background: #10b981;
            border-radius: 50%;
        }
        .footer-note-text {
            font-size: 9.5px;
            color: #64748b;
        }

        /* Print Media Styles */
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .invoice-card {
                box-shadow: none !important;
                border: 1.5px solid #cbd5e1 !important;
                border-radius: 0 !important;
                padding: 15px 18px !important;
                max-width: 100% !important;
                width: 100% !important;
                page-break-inside: avoid;
            }
            .no-print {
                display: none !important;
            }
            tr, td, th {
                page-break-inside: avoid !important;
            }
        }
    </style>
</head>
<body>

<div class="invoice-card">
    <?php if (!empty($data['watermark_text'])): ?>
        <div class="watermark"><?php echo esc_html($data['watermark_text']); ?></div>
    <?php endif; ?>

    <!-- Top Header -->
    <header class="mod-header">
        <div class="mod-header-right">
            <div class="mod-brand-row">
                <?php if ($logo_url): ?>
                    <div class="mod-logo">
                        <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($seller['name']); ?>">
                    </div>
                <?php else: ?>
                    <div class="mod-logo-placeholder">🛍️</div>
                <?php endif; ?>
                <span class="mod-store-name"><?php echo esc_html($seller['name']); ?></span>
            </div>
            <h1 class="mod-title">صورتحساب</h1>
        </div>

        <div class="mod-header-left">
            <?php if (!empty($seller['phone'])): ?>
                <div class="mod-contact-item">
                    <span><?php echo woo_factor_fa_digits($seller['phone']); ?></span>
                    <span>📞</span>
                </div>
            <?php endif; ?>
            <?php if (!empty($seller['address'])): ?>
                <div class="mod-contact-item" style="direction: rtl;">
                    <span>📍 <?php echo esc_html($seller['address']); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <!-- 2-Column Metadata Grid -->
    <div class="meta-grid">
        <div class="meta-box">
            <span class="meta-lbl">شماره فاکتور:</span>
            <span class="meta-val"><?php echo woo_factor_fa_digits($data['invoice_number']); ?></span>
        </div>
        <div class="meta-box">
            <span class="meta-lbl">شناسه سفارش:</span>
            <span class="meta-val">#<?php echo woo_factor_fa_digits($data['order_number']); ?></span>
        </div>
        <div class="meta-box">
            <span class="meta-lbl">تاریخ ثبت:</span>
            <span class="meta-val"><?php echo woo_factor_fa_digits($data['jalali_date']); ?></span>
        </div>
        <div class="meta-box">
            <span class="meta-lbl">روش پرداخت:</span>
            <span class="meta-val"><?php echo esc_html($data['payment_method'] ?: 'پرداخت آنلاین'); ?></span>
        </div>
    </div>

    <!-- Parties Grid: Seller & Buyer -->
    <div class="parties-grid">
        <!-- Seller -->
        <div class="party-card">
            <div class="party-top">
                <span class="party-badge-seller">اطلاعات فروشنده</span>
                <?php if (!empty($seller['national_id'])): ?>
                    <span style="font-size: 9.5px; color: #64748b;">شناسه: <?php echo woo_factor_fa_digits($seller['national_id']); ?></span>
                <?php endif; ?>
            </div>
            <div class="party-content">
                <div class="party-line">
                    <span class="p-lbl">فروشگاه:</span>
                    <span class="p-val"><?php echo esc_html($seller['name']); ?></span>
                </div>
                <div class="party-line">
                    <span class="p-lbl">نشانی:</span>
                    <span class="p-val"><?php echo esc_html($seller['address'] ?: '-'); ?></span>
                </div>
                <div class="party-line">
                    <span class="p-lbl">تلفن تماس:</span>
                    <span class="p-val"><?php echo woo_factor_fa_digits($seller['phone'] ?: '-'); ?></span>
                </div>
            </div>
        </div>

        <!-- Buyer -->
        <div class="party-card">
            <div class="party-top">
                <span class="party-badge-buyer">اطلاعات خریدار</span>
                <?php if (!empty($buyer['national_code'])): ?>
                    <span style="font-size: 9.5px; color: #64748b;">کد ملی: <?php echo woo_factor_fa_digits($buyer['national_code']); ?></span>
                <?php endif; ?>
            </div>
            <div class="party-content">
                <div class="party-line">
                    <span class="p-lbl">نام خریدار:</span>
                    <span class="p-val"><?php echo esc_html($buyer['name']); ?></span>
                </div>
                <div class="party-line">
                    <span class="p-lbl">نشانی تحویل:</span>
                    <span class="p-val"><?php echo esc_html($buyer['full_address'] ?: '-'); ?></span>
                </div>
                <div class="party-line">
                    <span class="p-lbl">شماره همراه:</span>
                    <span class="p-val"><?php echo woo_factor_fa_digits($buyer['phone'] ?: '-'); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Table with Slate Header -->
    <div class="items-wrapper">
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 6%;">ردیف</th>
                    <th style="width: 44%;">نام محصول / شرح کالا</th>
                    <th style="width: 8%;">تعداد</th>
                    <th style="width: 16%;">قیمت واحد (<?php echo esc_html($currency); ?>)</th>
                    <th style="width: 10%;">تخفیف</th>
                    <th style="width: 16%;">قیمت کل</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo woo_factor_fa_digits($i++); ?></td>
                        <td class="title-col">
                            <?php echo esc_html($item['title']); ?>
                            <?php if (!empty($item['sku'])): ?>
                                <div class="item-sku-tag">کد: <?php echo woo_factor_fa_digits($item['sku']); ?></div>
                            <?php endif; ?>
                            <?php if (!empty($item['meta'])): ?>
                                <div class="item-sku-tag"><?php echo esc_html($item['meta']); ?></div>
                            <?php endif; ?>
                        </td>
                        <td><?php echo woo_factor_fa_digits($item['qty']); ?></td>
                        <td class="num-col"><?php echo woo_factor_number_format($item['price']); ?></td>
                        <td class="num-col"><?php echo !empty($item['discount']) ? woo_factor_number_format($item['discount']) : '-'; ?></td>
                        <td class="num-col"><?php echo woo_factor_number_format($item['total']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Words Bar -->
    <?php if (!empty($totals['words'])): ?>
        <div class="words-strip">
            <span>مبلغ به حروف:</span>
            <strong><?php echo esc_html($totals['words']); ?> <?php echo esc_html($currency); ?></strong>
        </div>
    <?php endif; ?>

    <!-- Bottom Section: Signature Box & Totals -->
    <div class="bottom-section">
        <!-- Signature & Stamp Box -->
        <div class="sig-box">
            <span class="sig-title">مهر و امضای مجاز</span>
            <?php if ($stamp_url): ?>
                <img src="<?php echo esc_url($stamp_url); ?>" alt="مهر فروشگاه" class="sig-img">
            <?php else: ?>
                <div style="color: #94a3b8; font-size: 9.5px; margin: auto;">محل درج مهر فروشگاه</div>
            <?php endif; ?>
            <span style="font-size: 8.5px; color: #64748b;">با تشکر از حسن انتخاب شما</span>
        </div>

        <!-- Totals Card -->
        <div class="totals-card">
            <table class="totals-table">
                <tr>
                    <td class="t-lbl">جمع کل اقلام:</td>
                    <td class="t-val"><?php echo woo_factor_number_format($totals['subtotal']); ?> <?php echo esc_html($currency); ?></td>
                </tr>
                <?php if ($totals['discount'] > 0): ?>
                    <tr>
                        <td class="t-lbl">تخفیف:</td>
                        <td class="t-val" style="color: #dc2626;"><?php echo woo_factor_number_format($totals['discount']); ?>- <?php echo esc_html($currency); ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($totals['shipping'] > 0): ?>
                    <tr>
                        <td class="t-lbl">هزینه ارسال:</td>
                        <td class="t-val"><?php echo woo_factor_number_format($totals['shipping']); ?> <?php echo esc_html($currency); ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($totals['tax'] > 0): ?>
                    <tr>
                        <td class="t-lbl">مالیات بر ارزش افزوده:</td>
                        <td class="t-val"><?php echo woo_factor_number_format($totals['tax']); ?> <?php echo esc_html($currency); ?></td>
                    </tr>
                <?php endif; ?>
                <tr class="grand-row">
                    <td>مبلغ قابل پرداخت:</td>
                    <td class="t-val"><?php echo woo_factor_number_format($totals['grand_total']); ?> <?php echo esc_html($currency); ?></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Bottom Status & Barcode Strip -->
    <footer class="footer-strip">
        <div style="display: flex; align-items: center; gap: 10px;">
            <div class="status-pill">
                <span class="status-dot"></span>
                <span>پرداخت موفق</span>
            </div>
            <?php if (!empty($data['footer_note'])): ?>
                <span class="footer-note-text"><?php echo esc_html($data['footer_note']); ?></span>
            <?php endif; ?>
        </div>

        <?php if (!empty($data['show_barcode']) && !empty($data['barcode_svg'])): ?>
            <div style="text-align: left;">
                <?php echo $data['barcode_svg']; ?>
                <div style="font-size: 8.5px; color: #64748b; text-align: center; margin-top: 2px;">
                    #<?php echo woo_factor_fa_digits($data['order_number']); ?>
                </div>
            </div>
        <?php endif; ?>
    </footer>
</div>

</body>
</html>
