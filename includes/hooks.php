<?php
/**
 * WooCommerce Hooks Integration
 */
defined('ABSPATH') || exit;

// 1. Admin Order Actions & Column
add_filter('woocommerce_admin_order_actions', function ($actions, $order) {
    $order_id = $order->get_id();
    $invoice_url = wp_nonce_url(admin_url('admin-ajax.php?action=woo_factor_view_invoice&order_id=' . $order_id), 'woo_factor_view_' . $order_id);
    $label_url = wp_nonce_url(admin_url('admin-ajax.php?action=woo_factor_view_label&order_id=' . $order_id), 'woo_factor_label_' . $order_id);

    $actions['woo_factor_invoice'] = [
        'url'    => $invoice_url,
        'name'   => __('چاپ فاکتور', 'woo-factor'),
        'action' => 'woo_factor_invoice',
    ];

    $actions['woo_factor_label'] = [
        'url'    => $label_url,
        'name'   => __('برچسب پستی', 'woo-factor'),
        'action' => 'woo_factor_label',
    ];

    return $actions;
}, 10, 2);

// Add custom icon style for admin order actions
add_action('admin_head', function () {
    echo '<style>
        .wc-action-button-woo_factor_invoice::after { content: "\f498" !important; font-family: dashicons !important; }
        .wc-action-button-woo_factor_label::after { content: "\f475" !important; font-family: dashicons !important; }
    </style>';
});

// 2. AJAX Handlers to view invoice & shipping label
add_action('wp_ajax_woo_factor_view_invoice', 'woo_factor_handle_view_invoice');
add_action('wp_ajax_nopriv_woo_factor_view_invoice', 'woo_factor_handle_view_invoice');

function woo_factor_handle_view_invoice() {
    $order_id = isset($_GET['order_id']) ? absint($_GET['order_id']) : 0;
    if (!$order_id) {
        wp_die('شناسه سفارش نامعتبر است.');
    }

    $order = wc_get_order($order_id);
    if (!$order) {
        wp_die('سفارش یافت نشد.');
    }

    // Permission: admins/managers may always view. Everyone else must prove
    // ownership — a logged-in account match OR the unguessable order key.
    // A bare nonce is NOT enough: those links get forwarded and leak PII.
    $current_user_id = get_current_user_id();
    $is_admin = current_user_can('manage_woocommerce');
    $is_owner = $current_user_id && ($order->get_customer_id() === $current_user_id);
    $order_key = isset($_GET['key']) ? wc_clean(wp_unslash($_GET['key'])) : '';
    $has_valid_key = $order_key && hash_equals((string) $order->get_order_key(), $order_key);

    if (!$is_admin && !$is_owner && !$has_valid_key) {
        wp_die('شما دسترسی مشاهده این فاکتور را ندارید.');
    }

    $tpl = isset($_GET['template']) ? sanitize_text_field($_GET['template']) : null;
    $data = Woo_Factor_Invoice_Builder::build_from_order($order_id);
    if (!$data) {
        wp_die('خطا در بارگذاری اطلاعات فاکتور.');
    }

    echo Woo_Factor_Renderer::render_html($data, $tpl);
    exit;
}

add_action('wp_ajax_woo_factor_view_label', function () {
    if (!current_user_can('manage_woocommerce')) {
        wp_die('عدم دسترسی.');
    }
    $order_id = isset($_GET['order_id']) ? absint($_GET['order_id']) : 0;
    check_admin_referer('woo_factor_label_' . $order_id);

    $data = Woo_Factor_Invoice_Builder::build_from_order($order_id);
    echo Woo_Factor_Renderer::render_shipping_label($data);
    exit;
});

// 3. Customer My Account Orders Page - Add Download Invoice Button
add_filter('woocommerce_my_account_my_orders_actions', function ($actions, $order) {
    $opts = woo_factor_options();
    if (($opts['myaccount_page'] ?? 'yes') === 'yes') {
        $url = woo_factor_invoice_url($order);
        if ($url) {
            $actions['woo_factor_invoice'] = [
                'url'  => $url,
                'name' => __('دریافت فاکتور', 'woo-factor'),
            ];
        }
    }
    return $actions;
}, 10, 2);

// 4. Attach invoice button/link in WooCommerce Customer Emails
add_action('woocommerce_email_after_order_table', function ($order, $sent_to_admin, $plain_text, $email) {
    $opts = woo_factor_options();
    if (($opts['attach_woocommerce'] ?? 'yes') !== 'yes' || $plain_text) {
        return;
    }

    $url = woo_factor_invoice_url($order);
    if (!$url) {
        return;
    }

    echo '<div style="margin: 20px 0; text-align: center;">';
    echo '<a href="' . esc_url($url) . '" target="_blank" style="background-color: #0f766e; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">' . esc_html__('مشاهده و چاپ فاکتور خرید آنلاین', 'woo-factor') . '</a>';
    echo '</div>';
}, 10, 4);

// 5. Add National ID field to WooCommerce Checkout if enabled
add_filter('woocommerce_billing_fields', function ($fields) {
    $fields['billing_national_code'] = [
        'type'        => 'text',
        'label'       => __('کد ملی / شناسه ملی', 'woo-factor'),
        'placeholder' => __('جهت درج در فاکتور رسمی', 'woo-factor'),
        'required'    => false,
        'class'       => ['form-row-wide'],
        'clear'       => true,
        'priority'    => 25,
    ];
    return $fields;
});

// 6. Persist checkout national / economic codes onto the order so the
// invoice builder can actually print them (previously display-only).
add_action('woocommerce_checkout_create_order', function ($order, $data) {
    if (isset($_POST['billing_national_code'])) {
        $order->update_meta_data('_billing_national_code', sanitize_text_field(wp_unslash($_POST['billing_national_code'])));
    }
    if (isset($_POST['billing_economic_code'])) {
        $order->update_meta_data('_billing_economic_code', sanitize_text_field(wp_unslash($_POST['billing_economic_code'])));
    }
}, 10, 2);
