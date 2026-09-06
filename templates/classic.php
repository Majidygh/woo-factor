<?php
/**
 * Template 1: Classic & Official (Persian Tax Standard) - Fixed Box Fitting & Word Wrap
 */
defined('ABSPATH') || exit;
$logo_url = !empty($data['seller']['logo_url']) ? $data['seller']['logo_url'] : '';
$theme_color = woo_factor_normalize_color($data['color'] ?? '', '#0F766E');
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صورتحساب رسمی فروش کالا و خدمات - <?php echo esc_html($data['invoice_number']); ?></title>
    <style>
        @page { size: A4 portrait; margin: 8mm; }
        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
            -webkit-print-color-adjust: exact !important; 
            print-color-adjust: exact !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
        }
        body {
            font-family: 'Vazirmatn', 'Tahoma', 'Segoe UI', sans-serif;
            background: #f8fafc;
            color: #1e293b;
            direction: rtl;
            text-align: right;
            font-size: 11px;
            line-height: 1.4;
            padding: 12px;
        }
        .invoice-box {
            width: 100%;
            max-width: 820px;
            margin: 0 auto;
            background: #ffffff;
            border: 2px solid #1e293b;
            padding: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .table-fixed {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            white-space: normal !important;
        }
        td, th, p, span, strong, div {
            white-space: normal !important;
            overflow-wrap: anywhere !important;
            word-wrap: break-word !important;
            word-break: break-word !important;
            max-width: 100%;
        }
        img, svg { max-width: 100%; height: auto; }
        .header-table {
            margin-bottom: 8px;
            border-bottom: 2px solid #1e293b;
            padding-bottom: 8px;
        }
        .header-table td { vertical-align: middle; }
        .logo-container {
            width: 100%;
            max-height: 55px;
            display: flex;
            align-items: center;
        }
        .logo-img {
            max-height: 50px;
            max-width: 140px;
            object-fit: contain;
            display: block;
        }
        .logo-placeholder {
            border: 1.5px dashed #94a3b8;
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 10.5px;
            color: #475569;
            font-weight: bold;
            display: inline-block;
            background: #f8fafc;
        }
        .header-center {
            text-align: center;
        }
        .header-center h1 {
            font-size: 14.5px;
            font-weight: 900;
            color: #0f172a;
            margin-bottom: 2px;
        }
        .header-center h2 {
            font-size: 12px;
            font-weight: 700;
            color: <?php echo esc_attr($theme_color); ?>;
        }
        .meta-box {
            font-size: 10px;
            line-height: 1.6;
            background: #f1f5f9;
            padding: 6px 8px;
            border-radius: 4px;
            border: 1px solid #cbd5e1;
            text-align: right;
        }
        .section-bar {
            background: #1e293b;
            color: #ffffff;
            font-size: 11px;
            font-weight: bold;
            padding: 3px 8px;
            margin-top: 6px;
            margin-bottom: 4px;
            border-radius: 2px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 6px;
            font-size: 10.5px;
        }
        .data-table td {
            border: 1px solid #94a3b8;
            padding: 5px 6px;
            vertical-align: middle;
            white-space: normal;
        }
        .data-table .label {
            background: #f8fafc;
            font-weight: bold;
            color: #334155;
            width: 15%;
        }
        .data-table .val {
            width: 35%;
            color: #0f172a;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 6px;
            margin-bottom: 6px;
            font-size: 10.5px;
        }
        .items-table th {
            background: #e2e8f0;
            color: #0f172a;
            border: 1px solid #64748b;
            padding: 6px 4px;
            font-weight: bold;
            text-align: center;
        }
        .items-table td {
            border: 1px solid #94a3b8;
            padding: 5px 4px;
            text-align: center;
            vertical-align: middle;
            white-space: normal;
        }
        .items-table td.align-right {
            text-align: right;
            padding-right: 6px;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 4px;
            font-size: 10.5px;
        }
        .totals-table td {
            border: 1px solid #94a3b8;
            padding: 5px 6px;
            white-space: normal;
        }
        .grand-row {
            background: #f1f5f9;
            font-weight: 900;
            font-size: 11px;
            color: #0f172a;
        }
        .footer-signatures {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
            padding: 0 30px 5px;
            font-size: 10.5px;
            font-weight: bold;
        }
        .note-box {
            margin-top: 6px;
            padding: 6px 8px;
            background: #f8fafc;
            border-right: 3px solid <?php echo esc_attr($theme_color); ?>;
            font-size: 10px;
            color: #475569;
            border: 1px solid #e2e8f0;
            border-right-width: 3px;
        }
        .actions-bar {
            max-width: 820px;
            margin: 0 auto 10px;
            display: flex;
            justify-content: flex-end;
        }
        .btn-print {
            background: <?php echo esc_attr($theme_color); ?>;
            color: #fff;
            padding: 7px 18px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 12px;
            font-family: inherit;
            font-weight: bold;
        }

        /* Responsive Mobile Layout */
        @media screen and (max-width: 768px) {
            body { padding: 6px; }
            .invoice-box { padding: 8px; }
            .header-table td {
                display: block;
                width: 100% !important;
                text-align: center !important;
                margin-bottom: 6px;
            }
            .logo-container { justify-content: center; }
            .meta-box { text-align: center; }
            .data-table .label { width: 32%; }
            .data-table .val { width: 68%; }
            .data-table tr { display: flex; flex-wrap: wrap; margin-bottom: -1px; }
            .data-table td { display: block; border-bottom: 1px solid #94a3b8; }
            .items-table th:nth-child(2), .items-table td:nth-child(2) { display: none; } /* Hide SKU on mobile for cleaner fit */
            .items-table th:nth-child(1), .items-table td:nth-child(1) { width: 10%; }
            .items-table th:nth-child(3), .items-table td:nth-child(3) { width: 42%; }
            .items-table th:nth-child(4), .items-table td:nth-child(4) { width: 12%; }
            .items-table th:nth-child(5), .items-table td:nth-child(5) { width: 18%; }
            .items-table th:nth-child(6), .items-table td:nth-child(6) { width: 18%; }
            .totals-table tr { display: flex; flex-direction: column; }
            .totals-table td { display: block; width: 100% !important; }
        }

        @media print {
            body { background: #fff; padding: 0; }
            .invoice-box { border: 1px solid #000; box-shadow: none; padding: 8px; max-width: 100% !important; }
            .actions-bar { display: none !important; }
            .header-table td { display: table-cell !important; }
            .data-table tr { display: table-row !important; }
            .data-table td { display: table-cell !important; }
            .items-table th:nth-child(2), .items-table td:nth-child(2) { display: table-cell !important; }
            .totals-table tr { display: table-row !important; }
            .totals-table td { display: table-cell !important; }
        }
    </style>
</head>
<body>

    <div class="actions-bar">
        <button class="btn-print" onclick="window.print();">🖨️ چاپ فاکتور رسمی</button>
    </div>

    <div class="invoice-box">
        <!-- Header -->
        <table class="table-fixed header-table">
            <tr>
                <td style="width: 25%;">
                    <div class="logo-container">
                        <?php if (!empty($logo_url)): ?>
                            <img src="<?php echo esc_url($logo_url); ?>" class="logo-img" alt="Logo">
                        <?php else: ?>
                            <div class="logo-placeholder">🏷️ <?php echo esc_html($data['seller']['name']); ?></div>
                        <?php endif; ?>
                    </div>
                </td>
                <td style="width: 50%;" class="header-center">
                    <h1>صورتحساب رسمی فروش کالا و خدمات</h1>
                    <h2><?php echo esc_html($data['seller']['name']); ?></h2>
                </td>
                <td style="width: 25%;">
                    <div class="meta-box">
                        <div><strong>شماره فاکتور:</strong> <?php echo woo_factor_fa_digits($data['invoice_number']); ?></div>
                        <div><strong>تاریخ صدور:</strong> <?php echo esc_html($data['jalali_date']); ?></div>
                        <div><strong>وضعیت:</strong> <?php echo esc_html($data['status_name']); ?></div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Barcode -->
        <?php if (!empty($data['barcode_svg'])): ?>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                <span style="font-size: 9.5px; color: #475569;">سامانه یکپارچه فروشگاه</span>
                <div style="text-align: left;">
                    <?php echo $data['barcode_svg']; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Seller Details -->
        <div class="section-bar">مشخصات فروشنده</div>
        <table class="table-fixed data-table">
            <tr>
                <td class="label">فروشنده:</td>
                <td class="val"><?php echo esc_html($data['seller']['name']); ?></td>
                <td class="label">شناسه اقتصادی:</td>
                <td class="val"><?php echo woo_factor_fa_digits($data['seller']['national_id'] ?: '-'); ?></td>
            </tr>
            <tr>
                <td class="label">تلفن:</td>
                <td class="val"><?php echo woo_factor_fa_digits($data['seller']['phone'] ?: '-'); ?></td>
                <td class="label">ایمیل / سایت:</td>
                <td class="val"><?php echo esc_html($data['seller']['website'] ?: $data['seller']['email']); ?></td>
            </tr>
            <tr>
                <td class="label">نشانی فروشنده:</td>
                <td colspan="3" class="val" style="width: 85%;"><?php echo esc_html($data['seller']['address'] ?: '-'); ?></td>
            </tr>
        </table>

        <!-- Buyer Details -->
        <div class="section-bar">مشخصات خریدار</div>
        <table class="table-fixed data-table">
            <tr>
                <td class="label">خریدار:</td>
                <td class="val"><?php echo esc_html($data['buyer']['name']); ?><?php if (!empty($data['buyer']['company'])) echo ' (' . esc_html($data['buyer']['company']) . ')'; ?></td>
                <td class="label">کد ملی / شناسه:</td>
                <td class="val"><?php echo woo_factor_fa_digits($data['buyer']['national_id'] ?: '-'); ?></td>
            </tr>
            <tr>
                <td class="label">شماره همراه:</td>
                <td class="val"><?php echo woo_factor_fa_digits($data['buyer']['phone'] ?: '-'); ?></td>
                <td class="label">کد پستی:</td>
                <td class="val"><?php echo woo_factor_fa_digits($data['buyer']['postcode'] ?: '-'); ?></td>
            </tr>
            <tr>
                <td class="label">نشانی تحویل:</td>
                <td colspan="3" class="val" style="width: 85%;"><?php echo esc_html($data['buyer']['full_address'] ?: '-'); ?></td>
            </tr>
        </table>

        <!-- Products Table -->
        <div class="section-bar">مشخصات کالا یا خدمات مورد معامله</div>
        <table class="table-fixed items-table">
            <thead>
                <tr>
                    <th style="width: 6%;">ردیف</th>
                    <th style="width: 14%;">کد کالا</th>
                    <th style="width: 38%;">شرح کالا یا خدمات</th>
                    <th style="width: 8%;">تعداد</th>
                    <th style="width: 17%;">مبلغ واحد (<?php echo esc_html($data['totals']['currency']); ?>)</th>
                    <th style="width: 17%;">مبلغ کل (<?php echo esc_html($data['totals']['currency']); ?>)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['items'] as $item): ?>
                    <tr>
                        <td><?php echo woo_factor_fa_digits($item['index']); ?></td>
                        <td><?php echo woo_factor_fa_digits($item['sku']); ?></td>
                        <td class="align-right"><strong><?php echo esc_html($item['title']); ?></strong></td>
                        <td><?php echo woo_factor_fa_digits($item['qty']); ?></td>
                        <td><?php echo woo_factor_number_format($item['unit_price']); ?></td>
                        <td><strong><?php echo woo_factor_number_format($item['total']); ?></strong></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Totals & Notes -->
        <table class="table-fixed totals-table">
            <tr>
                <td style="width: 55%; vertical-align: top;" rowspan="5">
                    <div style="margin-bottom: 4px;"><strong>روش پرداخت:</strong> <?php echo esc_html($data['totals']['payment_method']); ?></div>
                    <div style="margin-bottom: 4px;"><strong>روش حمل:</strong> <?php echo esc_html($data['totals']['shipping_method']); ?></div>
                    <?php if (!empty($data['customer_note'])): ?>
                        <div><strong>توضیحات:</strong> <?php echo esc_html($data['customer_note']); ?></div>
                    <?php endif; ?>
                </td>
                <td style="width: 23%; background: #f8fafc; font-weight: bold;">جمع کل اقلام:</td>
                <td style="width: 22%; text-align: left;"><?php echo woo_factor_number_format($data['totals']['subtotal']); ?> <?php echo esc_html($data['totals']['currency']); ?></td>
            </tr>
            <?php if ($data['totals']['discount'] > 0): ?>
            <tr>
                <td style="background: #f8fafc; font-weight: bold; color: #dc2626;">تخفیف کل:</td>
                <td style="text-align: left; color: #dc2626;"><?php echo woo_factor_number_format($data['totals']['discount']); ?>- <?php echo esc_html($data['totals']['currency']); ?></td>
            </tr>
            <?php endif; ?>
            <?php if ($data['totals']['shipping'] > 0): ?>
            <tr>
                <td style="background: #f8fafc; font-weight: bold;">هزینه حمل:</td>
                <td style="text-align: left;"><?php echo woo_factor_number_format($data['totals']['shipping']); ?> <?php echo esc_html($data['totals']['currency']); ?></td>
            </tr>
            <?php endif; ?>
            <?php if ($data['totals']['tax'] > 0): ?>
            <tr>
                <td style="background: #f8fafc; font-weight: bold;">مالیات:</td>
                <td style="text-align: left;"><?php echo woo_factor_number_format($data['totals']['tax']); ?> <?php echo esc_html($data['totals']['currency']); ?></td>
            </tr>
            <?php endif; ?>
            <tr class="grand-row">
                <td style="background: #e2e8f0; color: #0f172a;">مبلغ پرداختی:</td>
                <td style="text-align: left; color: <?php echo esc_attr($theme_color); ?>; font-size: 11.5px;"><?php echo woo_factor_number_format($data['totals']['grand_total']); ?> <?php echo esc_html($data['totals']['currency']); ?></td>
            </tr>
        </table>

        <?php if (!empty($data['footer_note'])): ?>
            <div class="note-box"><?php echo esc_html($data['footer_note']); ?></div>
        <?php endif; ?>

        <!-- Signatures -->
        <div class="footer-signatures">
            <div>امضای خریدار</div>
            <div><?php echo esc_html($data['signature_stamp']); ?></div>
        </div>
    </div>

</body>
</html>
