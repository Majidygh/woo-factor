<?php
/**
 * Woo Factor Invoice - Helpers
 */
defined('ABSPATH') || exit;

function woo_factor_options() {
    $defaults = woo_factor_default_options();
    $opts = get_option('woo_factor_options', []);
    return wp_parse_args($opts, $defaults);
}

/**
 * Calculate a safe payable amount for a manually created invoice.
 * A discount can never make the final payable amount negative.
 */
function woo_factor_calculate_manual_grand_total($subtotal, $discount = 0, $shipping = 0, $tax = 0) {
    $subtotal = max(0, (float) $subtotal);
    $discount = min($subtotal, max(0, (float) $discount));
    $shipping = max(0, (float) $shipping);
    $tax = max(0, (float) $tax);

    return (float) max(0, $subtotal - $discount + $shipping + $tax);
}

/**
 * Keep the configurable brand color safe before it is injected into template CSS.
 */
function woo_factor_normalize_color($color, $fallback = '#0F766E') {
    $color = strtoupper(trim((string) $color));
    $fallback = strtoupper(trim((string) $fallback));

    return preg_match('/^#[A-F0-9]{6}$/', $color) ? $color : $fallback;
}

/**
 * Lighten (positive %) or darken (negative %) a hex color for gradients.
 */
function woo_factor_color_shade($color, $percent, $fallback = '#0F766E') {
    $fallback = strtoupper(trim((string) $fallback));
    if (!preg_match('/^#[A-F0-9]{6}$/', $fallback)) {
        $fallback = '#0F766E';
    }

    $color = trim((string) $color);
    if (!preg_match('/^#[A-F0-9]{6}$/i', $color)) {
        return $fallback;
    }

    $percent = max(-100, min(100, (float) $percent));
    $hex = substr($color, 1);
    $out = '#';

    for ($i = 0; $i < 3; $i++) {
        $channel = hexdec(substr($hex, $i * 2, 2));
        if ($percent >= 0) {
            $channel = $channel + ((255 - $channel) * $percent / 100);
        } else {
            $channel = $channel * (1 + $percent / 100);
        }
        $out .= str_pad(dechex((int) round(min(255, max(0, $channel)))), 2, '0', STR_PAD_LEFT);
    }

    return strtoupper($out);
}

/**
 * Build an invoice URL that lets a guest customer view only their own order.
 * WooCommerce order keys are unguessable and are validated server-side.
 */
function woo_factor_invoice_url($order) {
    if (!$order || !is_a($order, 'WC_Order')) {
        return '';
    }

    return add_query_arg([
        'action'   => 'woo_factor_view_invoice',
        'order_id' => $order->get_id(),
        'key'      => $order->get_order_key(),
    ], admin_url('admin-ajax.php'));
}

function woo_factor_templates() {
    return [
        'classic' => [
            'name' => 'قالب رسمی و کلاسیک (دارایی)',
            'desc' => 'ساختار رسمی با تفکیک دقیق مالیات، تخفیف، مشخصات فروشنده و خریدار مناسب شرکت‌ها و فروشگاه‌های رسمی',
            'file' => WOO_FACTOR_DIR . 'templates/classic.php',
        ],
        'modern' => [
            'name' => 'قالب مدرن و مینیمال',
            'desc' => 'طراحی تمیز، مدرن و کارت‌محور با رنگ‌بندی داینامیک مناسب فروشگاه‌های آنلاین و دیجیتال',
            'file' => WOO_FACTOR_DIR . 'templates/modern.php',
        ],
        'commercial' => [
            'name' => 'قالب ویژه تجاری (شیک و گرافیکی)',
            'desc' => 'طراحی شکیل با هدر گرادیانت، آیکون‌ها، کادر امضا و مهر برجسته برای فروشگاه‌های خاص',
            'file' => WOO_FACTOR_DIR . 'templates/commercial.php',
        ],
    ];
}

function woo_factor_ensure_invoice_dir() {
    $upload_dir = wp_upload_dir();
    $dir = trailingslashit($upload_dir['basedir']) . 'woo-factor-invoices';
    if (!file_exists($dir)) {
        wp_mkdir_p($dir);
        file_put_contents($dir . '/index.php', '<?php // Silence is golden');
        file_put_contents($dir . '/.htaccess', 'deny from all');
    }
    return $dir;
}

function woo_factor_get_logo_url() {
    $opts = woo_factor_options();
    if (!empty($opts['logo_id'])) {
        $src = wp_get_attachment_image_src($opts['logo_id'], 'full');
        if ($src) return $src[0];
    }
    if (!empty($opts['logo_url'])) {
        return $opts['logo_url'];
    }
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $src = wp_get_attachment_image_src($custom_logo_id, 'full');
        if ($src) return $src[0];
    }
    return '';
}

/**
 * Sanitize callback for the woo_factor_options setting (registered in admin/settings.php).
 * Keeps known keys only; each value sanitized by its type. Unknown keys dropped.
 */
function woo_factor_sanitize_options($input) {
    $defaults = woo_factor_default_options();
    $input = is_array($input) ? $input : [];
    $clean = [];
    foreach ($defaults as $key => $default) {
        if (!array_key_exists($key, $input)) {
            $clean[$key] = $default;
            continue;
        }
        $val = $input[$key];
        if (is_bool($default) || in_array($val, ['1', '0', 1, 0, true, false], true) && in_array($key, ['require_login', 'attach_to_emails', 'show_in_myaccount'], true)) {
            $clean[$key] = (bool) $val;
        } elseif ($key === 'footer_note' || $key === 'shop_address') {
            $clean[$key] = sanitize_textarea_field($val);
        } elseif ($key === 'theme_color' || $key === 'color') {
            $clean[$key] = woo_factor_normalize_color($val);
        } else {
            $clean[$key] = sanitize_text_field($val);
        }
    }
    return $clean;
}
