<?php
/**
 * Template 2: Modern & Minimal - Strict Box Constraint & Text Auto-wrap
 */
defined('ABSPATH') || exit;
$brand_color = woo_factor_normalize_color($data['color'] ?? '', '#2563EB');
$brand = $brand_color;
$brand_dark = woo_factor_color_shade($brand, -22, '#2563EB');
$brand_soft = woo_factor_color_shade($brand, 88, '#2563EB');
$brand_tint = woo_factor_color_shade($brand, 92, '#2563EB');
$logo_url = !empty($data['seller']['logo_url']) ? $data['seller']['logo_url'] : '';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاکتور خرید مدرن - <?php echo esc_html($data['invoice_number']); ?></title>
    <style>
        @page { size: A4 portrait; margin: 10mm; }
        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
            -webkit-print-color-adjust: exact !important; 
            print-color-adjust: exact !important;
            white-space: normal !important;
            word-wrap: break-word !important;
            overflow-wrap: anywhere !important;
            word-break: break-word !important;
        }
        body {
            font-family: 'Vazirmatn', 'Tahoma', 'Segoe UI', sans-serif;
            background: #f1f5f9;
            color: #334155;
            direction: rtl;
            text-align: right;
            font-size: 11px;
            line-height: 1.45;
            padding: 12px;
        }
        .invoice-card {
            width: 100%;
            max-width: 820px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            padding: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
            position: relative;
        }
        .header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, <?php echo esc_attr($brand); ?> 0%, <?php echo esc_attr($brand_dark); ?> 100%);
            border-radius: 10px;
            color: #ffffff;
            padding: 14px 16px;
            margin-bottom: 14px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .brand-box {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .logo-img {
            max-height: 50px;
            max-width: 130px;
            object-fit: contain;
            display: block;
        }
        .logo-badge {
            display: inline-block;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: bold;
            color: #475569;
        }
        .brand-title {
            font-size: 16px;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: -0.2px;
        }
        .brand-sub {
            font-size: 10.5px;
            color: rgba(255, 255, 255, 0.85);
        }
        .meta-tag {
            display: inline-block;
            background: rgba(255, 255, 255, 0.16);
            color: #ffffff;
            font-weight: bold;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 10px;
            margin-bottom: 4px;
        }
        .meta-tag + div strong { color: #ffffff; }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 12px;
        }
        .info-cell {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 12px;
        }
        .info-cell h3 {
            font-size: 11.5px;
            font-weight: bold;
            color: <?php echo esc_attr($brand_color); ?>;
            margin-bottom: 6px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
        }
        .info-row {
            margin-bottom: 3px;
            font-size: 10.5px;
            display: flex;
            justify-content: space-between;
            gap: 6px;
            min-width: 0;
        }
        .info-label { color: #475569; flex: 0 0 34%; min-width: 0; }
        .info-val { font-weight: bold; color: #1e293b; text-align: right; flex: 1 1 auto; min-width: 0; overflow-wrap: anywhere; }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            margin-bottom: 12px;
        }
        .items-table th {
            background: <?php echo esc_attr($brand_tint); ?>;
            color: <?php echo esc_attr($brand_dark); ?>;
            padding: 8px 6px;
            font-weight: bold;
            font-size: 10.5px;
            border-bottom: 1px solid <?php echo esc_attr($brand_soft); ?>;
            text-align: center;
        }
        .items-table td {
            padding: 8px 6px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 10.5px;
            text-align: center;
            vertical-align: middle;
            white-space: normal;
        }
        .items-table tbody tr:nth-child(even) td {
            background: #f8fafc;
        }
        .items-table td.text-right {
            text-align: right;
            padding-right: 10px;
        }
        .bottom-grid {
            display: grid;
            grid-template-columns: 1fr 280px;
            gap: 12px;
            align-items: start;
        }
        .summary-card {
            background: linear-gradient(180deg, <?php echo esc_attr($brand_tint); ?> 0%, #ffffff 55%);
            border: 1px solid <?php echo esc_attr($brand_soft); ?>;
            border-radius: 8px;
            padding: 12px 14px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-bottom: 5px;
        }
        .summary-row.grand {
            border-top: 2px dashed <?php echo esc_attr($brand); ?>;
            padding-top: 6px;
            margin-top: 5px;
            font-size: 12.5px;
            font-weight: 900;
            color: <?php echo esc_attr($brand_dark); ?>;
        }
        .grand-badge {
            display: inline-block;
            background: linear-gradient(135deg, <?php echo esc_attr($brand); ?>, <?php echo esc_attr($brand_dark); ?>);
            color: #ffffff;
            font-weight: 900;
            font-size: 13px;
            padding: 7px 14px;
            border-radius: 8px;
        }
        .print-bar {
            max-width: 820px;
            margin: 0 auto 10px;
            display: flex;
            justify-content: flex-end;
        }
        .btn-modern {
            background: linear-gradient(135deg, <?php echo esc_attr($brand); ?>, <?php echo esc_attr($brand_dark); ?>);
            color: #fff;
            padding: 8px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-family: inherit;
            font-weight: bold;
            box-shadow: 0 2px 8px <?php echo esc_attr($brand_soft); ?>;
        }

        /* Mobile View */
        @media screen and (max-width: 768px) {
            body { padding: 6px; }
            .invoice-card { padding: 10px; }
            .header-flex { flex-direction: column; align-items: center; text-align: center; }
            .brand-box { flex-direction: column; text-align: center; }
            .info-grid { grid-template-columns: 1fr; }
            .info-row { align-items: flex-start; gap: 8px; }
            .info-label { flex: 0 0 30%; }
            .info-val { text-align: right; }
            .bottom-grid { grid-template-columns: 1fr; }
            .items-table, .items-table thead, .items-table tbody, .items-table tr, .items-table td { display: block; width: 100% !important; }
            .items-table thead { display: none; }
            .items-table tr { border-bottom: 1px solid #e2e8f0; padding: 10px; }
            .items-table td { border: 0; padding: 3px 0; text-align: right; }
            .items-table td:nth-child(1)::before { content: '# '; color: #475569; font-weight: normal; }
            .items-table td:nth-child(3)::before { content: 'تعداد: '; color: #475569; font-weight: normal; }
            .items-table td:nth-child(4)::before { content: 'قیمت واحد: '; color: #475569; font-weight: normal; }
            .items-table td:nth-child(5)::before { content: 'جمع کل: '; color: #475569; font-weight: normal; }
        }

        @media print {
            body { background: #fff; padding: 0; }
            .invoice-card { box-shadow: none; border: 1px solid #cbd5e1; max-width: 100% !important; }
            .print-bar { display: none !important; }
            .info-grid { grid-template-columns: 1fr 1fr !important; }
            .bottom-grid { grid-template-columns: 1fr 280px !important; }
        }
    </style>
</head>
<body>

    <div class="print-bar">
        <button class="btn-modern" onclick="window.print();">🖨️ چاپ فاکتور مدرن</button>
    </div>

    <div class="invoice-card">
        <!-- Header -->
        <div class="header-flex">
            <div class="brand-box">
                <?php if (!empty($logo_url)): ?>
                    <img src="<?php echo esc_url($logo_url); ?>" class="logo-img" alt="Logo">
                <?php else: ?>
                    <div class="logo-badge">🏢 لوگوی سایت</div>
                <?php endif; ?>
                <div>
                    <div class="brand-title"><?php echo esc_html($data['seller']['name']); ?></div>
                    <div class="brand-sub"><?php echo esc_html($data['seller']['website'] ?: $data['seller']['email']); ?></div>
                </div>
            </div>
            <div style="text-align: left;">
                <span class="meta-tag">صورتحساب الکترونیکی</span>
                <div style="font-size: 10.5px; color: #fff; font-weight: bold; text-shadow: 0 1px 3px #0007; letter-spacing:0.8px">
  <div><strong>شماره فاکتور:</strong> <span style="color:#fff; font-weight:900; text-shadow: 0 1px 6px #000a;"><?php echo woo_factor_fa_digits($data['invoice_number']); ?></span></div>
  <div><strong>تاریخ:</strong> <span style="color:#fff; font-weight:900; text-shadow: 0 1px 6px #000a;"><?php echo esc_html($data['jalali_date']); ?></span></div>
</div>
            </div>
        </div>

        <!-- Buyer / Seller Grid -->
        <div class="info-grid">
            <div class="info-cell">
                <h3>🏢 مشخصات فروشنده</h3>
                <div class="info-row"><span class="info-label">فروشگاه:</span> <span class="info-val"><?php echo esc_html($data['seller']['name']); ?></span></div>
                <div class="info-row"><span class="info-label">تلفن:</span> <span class="info-val"><?php echo woo_factor_fa_digits($data['seller']['phone'] ?: '-'); ?></span></div>
                <div class="info-row"><span class="info-label">کد اقتصادی:</span> <span class="info-val"><?php echo woo_factor_fa_digits($data['seller']['national_id'] ?: '-'); ?></span></div>
                <div class="info-row" style="flex-direction: column;"><span class="info-label">آدرس:</span> <span class="info-val" style="font-size: 10px; margin-top: 2px; text-align: right;"><?php echo esc_html($data['seller']['address'] ?: '-'); ?></span></div>
            </div>

            <div class="info-cell">
                <h3>👤 مشخصات خریدار</h3>
                <div class="info-row"><span class="info-label">خریدار:</span> <span class="info-val"><?php echo esc_html($data['buyer']['name']); ?></span></div>
                <div class="info-row"><span class="info-label">تماس:</span> <span class="info-val"><?php echo woo_factor_fa_digits($data['buyer']['phone'] ?: '-'); ?></span></div>
                <div class="info-row"><span class="info-label">کد ملی:</span> <span class="info-val"><?php echo woo_factor_fa_digits($data['buyer']['national_id'] ?: '-'); ?></span></div>
                <div class="info-row" style="flex-direction: column;"><span class="info-label">آدرس تحویل:</span> <span class="info-val" style="font-size: 10px; margin-top: 2px; text-align: right;"><?php echo esc_html($data['buyer']['full_address'] ?: '-'); ?></span></div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 6%;">#</th>
                    <th style="width: 44%; text-align: right; padding-right: 10px;">عنوان محصول / شرح خدمت</th>
                    <th style="width: 10%;">تعداد</th>
                    <th style="width: 20%;">قیمت واحد (<?php echo esc_html($data['totals']['currency']); ?>)</th>
                    <th style="width: 20%;">جمع کل (<?php echo esc_html($data['totals']['currency']); ?>)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['items'] as $item): ?>
                    <tr>
                        <td><?php echo woo_factor_fa_digits($item['index']); ?></td>
                        <td class="text-right">
                            <strong><?php echo esc_html($item['title']); ?></strong>
                            <?php if (!empty($item['sku']) && $item['sku'] !== '-'): ?>
                                <span style="font-size: 9.5px; color: #475569;"> (کد: <?php echo woo_factor_fa_digits($item['sku']); ?>)</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo woo_factor_fa_digits($item['qty']); ?></td>
                        <td><?php echo woo_factor_number_format($item['unit_price']); ?></td>
                        <td><strong><?php echo woo_factor_number_format($item['total']); ?></strong></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Bottom Grid -->
        <div class="bottom-grid">
            <div>
                <div style="font-size: 10.5px; color: #475569; line-height: 1.5; margin-bottom: 6px;">
                    <div><strong>پرداخت:</strong> <?php echo esc_html($data['totals']['payment_method']); ?> | <strong>ارسال:</strong> <?php echo esc_html($data['totals']['shipping_method']); ?></div>
                    <?php if (!empty($data['customer_note'])): ?>
                        <div><strong>یادداشت خریدار:</strong> <?php echo esc_html($data['customer_note']); ?></div>
                    <?php endif; ?>
                    <div><?php echo esc_html($data['footer_note']); ?></div>
                </div>
                <?php if (!empty($data['barcode_svg'])): ?>
                    <div style="margin-top: 6px;">
                        <?php echo $data['barcode_svg']; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="summary-card">
                <div class="summary-row"><span>جمع کالاها:</span><span><?php echo woo_factor_number_format($data['totals']['subtotal']); ?> <?php echo esc_html($data['totals']['currency']); ?></span></div>
                <?php if ($data['totals']['discount'] > 0): ?>
                    <div class="summary-row" style="color: #ef4444;"><span>تخفیف:</span><span><?php echo woo_factor_number_format($data['totals']['discount']); ?>- <?php echo esc_html($data['totals']['currency']); ?></span></div>
                <?php endif; ?>
                <?php if ($data['totals']['shipping'] > 0): ?>
                    <div class="summary-row"><span>هزینه حمل:</span><span><?php echo woo_factor_number_format($data['totals']['shipping']); ?> <?php echo esc_html($data['totals']['currency']); ?></span></div>
                <?php endif; ?>
                <?php if ($data['totals']['tax'] > 0): ?>
                    <div class="summary-row"><span>مالیات:</span><span><?php echo woo_factor_number_format($data['totals']['tax']); ?> <?php echo esc_html($data['totals']['currency']); ?></span></div>
                <?php endif; ?>
                <div class="summary-row grand"><span>مبلغ پرداختی:</span><span class="grand-badge"><?php echo woo_factor_number_format($data['totals']['grand_total']); ?> <?php echo esc_html($data['totals']['currency']); ?></span></div>
            </div>
        </div>
    </div>

</body>
</html>
