<?php
/**
 * Manual Invoice Creation Page
 */
defined('ABSPATH') || exit;

function woo_factor_render_manual_invoice_page() {
    $opts = woo_factor_options();
    ?>
    <div class="wrap" style="direction: rtl; text-align: right; max-width: 1000px;">
        <h1 style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
            <span class="dashicons dashicons-edit-page" style="font-size: 32px; width: 32px; height: 32px;"></span>
            <span>صدور فاکتور دستی و مستقل</span>
        </h1>
        <p class="description">از این بخش می‌توانید بدون نیاز به ثبت سفارش در ووکامرس، برای مشتریان حضوری یا تلفنی فاکتور صادر و چاپ کنید.</p>

        <form id="woo_factor_manual_form" method="post" target="_blank" action="<?php echo admin_url('admin-ajax.php?action=woo_factor_generate_manual_invoice'); ?>">
            <?php wp_nonce_field('woo_factor_manual_nonce'); ?>

            <div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ccd0d4; margin-bottom: 20px;">
                <h3 style="color: #0f766e; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">اطلاعات فاکتور و خریدار</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-top: 15px;">
                    <div>
                        <label><strong>شماره فاکتور:</strong></label><br>
                        <input type="text" name="invoice_number" value="MAN-<?php echo rand(10000, 99999); ?>" class="regular-text" style="width: 100%;">
                    </div>
                    <div>
                        <label><strong>نام خریدار / شرکت:</strong></label><br>
                        <input type="text" name="buyer_name" placeholder="مثال: علی رضایی" class="regular-text" style="width: 100%;" required>
                    </div>
                    <div>
                        <label><strong>کد ملی / شناسه خریدار:</strong></label><br>
                        <input type="text" name="buyer_national_id" placeholder="۱۰ رقمی" class="regular-text" style="width: 100%;">
                    </div>
                    <div>
                        <label><strong>شماره تماس:</strong></label><br>
                        <input type="text" name="buyer_phone" placeholder="۰۹۱۲..." class="regular-text" style="width: 100%;">
                    </div>
                    <div>
                        <label><strong>قالب فاکتور:</strong></label><br>
                        <select name="template" style="width: 100%;">
                            <option value="classic">قالب رسمی دارایی (Classic)</option>
                            <option value="modern">قالب مدرن و شکیل (Modern)</option>
                            <option value="thermal">قالب فیش‌پرینتر حرارتی (Thermal 80mm)</option>
                        </select>
                    </div>
                    <div>
                        <label><strong>واحد پول:</strong></label><br>
                        <select name="currency" style="width: 100%;">
                            <option value="تومان">تومان</option>
                            <option value="ریال">ریال</option>
                            <option value="هزار تومان">هزار تومان</option>
                        </select>
                    </div>
                </div>
                <div style="margin-top: 12px;">
                    <label><strong>نشانی کامل خریدار:</strong></label><br>
                    <textarea name="buyer_address" rows="2" style="width: 100%;" placeholder="استان، شهر، خیابان..."></textarea>
                </div>
            </div>

            <div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ccd0d4; margin-bottom: 20px;">
                <h3 style="color: #0f766e; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">لیست اقلام و کالاها</h3>
                
                <table id="woo_factor_items_table" style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                    <thead>
                        <tr style="background: #f1f5f9; text-align: center;">
                            <th style="padding: 8px; border: 1px solid #cbd5e1; width: 40%;">شرح کالا یا خدمت</th>
                            <th style="padding: 8px; border: 1px solid #cbd5e1; width: 12%;">تعداد</th>
                            <th style="padding: 8px; border: 1px solid #cbd5e1; width: 20%;">قیمت واحد</th>
                            <th style="padding: 8px; border: 1px solid #cbd5e1; width: 20%;">جمع کل</th>
                            <th style="padding: 8px; border: 1px solid #cbd5e1; width: 8%;">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding: 6px; border: 1px solid #cbd5e1;"><input type="text" name="items[0][title]" placeholder="عنوان محصول" style="width: 100%;" required></td>
                            <td style="padding: 6px; border: 1px solid #cbd5e1;"><input type="number" name="items[0][qty]" value="1" min="1" class="item-qty" style="width: 100%; text-align: center;"></td>
                            <td style="padding: 6px; border: 1px solid #cbd5e1;"><input type="number" name="items[0][price]" value="0" min="0" class="item-price" style="width: 100%; text-align: center;"></td>
                            <td style="padding: 6px; border: 1px solid #cbd5e1; text-align: center; font-weight: bold;"><span class="item-subtotal">۰</span></td>
                            <td style="padding: 6px; border: 1px solid #cbd5e1; text-align: center;"><button type="button" class="button remove-row-btn">✖</button></td>
                        </tr>
                    </tbody>
                </table>
                <div style="margin-top: 10px;">
                    <button type="button" id="add_item_row_btn" class="button button-secondary">+ افزودن سطر کالا</button>
                </div>
            </div>

            <div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ccd0d4; margin-bottom: 20px;">
                <h3 style="color: #0f766e; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">هزینه‌های جانبی و توضیحات</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-top: 15px;">
                    <div>
                        <label><strong>تخفیف کل:</strong></label><br>
                        <input type="number" name="discount" value="0" min="0" class="regular-text" style="width: 100%;">
                    </div>
                    <div>
                        <label><strong>هزینه ارسال / حمل و نقل:</strong></label><br>
                        <input type="number" name="shipping" value="0" min="0" class="regular-text" style="width: 100%;">
                    </div>
                    <div>
                        <label><strong>مالیات و عوارض:</strong></label><br>
                        <input type="number" name="tax" value="0" min="0" class="regular-text" style="width: 100%;">
                    </div>
                </div>
                <div style="margin-top: 12px;">
                    <label><strong>توضیحات یا یادداشت فاکتور:</strong></label><br>
                    <textarea name="note" rows="2" style="width: 100%;" placeholder="توضیحات مربوط به نحوه تسویه یا تحویل..."></textarea>
                </div>
            </div>

            <button type="submit" class="button button-primary button-hero" style="background: #0f766e; border-color: #0f766e;">🖨️ صدور و پیش‌نمایش چاپ فاکتور دستی</button>
        </form>
    </div>

    <script>
    jQuery(document).ready(function($){
        var rowIdx = 1;
        $('#add_item_row_btn').on('click', function(){
            var rowHtml = '<tr>' +
                '<td style="padding: 6px; border: 1px solid #cbd5e1;"><input type="text" name="items['+rowIdx+'][title]" placeholder="عنوان محصول" style="width: 100%;" required></td>' +
                '<td style="padding: 6px; border: 1px solid #cbd5e1;"><input type="number" name="items['+rowIdx+'][qty]" value="1" min="1" class="item-qty" style="width: 100%; text-align: center;"></td>' +
                '<td style="padding: 6px; border: 1px solid #cbd5e1;"><input type="number" name="items['+rowIdx+'][price]" value="0" min="0" class="item-price" style="width: 100%; text-align: center;"></td>' +
                '<td style="padding: 6px; border: 1px solid #cbd5e1; text-align: center; font-weight: bold;"><span class="item-subtotal">۰</span></td>' +
                '<td style="padding: 6px; border: 1px solid #cbd5e1; text-align: center;"><button type="button" class="button remove-row-btn">✖</button></td>' +
            '</tr>';
            $('#woo_factor_items_table tbody').append(rowHtml);
            rowIdx++;
        });

        $(document).on('click', '.remove-row-btn', function(){
            if ($('#woo_factor_items_table tbody tr').length > 1) {
                $(this).closest('tr').remove();
            }
        });

        $(document).on('input', '.item-qty, .item-price', function(){
            var tr = $(this).closest('tr');
            var qty = parseFloat(tr.find('.item-qty').val()) || 0;
            var price = parseFloat(tr.find('.item-price').val()) || 0;
            tr.find('.item-subtotal').text((qty * price).toLocaleString('fa-IR'));
        });
    });
    </script>
    <?php
}

// Handle Manual Invoice Generation AJAX
add_action('wp_ajax_woo_factor_generate_manual_invoice', function () {
    if (!current_user_can('manage_woocommerce')) {
        wp_die('دسترسی غیرمجاز.');
    }
    check_admin_referer('woo_factor_manual_nonce');

    $opts = woo_factor_options();
    $template = sanitize_text_field($_POST['template'] ?? 'classic');
    $currency = sanitize_text_field($_POST['currency'] ?? 'تومان');
    $inv_num  = sanitize_text_field(wp_unslash($_POST['invoice_number'] ?? ('MAN-' . rand(1000, 9999))));
        $inv_num  = preg_replace('/[^A-Za-z0-9\\-_]/', '', $inv_num);
        if ($inv_num === '') { $inv_num = 'MAN-' . wp_rand(10000, 99999); }

    $seller = [
        'name'        => !empty($opts['shop_name']) ? $opts['shop_name'] : get_bloginfo('name'),
        'phone'       => $opts['shop_phone'] ?? '',
        'email'       => !empty($opts['shop_email']) ? $opts['shop_email'] : get_bloginfo('admin_email'),
        'national_id' => $opts['shop_national_id'] ?? '',
        'address'     => $opts['shop_address'] ?? '',
        'website'     => home_url(),
        'logo_url'    => woo_factor_get_logo_url(),
    ];

    $buyer = [
        'name'          => sanitize_text_field($_POST['buyer_name'] ?? 'مشتری محترم'),
        'company'       => '',
        'national_id'   => sanitize_text_field($_POST['buyer_national_id'] ?? ''),
        'economic_id'   => '',
        'phone'         => sanitize_text_field($_POST['buyer_phone'] ?? ''),
        'email'         => '',
        'postcode'      => '',
        'full_address'  => sanitize_textarea_field($_POST['buyer_address'] ?? ''),
        'shipping_address' => sanitize_textarea_field($_POST['buyer_address'] ?? ''),
        'shipping_name' => sanitize_text_field($_POST['buyer_name'] ?? 'مشتری محترم'),
        'shipping_phone'=> sanitize_text_field($_POST['buyer_phone'] ?? ''),
        'shipping_postcode' => '',
    ];

    $items_raw = $_POST['items'] ?? [];
    $items = [];
    $subtotal = 0;
    $idx = 1;

    foreach ($items_raw as $it) {
        $title = sanitize_text_field($it['title'] ?? '');
        if (empty($title)) continue;
        $qty = max(1, (int)($it['qty'] ?? 1));
        $price = max(0, (float)($it['price'] ?? 0));
        $tot = $qty * $price;
        $subtotal += $tot;

        $items[] = [
            'index'      => $idx++,
            'id'         => 0,
            'sku'        => '-',
            'title'      => $title,
            'qty'        => $qty,
            'price'      => $price,
            'unit_price' => $price,
            'subtotal'   => $tot,
            'discount'   => 0,
            'tax'        => 0,
            'total'      => $tot,
        ];
    }

    $discount = max(0, (float)($_POST['discount'] ?? 0));
    $shipping = max(0, (float)($_POST['shipping'] ?? 0));
    $tax      = max(0, (float)($_POST['tax'] ?? 0));
    $grand    = woo_factor_calculate_manual_grand_total($subtotal, $discount, $shipping, $tax);

    $totals = [
        'subtotal'          => $subtotal,
        'discount'          => $discount,
        'shipping'          => $shipping,
        'shipping_method'   => $shipping > 0 ? 'پیک / پست' : 'تحویل حضوری',
        'tax'               => $tax,
        'grand_total'       => $grand,
        'grand_total_words' => woo_factor_number_to_words($grand),
        'currency'          => $currency,
        'payment_method'    => 'نقدی / تسویه دستی',
        'transaction_id'    => '',
    ];

    $seller['stamp_url'] = woo_factor_get_stamp_url();
    $seller['economic_code'] = $opts['shop_economic_code'] ?? '';
    $seller['registration_no'] = $opts['shop_registration_no'] ?? '';
    $seller['postal_code'] = $opts['shop_postal_code'] ?? '';

    $data = [
        'type'                 => 'manual',
        'order_id'             => 0,
        'order_number'         => $inv_num,
        'invoice_number'       => $inv_num,
        'jalali_date'          => woo_factor_jdate(current_time('timestamp'), false),
        'jalali_time'          => woo_factor_jdate(current_time('timestamp'), true),
        'status'               => 'completed',
        'status_name'          => 'تسویه شده',
        'seller'               => $seller,
        'buyer'                => $buyer,
        'items'                => $items,
        'totals'               => $totals,
        'barcode_svg'          => Woo_Factor_Barcode_128::get_svg($inv_num, 40, 1.5),
        'customer_note'        => sanitize_textarea_field($_POST['note'] ?? ''),
        'footer_note'          => $opts['footer_note'] ?? 'از خرید شما سپاسگزاریم.',
        'invoice_terms'        => $opts['invoice_terms'] ?? '',
        'signature_stamp'      => $opts['signature_stamp'] ?? 'مهر و امضای فروشگاه',
        'stamp_url'            => $seller['stamp_url'],
        'color'                => woo_factor_normalize_color($opts['color'] ?? ''),
        'watermark_text'       => 'پرداخت شد',
        'show_barcode'         => ($opts['show_barcode'] ?? 'yes') === 'yes',
        'show_product_image'   => false,
        'show_sku'             => false,
        'show_tax_column'      => ($opts['show_tax_column'] ?? 'yes') === 'yes',
        'show_discount_column' => ($opts['show_discount_column'] ?? 'yes') === 'yes',
        'show_watermark'       => ($opts['show_watermark'] ?? 'yes') === 'yes',
        'show_signature'       => ($opts['show_signature'] ?? 'yes') === 'yes',
    ];

    echo Woo_Factor_Renderer::render_html($data, $template);
    exit;
});
