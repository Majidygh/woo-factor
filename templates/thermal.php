<?php
/**
 * Template 3: Thermal Receipt (فیش پرینتر حرارتی ۸۰ میلی‌متری)
 * Designed specifically for 80mm POS Thermal printers and fast store checkouts.
 */
defined('ABSPATH') || exit;

$seller = $data['seller'] ?? [];
$buyer = $data['buyer'] ?? [];
$totals = $data['totals'] ?? [];
$items = $data['items'] ?? [];
$logo_url = !empty($seller['logo_url']) ? $seller['logo_url'] : '';
$currency = $totals['currency'] ?? 'تومان';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>فیش خرید - <?php echo esc_html($data['invoice_number']); ?></title>
    <style>
        @page { 
            size: 80mm auto; 
            margin: 0; 
        }
        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
            -webkit-print-color-adjust: exact !important; 
            print-color-adjust: exact !important;
            font-family: 'Vazirmatn', 'Tahoma', sans-serif;
        }
        body {
            background: #f1f5f9;
            color: #0f172a;
            direction: rtl;
            text-align: right;
            font-size: 11px;
            line-height: 1.45;
            padding: 15px 10px;
        }
        .thermal-card {
            width: 100%;
            max-width: 80mm;
            margin: 0 auto;
            background: #ffffff;
            padding: 6mm 5mm;
            border-radius: 6px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
        }
        .text-center { text-align: center; }
        .divider {
            border-top: 1px dashed #cbd5e1;
            margin: 8px 0;
        }
        @media print {
            body { 
                background: #fff !important; 
                padding: 0 !important; 
                margin: 0 !important;
                width: 80mm !important;
            }
            .thermal-card { 
                box-shadow: none !important; 
                padding: 3mm 4mm !important; 
                max-width: 100% !important; 
                border: none !important; 
                border-radius: 0 !important;
                margin: 0 !important;
            }
            .no-print { display: none !important; }
        }
        .store-logo {
            max-height: 40px;
            max-width: 120px;
            margin: 0 auto 4px auto;
            display: block;
            object-fit: contain;
        }
        .store-name {
            font-size: 14px;
            font-weight: 900;
            margin-bottom: 2px;
        }
        .receipt-title {
            font-size: 10px;
            font-weight: bold;
            color: #444;
            margin-bottom: 6px;
        }
        .meta-row {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            margin-bottom: 2px;
        }
        .items-list {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
            margin: 6px 0;
        }
        .items-list th {
            border-bottom: 1px dashed #000;
            padding: 4px 2px;
            font-size: 10px;
            text-align: right;
        }
        .items-list td {
            padding: 4px 2px;
            vertical-align: top;
            border-bottom: 1px dotted #ccc;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 10.5px;
            margin-bottom: 3px;
        }
        .grand-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            font-weight: 900;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 5px 0;
            margin: 6px 0;
        }
        .barcode-box {
            text-align: center;
            margin-top: 8px;
        }
        .footer-text {
            text-align: center;
            font-size: 9.5px;
            margin-top: 6px;
        }
    </style>
</head>
<body>

<div class="thermal-card">
    <!-- Header -->
    <div class="text-center">
        <?php if ($logo_url): ?>
            <img src="<?php echo esc_url($logo_url); ?>" alt="" class="store-logo">
        <?php endif; ?>
        <div class="store-name"><?php echo esc_html($seller['name']); ?></div>
        <div class="receipt-title">فیش سفارش و رسید صندوق</div>
        <?php if (!empty($seller['phone'])): ?>
            <div style="font-size: 9.5px;">تلفن: <?php echo woo_factor_fa_digits($seller['phone']); ?></div>
        <?php endif; ?>
    </div>

    <div class="divider"></div>

    <!-- Meta Details -->
    <div class="meta-row">
        <span>شماره فاکتور:</span>
        <strong><?php echo woo_factor_fa_digits($data['invoice_number']); ?></strong>
    </div>
    <div class="meta-row">
        <span>تاریخ و ساعت:</span>
        <span><?php echo woo_factor_fa_digits($data['jalali_date']); ?> - <?php echo woo_factor_fa_digits($data['jalali_time']); ?></span>
    </div>
    <div class="meta-row">
        <span>مشتری:</span>
        <strong><?php echo esc_html($buyer['name']); ?></strong>
    </div>
    <?php if (!empty($buyer['phone'])): ?>
        <div class="meta-row">
            <span>تلفن مشتری:</span>
            <span><?php echo woo_factor_fa_digits($buyer['phone']); ?></span>
        </div>
    <?php endif; ?>

    <div class="divider"></div>

    <!-- Items -->
    <table class="items-list">
        <thead>
            <tr>
                <th style="width: 55%;">شرح کالا</th>
                <th style="width: 15%; text-align: center;">تعداد</th>
                <th style="width: 30%; text-align: left;">مبلغ کل</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td>
                        <?php echo esc_html($item['title']); ?>
                        <?php if (!empty($item['meta'])): ?>
                            <div style="font-size: 8.5px; color: #555;"><?php echo esc_html($item['meta']); ?></div>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;"><?php echo woo_factor_fa_digits($item['qty']); ?></td>
                    <td style="text-align: left;"><?php echo woo_factor_number_format($item['total']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Totals -->
    <div class="total-row">
        <span>جمع اقلام:</span>
        <span><?php echo woo_factor_number_format($totals['subtotal']); ?> <?php echo esc_html($currency); ?></span>
    </div>
    <?php if ($totals['discount'] > 0): ?>
        <div class="total-row">
            <span>تخفیف:</span>
            <span><?php echo woo_factor_number_format($totals['discount']); ?>- <?php echo esc_html($currency); ?></span>
        </div>
    <?php endif; ?>
    <?php if ($totals['shipping'] > 0): ?>
        <div class="total-row">
            <span>حمل و نقل:</span>
            <span><?php echo woo_factor_number_format($totals['shipping']); ?> <?php echo esc_html($currency); ?></span>
        </div>
    <?php endif; ?>
    <?php if ($totals['tax'] > 0): ?>
        <div class="total-row">
            <span>مالیات:</span>
            <span><?php echo woo_factor_number_format($totals['tax']); ?> <?php echo esc_html($currency); ?></span>
        </div>
    <?php endif; ?>

    <div class="grand-row">
        <span>مبلغ کل:</span>
        <span><?php echo woo_factor_number_format($totals['grand_total']); ?> <?php echo esc_html($currency); ?></span>
    </div>

    <!-- Barcode -->
    <?php if (!empty($data['show_barcode']) && !empty($data['barcode_svg'])): ?>
        <div class="barcode-box">
            <?php echo $data['barcode_svg']; ?>
            <div style="font-size: 8.5px; margin-top: 2px;">#<?php echo woo_factor_fa_digits($data['order_number']); ?></div>
        </div>
    <?php endif; ?>

    <!-- Footer Note -->
    <div class="footer-text">
        <div><?php echo esc_html($data['footer_note']); ?></div>
        <?php if (!empty($data['invoice_terms'])): ?>
            <div style="font-size: 8.5px; color: #555; margin-top: 3px;"><?php echo nl2br(esc_html($data['invoice_terms'])); ?></div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
