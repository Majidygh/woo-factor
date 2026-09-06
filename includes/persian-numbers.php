<?php
/**
 * Persian digit helpers.
 */
defined('ABSPATH') || exit;

/**
 * Convert ASCII digits to Persian digits.
 *
 * @param string $str Input.
 * @return string
 */
function woo_factor_fa_digits($str) {
    return strtr((string) $str, [
        '0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴',
        '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹',
    ]);
}

/**
 * Format a number with thousand separators then convert digits to Persian.
 *
 * @param float|int|string $num
 * @param int $decimals
 * @return string
 */
function woo_factor_number_format($num, $decimals = 0) {
    return woo_factor_fa_digits(number_format((float) $num, $decimals, '.', ','));
}
