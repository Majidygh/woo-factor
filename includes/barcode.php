<?php
/**
 * Lightweight pure-PHP Code128 Barcode generator outputting inline SVG
 */
defined('ABSPATH') || exit;

class Woo_Factor_Barcode_128 {
    private static $patterns = [
        '212222', '222122', '222221', '121223', '121322', '131222', '122213', '122312', '132212', '221213',
        '221312', '231212', '112232', '122132', '122231', '113222', '123122', '123221', '223211', '221132',
        '221231', '213212', '223112', '312131', '311222', '321122', '321221', '312212', '322112', '322211',
        '212123', '212321', '232121', '111323', '131123', '131321', '112313', '132113', '132311', '211313',
        '231113', '231311', '112133', '112331', '132131', '113123', '113321', '133121', '313121', '211331',
        '231131', '213113', '213311', '213131', '311123', '311321', '331121', '312113', '312311', '332111',
        '314111', '221411', '431111', '111224', '111422', '121124', '121421', '141122', '141221', '112214',
        '112412', '122114', '122411', '142112', '142211', '241211', '221114', '413111', '241112', '134111',
        '111242', '121142', '121241', '114212', '124112', '124211', '411212', '421112', '421211', '212141',
        '214121', '412121', '111143', '111341', '131141', '114113', '114311', '411113', '411311', '113141',
        '114131', '311141', '411131', '211412', '211214', '211232', '2331112'
    ];

    public static function get_svg($code, $height = 40, $width_scale = 1.5) {
        $code = (string) $code;
        // Defensive: barcode content must be plain ASCII alnum/dash/underscore (SVG- and Code128-B-safe)
        $code = preg_replace('/[^A-Za-z0-9\\-_]/', '', $code);
        if (empty($code)) return '';

        // Start with Code B (104)
        $values = [104];
        $checksum = 104;
        $len = strlen($code);

        for ($i = 0; $i < $len; $i++) {
            $char_val = ord($code[$i]) - 32;
            $values[] = $char_val;
            $checksum += $char_val * ($i + 1);
        }

        $values[] = $checksum % 103;
        $values[] = 106; // Stop character

        $bars = '';
        foreach ($values as $val) {
            if (isset(self::$patterns[$val])) {
                $bars .= self::$patterns[$val];
            }
        }

        $total_units = 0;
        for ($i = 0; $i < strlen($bars); $i++) {
            $total_units += (int)$bars[$i];
        }

        $total_width = $total_units * $width_scale;
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $total_width . '" height="' . $height . '" viewBox="0 0 ' . $total_width . ' ' . $height . '">';
        $svg .= '<rect width="100%" height="100%" fill="transparent"/>';

        $x = 0;
        for ($i = 0; $i < strlen($bars); $i++) {
            $w = (int)$bars[$i] * $width_scale;
            if ($i % 2 === 0) {
                // Bar
                $svg .= '<rect x="' . $x . '" y="0" width="' . $w . '" height="' . $height . '" fill="#1f2937"/>';
            }
            $x += $w;
        }

        $svg .= '</svg>';
        return $svg;
    }
}
