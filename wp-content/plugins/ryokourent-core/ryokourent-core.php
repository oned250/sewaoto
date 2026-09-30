<?php
/**
 * Plugin Name:       Ryokourent Core
 * Plugin URI:        https://github.com/oned250/sewaoto
 * Description:       Core business logic, CPT Armada & Penyewaan, WhatsApp booking engine, and role management for Ryokourent (Rental Motor Malang & Batu).
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Author:            Ryokourent Dev Team
 * Author URI:        https://github.com/oned250
 * Text Domain:       ryokourent
 * Domain Path:       /languages
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Define Plugin Constants.
define('RYOKOURENT_VERSION', '1.0.0');
define('RYOKOURENT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('RYOKOURENT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('RYOKOURENT_PLUGIN_FILE', __FILE__);

/**
 * Activation hook callback.
 * Checks system requirements and sets up initial roles/options safely.
 */
function ryokourent_activate_plugin() {
    // Check PHP version requirement
    if (version_compare(PHP_VERSION, '8.0', '<')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(
            esc_html__('Ryokourent Core memerlukan PHP versi 8.0 atau lebih tinggi.', 'ryokourent'),
            esc_html__('Versi PHP Tidak Kompatibel', 'ryokourent'),
            array('back_link' => true)
        );
    }

    // Set default version option
    if (!get_option('ryokourent_version')) {
        add_option('ryokourent_version', RYOKOURENT_VERSION);
    }

    // Flush rewrite rules on activation (after post types are registered in later tasks)
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'ryokourent_activate_plugin');

/**
 * Deactivation hook callback.
 * Flushes rewrite rules cleanly.
 */
function ryokourent_deactivate_plugin() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'ryokourent_deactivate_plugin');

/**
 * Plugin bootstrap initialization.
 * Sub-modules will be loaded here in TASK-003 and subsequent tasks.
 */
function ryokourent_init() {
    // Modular includes will be connected in subsequent tasks.
}
add_action('plugins_loaded', 'ryokourent_init');
