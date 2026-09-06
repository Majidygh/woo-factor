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

// Load Media Uploader scripts for logo selection
add_action('admin_enqueue_scripts', function ($hook) {
    if (strpos($hook, 'woo_factor') !== false) {
        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
    }
});

function woo_factor_render_settings_page() {
    $opts = woo_factor_options();
    $templates = woo_factor_templates();
    ?>
    <div class="wrap" style="direction: rtl; text-align: right; max-width: 1000px;">
        <h1 style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
            <span class="dashicons dashicons-media-spreadsheet" style="font-size: 32px; width: 32px; height: 32px;"></span>
            <span>تنظیمات پیشرفته فاکتورساز حرفه‌ای ووکامرس (ووفاکتور)</span>
        </h1>

        <?php if (isset($_GET['settings-updated'])): ?>
            <div class="notice notice-success is-dismissible"><p>تنظیمات با موفقیت ذخیره شدند.</p></div>
        <?php endif; ?>

        <form method="post" action="options.php">
            <?php settings_fields('woo_factor_settings_group'); ?>

            <div style="background: #fff; padding: 20px 25px; border-radius: 8px; border: 1px solid #ccd0d4; margin-bottom: 20px;">
                <h2 style="border-bottom: 2px solid #0f766e; padding-bottom: 8px; color: #0f766e;">🎨 ۱. انتخاب قالب پیش‌فرض فاکتور</h2>
                <p class="description">قالب دلخواه خود را برای صدور خودکار و دستی فاکتورها انتخاب نمایید:</p>

                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-top: 15px;">
                    <?php foreach ($templates as $key => $tpl): 
                        $is_checked = ($opts['template'] === $key);
                    ?>
                        <label style="border: 2px solid <?php echo $is_checked ? '#0f766e' : '#e2e8f0'; ?>; background: <?php echo $is_checked ? '#f0fdf4' : '#fff'; ?>; padding: 15px; border-radius: 8px; cursor: pointer; display: block;">
                            <input type="radio" name="woo_factor_options[template]" value="<?php echo esc_attr($key); ?>" <?php checked($opts['template'], $key); ?> style="margin-left: 6px;">
                            <strong style="font-size: 14px; color: #0f172a;"><?php echo esc_html($tpl['name']); ?></strong>
                            <p style="font-size: 12px; color: #64748b; margin-top: 8px; line-height: 1.5;"><?php echo esc_html($tpl['desc']); ?></p>
                        </label>
                    <?php endforeach; ?>
                </div>

                <div style="margin-top: 20px;">
                    <label><strong>رنگ سازمانی و تم فاکتورها:</strong></label><br>
                    <input type="text" name="woo_factor_options[color]" value="<?php echo esc_attr($opts['color'] ?? '#0f766e'); ?>" class="woo_factor-color-field" data-default-color="#0f766e">
                </div>
            </div>

            <div style="background: #fff; padding: 20px 25px; border-radius: 8px; border: 1px solid #ccd0d4; margin-bottom: 20px;">
                <h2 style="border-bottom: 2px solid #0f766e; padding-bottom: 8px; color: #0f766e;">🏢 ۲. مشخصات فروشگاه و فروشنده</h2>
                
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label>نام فروشگاه / شرکت</label></th>
                        <td><input name="woo_factor_options[shop_name]" type="text" value="<?php echo esc_attr($opts['shop_name']); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>لوگوی اختصاصی فروشگاه</label></th>
                        <td>
                            <input type="hidden" name="woo_factor_options[logo_id]" id="woo_factor_logo_id" value="<?php echo esc_attr($opts['logo_id']); ?>">
                            <div id="woo_factor_logo_preview" style="margin-bottom: 10px;">
                                <?php if (!empty($opts['logo_id'])): 
                                    $img = wp_get_attachment_image_src($opts['logo_id'], 'medium');
                                    if ($img): ?>
                                        <img src="<?php echo esc_url($img[0]); ?>" style="max-height: 80px; max-width: 200px; display: block; margin-bottom: 5px;">
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="button" id="woo_factor_upload_logo_btn">انتخاب / تغییر لوگو</button>
                            <button type="button" class="button" id="woo_factor_remove_logo_btn" style="<?php echo empty($opts['logo_id']) ? 'display:none;' : ''; ?>">حذف لوگو</button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label>شناسه ملی / کد اقتصادی</label></th>
                        <td><input name="woo_factor_options[shop_national_id]" type="text" value="<?php echo esc_attr($opts['shop_national_id']); ?>" class="regular-text" placeholder="مثال: ۱۰۱۰۲۳۴۵۶۷۸"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>تلفن‌های تماس</label></th>
                        <td><input name="woo_factor_options[shop_phone]" type="text" value="<?php echo esc_attr($opts['shop_phone']); ?>" class="regular-text" placeholder="مثال: ۰۲۱-۸۸۸۸۸۸۸۸"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>ایمیل فروشگاه</label></th>
                        <td><input name="woo_factor_options[shop_email]" type="email" value="<?php echo esc_attr($opts['shop_email']); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>آدرس کامل فروشگاه</label></th>
                        <td><textarea name="woo_factor_options[shop_address]" rows="3" class="large-text"><?php echo esc_textarea($opts['shop_address']); ?></textarea></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>متن پاورقی فاکتور</label></th>
                        <td><textarea name="woo_factor_options[footer_note]" rows="2" class="large-text" placeholder="مثال: از خرید شما متشکریم. اجناس فروخته شده تا ۷ روز قابل تعویض می‌باشند."><?php echo esc_textarea($opts['footer_note']); ?></textarea></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>متن کادر مهر و امضا</label></th>
                        <td><input name="woo_factor_options[signature_stamp]" type="text" value="<?php echo esc_attr($opts['signature_stamp']); ?>" class="regular-text"></td>
                    </tr>
                </table>
            </div>

            <div style="background: #fff; padding: 20px 25px; border-radius: 8px; border: 1px solid #ccd0d4; margin-bottom: 20px;">
                <h2 style="border-bottom: 2px solid #0f766e; padding-bottom: 8px; color: #0f766e;">⚙️ ۳. تنظیمات ارسال خودکار و قابلیت‌ها</h2>
                
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label>ارسال لینک فاکتور با ایمیل مشتری</label></th>
                        <td>
                            <label><input name="woo_factor_options[attach_woocommerce]" type="checkbox" value="yes" <?php checked($opts['attach_woocommerce'] ?? 'yes', 'yes'); ?>> اضافه کردن دکمه چاپ و مشاهده فاکتور به ایمیل‌های ارسالی ووکامرس برای مشتری</label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label>نمایش در پنل حساب کاربری مشتری</label></th>
                        <td>
                            <label><input name="woo_factor_options[myaccount_page]" type="checkbox" value="yes" <?php checked($opts['myaccount_page'] ?? 'yes', 'yes'); ?>> اضافه کردن دکمه «دریافت فاکتور» در صفحه سفارشات من در حساب کاربری</label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label>پیشوند شماره فاکتور</label></th>
                        <td>
                            <input name="woo_factor_options[invoice_prefix]" type="text" value="<?php echo esc_attr($opts['invoice_prefix'] ?? ''); ?>" class="small-text" placeholder="مثال: INV-">
                            <span class="description">در صورت خالی بودن، دقیقاً همان شماره سفارش ووکامرس درج می‌شود.</span>
                        </td>
                    </tr>
                </table>
            </div>

            <?php submit_button('ذخیره تغییرات فاکتورساز', 'primary large'); ?>
        </form>
    </div>

    <script>
    jQuery(document).ready(function($){
        if ($.fn.wpColorPicker) {
            $('.woo_factor-color-field').wpColorPicker();
        }

        // Media uploader for logo
        var file_frame;
        $('#woo_factor_upload_logo_btn').on('click', function(event){
            event.preventDefault();
            if (file_frame) {
                file_frame.open();
                return;
            }
            file_frame = wp.media.frames.file_frame = wp.media({
                title: 'انتخاب لوگوی فروشگاه',
                button: { text: 'استفاده به عنوان لوگو' },
                multiple: false
            });
            file_frame.on('select', function(){
                var attachment = file_frame.state().get('selection').first().toJSON();
                $('#woo_factor_logo_id').val(attachment.id);
                $('#woo_factor_logo_preview').html('<img src="' + attachment.url + '" style="max-height: 80px; max-width: 200px; display: block; margin-bottom: 5px;">');
                $('#woo_factor_remove_logo_btn').show();
            });
            file_frame.open();
        });

        $('#woo_factor_remove_logo_btn').on('click', function(){
            $('#woo_factor_logo_id').val('');
            $('#woo_factor_logo_preview').html('');
            $(this).hide();
        });
    });
    </script>
    <?php
}

require_once WOO_FACTOR_DIR . 'admin/manual-invoice.php';
