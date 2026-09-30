<?php
/**
 * GeneratePress Child Theme Functions.
 *
 * Catatan Arsitektur:
 * File ini HANYA digunakan untuk styling tema dan enqueue asset tampilan.
 * Seluruh logika bisnis (CPT, metabox, kalkulasi harga, booking, WhatsApp)
 * WAJIB diletakkan di dalam plugin "ryokourent-core".
 *
 * @package GeneratePress_Child_Ryokourent
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue parent GeneratePress styles and child theme stylesheet.
 */
function ryokourent_child_enqueue_styles() {
    // Parent theme stylesheet
    wp_enqueue_style(
        'generatepress-parent-style',
        get_template_directory_uri() . '/style.css',
        array(),
        filemtime(get_template_directory() . '/style.css')
    );

    // Child theme stylesheet
    wp_enqueue_style(
        'generatepress-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('generatepress-parent-style'),
        filemtime(get_stylesheet_directory() . '/style.css')
    );
}
add_action('wp_enqueue_scripts', 'ryokourent_child_enqueue_styles');
