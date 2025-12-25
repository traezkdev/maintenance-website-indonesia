<?php
/**
 * Plugin Name: Maintenance Website Indonesia
 * Plugin URI: https://github.com/traezkdev/maintenance-website-indonesia
 * Description: Plugin khusus untuk mengaktifkan mode maintenance pada website WordPress dengan tampilan khas Indonesia. Dibuat oleh developer Indonesia.
 * Version: 2.5.3
 * Author: Traezkdev
 * Author URI: https://github.com/traezkdev
 * License: GPL v2 or later
 * Text Domain: maintenance-website-id
 * Network: false
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4/up
 */

// Mencegah akses langsung
if (!defined('ABSPATH')) {
    exit;
}

// Konstanta plugin dengan nama yang unik
define('MWI_VERSION', filemtime(__FILE__));
define('MWI_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MWI_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('MWI_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Aktivasi plugin
register_activation_hook(__FILE__, 'mwi_activate');
register_deactivation_hook(__FILE__, 'mwi_deactivate');

function mwi_activate() {
    // Set default options dengan prefix yang unik
    add_option('mwi_enabled', 0);
    add_option('mwi_title', 'Website Sedang Dalam Perbaikan');
    add_option('mwi_message', 'Mohon maaf, website sedang dalam perbaikan untuk meningkatkan kualitas layanan. Silakan kembali lagi nanti.');
    add_option('mwi_end_time', '');
    add_option('mwi_background_color', '#2c3e50');
    add_option('mwi_text_color', '#ffffff');
    
    // Pastikan template default terset dengan benar
    if (!get_option('mwi_template')) {
        add_option('mwi_template', 'default');
    }
    
    // Flush rewrite rules
    flush_rewrite_rules();
}

function mwi_deactivate() {
    // Matikan maintenance mode saat plugin dinonaktifkan
    update_option('mwi_enabled', 0);
    
    // Flush rewrite rules
    flush_rewrite_rules();
}

// Include file-file yang diperlukan
require_once MWI_PLUGIN_PATH . 'includes/maintenance-functions.php';

// Hook untuk admin
if (is_admin()) {
    require_once MWI_PLUGIN_PATH . 'admin/admin-page.php';
}

// Inisialisasi plugin
add_action('init', 'mwi_init');

function mwi_init() {
    // Load text domain untuk translasi
    load_plugin_textdomain('maintenance-website-id', false, dirname(MWI_PLUGIN_BASENAME) . '/languages');
    
    // Cek apakah maintenance mode aktif
    if (get_option('mwi_enabled') && !mwi_should_bypass()) {
        add_action('template_redirect', 'mwi_display_page');
    }
}

// Enqueue scripts dan styles
add_action('wp_enqueue_scripts', 'mwi_enqueue_scripts');

function mwi_enqueue_scripts() {
    if (get_option('mwi_enabled') && !mwi_should_bypass()) {
        wp_enqueue_style('mwi-style', MWI_PLUGIN_URL . 'assets/css/maintenance-style.css', array(), MWI_VERSION);
        wp_enqueue_script('mwi-script', MWI_PLUGIN_URL . 'assets/js/maintenance-script.js', array('jquery'), MWI_VERSION, true);
        
        // Pass data ke JavaScript
        wp_localize_script('mwi-script', 'maintenanceWebsiteID', array(
            'endTime' => get_option('mwi_end_time'),
            'ajaxUrl' => admin_url('admin-ajax.php')
        ));
    }
}

// AJAX handler untuk toggle maintenance
add_action('wp_ajax_mwi_toggle_maintenance', 'mwi_ajax_toggle_maintenance');

function mwi_ajax_toggle_maintenance() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized');
    }
    
    check_ajax_referer('mwi_admin_nonce', 'nonce');
    
    $enabled = isset($_POST['enabled']) ? (bool)$_POST['enabled'] : false;
    update_option('mwi_enabled', $enabled);
    
    wp_send_json_success(array(
        'enabled' => $enabled,
        'message' => $enabled ? 'Maintenance mode diaktifkan' : 'Maintenance mode dinonaktifkan'
    ));
}

// AJAX handler untuk check status
add_action('wp_ajax_mwi_check_status', 'mwi_ajax_check_status');
add_action('wp_ajax_nopriv_mwi_check_status', 'mwi_ajax_check_status');

function mwi_ajax_check_status() {
    wp_send_json_success(array(
        'enabled' => (bool)get_option('mwi_enabled', 0)
    ));
}
