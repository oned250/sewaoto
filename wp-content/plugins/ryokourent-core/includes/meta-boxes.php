<?php
/**
 * Custom Meta Boxes for CPT Motor
 *
 * Provides admin interfaces for managing motor specifications, route character,
 * Bromo trip authorization, rental pricing packages, and internal physical fleet inventory.
 *
 * @package    Ryokourent_Core
 * @subpackage Ryokourent_Core/includes
 * @author     Ryokourent Dev Team
 * @since      1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register meta boxes for CPT 'motor'.
 *
 * @since 1.0.0
 * @return void
 */
function ryokourent_add_motor_meta_boxes() {
    add_meta_box(
        'ryokourent_motor_specs_metabox',
        __('Spesifikasi & Karakter Armada', 'ryokourent'),
        'ryokourent_render_motor_specs_metabox',
        'motor',
        'normal',
        'high'
    );

    add_meta_box(
        'ryokourent_motor_pricing_metabox',
        __('Tarif Sewa Armada (Rupiah)', 'ryokourent'),
        'ryokourent_render_motor_pricing_metabox',
        'motor',
        'normal',
        'high'
    );

    add_meta_box(
        'ryokourent_motor_stock_metabox',
        __('Inventaris Unit Fisik (Operator Internal)', 'ryokourent'),
        'ryokourent_render_motor_stock_metabox',
        'motor',
        'side',
        'default'
    );
}
add_action('add_meta_boxes_motor', 'ryokourent_add_motor_meta_boxes');

/**
 * Render Specifications Meta Box.
 *
 * @since 1.0.0
 * @param WP_Post $post Current post object.
 * @return void
 */
function ryokourent_render_motor_specs_metabox($post) {
    wp_nonce_field('ryokourent_save_motor_meta_action', 'ryokourent_motor_meta_nonce');

    $engine_cc        = get_post_meta($post->ID, '_ryokou_engine_cc', true);
    $transmission     = get_post_meta($post->ID, '_ryokou_transmission', true) ?: 'Otomatis (CVT)';
    $route_character  = get_post_meta($post->ID, '_ryokou_route_character', true);
    $is_bromo_ready   = (bool) get_post_meta($post->ID, '_ryokou_is_bromo_ready', true);
    $status_label     = get_post_meta($post->ID, '_ryokou_status_label', true) ?: 'Tersedia';
    ?>
    <table class="form-table" style="margin-top: 0;">
        <tbody>
            <tr>
                <th scope="row" style="width: 220px;">
                    <label for="ryokou_engine_cc"><?php esc_html_e('Kapasitas Mesin (CC)', 'ryokourent'); ?></label>
                </th>
                <td>
                    <input type="number" id="ryokou_engine_cc" name="_ryokou_engine_cc" value="<?php echo esc_attr($engine_cc); ?>" class="regular-text" style="max-width: 150px;" min="50" max="2000" step="1" placeholder="110" />
                    <span class="description"><?php esc_html_e('Contoh: 110, 125, 150, atau 160 cc.', 'ryokourent'); ?></span>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="ryokou_transmission"><?php esc_html_e('Jenis Transmisi', 'ryokourent'); ?></label>
                </th>
                <td>
                    <select id="ryokou_transmission" name="_ryokou_transmission" style="min-width: 200px;">
                        <option value="Otomatis (CVT)" <?php selected($transmission, 'Otomatis (CVT)'); ?>><?php esc_html_e('Otomatis (CVT)', 'ryokourent'); ?></option>
                        <option value="Manual (Kopling)" <?php selected($transmission, 'Manual (Kopling)'); ?>><?php esc_html_e('Manual (Kopling)', 'ryokourent'); ?></option>
                    </select>
                    <span class="description"><?php esc_html_e('Tipe transmisi kendaraan.', 'ryokourent'); ?></span>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="ryokou_route_character"><?php esc_html_e('Karakter Rute / Keunggulan', 'ryokourent'); ?></label>
                </th>
                <td>
                    <input type="text" id="ryokou_route_character" name="_ryokou_route_character" value="<?php echo esc_attr($route_character); ?>" class="large-text" placeholder="Misal: Lincah & Sangat Irit, Nyaman & Bagasi Lega" />
                    <span class="description"><?php esc_html_e('Deskripsi singkat rute atau kenyamanan armada untuk panduan wisatawan.', 'ryokourent'); ?></span>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="ryokou_is_bromo_ready"><?php esc_html_e('Kelayakan Rute Bromo', 'ryokourent'); ?></label>
                </th>
                <td>
                    <label>
                        <input type="checkbox" id="ryokou_is_bromo_ready" name="_ryokou_is_bromo_ready" value="1" <?php checked($is_bromo_ready, true); ?> />
                        <strong><?php esc_html_e('Unit Siap & Diizinkan untuk Trip Bromo (Wajib Trail CRF)', 'ryokourent'); ?></strong>
                    </label>
                    <p class="description" style="color: #b45309; margin-top: 4px;">
                        <span class="dashicons dashicons-warning" style="vertical-align: middle; font-size: 16px;"></span>
                        <?php esc_html_e('PERINGATAN: Centang opsi ini HANYA untuk motor Honda Trail CRF 150L. Seluruh motor matik dilarang keras untuk trip lautan pasir Gunung Bromo demi keselamatan penyewa.', 'ryokourent'); ?>
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="ryokou_status_label"><?php esc_html_e('Badge Status Publik', 'ryokourent'); ?></label>
                </th>
                <td>
                    <select id="ryokou_status_label" name="_ryokou_status_label" style="min-width: 200px;">
                        <option value="Tersedia" <?php selected($status_label, 'Tersedia'); ?>><?php esc_html_e('Tersedia (Hijau)', 'ryokourent'); ?></option>
                        <option value="Booking Menipis" <?php selected($status_label, 'Booking Menipis'); ?>><?php esc_html_e('Booking Menipis (Kuning/Oranye)', 'ryokourent'); ?></option>
                        <option value="Penuh" <?php selected($status_label, 'Penuh'); ?>><?php esc_html_e('Penuh (Merah)', 'ryokourent'); ?></option>
                    </select>
                    <span class="description"><?php esc_html_e('Label ketersediaan visual di halaman katalog frontend.', 'ryokourent'); ?></span>
                </td>
            </tr>
        </tbody>
    </table>
    <?php
}

/**
 * Render Pricing Meta Box.
 *
 * @since 1.0.0
 * @param WP_Post $post Current post object.
 * @return void
 */
function ryokourent_render_motor_pricing_metabox($post) {
    $price_daily   = get_post_meta($post->ID, '_ryokou_price_daily', true);
    $price_weekly  = get_post_meta($post->ID, '_ryokou_price_weekly', true);
    $price_monthly = get_post_meta($post->ID, '_ryokou_price_monthly', true);

    $can_edit_pricing = current_user_can('manage_ryokourent_settings') || current_user_can('manage_options');
    $disabled_attr    = $can_edit_pricing ? '' : 'disabled="disabled"';
    ?>
    <p class="description" style="margin-bottom: 12px;">
        <?php esc_html_e('Masukkan tarif dalam satuan angka Rupiah penuh (tanpa titik atau koma). Sistem kalkulator booking otomatis menghitung paket harian, mingguan, atau bulanan termurah.', 'ryokourent'); ?>
    </p>

    <?php if (!$can_edit_pricing) : ?>
        <div class="notice notice-warning inline" style="margin: 0 0 15px 0;">
            <p><?php esc_html_e('Anda hanya memiliki izin sebagai Operator. Pengubahan tarif sewa memerlukan kapabilitas Administrator.', 'ryokourent'); ?></p>
        </div>
    <?php endif; ?>

    <table class="form-table" style="margin-top: 0;">
        <tbody>
            <tr>
                <th scope="row" style="width: 220px;">
                    <label for="ryokou_price_daily"><?php esc_html_e('Tarif Harian (24 Jam)', 'ryokourent'); ?></label>
                </th>
                <td>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="font-weight: 600; color: #64748b;">Rp</span>
                        <input type="number" id="ryokou_price_daily" name="_ryokou_price_daily" value="<?php echo esc_attr($price_daily); ?>" class="regular-text" style="max-width: 180px;" min="0" step="1000" placeholder="85000" <?php echo $disabled_attr; ?> />
                        <span class="description"><?php esc_html_e('Contoh: 85000 (Rp 85.000 / 24 jam)', 'ryokourent'); ?></span>
                    </div>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="ryokou_price_weekly"><?php esc_html_e('Tarif Mingguan (7 Hari)', 'ryokourent'); ?></label>
                </th>
                <td>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="font-weight: 600; color: #64748b;">Rp</span>
                        <input type="number" id="ryokou_price_weekly" name="_ryokou_price_weekly" value="<?php echo esc_attr($price_weekly); ?>" class="regular-text" style="max-width: 180px;" min="0" step="1000" placeholder="500000" <?php echo $disabled_attr; ?> />
                        <span class="description"><?php esc_html_e('Contoh: 500000 (Paket 7 hari hemat)', 'ryokourent'); ?></span>
                    </div>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="ryokou_price_monthly"><?php esc_html_e('Tarif Bulanan (30 Hari)', 'ryokourent'); ?></label>
                </th>
                <td>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="font-weight: 600; color: #64748b;">Rp</span>
                        <input type="number" id="ryokou_price_monthly" name="_ryokou_price_monthly" value="<?php echo esc_attr($price_monthly); ?>" class="regular-text" style="max-width: 180px;" min="0" step="1000" placeholder="1600000" <?php echo $disabled_attr; ?> />
                        <span class="description"><?php esc_html_e('Contoh: 1600000 (Paket 30 hari langganan)', 'ryokourent'); ?></span>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <?php
}

/**
 * Render Physical Stock & License Plates Meta Box.
 *
 * @since 1.0.0
 * @param WP_Post $post Current post object.
 * @return void
 */
function ryokourent_render_motor_stock_metabox($post) {
    $physical_stock = get_post_meta($post->ID, '_ryokou_physical_stock', true);
    $plate_numbers  = get_post_meta($post->ID, '_ryokou_plate_numbers', true);

    $can_edit_stock = current_user_can('manage_ryokourent_settings') || current_user_can('manage_options');
    $disabled_attr  = $can_edit_stock ? '' : 'disabled="disabled"';
    ?>
    <p style="margin-top: 0; font-size: 12px; color: #64748b;">
        <span class="dashicons dashicons-lock" style="font-size: 15px; vertical-align: middle;"></span>
        <?php esc_html_e('Data ini bersifat RAHASIA OPERATOR INTERNAL. Tertutup dari REST API publik.', 'ryokourent'); ?>
    </p>

    <p>
        <label for="ryokou_physical_stock"><strong><?php esc_html_e('Total Unit Fisik Dimiliki:', 'ryokourent'); ?></strong></label><br />
        <input type="number" id="ryokou_physical_stock" name="_ryokou_physical_stock" value="<?php echo esc_attr($physical_stock); ?>" style="width: 100%; margin-top: 4px;" min="0" max="500" step="1" placeholder="5" <?php echo $disabled_attr; ?> />
        <span class="description" style="font-size: 11px;"><?php esc_html_e('Batas kuota maksimal unit fisik untuk model ini.', 'ryokourent'); ?></span>
    </p>

    <p style="margin-top: 15px;">
        <label for="ryokou_plate_numbers"><strong><?php esc_html_e('Daftar Plat Nomor Unit:', 'ryokourent'); ?></strong></label><br />
        <textarea id="ryokou_plate_numbers" name="_ryokou_plate_numbers" rows="6" style="width: 100%; font-family: monospace; font-size: 12px; margin-top: 4px;" placeholder="N 1234 ABC&#10;N 5678 DEF" <?php echo $disabled_attr; ?>><?php echo esc_textarea($plate_numbers); ?></textarea>
        <span class="description" style="font-size: 11px;"><?php esc_html_e('Tulis satu plat nomor per baris (otomatis dikapitalisasi & dibersihkan).', 'ryokourent'); ?></span>
    </p>
    <?php
}

/**
 * Save meta box data when a 'motor' post is saved.
 *
 * @since 1.0.0
 * @param int $post_id The post ID.
 * @return void
 */
function ryokourent_save_motor_meta_data($post_id) {
    // 1. Guard against autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // 2. Verify nonce
    if (!isset($_POST['ryokourent_motor_meta_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ryokourent_motor_meta_nonce'])), 'ryokourent_save_motor_meta_action')) {
        return;
    }

    // 3. Verify post type
    if (!isset($_POST['post_type']) || 'motor' !== $_POST['post_type']) {
        return;
    }

    // 4. Verify post editing capability
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // 5. Save General Specifications (Requires edit_post)
    if (isset($_POST['_ryokou_engine_cc'])) {
        $clean_cc = function_exists('ryokourent_sanitize_engine_cc')
            ? ryokourent_sanitize_engine_cc(wp_unslash($_POST['_ryokou_engine_cc']))
            : absint(wp_unslash($_POST['_ryokou_engine_cc']));
        update_post_meta($post_id, '_ryokou_engine_cc', $clean_cc);
    }

    if (isset($_POST['_ryokou_transmission'])) {
        $clean_trans = function_exists('ryokourent_sanitize_transmission')
            ? ryokourent_sanitize_transmission(wp_unslash($_POST['_ryokou_transmission']))
            : sanitize_text_field(wp_unslash($_POST['_ryokou_transmission']));
        update_post_meta($post_id, '_ryokou_transmission', $clean_trans);
    }

    if (isset($_POST['_ryokou_route_character'])) {
        $clean_route = function_exists('ryokourent_sanitize_route_character')
            ? ryokourent_sanitize_route_character(wp_unslash($_POST['_ryokou_route_character']))
            : sanitize_text_field(wp_unslash($_POST['_ryokou_route_character']));
        update_post_meta($post_id, '_ryokou_route_character', $clean_route);
    }

    // Bromo readiness checkbox (0 if unchecked, 1 if checked)
    $bromo_val = isset($_POST['_ryokou_is_bromo_ready']) ? 1 : 0;
    update_post_meta($post_id, '_ryokou_is_bromo_ready', $bromo_val);

    if (isset($_POST['_ryokou_status_label'])) {
        $clean_status = function_exists('ryokourent_sanitize_status_label')
            ? ryokourent_sanitize_status_label(wp_unslash($_POST['_ryokou_status_label']))
            : sanitize_text_field(wp_unslash($_POST['_ryokou_status_label']));
        update_post_meta($post_id, '_ryokou_status_label', $clean_status);
    }

    // 6. Save Pricing & Stock (Requires manage_ryokourent_settings or manage_options)
    $can_manage_settings = current_user_can('manage_ryokourent_settings') || current_user_can('manage_options');

    if ($can_manage_settings) {
        if (isset($_POST['_ryokou_price_daily'])) {
            $clean_daily = function_exists('ryokourent_sanitize_price_integer')
                ? ryokourent_sanitize_price_integer(wp_unslash($_POST['_ryokou_price_daily']))
                : absint(wp_unslash($_POST['_ryokou_price_daily']));
            update_post_meta($post_id, '_ryokou_price_daily', $clean_daily);
        }

        if (isset($_POST['_ryokou_price_weekly'])) {
            $clean_weekly = function_exists('ryokourent_sanitize_price_integer')
                ? ryokourent_sanitize_price_integer(wp_unslash($_POST['_ryokou_price_weekly']))
                : absint(wp_unslash($_POST['_ryokou_price_weekly']));
            update_post_meta($post_id, '_ryokou_price_weekly', $clean_weekly);
        }

        if (isset($_POST['_ryokou_price_monthly'])) {
            $clean_monthly = function_exists('ryokourent_sanitize_price_integer')
                ? ryokourent_sanitize_price_integer(wp_unslash($_POST['_ryokou_price_monthly']))
                : absint(wp_unslash($_POST['_ryokou_price_monthly']));
            update_post_meta($post_id, '_ryokou_price_monthly', $clean_monthly);
        }

        if (isset($_POST['_ryokou_physical_stock'])) {
            $clean_stock = function_exists('ryokourent_sanitize_physical_stock')
                ? ryokourent_sanitize_physical_stock(wp_unslash($_POST['_ryokou_physical_stock']))
                : min(500, absint(wp_unslash($_POST['_ryokou_physical_stock'])));
            update_post_meta($post_id, '_ryokou_physical_stock', $clean_stock);
        }

        if (isset($_POST['_ryokou_plate_numbers'])) {
            $clean_plates = function_exists('ryokourent_sanitize_plate_numbers_text')
                ? ryokourent_sanitize_plate_numbers_text(wp_unslash($_POST['_ryokou_plate_numbers']))
                : sanitize_textarea_field(wp_unslash($_POST['_ryokou_plate_numbers']));
            update_post_meta($post_id, '_ryokou_plate_numbers', $clean_plates);
        }
    }
}
add_action('save_post_motor', 'ryokourent_save_motor_meta_data');
