<?php
/**
 * PDF helper
 */
defined('ABSPATH') || exit;

// PDF utilities placeholder for direct download if needed
function woo_factor_pdf_headers($filename = 'invoice.pdf') {
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . esc_attr($filename) . '"');
}
