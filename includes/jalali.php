<?php
/**
 * Jalali (Shamsi) date conversion — standard algorithm.
 */
defined('ABSPATH') || exit;

if (!function_exists('woo_factor_gregorian_to_jalali')) {
    /**
     * @param int $gy Gregorian year
     * @param int $gm Gregorian month
     * @param int $gd Gregorian day
     * @return int[] [jy, jm, jd]
     */
    function woo_factor_gregorian_to_jalali($gy, $gm, $gd) {
        $g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
        $jy = ($gy <= 1600) ? 0 : 979;
        $gy -= ($gy <= 1600) ? 621 : 1600;
        $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
        $days = (365 * $gy) + ((int) (($gy2 + 3) / 4)) - ((int) (($gy2 + 99) / 100))
            + ((int) (($gy2 + 399) / 400)) - 80 + $gd + $g_d_m[$gm - 1];
        $jy += 33 * ((int) ($days / 12053));
        $days %= 12053;
        $jy += 4 * ((int) ($days / 1461));
        $days %= 1461;
        if ($days > 365) {
            $jy += (int) (($days - 1) / 365);
            $days = ($days - 1) % 365;
        }
        $jm = ($days < 186) ? 1 + (int) ($days / 31) : 7 + (int) (($days - 186) / 30);
        $jd = 1 + (($days < 186) ? ($days % 31) : (($days - 186) % 30));
        return [$jy, $jm, $jd];
    }
}

if (!function_exists('woo_factor_jalali_to_gregorian')) {
    /**
     * @param int $jy Jalali year
     * @param int $jm Jalali month
     * @param int $jd Jalali day
     * @return int[] [gy, gm, gd]
     */
    function woo_factor_jalali_to_gregorian($jy, $jm, $jd) {
        $jy += 1595;
        $days = -355668 + (365 * $jy) + (((int) ($jy / 33)) * 8) + (((int) ((($jy % 33) + 3) / 4))) + $jd
            + (($jm < 7) ? ($jm - 1) * 31 : (($jm - 7) * 30) + 186);
        $gy = 400 * ((int) ($days / 146097));
        $days %= 146097;
        if ($days > 36524) {
            $gy += 100 * ((int) (--$days / 36524));
            $days %= 36524;
            if ($days >= 365) $days++;
        }
        $gy += 4 * ((int) ($days / 1461));
        $days %= 1461;
        if ($days > 365) {
            $gy += (int) (($days - 1) / 365);
            $days = ($days - 1) % 365;
        }
        $gd = $days + 1;
        $sal_a = [0, 31, ($gy % 4 === 0 && $gy % 100 !== 0) || ($gy % 400 === 0) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        $gm = 0;
        for ($i = 1; $i <= 12; $i++) {
            if ($gd <= $sal_a[$i]) {
                $gm = $i;
                break;
            }
            $gd -= $sal_a[$i];
        }
        return [$gy, $gm, $gd];
    }
}

/**
 * Format a WordPress timestamp as Jalali date string in Persian.
 *
 * @param int|null $timestamp Unix timestamp (defaults to now).
 * @param bool $with_time Include HH:MM.
 * @return string e.g. "۱۴۰۵/۰۶/۱۴" or with " - ۰۸:۳۰"
 */
function woo_factor_jdate($timestamp = null, $with_time = false) {
    $timestamp = $timestamp ?: current_time('timestamp');
    $gy = (int) date('Y', $timestamp);
    $gm = (int) date('n', $timestamp);
    $gd = (int) date('j', $timestamp);
    [$jy, $jm, $jd] = woo_factor_gregorian_to_jalali($gy, $gm, $gd);
    $out = sprintf('%04d/%02d/%02d', $jy, $jm, $jd);
    if ($with_time) {
        $out .= ' - ' . date('H:i', $timestamp);
    }
    return woo_factor_fa_digits($out);
}

/**
 * Persian month names for a Jalali month number (1-12).
 */
function woo_factor_jmonth_name($m) {
    $names = [
        1 => 'فروردین', 2 => 'اردیبهشت', 3 => 'خرداد',
        4 => 'تیر', 5 => 'مرداد', 6 => 'شهریور',
        7 => 'مهر', 8 => 'آبان', 9 => 'آذر',
        10 => 'دی', 11 => 'بهمن', 12 => 'اسفند',
    ];
    return $names[(int) $m] ?? '';
}
