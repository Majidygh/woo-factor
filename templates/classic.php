<?php
/**
 * Template 1: Classic & Official (صورتحساب رسمی فروش کالا و خدمات - استاندارد سازمان امور مالیاتی)
 * Conforming to Article 19 of Value Added Tax law with Legal/Natural buyer distinction.
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
    <title>صورتحساب رسمی فروش کالا و خدمات - <?php echo esc_html($data['invoice_number']); ?></title>
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
            background: #f1f5f9;
            color: #0f172a;
            direction: rtl;
            text-align: right;
            font-size: 10.5px;
            line-height: 1.45;
            padding: 15px;
        }
        .invoice-box {
            position: relative;
            width: 100%;
            max-width: 820px;
            margin: 0 auto;
            background: #ffffff;
            border: 2px solid #334155;
            padding: 14px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-28deg);
            font-size: 64px;
            font-weight: 900;
            color: rgba(15, 23, 42, 0.05);
            border: 6px dashed rgba(15, 23, 42, 0.07);
            padding: 15px 40px;
            border-radius: 12px;
            pointer-events: none;
            user-select: none;
            white-space: nowrap;
            z-index: 0;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #334155;
            padding-bottom: 10px;
            margin-bottom: 8px;
        }
        .header-table td { vertical-align: middle; }
        .logo-box {
            max-height: 60px;
            max-width: 150px;
            display: flex;
            align-items: center;
        }
        .logo-box img {
            max-height: 55px;
            max-width: 140px;
            object-fit: contain;
        }
        .header-title-box { text-align: center; }
        .header-title-box h1 {
            font-size: 15px;
            font-weight: 900;
            color: #0f172a;
            margin-bottom: 3px;
        }
        .header-title-box h2 {
            font-size: 11.5px;
            font-weight: 700;
            color: <?php echo esc_attr($theme_color); ?>;
        }
        .meta-card {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 6px 10px;
            font-size: 10px;
            line-height: 1.6;
        }
        .section-header {
            background: #334155;
            color: #ffffff;
            font-size: 11px;
            font-weight: bold;
            padding: 4px 10px;
            margin-top: 8px;
            margin-bottom: 4px;
            border-radius: 2px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .grid-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 10px;
            margin-bottom: 4px;
        }
        .grid-table td {
            border: 1px solid #cbd5e1;
            padding: 4px 6px;
            vertical-align: middle;
            word-break: break-word;
        }
        .grid-table td.lbl {
            background: #f8fafc;
            font-weight: bold;
            color: #475569;
            width: 14%;
        }
        .grid-table td.val {
            color: #0f172a;
            width: 36%;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 8px;
            margin-bottom: 8px;
            font-size: 10px;
        }
        .items-table th {
            background: #e2e8f0;
            color: #1e293b;
            border: 1px solid #94a3b8;
            padding: 6px 4px;
            font-weight: 800;
            text-align: center;
        }
        .items-table td {
            border: 1px solid #cbd5e1;
            padding: 5px 4px;
            text-align: center;
            vertical-align: middle;
            word-break: break-word;
        }
        .items-table td.title-col {
            text-align: right;
            padding-right: 8px;
        }
        .items-table tr:nth-child(even) {
            background: #f8fafc;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
            margin-top: 6px;
            border: 1px solid #94a3b8;
        }
        .totals-table td {
            padding: 6px 8px;
            border: 1px solid #cbd5e1;
        }
        .totals-table .total-lbl {
            background: #f8fafc;
            font-weight: bold;
            color: #334155;
            text-align: right;
        }
        .totals-table .total-val {
            font-weight: 800;
            text-align: left;
            direction: ltr;
        }
        .grand-total-row {
            background: #f1f5f9;
            font-size: 12px;
        }
        .grand-total-row .total-lbl {
            color: #0f172a;
            font-weight: 900;
        }
        .grand-total-row .total-val {
            color: <?php echo esc_attr($theme_color); ?>;
            font-weight: 900;
            font-size: 13px;
        }
        .words-bar {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 5px 10px;
            font-size: 10.5px;
            margin-top: 6px;
            border-radius: 3px;
        }
        .signatures-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
            margin-top: 12px;
            margin-bottom: 8px;
        }
        .sig-box {
            border: 1px dashed #94a3b8;
            border-radius: 4px;
            padding: 8px;
            height: 95px;
            position: relative;
            background: #fff;
            text-align: center;
        }
        .sig-title {
            font-size: 10px;
            font-weight: bold;
            color: #475569;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 3px;
            margin-bottom: 6px;
        }
        .sig-img {
            max-height: 60px;
            max-width: 120px;
            object-fit: contain;
            margin: 0 auto;
            display: block;
        }
        .footer-note {
            border-top: 1px solid #cbd5e1;
            padding-top: 6px;
            margin-top: 8px;
            font-size: 9.5px;
            color: #64748b;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        @media print {
            body { background: #fff; padding: 0; }
            .invoice-box { border: 2px solid #000; box-shadow: none; max-width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<?php if (!empty($data['show_watermark']) && !empty($data['watermark_text'])): ?>
    <div class="watermark"><?php echo esc_html($data['watermark_text']); ?></div>
<?php endif; ?>

<div class="invoice-box">
    <!-- Header -->
    <table class="header-table">
        <tr>
            <td style="width: 25%;">
                <div class="logo-box">
                    <?php if ($logo_url): ?>
                        <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($seller['name']); ?>">
                    <?php else: ?>
                        <span style="font-weight: bold; font-size: 13px; color: <?php echo esc_attr($theme_color); ?>;"><?php echo esc_html($seller['name']); ?></span>
                    <?php endif; ?>
                </div>
            </td>
            <td style="width: 50%;" class="header-title-box">
                <h1>صورتحساب رسمی فروش کالا و خدمات</h1>
                <h2>(ماده ۱۹ قانون مالیات بر ارزش افزوده)</h2>
            </td>
            <td style="width: 25%;">
                <div class="meta-card">
                    <div><strong>شماره فاکتور:</strong> <?php echo woo_factor_fa_digits($data['invoice_number']); ?></div>
                    <div><strong>تاریخ صدور:</strong> <?php echo woo_factor_fa_digits($data['jalali_date']); ?></div>
                    <div><strong>زمان:</strong> <?php echo woo_factor_fa_digits($data['jalali_time']); ?></div>
                    <div><strong>وضعیت:</strong> <?php echo esc_html($data['status_name']); ?></div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Seller Information -->
    <div class="section-header">
        <span>الف) مشخصات فروشنده</span>
    </div>
    <table class="grid-table">
        <tr>
            <td class="lbl">نام شخص حقیقی/حقوقی:</td>
            <td class="val"><strong><?php echo esc_html($seller['name']); ?></strong></td>
            <td class="lbl">شماره اقتصادی:</td>
            <td class="val"><?php echo !empty($seller['economic_code']) ? woo_factor_fa_digits($seller['economic_code']) : (!empty($seller['national_id']) ? woo_factor_fa_digits($seller['national_id']) : '-'); ?></td>
        </tr>
        <tr>
            <td class="lbl">شناسه ملی / کد ملی:</td>
            <td class="val"><?php echo !empty($seller['national_id']) ? woo_factor_fa_digits($seller['national_id']) : '-'; ?></td>
            <td class="lbl">شماره ثبت / پروانه:</td>
            <td class="val"><?php echo !empty($seller['registration_no']) ? woo_factor_fa_digits($seller['registration_no']) : '-'; ?></td>
        </tr>
        <tr>
            <td class="lbl">تلفن و نمابر:</td>
            <td class="val"><?php echo !empty($seller['phone']) ? woo_factor_fa_digits($seller['phone']) : '-'; ?></td>
            <td class="lbl">کد پستی ۱۰ رقمی:</td>
            <td class="val"><?php echo !empty($seller['postal_code']) ? woo_factor_fa_digits($seller['postal_code']) : '-'; ?></td>
        </tr>
        <tr>
            <td class="lbl">نشانی کامل:</td>
            <td class="val" colspan="3"><?php echo !empty($seller['address']) ? esc_html($seller['address']) : '-'; ?></td>
        </tr>
    </table>

    <!-- Buyer Information -->
    <div class="section-header">
        <span>ب) مشخصات خریدار (<?php echo $is_legal_buyer ? 'مشتری حقوقی / شرکت' : 'مشتری حقیقی'; ?>)</span>
    </div>
    <table class="grid-table">
        <tr>
            <td class="lbl">نام خریدار / شرکت:</td>
            <td class="val"><strong><?php echo esc_html($buyer['company'] ?: $buyer['name']); ?></strong></td>
            <td class="lbl"><?php echo $is_legal_buyer ? 'شناسه ملی شرکت:' : 'کد ملی:'; ?></td>
            <td class="val"><?php echo !empty($buyer['national_id']) ? woo_factor_fa_digits($buyer['national_id']) : '-'; ?></td>
        </tr>
        <tr>
            <td class="lbl">شماره اقتصادی:</td>
            <td class="val"><?php echo !empty($buyer['economic_id']) ? woo_factor_fa_digits($buyer['economic_id']) : '-'; ?></td>
            <td class="lbl">شماره تماس / همراه:</td>
            <td class="val"><?php echo !empty($buyer['phone']) ? woo_factor_fa_digits($buyer['phone']) : '-'; ?></td>
        </tr>
        <tr>
            <td class="lbl">کد پستی:</td>
            <td class="val"><?php echo !empty($buyer['postcode']) ? woo_factor_fa_digits($buyer['postcode']) : '-'; ?></td>
            <td class="lbl">پست الکترونیکی:</td>
            <td class="val"><?php echo !empty($buyer['email']) ? esc_html($buyer['email']) : '-'; ?></td>
        </tr>
        <tr>
            <td class="lbl">نشانی کامل خریدار:</td>
            <td class="val" colspan="3"><?php echo !empty($buyer['full_address']) ? esc_html($buyer['full_address']) : '-'; ?></td>
        </tr>
    </table>

    <!-- Items Table -->
    <div class="section-header">
        <span>ج) مشخصات کالا یا خدمات مورد معامله</span>
    </div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">ردیف</th>
                <?php if (!empty($data['show_sku'])): ?>
                    <th style="width: 11%;">کد کالا</th>
                <?php endif; ?>
                <th style="width: <?php echo (!empty($data['show_sku']) ? '32%' : '43%'); ?>;">شرح کالا یا خدمت</th>
                <th style="width: 7%;">تعداد</th>
                <th style="width: 14%;">مبلغ واحد (<?php echo esc_html($currency); ?>)</th>
                <th style="width: 14%;">مبلغ کل</th>
                <?php if (!empty($data['show_discount_column'])): ?>
                    <th style="width: 10%;">تخفیف</th>
                <?php endif; ?>
                <?php if (!empty($data['show_tax_column'])): ?>
                    <th style="width: 9%;">مالیات</th>
                <?php endif; ?>
                <th style="width: 15%;">جمع نهایی</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo woo_factor_fa_digits($item['index']); ?></td>
                    <?php if (!empty($data['show_sku'])): ?>
                        <td style="font-size: 9.5px;"><?php echo esc_html($item['sku']); ?></td>
                    <?php endif; ?>
                    <td class="title-col">
                        <strong><?php echo esc_html($item['title']); ?></strong>
                        <?php if (!empty($item['meta'])): ?>
                            <div style="font-size: 9px; color: #64748b; margin-top: 2px;"><?php echo esc_html($item['meta']); ?></div>
                        <?php endif; ?>
                    </td>
                    <td><?php echo woo_factor_fa_digits($item['qty']); ?></td>
                    <td><?php echo woo_factor_number_format($item['unit_price']); ?></td>
                    <td><?php echo woo_factor_number_format($item['subtotal']); ?></td>
                    <?php if (!empty($data['show_discount_column'])): ?>
                        <td><?php echo $item['discount'] > 0 ? woo_factor_number_format($item['discount']) : '۰'; ?></td>
                    <?php endif; ?>
                    <?php if (!empty($data['show_tax_column'])): ?>
                        <td><?php echo $item['tax'] > 0 ? woo_factor_number_format($item['tax']) : '۰'; ?></td>
                    <?php endif; ?>
                    <td style="font-weight: bold;"><?php echo woo_factor_number_format($item['total']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Totals Summary Table -->
    <table class="totals-table">
        <tr>
            <td class="total-lbl" style="width: 25%;">جمع کل اقلام:</td>
            <td class="total-val" style="width: 25%;"><?php echo woo_factor_number_format($totals['subtotal']); ?> <?php echo esc_html($currency); ?></td>
            <td class="total-lbl" style="width: 25%;">مجموع تخفیفات:</td>
            <td class="total-val" style="width: 25%;"><?php echo woo_factor_number_format($totals['discount']); ?> <?php echo esc_html($currency); ?></td>
        </tr>
        <tr>
            <td class="total-lbl">هزینه حمل و نقل / پست:</td>
            <td class="total-val"><?php echo $totals['shipping'] > 0 ? woo_factor_number_format($totals['shipping']) . ' ' . esc_html($currency) : 'رایگان'; ?></td>
            <td class="total-lbl">مالیات و عوارض ارزش افزوده:</td>
            <td class="total-val"><?php echo $totals['tax'] > 0 ? woo_factor_number_format($totals['tax']) . ' ' . esc_html($currency) : '۰ ' . esc_html($currency); ?></td>
        </tr>
        <tr class="grand-total-row">
            <td class="total-lbl" colspan="2">مبلغ قابل پرداخت نهایی:</td>
            <td class="total-val" colspan="2"><?php echo woo_factor_number_format($totals['grand_total']); ?> <?php echo esc_html($currency); ?></td>
        </tr>
    </table>

    <?php if (!empty($totals['grand_total_words'])): ?>
        <div class="words-bar">
            <strong>مبلغ به حروف:</strong> <?php echo esc_html($totals['grand_total_words']); ?> <?php echo esc_html($currency); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($data['invoice_terms'])): ?>
        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 3px; padding: 6px 8px; margin-top: 6px; font-size: 9.5px; color: #475569;">
            <strong>شرایط و ضوابط:</strong> <?php echo nl2br(esc_html($data['invoice_terms'])); ?>
        </div>
    <?php endif; ?>

    <!-- Signatures and Verification Area -->
    <div class="signatures-grid">
        <div class="sig-box">
            <div class="sig-title">مهر و امضای فروشنده</div>
            <?php if (!empty($data['show_signature'])): ?>
                <?php if ($stamp_url): ?>
                    <img src="<?php echo esc_url($stamp_url); ?>" alt="مهر فروشگاه" class="sig-img">
                <?php else: ?>
                    <div style="margin-top: 15px; font-weight: bold; color: #64748b;"><?php echo esc_html($data['signature_stamp']); ?></div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <div class="sig-box">
            <div class="sig-title">امضا و اثر انگشت خریدار</div>
            <div style="margin-top: 25px; font-size: 9.5px; color: #94a3b8;">کالا و خدمات فوق تحویل گرفته شد.</div>
        </div>
        <div class="sig-box" style="display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 4px;">
            <div class="sig-title" style="width: 100%;">بارکد رهگیری سفارش</div>
            <?php if (!empty($data['show_barcode']) && !empty($data['barcode_svg'])): ?>
                <div style="display: inline-block;"><?php echo $data['barcode_svg']; ?></div>
                <div style="font-size: 9px; color: #64748b; margin-top: 2px;">#<?php echo woo_factor_fa_digits($data['order_number']); ?></div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer-note">
        <span><?php echo esc_html($data['footer_note']); ?></span>
        <span>روش پرداخت: <?php echo esc_html($totals['payment_method']); ?> <?php if (!empty($totals['transaction_id'])): ?>(کد رهگیری: <?php echo woo_factor_fa_digits($totals['transaction_id']); ?>)<?php endif; ?></span>
    </div>
</div>

</body>
</html>
