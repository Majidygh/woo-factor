<?php
/**
 * Render Engine for Woo Factor Invoices
 */
defined('ABSPATH') || exit;

class Woo_Factor_Renderer {

    const FONT_CSS = 'assets/fonts/woo-factor-fonts.css';

    /**
     * Persian font faces (weight, script subset) shipped with the plugin.
     */
    protected static function font_faces() {
        return [
            ['400', 'arabic', 'U+0600-06FF, U+0750-077F, U+0870-088E, U+0890-0891, U+0897-08E1, U+08E3-08FF, U+200C-200E, U+2010-2011, U+204F, U+2E41, U+FB50-FDFF, U+FE70-FEFC'],
            ['700', 'arabic', 'U+0600-06FF, U+0750-077F, U+0870-088E, U+0890-0891, U+0897-08E1, U+08E3-08FF, U+200C-200E, U+2010-2011, U+204F, U+2E41, U+FB50-FDFF, U+FE70-FEFC'],
            ['900', 'arabic', 'U+0600-06FF, U+0750-077F, U+0870-088E, U+0890-0891, U+0897-08E1, U+08E3-08FF, U+200C-200E, U+2010-2011, U+204F, U+2E41, U+FB50-FDFF, U+FE70-FEFC'],
            ['400', 'latin', 'U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD'],
            ['700', 'latin', 'U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD'],
            ['900', 'latin', 'U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD'],
        ];
    }

    /**
     * Build the shared font/base CSS. Vazirmatn TTF files are inlined as
     * data URIs so rendering never depends on network, CDN, or URL path
     * resolution — works in browsers, print, and PDF/screenshot engines.
     */
    public static function font_css() {
        static $css = null;
        if ($css !== null) {
            return $css;
        }

        $css = '';

        foreach (self::font_faces() as [$weight, $script, $range]) {
            $path = WOO_FACTOR_DIR . "assets/fonts/vazirmatn-{$script}-{$weight}-normal.ttf";
            if (!is_readable($path)) {
                continue;
            }
            $uri = 'data:font/ttf;base64,' . base64_encode((string) file_get_contents($path));
            $css .= "@font-face { font-family: 'Vazirmatn'; font-style: normal; font-weight: {$weight}; "
                  . "src: url('{$uri}') format('truetype'); unicode-range: {$range}; }\n";
        }

        $base = WOO_FACTOR_DIR . self::FONT_CSS;
        if (is_readable($base)) {
            $css .= (string) file_get_contents($base);
        }

        return $css;
    }

    /**
     * Inject shared font/base styles right after <head> (or prepend if missing).
     */
    public static function inject_fonts($html) {
        $style = '<style id="woo-factor-fonts">' . self::font_css() . '</style>';

        if (stripos($html, '<head>') !== false) {
            return preg_replace('/<head>/i', '<head>' . $style, $html, 1);
        }

        return $style . $html;
    }

    public static function render_html($data, $template_key = null) {
        $opts = woo_factor_options();
        if (empty($template_key)) {
            $template_key = $opts['template'] ?? 'classic';
        }

        $templates = woo_factor_templates();
        $template_file = $templates[$template_key]['file'] ?? $templates['classic']['file'];

        if (!file_exists($template_file)) {
            $template_file = WOO_FACTOR_DIR . 'templates/classic.php';
        }

        ob_start();
        include $template_file;
        $html = ob_get_clean();

        return self::inject_fonts($html);
    }

    public static function render_shipping_label($data) {
        $label_file = WOO_FACTOR_DIR . 'templates/shipping-label.php';
        ob_start();
        include $label_file;
        $html = ob_get_clean();

        return self::inject_fonts($html);
    }
}
