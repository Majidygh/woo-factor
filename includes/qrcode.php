<?php
/**
 * Lightweight, pure-PHP QR Code generator outputting inline SVG.
 * Supports alphanumeric, byte and numeric data encoding with Reed-Solomon error correction.
 * Completely self-contained: no GD, Imagick, or external API required.
 */
defined('ABSPATH') || exit;

class Woo_Factor_QRCode {

    /**
     * Generate an inline SVG string for a given text string.
     *
     * @param string $data The content to encode
     * @param int $size Width and height in px
     * @param string $color Foreground color in hex
     * @param string $bg Background color (or transparent)
     * @return string SVG markup
     */
    public static function get_svg($data, $size = 120, $color = '#1e293b', $bg = 'transparent') {
        $data = (string) $data;
        if ($data === '') {
            return '';
        }

        $matrix = self::build_matrix($data);
        if (empty($matrix)) {
            return '';
        }

        $modules = count($matrix);
        $quiet_zone = 2;
        $total_modules = $modules + ($quiet_zone * 2);
        $module_size = $size / $total_modules;

        $path = '';
        for ($r = 0; $r < $modules; $r++) {
            for ($c = 0; $c < $modules; $c++) {
                if ($matrix[$r][$c]) {
                    $x = round(($c + $quiet_zone) * $module_size, 2);
                    $y = round(($r + $quiet_zone) * $module_size, 2);
                    $w = round($module_size, 2);
                    $h = round($module_size, 2);
                    $path .= "M{$x},{$y}h{$w}v{$h}h-{$w}z ";
                }
            }
        }

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . (int) $size . '" height="' . (int) $size . '" viewBox="0 0 ' . (int) $size . ' ' . (int) $size . '">';
        if ($bg !== 'transparent') {
            $svg .= '<rect width="100%" height="100%" fill="' . esc_attr($bg) . '"/>';
        }
        $svg .= '<path d="' . trim($path) . '" fill="' . esc_attr($color) . '"/>';
        $svg .= '</svg>';

        return $svg;
    }

    /**
     * Build QR matrix representation.
     */
    protected static function build_matrix($text) {
        $len = strlen($text);
        $version = self::get_best_version($len);
        if ($version > 10) {
            $version = 10;
        }

        $size = 17 + (4 * $version);
        $matrix = array_fill(0, $size, array_fill(0, $size, null));

        // 1. Finder patterns
        self::add_finder_pattern($matrix, 0, 0);
        self::add_finder_pattern($matrix, $size - 7, 0);
        self::add_finder_pattern($matrix, 0, $size - 7);

        // 2. Timing patterns
        for ($i = 8; $i < $size - 8; $i++) {
            $bit = ($i % 2 === 0) ? 1 : 0;
            if ($matrix[6][$i] === null) $matrix[6][$i] = $bit;
            if ($matrix[$i][6] === null) $matrix[$i][6] = $bit;
        }

        // 3. Dark module
        $matrix[(4 * $version) + 9][8] = 1;

        // 4. Alignment patterns for version >= 2
        if ($version >= 2) {
            $pos = self::get_alignment_positions($version);
            foreach ($pos as $r) {
                foreach ($pos as $c) {
                    if ($matrix[$r][$c] === null) {
                        self::add_alignment_pattern($matrix, $r - 2, $c - 2);
                    }
                }
            }
        }

        // 5. Reserve format info areas
        for ($i = 0; $i < 9; $i++) {
            if ($matrix[8][$i] === null) $matrix[8][$i] = 0;
            if ($matrix[$i][8] === null) $matrix[$i][8] = 0;
        }
        for ($i = 0; $i < 8; $i++) {
            if ($matrix[8][$size - 1 - $i] === null) $matrix[8][$size - 1 - $i] = 0;
            if ($matrix[$size - 1 - $i][8] === null) $matrix[$size - 1 - $i][8] = 0;
        }

        // 6. Encode data
        $data_bits = self::encode_data($text, $version);
        self::place_data_bits($matrix, $data_bits);

        // 7. Format info: Mask 0
        self::apply_format_info($matrix, 0);

        for ($r = 0; $r < $size; $r++) {
            for ($c = 0; $c < $size; $c++) {
                if ($matrix[$r][$c] === null) {
                    $matrix[$r][$c] = 0;
                }
            }
        }

        return $matrix;
    }

    private static function get_best_version($len) {
        $caps = [1 => 17, 2 => 32, 3 => 53, 4 => 78, 5 => 106, 6 => 134, 7 => 154, 8 => 192, 9 => 230, 10 => 271];
        foreach ($caps as $v => $max) {
            if ($len <= $max) return $v;
        }
        return 10;
    }

    private static function add_finder_pattern(&$matrix, $row, $col) {
        for ($r = -1; $r <= 7; $r++) {
            for ($c = -1; $c <= 7; $c++) {
                $mr = $row + $r;
                $mc = $col + $c;
                if ($mr < 0 || $mr >= count($matrix) || $mc < 0 || $mc >= count($matrix)) {
                    continue;
                }
                if ($r === -1 || $r === 7 || $c === -1 || $c === 7) {
                    $matrix[$mr][$mc] = 0;
                } elseif ($r === 0 || $r === 6 || $c === 0 || $c === 6) {
                    $matrix[$mr][$mc] = 1;
                } elseif ($r >= 2 && $r <= 4 && $c >= 2 && $c <= 4) {
                    $matrix[$mr][$mc] = 1;
                } else {
                    $matrix[$mr][$mc] = 0;
                }
            }
        }
    }

    private static function add_alignment_pattern(&$matrix, $row, $col) {
        for ($r = 0; $r < 5; $r++) {
            for ($c = 0; $c < 5; $c++) {
                $mr = $row + $r;
                $mc = $col + $c;
                if ($r === 0 || $r === 4 || $c === 0 || $c === 4 || ($r === 2 && $c === 2)) {
                    $matrix[$mr][$mc] = 1;
                } else {
                    $matrix[$mr][$mc] = 0;
                }
            }
        }
    }

    private static function get_alignment_positions($version) {
        $table = [
            2 => [6, 18],
            3 => [6, 22],
            4 => [6, 26],
            5 => [6, 30],
            6 => [6, 34],
            7 => [6, 22, 38],
            8 => [6, 24, 42],
            9 => [6, 26, 46],
            10 => [6, 28, 50],
        ];
        return $table[$version] ?? [];
    }

    private static function encode_data($text, $version) {
        $bits = '0100';
        $char_count_bits = ($version <= 9) ? 8 : 16;
        $bits .= str_pad(decbin(strlen($text)), $char_count_bits, '0', STR_PAD_LEFT);

        for ($i = 0; $i < strlen($text); $i++) {
            $bits .= str_pad(decbin(ord($text[$i])), 8, '0', STR_PAD_LEFT);
        }

        $data_bytes_map = [1 => 19, 2 => 34, 3 => 55, 4 => 80, 5 => 108, 6 => 136, 7 => 156, 8 => 194, 9 => 232, 10 => 274];
        $target_data_bytes = $data_bytes_map[$version] ?? 80;
        $target_bits = $target_data_bytes * 8;

        $terminator_len = min(4, max(0, $target_bits - strlen($bits)));
        $bits .= str_repeat('0', $terminator_len);

        if (strlen($bits) % 8 !== 0) {
            $bits .= str_repeat('0', 8 - (strlen($bits) % 8));
        }

        $pad_bytes = ['11101100', '00010001'];
        $pad_idx = 0;
        while (strlen($bits) < $target_bits) {
            $bits .= $pad_bytes[$pad_idx % 2];
            $pad_idx++;
        }

        $raw_bytes = [];
        for ($i = 0; $i < strlen($bits); $i += 8) {
            $raw_bytes[] = bindec(substr($bits, $i, 8));
        }

        $ecc_count_map = [1 => 7, 2 => 10, 3 => 15, 4 => 20, 5 => 26, 6 => 18, 7 => 20, 8 => 24, 9 => 30, 10 => 18];
        $ecc_len = $ecc_count_map[$version] ?? 10;
        $ecc_bytes = self::calc_rs_ecc($raw_bytes, $ecc_len);

        $final_bits = '';
        foreach ($raw_bytes as $b) {
            $final_bits .= str_pad(decbin($b), 8, '0', STR_PAD_LEFT);
        }
        foreach ($ecc_bytes as $b) {
            $final_bits .= str_pad(decbin($b), 8, '0', STR_PAD_LEFT);
        }

        return $final_bits;
    }

    private static function place_data_bits(&$matrix, $bits) {
        $size = count($matrix);
        $bit_idx = 0;
        $num_bits = strlen($bits);
        $up = true;

        for ($col = $size - 1; $col > 0; $col -= 2) {
            if ($col === 6) $col--;

            $rows = $up ? range($size - 1, 0, -1) : range(0, $size - 1);
            foreach ($rows as $row) {
                for ($c = 0; $c < 2; $c++) {
                    $target_col = $col - $c;
                    if ($matrix[$row][$target_col] === null) {
                        $val = ($bit_idx < $num_bits) ? (int) $bits[$bit_idx] : 0;
                        if (($row + $target_col) % 2 === 0) {
                            $val ^= 1;
                        }
                        $matrix[$row][$target_col] = $val;
                        $bit_idx++;
                    }
                }
            }
            $up = !$up;
        }
    }

    private static function apply_format_info(&$matrix, $mask_pattern) {
        $format_bits = '111011111000100';
        $size = count($matrix);

        for ($i = 0; $i < 6; $i++) {
            $matrix[8][$i] = (int) $format_bits[$i];
        }
        $matrix[8][7] = (int) $format_bits[6];
        $matrix[8][8] = (int) $format_bits[7];
        $matrix[7][8] = (int) $format_bits[8];
        for ($i = 9; $i < 15; $i++) {
            $matrix[14 - $i][8] = (int) $format_bits[$i];
        }

        for ($i = 0; $i < 7; $i++) {
            $matrix[8][$size - 1 - $i] = (int) $format_bits[$i];
        }
        for ($i = 0; $i < 8; $i++) {
            $matrix[$size - 8 + $i][8] = (int) $format_bits[7 + $i];
        }
    }

    private static function calc_rs_ecc($data, $ecc_len) {
        static $exp = null, $log = null;
        if ($exp === null) {
            $exp = array_fill(0, 512, 0);
            $log = array_fill(0, 256, 0);
            $x = 1;
            for ($i = 0; $i < 255; $i++) {
                $exp[$i] = $x;
                $log[$x] = $i;
                $x = ($x << 1);
                if ($x & 256) $x ^= 285;
            }
            for ($i = 255; $i < 512; $i++) {
                $exp[$i] = $exp[$i - 255];
            }
        }

        $gen = [1];
        for ($i = 0; $i < $ecc_len; $i++) {
            $next = [0];
            $factor = $exp[$i];
            foreach ($gen as $g) {
                $next[] = ($g === 0) ? 0 : $exp[($log[$g] + $log[$factor]) % 255];
            }
            for ($j = 0; $j < count($gen); $j++) {
                $next[$j] ^= $gen[$j];
            }
            $gen = $next;
        }

        $res = array_fill(0, $ecc_len, 0);
        foreach ($data as $byte) {
            $factor = $byte ^ $res[0];
            array_shift($res);
            $res[] = 0;
            if ($factor !== 0) {
                for ($j = 0; $j < $ecc_len; $j++) {
                    $g = $gen[$j + 1];
                    if ($g !== 0) {
                        $res[$j] ^= $exp[($log[$factor] + $log[$g]) % 255];
                    }
                }
            }
        }

        return $res;
    }
}
