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

/**
 * Convert number to Persian words (e.g. 1,500,000 => یک میلیون و پانصد هزار).
 *
 * @param int|float|string $number
 * @return string
 */
function woo_factor_number_to_words($number) {
    $number = (int) round((float) $number);
    if ($number === 0) {
        return 'صفر';
    }

    $is_negative = false;
    if ($number < 0) {
        $is_negative = true;
        $number = abs($number);
    }

    $ones = ['', 'یک', 'دو', 'سه', 'چهار', 'پنج', 'شش', 'هفت', 'هشت', 'نه'];
    $teens = ['ده', 'یازده', 'دوازده', 'سیزده', 'چهارده', 'پانزده', 'شانزده', 'هفده', 'هجده', 'نوزده'];
    $tens = ['', '', 'بیست', 'سی', 'چهل', 'پنجاه', 'شصت', 'هفتاد', 'هشتاد', 'نود'];
    $hundreds = ['', 'یکصد', 'دویست', 'سیصد', 'چهارصد', 'پانصد', 'ششصد', 'هفتصد', 'هشتصد', 'نهصد'];
    $units = ['', 'هزار', 'میلیون', 'میلیارد', 'تریلیون'];

    $parts = [];
    $unit_idx = 0;

    while ($number > 0) {
        $chunk = $number % 1000;
        if ($chunk > 0) {
            $chunk_words = [];
            $h = (int) ($chunk / 100);
            $rem = $chunk % 100;
            $t = (int) ($rem / 10);
            $o = $rem % 10;

            if ($h > 0) {
                $chunk_words[] = $hundreds[$h];
            }

            if ($rem >= 10 && $rem < 20) {
                $chunk_words[] = $teens[$rem - 10];
            } else {
                if ($t > 1) {
                    $chunk_words[] = $tens[$t];
                }
                if ($o > 0) {
                    $chunk_words[] = $ones[$o];
                }
            }

            $part_str = implode(' و ', array_filter($chunk_words));
            if (!empty($units[$unit_idx])) {
                $part_str .= ' ' . $units[$unit_idx];
            }
            array_unshift($parts, $part_str);
        }
        $number = (int) ($number / 1000);
        $unit_idx++;
    }

    $result = implode(' و ', array_filter($parts));
    return ($is_negative ? 'منفی ' : '') . trim($result);
}
