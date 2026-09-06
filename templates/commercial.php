<?php
/**
 * Template 3: Commercial & Elegant - Mobile Responsive & Pixel Perfect
 */
defined('ABSPATH') || exit;
$logo_url = !empty($data['seller']['logo_url']) ? $data['seller']['logo_url'] : '';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاکتور تجاری - <?php echo esc_html($data['invoice_number']); ?></title>
    <style>
        @page { size: A4 portrait; margin: 8mm; }
        * { box-sizing: border-box; margin: 0; padding: 0; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; overflow-wrap: anywhere; word-break: break-word; }
        body {
            font-family: 'Vazirmatn', 'Tahoma', 'Segoe UI', sans-serif;
            background: #e2e8f0;
            color: #1e293b;
            direction: rtl;
            text-align: right;
            font-size: 11.5px;
            padding: 15px;
        }
        td, th, p, span, strong, div {
            white-space: normal !important;
            overflow-wrap: anywhere !important;
            word-wrap: break-word !important;
            word-break: break-word !important;
            max-width: 100%;
        }
        img, svg { max-width: 100%; height: auto; }
        .wrapper {
            width: 100%;
            max-width: 820px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        }
        .header-bg {
            background: #0f172a;
            color: #ffffff;
            padding: 18px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }
        .header-bg h1 {
            font-size: 18px;
            font-weight: 900;
            margin-bottom: 2px;
            color: #f8fafc;
        }
        .header-meta-badge {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 11px;
            line-height: 1.7;
            text-align: right;
        }
        .body-content {
            padding: 18px 20px;
        }
        .parties-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 15px;
        }
        .p-box {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 12px;
            background: #f8fafc;
            font-size: 11px;
        }
        .p-title {
            font-weight: bold;
            font-size: 11.5px;
            color: #0f172a;
            border-bottom: 2px solid #cbd5e1;
            padding-bottom: 4px;
            margin-bottom: 6px;
        }
        .p-box p {
            margin-bottom: 3px;
            color: #475569;
            min-width: 0;
            overflow-wrap: anywhere;
            word-break: break-word;
        }
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }
        .comm-table {
            width: 100%;
            min-width: 520px;
            border-collapse: collapse;
            font-size: 11px;
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
        }
        .comm-table td.align-r {
            text-align: right;
            padding-right: 10px;
        }
        .bottom-grid {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 15px;
            align-items: start;
        }
        .summary-box {
            border: 1px solid #0f172a;
            border-radius: 6px;
            overflow: hidden;
            background: #fff;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 12px;
            font-size: 11px;
            border-bottom: 1px solid #f1f5f9;
        }
        .summary-row.final {
            background: #0f172a;
            color: #fff;
            font-weight: 900;
            font-size: 12.5px;
            border-bottom: none;
        }
        .stamps-flex {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            padding: 0 20px;
            font-size: 11px;
            font-weight: bold;
            color: #334155;
        }
        .print-btn-bar {
            max-width: 820px;
            margin: 0 auto 12px;
            display: flex;
            justify-content: flex-end;
        }
        .btn-c {
            background: #0f172a;
            color: white;
            padding: 8px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-family: inherit;
            font-weight: bold;
        }

        /* Mobile View */
        @media screen and (max-width: 768px) {
            body { padding: 8px; }
            .header-bg { flex-direction: column; text-align: center; }
            .header-meta-badge { text-align: center; }
            .body-content { padding: 12px; }
            .parties-grid { grid-template-columns: 1fr; }
            .bottom-grid { grid-template-columns: 1fr; }
            .stamps-flex { padding: 0 5px; }
            .p-box { width: 100%; min-width: 0; }
            .p-box p { white-space: normal !important; overflow-wrap: anywhere !important; word-break: break-word !important; line-height: 1.8; }
            .comm-table, .comm-table thead, .comm-table tbody, .comm-table tr, .comm-table td { display: block; width: 100% !important; }
            .comm-table thead { display: none; }
            .comm-table tr { padding: 10px; border-bottom: 1px solid #e2e8f0; }
            .comm-table td { border: 0; padding: 3px 0; text-align: right; }
            .comm-table td:nth-child(1)::before { content: '# '; color: #475569; }
            .comm-table td:nth-child(3)::before { content: 'تعداد: '; color: #475569; font-weight: normal; }
            .comm-table td:nth-child(4)::before { content: 'قیمت واحد: '; color: #475569; font-weight: normal; }
            .comm-table td:nth-child(5)::before { content: 'مبلغ کل: '; color: #475569; font-weight: normal; }
        }

        @media print {
            body { background: #fff; padding: 0; }
            .wrapper { box-shadow: none; border: 1px solid #cbd5e1; max-width: 100% !important; }
            .print-btn-bar { display: none !important; }
            .parties-grid { grid-template-columns: 1fr 1fr !important; }
            .bottom-grid { grid-template-columns: 1fr 300px !important; }
        }
    </style>
</head>
<body>

    <div class="print-btn-bar">
        <button class="btn-c" onclick="window.print();">🖨️ چاپ فاکتور تجاری</button>
    </div>

    <div class="wrapper">
        <div class="header-bg">
            <div>
                <?php if (!empty($logo_url)): ?>
                    <img src="<?php echo esc_url($logo_url); ?>" class="logo-img" style="max-height: 45px; margin-bottom: 6px; display: block;" alt="Logo">
                <?php endif; ?>
                <h1>فاکتور رسمی فروش</h1>
                <div style="color: #38bdf8; font-size: 12px; font-weight: bold; margin-top: 3px; letter-spacing:0.5px; text-shadow: 0 1px 3px #0002;"> <?php echo esc_html($data['seller']['name']); ?> </div>
            </div>
            <div class="header-meta-badge">
                <div>شماره: <strong><?php echo woo_factor_fa_digits($data['invoice_number']); ?></strong></div>
                <div>تاریخ: <strong><?php echo esc_html($data['jalali_date']); ?></strong></div>
                <div>وضعیت: <strong><?php echo esc_html($data['status_name']); ?></strong></div>
            </div>
        </div>

        <div class="body-content">
            <div class="parties-grid">
                <div class="p-box">
                    <div class="p-title">🏢 مشخصات فروشنده</div>
                    <p>نام فروشگاه: <strong><?php echo esc_html($data['seller']['name']); ?></strong></p>
                    <p>کد اقتصادی: <strong><?php echo woo_factor_fa_digits($data['seller']['national_id'] ?: '-'); ?></strong></p>
                    <p>شماره تماس: <strong><?php echo woo_factor_fa_digits($data['seller']['phone'] ?: '-'); ?></strong></p>
                    <p>آدرس: <span><?php echo esc_html($data['seller']['address'] ?: '-'); ?></span></p>
                </div>

                <div class="p-box">
                    <div class="p-title">👤 مشخصات خریدار</div>
                    <p>نام خریدار: <strong><?php echo esc_html($data['buyer']['name']); ?></strong></p>
                    <p>کد ملی: <strong><?php echo woo_factor_fa_digits($data['buyer']['national_id'] ?: '-'); ?></strong></p>
                    <p>شماره همراه: <strong><?php echo woo_factor_fa_digits($data['buyer']['phone'] ?: '-'); ?></strong></p>
                    <p>آدرس: <span><?php echo esc_html($data['buyer']['full_address'] ?: '-'); ?></span></p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="comm-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 45%; text-align: right; padding-right: 10px;">شرح کالا یا خدمات</th>
                            <th style="width: 10%;">تعداد</th>
                            <th style="width: 20%;">مبلغ واحد (<?php echo esc_html($data['totals']['currency']); ?>)</th>
                            <th style="width: 20%;">مبلغ کل (<?php echo esc_html($data['totals']['currency']); ?>)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['items'] as $item): ?>
                            <tr>
                                <td><?php echo woo_factor_fa_digits($item['index']); ?></td>
                                <td class="align-r">
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
            </div>

            <div class="bottom-grid">
                <div>
                    <?php if (!empty($data['barcode_svg'])): ?>
                        <div style="margin-bottom: 8px;">
                            <?php echo $data['barcode_svg']; ?>
                        </div>
                    <?php endif; ?>
                    <p style="font-size: 10.5px; color: #475569; line-height: 1.5;"><?php echo esc_html($data['footer_note']); ?></p>
                </div>

                <div class="summary-box">
                    <div class="summary-row"><span>جمع اقلام:</span><span><?php echo woo_factor_number_format($data['totals']['subtotal']); ?> <?php echo esc_html($data['totals']['currency']); ?></span></div>
                    <?php if ($data['totals']['discount'] > 0): ?>
                        <div class="summary-row" style="color: #dc2626;"><span>تخفیف:</span><span><?php echo woo_factor_number_format($data['totals']['discount']); ?>- <?php echo esc_html($data['totals']['currency']); ?></span></div>
                    <?php endif; ?>
                    <?php if ($data['totals']['shipping'] > 0): ?>
                        <div class="summary-row"><span>ارسال:</span><span><?php echo woo_factor_number_format($data['totals']['shipping']); ?> <?php echo esc_html($data['totals']['currency']); ?></span></div>
                    <?php endif; ?>
                    <?php if ($data['totals']['tax'] > 0): ?>
                        <div class="summary-row"><span>مالیات:</span><span><?php echo woo_factor_number_format($data['totals']['tax']); ?> <?php echo esc_html($data['totals']['currency']); ?></span></div>
                    <?php endif; ?>
                    <div class="summary-row final"><span>مبلغ نهایی:</span><span><?php echo woo_factor_number_format($data['totals']['grand_total']); ?> <?php echo esc_html($data['totals']['currency']); ?></span></div>
                </div>
            </div>

            <div class="stamps-flex">
                <div>امضای تحویل‌گیرنده</div>
                <div><?php echo esc_html($data['signature_stamp']); ?></div>
            </div>
        </div>
    </div>

</body>
</html>
