<?php
/**
 * WooFactor SMS Gateway Integration
 * Supports top Iranian SMS providers with Pattern / Normal sending.
 */
defined('ABSPATH') || exit;

class WooFactor_SMS {

    public static function get_gateways() {
        return [
            'ippanel'     => 'فراز اس‌ام‌اس / IPPanel (پترن خدماتی)',
            'kavenegar'   => 'کاوه‌نگار (وب‌سرویس اعتبارسنجی Lookup)',
            'melipayamak' => 'ملی‌پیامک (وب‌سرویس BaseService)',
            'smsir'       => 'SMS.ir (ارسال سریع Verify)',
        ];
    }

    public static function send_invoice_sms($order) {
        if (!$order || !is_a($order, 'WC_Order')) {
            return false;
        }

        $opts = woo_factor_options();
        if (($opts['sms_enabled'] ?? 'no') !== 'yes') {
            return false;
        }

        $phone = $order->get_billing_phone();
        if (empty($phone)) {
            return false;
        }

        // Normalize phone number (convert Persian numbers, ensure 09...)
        $phone = self::normalize_mobile($phone);
        if (!preg_match('/^09[0-9]{9}$/', $phone)) {
            return false;
        }

        $gateway = $opts['sms_gateway'] ?? 'ippanel';
        $api_key = trim($opts['sms_api_key'] ?? '');
        $sender  = trim($opts['sms_sender'] ?? '');
        $pattern = trim($opts['sms_pattern'] ?? '');

        if (empty($api_key)) {
            return false;
        }

        $invoice_url = woo_factor_invoice_url($order);
        $buyer_name  = trim($order->get_billing_first_name() . ' ' . $order->get_billing_last_name()) ?: 'مشتری گرامی';
        $order_num   = (string)$order->get_order_number();
        $total       = woo_factor_number_format($order->get_total());

        switch ($gateway) {
            case 'ippanel':
            case 'farazsms':
                return self::send_ippanel($api_key, $sender, $pattern, $phone, [
                    'name'        => $buyer_name,
                    'order_id'    => $order_num,
                    'total'       => $total,
                    'invoice_url' => $invoice_url,
                ]);

            case 'kavenegar':
                return self::send_kavenegar($api_key, $pattern, $phone, $buyer_name, $order_num, $total, $invoice_url);

            case 'melipayamak':
                return self::send_melipayamak($api_key, $pattern, $phone, [$buyer_name, $order_num, $total]);

            case 'smsir':
                return self::send_smsir($api_key, $pattern, $phone, $buyer_name, $order_num, $total);

            default:
                return false;
        }
    }

    private static function normalize_mobile($mobile) {
        $mobile = strtr((string)$mobile, [
            '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
            '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
            '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
            '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
            '+98' => '0', '98' => '0', ' ' => '', '-' => '',
        ]);
        if (substr($mobile, 0, 2) === '98') {
            $mobile = '0' . substr($mobile, 2);
        }
        return trim($mobile);
    }

    private static function send_ippanel($api_key, $sender, $pattern, $phone, $values) {
        $url = 'https://api2.ippanel.com/api/v1/sms/pattern/normal/send';
        $body = [
            'code'      => $pattern,
            'sender'    => $sender ?: '+983000505',
            'recipient' => $phone,
            'variable'  => $values,
        ];

        $response = wp_remote_post($url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'apikey'       => $api_key,
            ],
            'body'    => wp_json_encode($body),
            'timeout' => 12,
        ]);

        return !is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200;
    }

    private static function send_kavenegar($api_key, $pattern, $phone, $token, $token2, $token3, $token10) {
        $url = "https://api.kavenegar.com/v1/{$api_key}/verify/lookup.json";
        $params = [
            'receptor' => $phone,
            'template' => $pattern,
            'token'    => str_replace(' ', '-', $token),
            'token2'   => $token2,
            'token3'   => str_replace(',', '', $token3),
        ];
        if (!empty($token10)) {
            $params['token10'] = $token10;
        }

        $response = wp_remote_post($url, [
            'body'    => $params,
            'timeout' => 12,
        ]);

        return !is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200;
    }

    private static function send_melipayamak($api_key, $pattern, $phone, $args) {
        $url = "https://rest.payamak-panel.com/api/SendSMS/BaseServiceNumber";
        $response = wp_remote_post($url, [
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => wp_json_encode([
                'userName' => $api_key,
                'to'       => $phone,
                'bodyId'   => (int)$pattern,
                'text'     => implode(';', $args),
            ]),
            'timeout' => 12,
        ]);

        return !is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200;
    }

    private static function send_smsir($api_key, $pattern, $phone, $name, $order_id, $total) {
        $url = 'https://api.sms.ir/v1/send/verify';
        $response = wp_remote_post($url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-API-KEY'    => $api_key,
            ],
            'body'    => wp_json_encode([
                'mobile'     => $phone,
                'templateId' => (int)$pattern,
                'parameters' => [
                    ['name' => 'NAME', 'value' => $name],
                    ['name' => 'ORDERID', 'value' => $order_id],
                    ['name' => 'TOTAL', 'value' => $total],
                ],
            ]),
            'timeout' => 12,
        ]);

        return !is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200;
    }
}

class_alias('WooFactor_SMS', 'Woo_Factor_SMS');

