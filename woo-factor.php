<?php
/**
 * Plugin Name: ووفاکتور | Woo Factor for WooCommerce
 * Plugin URI:  https://github.com/majidygh/woo-factor
 * Description: پیشرفته‌ترین افزونه صدور فاکتور و برچسب پستی فارسی برای ووکامرس — دارای ۳ قالب جذاب و مدرن، تاریخ شمسی، بارکد سفارش، صدور فاکتور دستی، پیش‌فاکتور و ارسال ایمیل خودکار.
 * Version:           1.2.0
 * Author:      مجید یعقوبی (majidygh)
 * Author URI:  https://github.com/majidygh
 * Text Domain: woo-factor
 * Domain Path: /languages
 * Requires PHP: 7.4
 * WC requires at least: 6.0
 */

defined('ABSPATH') || exit;

define('WOO_FACTOR_VERSION', '1.2.0');
define('WOO_FACTOR_FILE', __FILE__);
define('WOO_FACTOR_DIR', plugin_dir_path(__FILE__));
define('WOO_FACTOR_URL', plugin_dir_url(__FILE__));

require_once WOO_FACTOR_DIR . 'includes/jalali.php';
require_once WOO_FACTOR_DIR . 'includes/persian-numbers.php';
require_once WOO_FACTOR_DIR . 'includes/helpers.php';
require_once WOO_FACTOR_DIR . 'includes/barcode.php';
require_once WOO_FACTOR_DIR . 'includes/builder.php';
require_once WOO_FACTOR_DIR . 'includes/render.php';
require_once WOO_FACTOR_DIR . 'includes/pdf.php';
require_once WOO_FACTOR_DIR . 'includes/hooks.php';
require_once WOO_FACTOR_DIR . 'includes/proforma.php';
require_once WOO_FACTOR_DIR . 'includes/shortcode.php';

if (is_admin()) {
    require_once WOO_FACTOR_DIR . 'admin/settings.php';
    require_once WOO_FACTOR_DIR . 'admin/meta-boxes.php';
}

register_activation_hook(__FILE__, 'woo_factor_activate');
register_deactivation_hook(__FILE__, 'woo_factor_deactivate');

function woo_factor_activate() {
    $opts = get_option('woo_factor_options', []);
    if (!is_array($opts)) {
        $opts = [];
    }
    $defaults = woo_factor_default_options();
    update_option('woo_factor_options', array_merge($defaults, $opts));

    if (!wp_next_scheduled('woo_factor_daily_retention_cron')) {
        wp_schedule_event(strtotime('tomorrow 03:30'), 'daily', 'woo_factor_daily_retention_cron');
    }
    woo_factor_ensure_invoice_dir();
}

function woo_factor_deactivate() {
    $ts = wp_next_scheduled('woo_factor_daily_retention_cron');
    if ($ts) {
        wp_unschedule_event($ts, 'woo_factor_daily_retention_cron');
    }
}

function woo_factor_default_options() {
    return [
        'template'           => 'classic',
        'logo_id'            => 0,
        'shop_name'          => get_bloginfo('name'),
        'shop_phone'         => '',
        'shop_email'         => get_bloginfo('admin_email'),
        'shop_address'       => '',
        'shop_national_id'   => '',
        'footer_note'        => 'از خرید و اعتماد شما به فروشگاه ما سپاسگزاریم.',
        'color'              => '#0f766e',
        'auto_send'          => 'yes',
        'auto_send_status'   => 'wc-completed',
        'attach_woocommerce' => 'yes',
        'myaccount_page'     => 'yes',
        'show_barcode'       => 'yes',
        'show_signature'     => 'yes',
        'signature_stamp'    => 'مهر و امضای فروشگاه',
        'invoice_prefix'     => '',
        'number_mode'        => 'auto',
    ];
}

add_action('admin_notices', function () {
    if (class_exists('WooCommerce')) {
        return;
    }
    echo '<div class="notice notice-error"><p>';
    echo esc_html__('افزونه «Woo Factor» برای کارکرد صحیح به ووکامرس نیاز دارد.', 'woo-factor');
    echo '</p></div>';
});

// Daily retention: purge manual invoice PDFs older than N days (option-controlled)
add_action('woo_factor_daily_retention_cron', function () {
    $opts = woo_factor_options();
    $days = isset($opts['retention_days']) ? (int) $opts['retention_days'] : 90;
    if ($days <= 0 || !class_exists('WP_Filesystem_Direct')) {
        return;
    }
    global $wp_filesystem;
    if (!$wp_filesystem) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        WP_Filesystem();
    }
    $dir = WOO_FACTOR_DIR . 'generated/';
    if (!is_dir($dir)) {
        return;
    }
    $cutoff = time() - ($days * DAY_IN_SECONDS);
    foreach ((array) glob($dir . '*.pdf') as $file) {
        if (filemtime($file) < $cutoff) {
            $wp_filesystem->delete($file);
        }
    }
});
