<?php
/**
 * Template 2: Modern Minimalist (قالب مدرن و مینیمال فاکتورساز ووفاکتور)
 * Airy, user-friendly, uncluttered, contemporary styling with clean print support.
 */
defined('ABSPATH') || exit;

$brand = woo_factor_normalize_color($data['color'] ?? '', '#0F766E');
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
            color: #1e293b;
            direction: rtl;
            text-align: right;
            font-size: 11px;
            line-height: 1.55;
            padding: 20px 10px;
        }
        .invoice-card {
            position: relative;
            width: 100%;
            max-width: 820px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 24px 28px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-28deg);
            font-size: 58px;
            font-weight: 900;
            color: rgba(15, 118, 110, 0.04);
            border: 5px dashed rgba(15, 118, 110, 0.06);
            padding: 12px 36px;
            border-radius: 12px;
            pointer-events: none;
            user-select: none;
            white-space: nowrap;
            z-index: 0;
        }

        /* Top Accent Bar */
        .top-accent-bar {
            height: 4px;
            background: <?php echo esc_attr($brand); ?>;
            border-radius: 4px 4px 0 0;
            margin: -24px -28px 20px -28px;
        }

        /* Header */
        .header-wrap {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 16px;
            margin-bottom: 18px;
            gap: 16px;
        }
        .brand-box {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .brand-logo img {
            max-height: 48px;
            max-width: 130px;
            object-fit: contain;
            display: block;
        }
        .brand-title {
            font-size: 16px;
            font-weight: 900;
            color: #0f172a;
        }
        .brand-subtitle {
            font-size: 10.5px;
            color: #64748b;
            margin-top: 2px;
        }
        .header-meta {
            text-align: left;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 4px;
        }
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
        }
        .meta-text {
            font-size: 10.5px;
            color: #64748b;
        }

        /* Parties Grid */
        .parties-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 18px;
        }
        .party-card {
            background: #fbfcfe;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 14px;
        }
        .party-header {
            font-size: 11.5px;
            font-weight: 800;
            color: <?php echo esc_attr($brand); ?>;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 6px;
        }
        .party-row {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            margin-bottom: 4px;
            color: #334155;
        }
        .party-row span.k {
            color: #64748b;
        }
        .party-row span.v {
            font-weight: 600;
            color: #0f172a;
        }

        /* Items Table */
        .items-box {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 18px;
        }
        .m-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
        }
        .m-table th {
            background: #f8fafc;
            color: #334155;
            font-weight: 700;
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            text-align: center;
        }
        .m-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #f8fafc;
            vertical-align: middle;
            text-align: center;
        }
        .m-table tr:last-child td {
            border-bottom: none;
        }
        .m-table td.desc-cell {
            text-align: right;
        }
        .m-table td.amount-cell {
            text-align: left;
            font-weight: 600;
            font-feature-settings: "tnum";
        }
        .product-meta-pill {
            font-size: 9px;
            color: #64748b;
            margin-top: 2px;
        }

        /* Totals & Notes */
        .bottom-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }
        .notes-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 10px;
        }
        .terms-pill {
            background: #fbfcfe;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 9.5px;
            color: #475569;
            line-height: 1.5;
        }
        .words-pill {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 10px;
            font-weight: bold;
        }
        .totals-card {
            background: #fbfcfe;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 16px;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            font-size: 10.5px;
            margin-bottom: 6px;
            color: #475569;
        }
        .totals-row.final-row {
            border-top: 1px solid #cbd5e1;
            padding-top: 8px;
            margin-top: 8px;
            margin-bottom: 0;
            font-size: 12.5px;
            font-weight: 900;
            color: <?php echo esc_attr($brand); ?>;
        }

        /* Footer & Signatures */
        .footer-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 14px;
            border-top: 1px solid #f1f5f9;
            padding-top: 14px;
            align-items: center;
        }
        .sig-box {
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            height: 85px;
            padding: 6px 10px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            background: #fbfcfe;
        }
        .sig-box .title {
            font-size: 10px;
            font-weight: bold;
            color: #64748b;
        }
        .sig-box img {
            max-height: 48px;
            max-width: 110px;
            object-fit: contain;
        }
        .barcode-box {
            text-align: center;
        }
        .barcode-text {
            font-size: 9px;
            color: #64748b;
            margin-top: 2px;
        }
        .footer-tagline {
            text-align: center;
            margin-top: 12px;
            font-size: 9.5px;
            color: #94a3b8;
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
            .invoice-card {
                border: 1px solid #cbd5e1 !important;
                box-shadow: none !important;
                padding: 12px 16px !important;
                margin: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                border-radius: 0 !important;
            }
            .watermark {
                display: none !important;
            }
            tr, td, th {
                page-break-inside: avoid !important;
            }
            .parties-row, .bottom-grid, .footer-grid {
                page-break-inside: avoid !important;
            }
        }
    </style>
</head>
<body>

<div class="invoice-card">
    <div class="top-accent-bar"></div>

    <?php if (!empty($data['show_watermark']) && !empty($data['watermark_text'])): ?>
        <div class="watermark"><?php echo esc_html($data['watermark_text']); ?></div>
    <?php endif; ?>

    <!-- Header -->
    <div class="header-wrap">
        <div class="brand-box">
            <?php if ($logo_url): ?>
                <div class="brand-logo"><img src="<?php echo esc_url($logo_url); ?>" alt="لوگو"></div>
            <?php endif; ?>
            <div>
                <div class="brand-title"><?php echo esc_html($seller['name']); ?></div>
                <div class="brand-subtitle">فاکتور خرید سفارش رسمی #<?php echo woo_factor_fa_digits($data['order_number']); ?></div>
            </div>
        </div>

        <div class="header-meta">
            <div class="status-pill">
                <span>●</span> <?php echo esc_html($data['status_name']); ?>
            </div>
            <div class="meta-text">شماره فاکتور: <strong><?php echo woo_factor_fa_digits($data['invoice_number']); ?></strong></div>
            <div class="meta-text">تاریخ: <?php echo woo_factor_fa_digits($data['jalali_date']); ?></div>
        </div>
    </div>

    <!-- Parties -->
    <div class="parties-row">
        <!-- Seller -->
        <div class="party-card">
            <div class="party-header">
                <span>🏢 مشخصات فروشگاه</span>
            </div>
            <div class="party-row">
                <span class="k">فروشنده:</span>
                <span class="v"><?php echo esc_html($seller['name']); ?></span>
            </div>
            <?php if (!empty($seller['national_id'])): ?>
                <div class="party-row">
                    <span class="k">شناسه / کد ملی:</span>
                    <span class="v"><?php echo woo_factor_fa_digits($seller['national_id']); ?></span>
                </div>
            <?php endif; ?>
            <?php if (!empty($seller['phone'])): ?>
                <div class="party-row">
                    <span class="k">تلفن تماس:</span>
                    <span class="v"><?php echo woo_factor_fa_digits($seller['phone']); ?></span>
                </div>
            <?php endif; ?>
            <div class="party-row">
                <span class="k">نشانی:</span>
                <span class="v" style="font-size: 9px;"><?php echo esc_html($seller['address']); ?></span>
            </div>
        </div>

        <!-- Buyer -->
        <div class="party-card">
            <div class="party-header">
                <span>👤 مشخصات خریدار</span>
            </div>
            <div class="party-row">
                <span class="k">نام خریدار:</span>
                <span class="v"><?php echo esc_html($buyer['name']); ?></span>
            </div>
            <?php if (!empty($buyer['national_id'])): ?>
                <div class="party-row">
                    <span class="k">کد ملی / شناسه:</span>
                    <span class="v"><?php echo woo_factor_fa_digits($buyer['national_id']); ?></span>
                </div>
            <?php endif; ?>
            <?php if (!empty($buyer['phone'])): ?>
                <div class="party-row">
                    <span class="k">شماره تماس:</span>
                    <span class="v"><?php echo woo_factor_fa_digits($buyer['phone']); ?></span>
                </div>
            <?php endif; ?>
            <div class="party-row">
                <span class="k">نشانی تحویل:</span>
                <span class="v" style="font-size: 9px;"><?php echo esc_html($buyer['address']); ?></span>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="items-box">
        <table class="m-table">
            <thead>
                <tr>
                    <th style="width: 6%;">ردیف</th>
                    <?php if (!empty($data['show_sku'])): ?>
                        <th style="width: 12%;">کد کالا</th>
                    <?php endif; ?>
                    <th style="width: 40%; text-align: right;">شرح محصول</th>
                    <th style="width: 8%;">تعداد</th>
                    <th style="width: 15%; text-align: left;">قیمت واحد</th>
                    <?php if (!empty($data['show_discount_column'])): ?>
                        <th style="width: 11%; text-align: left;">تخفیف</th>
                    <?php endif; ?>
                    <th style="width: 16%; text-align: left;">جمع کل</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo woo_factor_fa_digits($item['index']); ?></td>
                        <?php if (!empty($data['show_sku'])): ?>
                            <td style="font-size: 9px; color: #64748b;"><?php echo esc_html($item['sku']); ?></td>
                        <?php endif; ?>
                        <td class="desc-cell">
                            <strong><?php echo esc_html($item['title']); ?></strong>
                            <?php if (!empty($item['meta'])): ?>
                                <div class="product-meta-pill"><?php echo esc_html($item['meta']); ?></div>
                            <?php endif; ?>
                        </td>
                        <td style="font-weight: bold;"><?php echo woo_factor_fa_digits($item['qty']); ?></td>
                        <td class="amount-cell"><?php echo woo_factor_number_format($item['unit_price']); ?> <?php echo esc_html($currency); ?></td>
                        <?php if (!empty($data['show_discount_column'])): ?>
                            <td class="amount-cell" style="color: #dc2626;"><?php echo $item['discount'] > 0 ? woo_factor_number_format($item['discount']) : '-'; ?></td>
                        <?php endif; ?>
                        <td class="amount-cell" style="font-weight: bold; color: #0f172a;"><?php echo woo_factor_number_format($item['total']); ?> <?php echo esc_html($currency); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Totals & Notes -->
    <div class="bottom-grid">
        <div class="notes-card">
            <?php if (!empty($totals['grand_total_words'])): ?>
                <div class="words-pill">
                    مبلغ به حروف: <?php echo esc_html($totals['grand_total_words']); ?> <?php echo esc_html($currency); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($data['invoice_terms'])): ?>
                <div class="terms-pill">
                    <strong>شرایط و قوانین:</strong> <?php echo nl2br(esc_html($data['invoice_terms'])); ?>
                </div>
            <?php endif; ?>

            <div style="font-size: 9.5px; color: #64748b;">
                روش پرداخت: <strong><?php echo esc_html($totals['payment_method']); ?></strong>
                <?php if (!empty($totals['transaction_id'])): ?>
                    • شماره پیگیری: <?php echo woo_factor_fa_digits($totals['transaction_id']); ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="totals-card">
            <div class="totals-row">
                <span>جمع کل اقلام:</span>
                <strong><?php echo woo_factor_number_format($totals['subtotal']); ?> <?php echo esc_html($currency); ?></strong>
            </div>
            <?php if (!empty($totals['discount']) && $totals['discount'] > 0): ?>
                <div class="totals-row">
                    <span>تخفیف سفارش:</span>
                    <strong style="color: #dc2626;"><?php echo woo_factor_number_format($totals['discount']); ?>- <?php echo esc_html($currency); ?></strong>
                </div>
            <?php endif; ?>
            <div class="totals-row">
                <span>هزینه حمل و نقل:</span>
                <strong><?php echo $totals['shipping'] > 0 ? woo_factor_number_format($totals['shipping']) . ' ' . esc_html($currency) : 'رایگان'; ?></strong>
            </div>
            <?php if (!empty($totals['tax']) && $totals['tax'] > 0): ?>
                <div class="totals-row">
                    <span>مالیات ارزش افزوده:</span>
                    <strong><?php echo woo_factor_number_format($totals['tax']); ?> <?php echo esc_html($currency); ?></strong>
                </div>
            <?php endif; ?>
            <div class="totals-row final-row">
                <span>مبلغ قابل پرداخت:</span>
                <strong><?php echo woo_factor_number_format($totals['grand_total']); ?> <?php echo esc_html($currency); ?></strong>
            </div>
        </div>
    </div>

    <!-- Signatures & Barcode -->
    <div class="footer-grid">
        <div class="sig-box">
            <div class="title">مهر و امضای فروشگاه</div>
            <?php if (!empty($data['show_signature'])): ?>
                <?php if ($stamp_url): ?>
                    <img src="<?php echo esc_url($stamp_url); ?>" alt="مهر فروشگاه">
                <?php else: ?>
                    <span style="font-size: 9px; color: #94a3b8; margin-top: 10px;"><?php echo esc_html($data['signature_stamp']); ?></span>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="sig-box">
            <div class="title">امضای خریدار</div>
            <span style="font-size: 9px; color: #94a3b8; margin-top: 18px;">کالا سالم تحویل داده شد.</span>
        </div>

        <div class="barcode-box">
            <?php if (!empty($data['show_barcode']) && !empty($data['barcode_svg'])): ?>
                <div><?php echo $data['barcode_svg']; ?></div>
                <div class="barcode-text">#<?php echo woo_factor_fa_digits($data['order_number']); ?></div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer Note -->
    <?php if (!empty($data['footer_note'])): ?>
        <div class="footer-tagline">
            <?php echo esc_html($data['footer_note']); ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
