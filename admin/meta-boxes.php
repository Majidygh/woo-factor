<?php
/**
 * Meta Boxes on WooCommerce Single Order Admin Page
 */
defined('ABSPATH') || exit;

add_action('add_meta_boxes', function () {
    $screen = class_exists('\Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController') 
        && wc_get_container()->get(\Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController::class)->custom_orders_table_usage_is_enabled()
        ? wc_get_page_screen_id('shop-order')
        : 'shop_order';

    add_meta_box(
        'woo_factor_order_invoice_box',
        __('🧾 ووفاکتور', 'woo-factor'),
        'woo_factor_render_order_meta_box',
        $screen,
        'side',
        'high'
    );
});

function woo_factor_render_order_meta_box($post_or_order) {
    $order = ($post_or_order instanceof WC_Order) ? $post_or_order : wc_get_order($post_or_order->ID);
    if (!$order) return;

    $order_id = $order->get_id();
    $inv_url = wp_nonce_url(admin_url('admin-ajax.php?action=woo_factor_view_invoice&order_id=' . $order_id), 'woo_factor_view_' . $order_id);
    $label_url = wp_nonce_url(admin_url('admin-ajax.php?action=woo_factor_view_label&order_id=' . $order_id), 'woo_factor_label_' . $order_id);
    $custom_inv_num = $order->get_meta('_woo_factor_invoice_number') ?: $order->get_order_number();
    ?>
    <div style="direction: rtl; text-align: right;">
        <p style="margin-bottom: 8px;">
            <strong>شماره فاکتور:</strong> <?php echo woo_factor_fa_digits($custom_inv_num); ?>
        </p>

        <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 12px;">
            <a href="<?php echo esc_url($inv_url); ?>" target="_blank" class="button button-primary" style="text-align: center; background: #0f766e; border-color: #0f766e;">
                🖨️ چاپ فاکتور (قالب پیش‌فرض)
            </a>

            <div style="display: flex; gap: 4px;">
                <a href="<?php echo esc_url($inv_url . '&template=classic'); ?>" target="_blank" class="button" style="flex: 1; text-align: center; font-size: 11px;">قالب رسمی</a>
                <a href="<?php echo esc_url($inv_url . '&template=modern'); ?>" target="_blank" class="button" style="flex: 1; text-align: center; font-size: 11px;">قالب مدرن</a>
                <a href="<?php echo esc_url($inv_url . '&template=commercial'); ?>" target="_blank" class="button" style="flex: 1; text-align: center; font-size: 11px;">قالب تجاری</a>
            </div>

            <a href="<?php echo esc_url($label_url); ?>" target="_blank" class="button button-secondary" style="text-align: center; margin-top: 4px;">
                📮 چاپ برچسب پستی مرسوله
            </a>
        </div>
    </div>
    <?php
}
