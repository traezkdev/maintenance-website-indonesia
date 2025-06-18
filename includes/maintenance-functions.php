<?php
/**
 * Fungsi-fungsi utama untuk Maintenance Website Indonesia Plugin
 */

// Mencegah akses langsung
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Cek apakah user harus bypass maintenance mode
 */
function mwi_should_bypass() {
    // Admin yang login bisa bypass
    if (current_user_can('manage_options')) {
        return true;
    }
    
    // Cek jika sedang mengakses halaman admin
    if (is_admin() || strpos($_SERVER['REQUEST_URI'], '/wp-admin/') !== false) {
        return true;
    }
    
    // Cek jika sedang mengakses wp-login.php
    if (strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false) {
        return true;
    }
    
    // Cek jika sedang mengakses AJAX
    if (defined('DOING_AJAX') && DOING_AJAX) {
        return true;
    }
    
    // Cek jika sedang preview
    if (isset($_GET['mwi_preview']) && current_user_can('manage_options')) {
        return false; // Jangan bypass untuk preview
    }
    
    return false;
}

/**
 * Tampilkan halaman maintenance
 */
function mwi_display_page() {
    // Set HTTP status 503 (Service Unavailable)
    status_header(503);
    
    // Prevent caching
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    // Set header Retry-After jika ada waktu selesai
    $end_time = get_option('mwi_end_time');
    if (!empty($end_time)) {
        $retry_after = strtotime($end_time) - time();
        if ($retry_after > 0) {
            header('Retry-After: ' . $retry_after);
        }
    }
    
    // Force refresh semua options dari database (bypass cache)
    wp_cache_delete('mwi_template', 'options');
    wp_cache_delete('mwi_enabled', 'options');
    wp_cache_delete('mwi_title', 'options');
    wp_cache_delete('mwi_message', 'options');
    wp_cache_delete('mwi_background_color', 'options');
    wp_cache_delete('mwi_text_color', 'options');
    wp_cache_delete('mwi_end_time', 'options');
    wp_cache_delete('mwi_cache_buster', 'options');
    
    // Cek apakah ada file index.html hasil upload
    $custom_index_path = MWI_PLUGIN_PATH . 'uploads/index.html';
    if (file_exists($custom_index_path)) {
        // Tampilkan file index.html custom
        echo file_get_contents($custom_index_path);
        exit;
    }
    
    // Get template name dengan debug
    $template = get_option('mwi_template', 'default');
    
    // Debug info yang lebih detail
    $debug_info = "<!-- MWI Debug: \n";
    $debug_info .= "  Template from DB: '{$template}'\n";
    $debug_info .= "  Template trimmed: '" . trim($template) . "'\n";
    $debug_info .= "  Time: " . date('Y-m-d H:i:s') . "\n";
    $debug_info .= "  All options: " . print_r([
        'mwi_template' => get_option('mwi_template'),
        'mwi_enabled' => get_option('mwi_enabled'),
        'mwi_title' => get_option('mwi_title'),
    ], true) . "\n";
    $debug_info .= "-->\n";
    
    // Normalisasi nilai template
    $template_normalized = strtolower(trim($template));
    
    // Tentukan template file berdasarkan template yang dipilih
    $template_file = 'maintenance-page.php'; // default
    
    // Pastikan template yang dipilih valid
    switch ($template_normalized) {
        case 'modern':
            $template_file = 'maintenance-modern.php';
            break;
        case 'corporate':
            $template_file = 'maintenance-corporate.php';
            break;
        case 'creative':
            $template_file = 'maintenance-creative.php';
            break;
        default:
            $template_file = 'maintenance-page.php';
            break;
    }
    
    // Ambil semua pengaturan untuk template - pastikan variabel tersedia untuk semua template
    $title = get_option('mwi_title', 'Website Sedang Dalam Perbaikan');
    $message = get_option('mwi_message', 'Mohon maaf, website sedang dalam perbaikan untuk meningkatkan kualitas layanan. Silakan kembali lagi nanti.');
    $bg_color = get_option('mwi_background_color', '#2c3e50');
    $text_color = get_option('mwi_text_color', '#ffffff');

    // New fields
    $contact_email = get_option('mwi_contact_email', 'info@' . $_SERVER['HTTP_HOST']);
    $contact_phone = get_option('mwi_contact_phone', '+62123456789');
    $maintenance_icon = get_option('mwi_maintenance_icon', '🔧');
    $progress_text = get_option('mwi_progress_text', 'Sedang dalam proses perbaikan...');
    $contact_text = get_option('mwi_contact_text', 'Untuk informasi lebih lanjut, hubungi kami:');
    $footer_text = get_option('mwi_footer_text', '© ' . date('Y') . ' ' . $_SERVER['HTTP_HOST'] . '. Semua hak dilindungi.');

    // Load template maintenance
    $template_path = MWI_PLUGIN_PATH . 'templates/' . $template_file;

    // Output debug info
    echo $debug_info;

    if (file_exists($template_path)) {
        include $template_path;
    } else {
        // Fallback ke template default
        include MWI_PLUGIN_PATH . 'templates/maintenance-page.php';
    }
    exit;
}

/**
 * Format waktu untuk countdown
 */
function mwi_format_time($timestamp) {
    if (empty($timestamp)) {
        return '';
    }
    
    $time = strtotime($timestamp);
    if ($time === false) {
        return '';
    }
    
    return date('Y-m-d H:i:s', $time);
}

/**
 * Cek apakah waktu maintenance sudah berakhir
 */
function mwi_is_expired() {
    $end_time = get_option('mwi_end_time');
    if (empty($end_time)) {
        return false;
    }
    
    $time = strtotime($end_time);
    if ($time === false) {
        return false;
    }
    
    return time() > $time;
}

/**
 * Auto disable maintenance mode jika sudah expired
 */
add_action('wp_loaded', 'mwi_check_expiry');

function mwi_check_expiry() {
    if (get_option('mwi_enabled') && mwi_is_expired()) {
        update_option('mwi_enabled', 0);
    }
}

/**
 * Tambahkan notice di admin jika maintenance mode aktif
 */
add_action('admin_notices', 'mwi_admin_notice');

function mwi_admin_notice() {
    if (get_option('mwi_enabled')) {
        $end_time = get_option('mwi_end_time');
        $message = 'Mode Maintenance sedang aktif!';
        
        if (!empty($end_time)) {
            $message .= ' Akan berakhir pada: ' . date('d/m/Y H:i', strtotime($end_time));
        }
        
        echo '<div class="notice notice-warning is-dismissible">';
        echo '<p><strong>' . esc_html($message) . '</strong></p>';
        echo '</div>';
    }
}

/**
 * Tambahkan link preview di admin bar
 */
add_action('admin_bar_menu', 'mwi_admin_bar_link', 100);

function mwi_admin_bar_link($wp_admin_bar) {
    if (!current_user_can('manage_options') || !get_option('mwi_enabled')) {
        return;
    }
    
    $wp_admin_bar->add_node(array(
        'id' => 'mwi-preview',
        'title' => 'Preview Maintenance',
        'href' => add_query_arg('mwi_preview', '1', home_url()),
        'meta' => array(
            'target' => '_blank'
        )
    ));
}

/**
 * Handle preview maintenance page
 */
add_action('template_redirect', 'mwi_handle_preview');

function mwi_handle_preview() {
    if (isset($_GET['mwi_preview']) && current_user_can('manage_options')) {
        // Force tampilkan halaman maintenance untuk preview
        mwi_display_page();
    }
}

/**
 * Tambahkan CSS untuk admin notice
 */
add_action('admin_head', 'mwi_admin_styles');

function mwi_admin_styles() {
    if (get_option('mwi_enabled')) {
        echo '<style>
        .mwi-admin-notice {
            border-left: 4px solid #dc3232;
            background: #fff;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
            margin: 5px 15px 2px;
            padding: 1px 12px;
        }
        .mwi-admin-notice p {
            margin: 0.5em 0;
            padding: 2px;
            font-weight: 600;
        }
        </style>';
    }
}

/**
 * Tambahkan meta box di dashboard
 */
add_action('wp_dashboard_setup', 'mwi_dashboard_widget');

function mwi_dashboard_widget() {
    if (current_user_can('manage_options')) {
        wp_add_dashboard_widget(
            'mwi_dashboard_widget',
            'Status Maintenance Website',
            'mwi_dashboard_widget_content'
        );
    }
}

function mwi_dashboard_widget_content() {
    $enabled = get_option('mwi_enabled');
    $end_time = get_option('mwi_end_time');
    
    echo '<div style="text-align: center; padding: 10px;">';
    
    if ($enabled) {
        echo '<h3 style="color: #dc3232;">🔧 Mode Maintenance AKTIF</h3>';
        if (!empty($end_time)) {
            echo '<p>Berakhir: <strong>' . date('d/m/Y H:i', strtotime($end_time)) . '</strong></p>';
        }
        echo '<p><a href="' . admin_url('options-general.php?page=maintenance-website-id') . '" class="button button-primary">Kelola Maintenance</a></p>';
    } else {
        echo '<h3 style="color: #00a32a;">✅ Website Normal</h3>';
        echo '<p>Mode maintenance tidak aktif</p>';
        echo '<p><a href="' . admin_url('options-general.php?page=maintenance-website-id') . '" class="button">Pengaturan Maintenance</a></p>';
    }
    
    echo '</div>';
}
