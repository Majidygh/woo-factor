<?php
/**
 * Shortcodes and PDF dummy helper
 */
defined('ABSPATH') || exit;

// Shortcode to show invoice link or form: [woo_factor_invoice order_id="123"]
add_shortcode('woo_factor_invoice', function ($atts) {
    $atts = shortcode_atts(['order_id' => 0], $atts);
    $order_id = absint($atts['order_id']);
    if (!$order_id) {
        return '<p>' . esc_html__('شناسه سفارش مشخص نشده است.', 'woo-factor') . '</p>';
    }

    $url = wp_nonce_url(admin_url('admin-ajax.php?action=woo_factor_view_invoice&order_id=' . $order_id), 'woo_factor_view_' . $order_id);
    return '<a href="' . esc_url($url) . '" target="_blank" class="button woo-factor-btn">' . esc_html__('مشاهده فاکتور سفارش', 'woo-factor') . '</a>';
});
