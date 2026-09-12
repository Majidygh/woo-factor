<?php
/**
 * Admin Settings Page for Woo Factor Invoice
 */
defined('ABSPATH') || exit;

add_action('admin_menu', function () {
    add_menu_page(
        __('فاکتورساز ووکامرس', 'woo-factor'),
        __('فاکتورساز ووکامرس', 'woo-factor'),
        'manage_woocommerce',
        'woo-factor',
        'woo_factor_render_settings_page',
        'dashicons-media-spreadsheet',
        56
    );

    add_submenu_page(
        'woo-factor',
        __('تنظیمات فاکتور', 'woo-factor'),
        __('تنظیمات فاکتور', 'woo-factor'),
        'manage_woocommerce',
        'woo-factor',
        'woo_factor_render_settings_page'
    );

    add_submenu_page(
        'woo-factor',
        __('صدور فاکتور دستی', 'woo-factor'),
        __('صدور فاکتور دستی', 'woo-factor'),
        'manage_woocommerce',
        'woo-factor-manual-invoice',
        'woo_factor_render_manual_invoice_page'
    );
});

add_action('admin_init', function () {
    register_setting('woo_factor_settings_group', 'woo_factor_options', [
        'sanitize_callback' => 'woo_factor_sanitize_options',
        'default' => [],
    ]);
});

add_action('admin_enqueue_scripts', function ($hook) {
    if (strpos($hook, 'woo_factor') !== false || strpos($hook, 'woo-factor') !== false) {
        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
    }
});

function woo_factor_render_settings_page() {
    $opts = woo_factor_options();
    $templates = woo_factor_templates();
    ?>
    <div class="wrap" style="direction: rtl; text-align: right; max-width: 1060px;">
        <h1 style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
            <span class="dashicons dashicons-media-spreadsheet" style="font-size: 34px; width: 34px; height: 34px; color: #0f766e;"></span>
            <span>تنظیمات پیشرفته فاکتورساز ووکامرس (نسخه ۲.۰)</span>
        </h1>

        <?php if (isset($_GET['settings-updated'])): ?>
            <div class="notice notice-success is-dismissible"><p>تنظیمات با موفقیت ذخیره شدند.</p></div>
        <?php endif; ?>

        <form method="post" action="options.php">
            <?php settings_fields('woo_factor_settings_group'); ?>

            <!-- Section 1: Template Selection -->
            <div style="background: #fff; padding: 22px 26px; border-radius: 10px; border: 1px solid #cbd5e1; margin-bottom: 22px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <h2 style="border-bottom: 2px solid #0f766e; padding-bottom: 8px; color: #0f766e; margin-top: 0;">🎨 ۱. انتخاب قالب پیش‌فرض و رنگ‌بندی سازمانی</h2>
                <p class="description" style="margin-bottom: 16px;">قالب دلخواه خود را برای صدور خودکار و پیش‌فرض فاکتورها انتخاب کنید:</p>

                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
                    <?php foreach ($templates as $key => $tpl): 
                        $is_checked = ($opts['template'] === $key);
                    ?>
                        <label style="border: 2px solid <?php echo $is_checked ? '#0f766e' : '#e2e8f0'; ?>; background: <?php echo $is_checked ? '#f0fdf4' : '#fff'; ?>; padding: 16px; border-radius: 8px; cursor: pointer; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.2s;">
                            <div>
                                <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                                    <input type="radio" name="woo_factor_options[template]" value="<?php echo esc_attr($key); ?>" <?php checked($opts['template'], $key); ?>>
                                    <strong style="font-size: 13.5px; color: #0f172a;"><?php echo esc_html($tpl['name']); ?></strong>
                                </div>
                                <p style="font-size: 11.5px; color: #64748b; line-height: 1.5; margin: 0;"><?php echo esc_html($tpl['desc']); ?></p>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>

                <div style="margin-top: 20px; display: flex; align-items: center; gap: 20px;">
                    <div>
                        <label><strong>رنگ تم و المان‌های شاخص فاکتور:</strong></label><br>
                        <input type="text" name="woo_factor_options[color]" value="<?php echo esc_attr($opts['color'] ?? '#0f766e'); ?>" class="woo_factor-color-field" data-default-color="#0f766e">
                    </div>
                </div>
            </div>

            <!-- Section 2: Seller & Legal Information -->
            <div style="background: #fff; padding: 22px 26px; border-radius: 10px; border: 1px solid #cbd5e1; margin-bottom: 22px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <h2 style="border-bottom: 2px solid #0f766e; padding-bottom: 8px; color: #0f766e; margin-top: 0;">🏢 ۲. مشخصات هویتی و حقوقی فروشنده (دارایی / رسمی)</h2>
                
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label>نام فروشگاه / شرکت رسمی</label></th>
                        <td><input name="woo_factor_options[shop_name]" type="text" value="<?php echo esc_attr($opts['shop_name']); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>شناسه ملی / کد ملی</label></th>
                        <td><input name="woo_factor_options[shop_national_id]" type="text" value="<?php echo esc_attr($opts['shop_national_id'] ?? ''); ?>" class="regular-text" placeholder="مثال: ۱۰۱۰۲۳۴۵۶۷۸"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>کد اقتصادی</label></th>
                        <td><input name="woo_factor_options[shop_economic_code]" type="text" value="<?php echo esc_attr($opts['shop_economic_code'] ?? ''); ?>" class="regular-text" placeholder="کد اقتصادی ۱۲ یا ۱۴ رقمی"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>شماره ثبت / مجوز کسب</label></th>
                        <td><input name="woo_factor_options[shop_registration_no]" type="text" value="<?php echo esc_attr($opts['shop_registration_no'] ?? ''); ?>" class="regular-text" placeholder="شماره ثبت شرکت یا پروانه کسب"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>کد پستی ۱۰ رقمی</label></th>
                        <td><input name="woo_factor_options[shop_postal_code]" type="text" value="<?php echo esc_attr($opts['shop_postal_code'] ?? ''); ?>" class="regular-text" placeholder="مثال: ۱۹۸۵۷۱۱۱۱۱"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>تلفن‌های تماس و پشتیبانی</label></th>
                        <td><input name="woo_factor_options[shop_phone]" type="text" value="<?php echo esc_attr($opts['shop_phone']); ?>" class="regular-text" placeholder="مثال: ۰۲۱-۸۸۸۸۸۸۸۸"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>ایمیل رسمی فروشگاه</label></th>
                        <td><input name="woo_factor_options[shop_email]" type="email" value="<?php echo esc_attr($opts['shop_email']); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>نشانی کامل پستی</label></th>
                        <td><textarea name="woo_factor_options[shop_address]" rows="3" class="large-text"><?php echo esc_textarea($opts['shop_address']); ?></textarea></td>
                    </tr>
                </table>
            </div>

            <!-- Section 3: Logo & Stamp Upload -->
            <div style="background: #fff; padding: 22px 26px; border-radius: 10px; border: 1px solid #cbd5e1; margin-bottom: 22px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <h2 style="border-bottom: 2px solid #0f766e; padding-bottom: 8px; color: #0f766e; margin-top: 0;">🖼️ ۳. لوگوی اختصاصی و تصویر مهر/امضا</h2>
                
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label>لوگوی اختصاصی فروشگاه</label></th>
                        <td>
                            <input type="hidden" name="woo_factor_options[logo_id]" id="woo_factor_logo_id" value="<?php echo esc_attr($opts['logo_id']); ?>">
                            <div id="woo_factor_logo_preview" style="margin-bottom: 10px;">
                                <?php if (!empty($opts['logo_id'])): 
                                    $img = wp_get_attachment_image_src($opts['logo_id'], 'medium');
                                    if ($img): ?>
                                        <img src="<?php echo esc_url($img[0]); ?>" style="max-height: 75px; max-width: 180px; display: block; margin-bottom: 6px; border: 1px solid #e2e8f0; border-radius: 4px; padding: 4px;">
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="button" id="woo_factor_upload_logo_btn">انتخاب / تغییر لوگو</button>
                            <button type="button" class="button" id="woo_factor_remove_logo_btn" style="<?php echo empty($opts['logo_id']) ? 'display:none;' : ''; ?>">حذف لوگو</button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label>تصویر مهر و امضای رسمی (شفاف PNG)</label></th>
                        <td>
                            <input type="hidden" name="woo_factor_options[stamp_image_id]" id="woo_factor_stamp_id" value="<?php echo esc_attr($opts['stamp_image_id'] ?? ''); ?>">
                            <div id="woo_factor_stamp_preview" style="margin-bottom: 10px;">
                                <?php if (!empty($opts['stamp_image_id'])): 
                                    $stamp_img = wp_get_attachment_image_src($opts['stamp_image_id'], 'medium');
                                    if ($stamp_img): ?>
                                        <img src="<?php echo esc_url($stamp_img[0]); ?>" style="max-height: 75px; max-width: 180px; display: block; margin-bottom: 6px; border: 1px dashed #cbd5e1; border-radius: 4px; padding: 4px;">
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="button" id="woo_factor_upload_stamp_btn">انتخاب تصویر مهر و امضا</button>
                            <button type="button" class="button" id="woo_factor_remove_stamp_btn" style="<?php echo empty($opts['stamp_image_id']) ? 'display:none;' : ''; ?>">حذف تصویر مهر</button>
                            <p class="description">پیشنهاد می‌شود از تصویر با پس‌زمینه شفاف (فرمت PNG) استفاده فرمایید تا روی کادر مهر قرار گیرد.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label>عنوان کادر مهر و امضا</label></th>
                        <td>
                            <input name="woo_factor_options[signature_stamp]" type="text" value="<?php echo esc_attr($opts['signature_stamp'] ?? 'مهر و امضای فروشگاه'); ?>" class="regular-text">
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Section 4: Display Elements & Features -->
            <div style="background: #fff; padding: 22px 26px; border-radius: 10px; border: 1px solid #cbd5e1; margin-bottom: 22px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <h2 style="border-bottom: 2px solid #0f766e; padding-bottom: 8px; color: #0f766e; margin-top: 0;">⚡ ۴. ستون‌ها و امکانات پیشرفته فاکتور</h2>
                
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row">نمایش کدهای رهگیری</th>
                        <td>
                            <fieldset>
                                <label style="display: block; margin-bottom: 6px;">
                                    <input name="woo_factor_options[show_qrcode]" type="checkbox" value="yes" <?php checked($opts['show_qrcode'] ?? 'yes', 'yes'); ?>>
                                    <strong>تولید کد QR هوشمند:</strong> درج بارکد دو بعدی QR برای رهگیری و اعتبارسنجی آنلاین فاکتور با موبایل
                                </label>
                                <label style="display: block;">
                                    <input name="woo_factor_options[show_barcode]" type="checkbox" value="yes" <?php checked($opts['show_barcode'] ?? 'yes', 'yes'); ?>>
                                    <strong>بارکد خطی Code128:</strong> درج بارکد استاندارد شماره سفارش جهت دستگاه‌های بارکدخوان و انبارداری
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">شخصی‌سازی ستون‌های اقلام</th>
                        <td>
                            <fieldset>
                                <label style="display: block; margin-bottom: 6px;">
                                    <input name="woo_factor_options[show_product_image]" type="checkbox" value="yes" <?php checked($opts['show_product_image'] ?? 'yes', 'yes'); ?>>
                                    نمایش تصویر بندانگشتی محصول در جدول اقلام
                                </label>
                                <label style="display: block; margin-bottom: 6px;">
                                    <input name="woo_factor_options[show_sku]" type="checkbox" value="yes" <?php checked($opts['show_sku'] ?? 'yes', 'yes'); ?>>
                                    نمایش شناسه محصول (کد SKU) در فاکتور
                                </label>
                                <label style="display: block; margin-bottom: 6px;">
                                    <input name="woo_factor_options[show_discount_column]" type="checkbox" value="yes" <?php checked($opts['show_discount_column'] ?? 'yes', 'yes'); ?>>
                                    نمایش ستون تخفیف در جدول اقلام
                                </label>
                                <label style="display: block;">
                                    <input name="woo_factor_options[show_tax_column]" type="checkbox" value="yes" <?php checked($opts['show_tax_column'] ?? 'yes', 'yes'); ?>>
                                    نمایش ستون مالیات و عوارض در جدول اقلام
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">واترمارک وضعیت فاکتور</th>
                        <td>
                            <label style="display: block; margin-bottom: 6px;">
                                <input name="woo_factor_options[show_watermark]" type="checkbox" value="yes" <?php checked($opts['show_watermark'] ?? 'yes', 'yes'); ?>>
                                فعال‌سازی واترمارک پس‌زمینه (مانند «پرداخت شد»، «باطل شد» و «پیش‌فاکتور»)
                            </label>
                            <input name="woo_factor_options[watermark_text]" type="text" value="<?php echo esc_attr($opts['watermark_text'] ?? ''); ?>" class="regular-text" placeholder="متن سفارشی (در صورت خالی بودن، خودکار بر اساس وضعیت سفارش تعیین می‌شود)">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label>شرایط، گارانتی و تعهدات خرید</label></th>
                        <td>
                            <textarea name="woo_factor_options[invoice_terms]" rows="3" class="large-text"><?php echo esc_textarea($opts['invoice_terms'] ?? ''); ?></textarea>
                            <p class="description">متن قوانین، شرایط مرجوعی کالا یا گارانتی که در پایین فاکتور نمایش داده می‌شود.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label>متن تشکر و پاورقی</label></th>
                        <td>
                            <textarea name="woo_factor_options[footer_note]" rows="2" class="large-text"><?php echo esc_textarea($opts['footer_note']); ?></textarea>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Section 5: Automated Delivery & Account -->
            <div style="background: #fff; padding: 22px 26px; border-radius: 10px; border: 1px solid #cbd5e1; margin-bottom: 22px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <h2 style="border-bottom: 2px solid #0f766e; padding-bottom: 8px; color: #0f766e; margin-top: 0;">🚀 ۵. ادغام با ایمیل، حساب کاربری و شماره فاکتور</h2>
                
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label>ارسال لینک فاکتور با ایمیل مشتری</label></th>
                        <td>
                            <label><input name="woo_factor_options[attach_woocommerce]" type="checkbox" value="yes" <?php checked($opts['attach_woocommerce'] ?? 'yes', 'yes'); ?>> اضافه کردن دکمه دسترسی سریع و چاپ آنلاین فاکتور در ایمیل‌های ارسالی سفارش برای مشتری</label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label>نمایش در پنل حساب کاربری مشتری</label></th>
                        <td>
                            <label><input name="woo_factor_options[myaccount_page]" type="checkbox" value="yes" <?php checked($opts['myaccount_page'] ?? 'yes', 'yes'); ?>> اضافه کردن دکمه «دریافت فاکتور» در لیست سفارشات کاربر در حساب کاربری</label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label>پیشوند شماره فاکتور</label></th>
                        <td>
                            <input name="woo_factor_options[invoice_prefix]" type="text" value="<?php echo esc_attr($opts['invoice_prefix'] ?? ''); ?>" class="small-text" placeholder="مثال: INV-">
                            <span class="description">در صورت خالی بودن، شماره سفارش ووکامرس بدون پیشوند درج می‌شود.</span>
                        </td>
                    </tr>
                </table>
            </div>

            <?php submit_button('ذخیره تغییرات و به‌روزرسانی فاکتورساز', 'primary large'); ?>
        </form>
    </div>

    <script>
    jQuery(document).ready(function($){
        if ($.fn.wpColorPicker) {
            $('.woo_factor-color-field').wpColorPicker();
        }

        // 1. Media uploader for Logo
        var logo_frame;
        $('#woo_factor_upload_logo_btn').on('click', function(e){
            e.preventDefault();
            if (logo_frame) { logo_frame.open(); return; }
            logo_frame = wp.media.frames.logo_frame = wp.media({
                title: 'انتخاب لوگوی فروشگاه',
                button: { text: 'استفاده به عنوان لوگو' },
                multiple: false
            });
            logo_frame.on('select', function(){
                var att = logo_frame.state().get('selection').first().toJSON();
                $('#woo_factor_logo_id').val(att.id);
                $('#woo_factor_logo_preview').html('<img src="' + att.url + '" style="max-height: 75px; max-width: 180px; display: block; margin-bottom: 6px; border: 1px solid #e2e8f0; border-radius: 4px; padding: 4px;">');
                $('#woo_factor_remove_logo_btn').show();
            });
            logo_frame.open();
        });

        $('#woo_factor_remove_logo_btn').on('click', function(){
            $('#woo_factor_logo_id').val('');
            $('#woo_factor_logo_preview').html('');
            $(this).hide();
        });

        // 2. Media uploader for Stamp
        var stamp_frame;
        $('#woo_factor_upload_stamp_btn').on('click', function(e){
            e.preventDefault();
            if (stamp_frame) { stamp_frame.open(); return; }
            stamp_frame = wp.media.frames.stamp_frame = wp.media({
                title: 'انتخاب تصویر مهر و امضا (PNG شفاف)',
                button: { text: 'استفاده به عنوان مهر' },
                multiple: false
            });
            stamp_frame.on('select', function(){
                var att = stamp_frame.state().get('selection').first().toJSON();
                $('#woo_factor_stamp_id').val(att.id);
                $('#woo_factor_stamp_preview').html('<img src="' + att.url + '" style="max-height: 75px; max-width: 180px; display: block; margin-bottom: 6px; border: 1px dashed #cbd5e1; border-radius: 4px; padding: 4px;">');
                $('#woo_factor_remove_stamp_btn').show();
            });
            stamp_frame.open();
        });

        $('#woo_factor_remove_stamp_btn').on('click', function(){
            $('#woo_factor_stamp_id').val('');
            $('#woo_factor_stamp_preview').html('');
            $(this).hide();
        });
    });
    </script>
    <?php
}

require_once WOO_FACTOR_DIR . 'admin/manual-invoice.php';
