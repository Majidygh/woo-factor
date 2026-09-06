<?php
/**
 * Minimal regression tests for Woo Factor helpers.
 * Run: php tests/test_helpers.php
 */
define('ABSPATH', __DIR__ . '/');

function fail_test($message) {
    fwrite(STDERR, "FAIL: {$message}\n");
    exit(1);
}

function assert_same($expected, $actual, $message) {
    if ($expected !== $actual) {
        fail_test($message . ' (expected ' . var_export($expected, true) . ', got ' . var_export($actual, true) . ')');
    }
}

require_once dirname(__DIR__) . '/includes/helpers.php';

assert_same(0.0, woo_factor_calculate_manual_grand_total(100, 200, 0, 0), 'Manual invoice total must never be negative');
assert_same(115.0, woo_factor_calculate_manual_grand_total(100, 10, 20, 5), 'Manual invoice total must include discount, shipping, and tax');
assert_same('#0F766E', woo_factor_normalize_color('#0f766e'), 'Valid hex color must be normalized');
assert_same('#0F766E', woo_factor_normalize_color('invalid', '#0f766e'), 'Invalid color must fall back safely');

// Color shade: exact anchor + channel direction properties
assert_same('#808080', woo_factor_color_shade('#000000', 50), '50% tint of black must be mid gray');
$dark = woo_factor_color_shade('#0F766E', -25);
$light = woo_factor_color_shade('#0F766E', 25);
foreach (['dark' => $dark, 'light' => $light] as $label => $hex) {
    if (!preg_match('/^#[0-9A-F]{6}$/', $hex)) {
        fail_test("Shade $label must be a 6-digit hex color, got " . var_export($hex, true));
    }
}
$base = [0x0F, 0x76, 0x6E];
$dk = [hexdec(substr($dark, 1, 2)), hexdec(substr($dark, 3, 2)), hexdec(substr($dark, 5, 2))];
$lt = [hexdec(substr($light, 1, 2)), hexdec(substr($light, 3, 2)), hexdec(substr($light, 5, 2))];
foreach ($base as $i => $ch) {
    if ($dk[$i] >= $ch) fail_test("Dark shade channel $i must be darker");
    if ($lt[$i] <= $ch) fail_test("Light shade channel $i must be lighter");
}
assert_same('#0F766E', woo_factor_color_shade('nonsense', 20, '#0F766E'), 'Shade of invalid color must fall back');

fwrite(STDOUT, "PASS: Woo Factor helper regressions\n");
