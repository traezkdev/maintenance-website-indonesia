<?php
/**
 * Halaman Admin untuk Maintenance Website Indonesia Plugin
 */

// Mencegah akses langsung
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('get_current_user_id') && file_exists(dirname(__DIR__) . '/wp-stubs.php')) {
    require_once dirname(__DIR__) . '/wp-stubs.php';
}

// Tambahkan menu di admin
add_action('admin_menu', 'mwi_admin_menu');

function mwi_admin_menu() {
    add_options_page(
        'Maintenance Website Indonesia Settings',
        'Maintenance Website',
        'manage_options',
        'maintenance-website-id',
        'mwi_admin_page'
    );
}

// Tambahkan menu toggle maintenance di admin bar atas
add_action('admin_bar_menu', 'mwi_admin_bar_menu', 100);

function mwi_admin_bar_menu($wp_admin_bar) {
    if (!current_user_can('manage_options')) {
        return;
    }

    $enabled = get_option('mwi_enabled', 0);
    $status_label = $enabled ? 'Aktif' : 'Nonaktif';

    $icon_html = '<span class="mwi-admin-bar-icon" style="margin-right:5px;">🔧</span>';
    $args = array(
        'id'    => 'mwi_maintenance',
        'title' => $icon_html . "Maintenance: $status_label",
        'href'  => '#',
        'meta'  => array(
            'class' => 'mwi-maintenance-admin-bar',
            'title' => 'Toggle Maintenance Mode',
            'html'  => true,
        ),
    );
    $wp_admin_bar->add_node($args);

    // Submenu Aktifkan Maintenance
    $enable_url = wp_nonce_url(admin_url('admin-post.php?action=mwi_enable'), 'mwi_enable_nonce');
    $wp_admin_bar->add_node(array(
        'id'     => 'mwi_enable',
        'title'  => 'Aktifkan Maintenance',
        'parent' => 'mwi_maintenance',
        'href'   => $enable_url,
        'meta'   => array(
            'title' => 'Aktifkan mode maintenance',
        ),
    ));

    // Submenu Nonaktifkan Maintenance
    $disable_url = wp_nonce_url(admin_url('admin-post.php?action=mwi_disable'), 'mwi_disable_nonce');
    $wp_admin_bar->add_node(array(
        'id'     => 'mwi_disable',
        'title'  => 'Nonaktifkan Maintenance',
        'parent' => 'mwi_maintenance',
        'href'   => $disable_url,
        'meta'   => array(
            'title' => 'Nonaktifkan mode maintenance',
        ),
    ));

    // Submenu Pengaturan
    $settings_url = admin_url('options-general.php?page=maintenance-website-id');
    $wp_admin_bar->add_node(array(
        'id'     => 'mwi_settings',
        'title'  => 'Pengaturan',
        'parent' => 'mwi_maintenance',
        'href'   => $settings_url,
        'meta'   => array(
            'title' => 'Pengaturan Maintenance Website Indonesia',
        ),
    ));
}

// Handler untuk mengaktifkan maintenance mode
add_action('admin_post_mwi_enable', 'mwi_enable_maintenance');
function mwi_enable_maintenance() {
    if (!current_user_can('manage_options') || !check_admin_referer('mwi_enable_nonce')) {
        wp_die('Tidak memiliki izin untuk melakukan aksi ini.');
    }
    update_option('mwi_enabled', 1);
    wp_redirect(wp_get_referer() ? wp_get_referer() : admin_url());
    exit;
}

// Handler untuk menonaktifkan maintenance mode
add_action('admin_post_mwi_disable', 'mwi_disable_maintenance');
function mwi_disable_maintenance() {
    if (!current_user_can('manage_options') || !check_admin_referer('mwi_disable_nonce')) {
        wp_die('Tidak memiliki izin untuk melakukan aksi ini.');
    }
    update_option('mwi_enabled', 0);
    wp_redirect(wp_get_referer() ? wp_get_referer() : admin_url());
    exit;
}

// Registrasi settings
add_action('admin_init', 'mwi_admin_init');

function mwi_admin_init() {
    register_setting('mwi_settings', 'mwi_enabled');
    register_setting('mwi_settings', 'mwi_title');
    register_setting('mwi_settings', 'mwi_message');
    register_setting('mwi_settings', 'mwi_end_time');
    register_setting('mwi_settings', 'mwi_background_color');
    register_setting('mwi_settings', 'mwi_text_color');
    register_setting('mwi_settings', 'mwi_template');
    register_setting('mwi_settings', 'mwi_custom_template_enabled');
    register_setting('mwi_settings', 'mwi_contact_email');
    register_setting('mwi_settings', 'mwi_contact_phone');
    register_setting('mwi_settings', 'mwi_maintenance_icon');
    register_setting('mwi_settings', 'mwi_progress_text');
    register_setting('mwi_settings', 'mwi_contact_text');
    register_setting('mwi_settings', 'mwi_footer_text');
}

// Enqueue admin scripts
add_action('admin_enqueue_scripts', 'mwi_admin_scripts');

function mwi_admin_scripts($hook) {
    // Perbaiki pengecekan hook agar lebih fleksibel
    if (strpos($hook, 'settings_page_maintenance-website-id') === false) {
        return;
    }
    
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
    wp_enqueue_script('mwi-admin', MWI_PLUGIN_URL . 'assets/js/admin-script.js', array('jquery', 'wp-color-picker'), MWI_VERSION, true);
    
    // Localize script untuk AJAX
    wp_localize_script('mwi-admin', 'mwiAdmin', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('mwi_admin_nonce')
    ));
}

/**
 * Halaman pengaturan admin
 */
function mwi_admin_page() {
    // Load current user language preference or default to site language
    if (!function_exists('get_current_user_id') || !function_exists('get_user_meta') || !function_exists('get_locale') || !function_exists('update_user_meta')) {
        echo '<div class="notice notice-error"><p><strong>Required WordPress functions are not available. Please ensure this file is loaded within WordPress environment.</strong></p></div>';
        return;
    }

    $current_user_id = get_current_user_id();
    $user_lang = get_user_meta($current_user_id, 'mwi_admin_language', true);
    if (!$user_lang) {
        $user_lang = get_locale();
    }

    // Handle language switcher form submission
    if (isset($_POST['mwi_admin_language'])) {
        check_admin_referer('mwi_language_switch');
        $lang = sanitize_text_field($_POST['mwi_admin_language']);
        if (in_array($lang, ['en_US', 'id_ID'])) {
            update_user_meta($current_user_id, 'mwi_admin_language', $lang);
            $user_lang = $lang;
        }
    }

if (isset($_POST['submit'])) {
    check_admin_referer('mwi_settings');
    
    // Handle delete uploaded index.html
    if (isset($_POST['delete_index_html']) && check_admin_referer('mwi_delete_index_html')) {
        $file_path = MWI_PLUGIN_PATH . 'uploads/index.html';
        if (file_exists($file_path)) {
            if (unlink($file_path)) {
                // Set template back to default after deletion
                update_option('mwi_template', 'default');
                // Redirect to avoid form resubmission and reload updated settings
                wp_redirect(admin_url('options-general.php?page=maintenance-website-id&deleted=1'));
                exit;
            } else {
                echo '<div class="notice notice-error"><p><strong>Gagal menghapus file index.html.</strong></p></div>';
            }
        } else {
            echo '<div class="notice notice-warning"><p><strong>Tidak ada file index.html untuk dihapus.</strong></p></div>';
        }
        // Clear cache after deletion
        wp_cache_delete('mwi_template', 'options');
        wp_cache_delete('mwi_enabled', 'options');
        wp_cache_delete('mwi_title', 'options');
        wp_cache_delete('mwi_message', 'options');
        wp_cache_delete('mwi_background_color', 'options');
        wp_cache_delete('mwi_text_color', 'options');
        wp_cache_delete('mwi_end_time', 'options');
        wp_cache_delete('mwi_cache_buster', 'options');
        update_option('mwi_cache_buster', time());
        set_transient('mwi_force_refresh', time(), 300);
        delete_transient('mwi_force_refresh');
    }
    
    update_option('mwi_enabled', isset($_POST['mwi_enabled']) ? 1 : 0);
    update_option('mwi_title', sanitize_text_field($_POST['mwi_title']));
    update_option('mwi_message', wp_kses_post($_POST['mwi_message']));
    update_option('mwi_end_time', sanitize_text_field($_POST['mwi_end_time']));
    update_option('mwi_background_color', sanitize_hex_color($_POST['mwi_background_color']));
    update_option('mwi_text_color', sanitize_hex_color($_POST['mwi_text_color']));
    
    // Update field-field baru
    if (function_exists('sanitize_email')) {
        update_option('mwi_contact_email', sanitize_email($_POST['mwi_contact_email']));
    } else {
        update_option('mwi_contact_email', filter_var($_POST['mwi_contact_email'], FILTER_SANITIZE_EMAIL));
    }
    update_option('mwi_contact_phone', sanitize_text_field($_POST['mwi_contact_phone']));
    update_option('mwi_maintenance_icon', sanitize_text_field($_POST['mwi_maintenance_icon']));
    update_option('mwi_progress_text', sanitize_text_field($_POST['mwi_progress_text']));
    update_option('mwi_contact_text', sanitize_text_field($_POST['mwi_contact_text']));
    update_option('mwi_footer_text', sanitize_text_field($_POST['mwi_footer_text']));
    
    // Force update template dengan cache clearing yang lebih agresif
    $new_template = isset($_POST['mwi_template']) ? sanitize_text_field($_POST['mwi_template']) : '';
    
    // Validasi template yang diizinkan
    $allowed_templates = ['default', 'modern', 'corporate', 'creative', 'custom'];
    $new_template = strtolower(trim($new_template));
    if ($new_template === '' || !in_array($new_template, $allowed_templates)) {
        // Jika kosong atau tidak valid, gunakan template yang sudah tersimpan sebelumnya
        $new_template = get_option('mwi_template', 'default');
    }
    
    // Handle file upload index.html
if (isset($_POST['mwi_index_html_content'])) {
        $custom_index_path = MWI_PLUGIN_PATH . 'uploads/';
        if (!file_exists($custom_index_path)) {
            wp_mkdir_p($custom_index_path);
        }
        $destination = $custom_index_path . 'index.html';
        $content = wp_unslash($_POST['mwi_index_html_content']);
        if (trim($content) === '') {
            // If content is empty, delete the file if exists
            if (file_exists($destination)) {
                unlink($destination);
            }
            // Only reset template to default if current template is custom
            $current_template = get_option('mwi_template', 'default');
            if ($current_template === 'custom') {
                $new_template = 'default';
            }
        } else {
            // Save content to file and keep template as custom
            if (file_put_contents($destination, $content) !== false) {
                echo '<div class="notice notice-success"><p><strong>File index.html berhasil disimpan dari editor.</strong></p></div>';
                $new_template = 'custom';
            } else {
                echo '<div class="notice notice-error"><p><strong>Gagal menyimpan file index.html dari editor.</strong></p></div>';
            }
        }
    }
    
    // Clear WordPress object cache
    wp_cache_delete('mwi_template', 'options');
    wp_cache_delete('mwi_enabled', 'options');
    wp_cache_delete('mwi_title', 'options');
    wp_cache_delete('mwi_message', 'options');
    wp_cache_delete('mwi_background_color', 'options');
    wp_cache_delete('mwi_text_color', 'options');
    wp_cache_delete('mwi_end_time', 'options');
    wp_cache_delete('mwi_cache_buster', 'options');
    
    // Force update template
    update_option('mwi_template', $new_template);
    
    // Clear all WordPress cache
    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
    }
    
    // Clear Redis cache jika ada
    if (function_exists('wp_cache_flush_group')) {
        wp_cache_flush_group('options');
    }
    
    // Clear plugin cache populer
    // WP Rocket
    if (function_exists('rocket_clean_domain')) {
        rocket_clean_domain();
    }
    
    // W3 Total Cache
    if (function_exists('w3tc_flush_all')) {
        w3tc_flush_all();
    }
    
    // WP Super Cache
    if (function_exists('wp_cache_clear_cache')) {
        wp_cache_clear_cache();
    }
    
    // LiteSpeed Cache
    if (class_exists('LiteSpeed_Cache_API')) {
        LiteSpeed_Cache_API::purge_all();
    }
    
    // WP Fastest Cache
    if (class_exists('WpFastestCache')) {
        $wpfc = new WpFastestCache();
        $wpfc->deleteCache(true);
    }
    
    // Autoptimize
    if (class_exists('autoptimizeCache')) {
        autoptimizeCache::clearall();
    }
    
    // SG Optimizer
    if (function_exists('sg_cachepress_purge_cache')) {
        sg_cachepress_purge_cache();
    }
    
    // Cloudflare (jika ada plugin)
    if (class_exists('CF\WordPress\Hooks')) {
        do_action('cloudflare_purge_cache');
    }
    
    // Force database option refresh dengan timestamp
    $timestamp = time();
    update_option('mwi_cache_buster', $timestamp);
    
    // Set transient untuk memaksa refresh
    set_transient('mwi_force_refresh', $timestamp, 300); // 5 menit
    
    // Hapus transient untuk memaksa refresh cache
    delete_transient('mwi_force_refresh');
    
    echo '<div class="notice notice-success"><p><strong>Pengaturan berhasil disimpan! Template: ' . esc_html($new_template) . '</strong></p></div>';
}
    
    // Clear cache to ensure file_exists check is accurate
    wp_cache_delete('mwi_template', 'options');
    wp_cache_delete('mwi_enabled', 'options');
    wp_cache_delete('mwi_title', 'options');
    wp_cache_delete('mwi_message', 'options');
    wp_cache_delete('mwi_background_color', 'options');
    wp_cache_delete('mwi_text_color', 'options');
    wp_cache_delete('mwi_end_time', 'options');
    wp_cache_delete('mwi_cache_buster', 'options');

    // Ambil nilai current settings
    $enabled = get_option('mwi_enabled', 0);
    $title = get_option('mwi_title', 'Website Sedang Dalam Perbaikan');
    $message = get_option('mwi_message', 'Mohon maaf, website sedang dalam perbaikan untuk meningkatkan kualitas layanan. Silakan kembali lagi nanti.');
    $end_time = get_option('mwi_end_time', '');
    $bg_color = get_option('mwi_background_color', '#2c3e50');
    $text_color = get_option('mwi_text_color', '#ffffff');
    $template = get_option('mwi_template', 'default');
    ?>
    
    <div class="wrap">
        <h1>🔧 Maintenance Website Indonesia</h1>
        
        <?php if ($enabled): ?>
            <div class="notice notice-warning">
                <p><strong>⚠️ Mode Maintenance sedang AKTIF!</strong> 
                   <a href="<?php echo add_query_arg('mwi_preview', '1', home_url()); ?>" target="_blank" class="button button-small">Preview Halaman Maintenance</a>
                </p>
            </div>
        <?php endif; ?>
        
        <div class="mwi-admin-container">
            <div class="mwi-main-content">
<form method="post" action="" id="mwi-settings-form" enctype="multipart/form-data">
    <?php wp_nonce_field('mwi_settings'); ?>
    
    <table class="form-table">
        <tr>
            <th scope="row">Status Maintenance Mode</th>
            <td>
                <div class="mwi-toggle-container">
                    <label class="mwi-toggle">
                        <input type="checkbox" name="mwi_enabled" value="1" <?php checked($enabled, 1); ?>>
                        <span class="mwi-slider"></span>
                    </label>
                    <span class="mwi-toggle-label">Aktifkan Mode Maintenance</span>
                </div>
                <p class="description">Centang untuk mengaktifkan mode maintenance di website Anda.</p>
            </td>
        </tr>
        
        <tr>
            <th scope="row">Judul Halaman</th>
            <td>
                <input type="text" name="mwi_title" value="<?php echo esc_attr($title); ?>" class="regular-text" placeholder="Website Sedang Dalam Perbaikan">
                <p class="description">Judul yang akan ditampilkan di halaman maintenance.</p>
            </td>
        </tr>
        
        <tr>
            <th scope="row">Pesan Maintenance</th>
            <td>
                <textarea name="mwi_message" rows="4" cols="50" class="large-text" placeholder="Mohon maaf, website sedang dalam perbaikan..."><?php echo esc_textarea($message); ?></textarea>
                <p class="description">Pesan yang akan ditampilkan kepada pengunjung. HTML dasar diperbolehkan.</p>
            </td>
        </tr>
        
        <tr>
            <th scope="row">Waktu Selesai Maintenance</th>
            <td>
                <input type="datetime-local" name="mwi_end_time" value="<?php echo !empty($end_time) ? esc_attr(date('Y-m-d\TH:i', strtotime($end_time))) : ''; ?>">
                <p class="description">Opsional: Waktu perkiraan selesai maintenance. Akan menampilkan countdown timer.</p>
            </td>
        </tr>
        
        <tr>
            <th scope="row">Warna Background</th>
            <td>
                <input type="text" name="mwi_background_color" value="<?php echo esc_attr($bg_color); ?>" class="color-picker">
                <p class="description">Warna background halaman maintenance.</p>
            </td>
        </tr>
        
        <tr>
            <th scope="row">Warna Teks</th>
            <td>
                <input type="text" name="mwi_text_color" value="<?php echo esc_attr($text_color); ?>" class="color-picker">
                <p class="description">Warna teks di halaman maintenance.</p>
            </td>
        </tr>

        <tr>
            <th scope="row">Email Kontak</th>
            <td>
                <input type="email" name="mwi_contact_email" value="<?php echo esc_attr(get_option('mwi_contact_email', 'info@' . $_SERVER['HTTP_HOST'])); ?>" class="regular-text">
                <p class="description">Email yang akan ditampilkan di halaman maintenance untuk kontak.</p>
            </td>
        </tr>

        <tr>
            <th scope="row">Nomor Telepon</th>
            <td>
                <input type="text" name="mwi_contact_phone" value="<?php echo esc_attr(get_option('mwi_contact_phone', '+62123456789')); ?>" class="regular-text">
                <p class="description">Nomor telepon yang akan ditampilkan di halaman maintenance untuk kontak.</p>
            </td>
        </tr>

        <tr>
            <th scope="row">Icon Maintenance</th>
            <td>
                <input type="text" name="mwi_maintenance_icon" value="<?php echo esc_attr(get_option('mwi_maintenance_icon', '🔧')); ?>" class="regular-text">
                <p class="description">Icon yang akan ditampilkan di halaman maintenance (emoji atau HTML entity).</p>
            </td>
        </tr>

        <tr>
            <th scope="row">Teks Progress</th>
            <td>
                <input type="text" name="mwi_progress_text" value="<?php echo esc_attr(get_option('mwi_progress_text', 'Sedang dalam proses perbaikan...')); ?>" class="regular-text">
                <p class="description">Teks yang ditampilkan di bawah progress bar.</p>
            </td>
        </tr>

        <tr>
            <th scope="row">Teks Kontak Info</th>
            <td>
                <input type="text" name="mwi_contact_text" value="<?php echo esc_attr(get_option('mwi_contact_text', 'Untuk informasi lebih lanjut, hubungi kami:')); ?>" class="regular-text">
                <p class="description">Teks yang ditampilkan di atas tombol kontak.</p>
            </td>
        </tr>

        <tr>
            <th scope="row">Teks Footer</th>
            <td>
                <input type="text" name="mwi_footer_text" value="<?php echo esc_attr(get_option('mwi_footer_text', '© ' . date('Y') . ' ' . $_SERVER['HTTP_HOST'] . '. Semua hak dilindungi.')); ?>" class="regular-text">
                <p class="description">Teks yang ditampilkan di footer halaman maintenance.</p>
            </td>
        </tr>
        
        <tr>
            <th scope="row">Template Maintenance</th>
            <td>
        <fieldset>
            <label>
                <input type="radio" name="mwi_template" value="default" <?php checked($template, 'default'); ?>>
                🇮🇩 Indonesia Classic (Default)
            </label><br>
            <label>
                <input type="radio" name="mwi_template" value="modern" <?php checked($template, 'modern'); ?>>
                ✨ Modern Minimalist
            </label><br>
            <label>
                <input type="radio" name="mwi_template" value="corporate" <?php checked($template, 'corporate'); ?>>
                🏢 Corporate Professional
            </label><br>
            <label>
                <input type="radio" name="mwi_template" value="creative" <?php checked($template, 'creative'); ?>>
                🎨 Creative Colorful
            </label><br>
            <label>
                <input type="radio" name="mwi_template" value="custom" <?php checked($template, 'custom'); ?>>
                📄 Custom Template (index.html)
            </label>
        </fieldset>
        <p class="description">Pilih template tampilan halaman maintenance yang sesuai dengan brand website Anda.</p>
        <div class="mwi-template-preview" id="template-preview">
            <div class="template-preview-item" data-template="<?php echo esc_attr($template); ?>">
                <div class="preview-box">
                    <?php if ($template === 'default'): ?>
                        <h3>🇮🇩 Indonesia Classic</h3>
                        <p>Template dengan nuansa Indonesia, floating shapes, dan countdown timer.</p>
                    <?php elseif ($template === 'modern'): ?>
                        <h3>✨ Modern Minimalist</h3>
                        <p>Template dengan desain minimalis modern dan elegan.</p>
                    <?php elseif ($template === 'corporate'): ?>
                        <h3>🏢 Corporate Professional</h3>
                        <p>Template dengan tampilan profesional untuk website bisnis.</p>
                    <?php elseif ($template === 'creative'): ?>
                        <h3>🎨 Creative Colorful</h3>
                        <p>Template dengan desain kreatif dan warna-warni menarik.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <style>
        .mwi-template-preview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
            perspective: 1000px;
        }
        
        .template-preview-item {
            background: transparent;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            transform-style: preserve-3d;
            position: relative;
        }
        
        .template-preview-item:hover {
            transform: translateY(-10px) rotateX(5deg);
        }
        
        .preview-box {
            position: relative;
            padding: 40px 30px;
            text-align: center;
            min-height: 220px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            transition: all 0.5s ease;
        }

        /* Default Template - Indonesia Classic */
        .template-preview-item[data-template="default"] .preview-box {
            background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
            animation: gradientFlow 10s ease infinite;
        }
        
        .template-preview-item[data-template="default"] .preview-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='rgba(255,255,255,0.1)' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.5;
            animation: floatingShapes 20s linear infinite;
        }

        /* Modern Template */
        .template-preview-item[data-template="modern"] .preview-box {
            background: linear-gradient(135deg, #614385, #516395);
            animation: modernGlow 3s ease-in-out infinite;
        }
        
        .template-preview-item[data-template="modern"] .preview-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 48%, rgba(255,255,255,0.1) 50%, transparent 52%);
            background-size: 200% 200%;
            animation: modernLines 10s linear infinite;
        }

        /* Corporate Template */
        .template-preview-item[data-template="corporate"] .preview-box {
            background: linear-gradient(135deg, #2C3E50, #3498DB);
            animation: corporateShine 5s ease-in-out infinite;
        }
        
        .template-preview-item[data-template="corporate"] .preview-box::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            right: -50%;
            bottom: -50%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            animation: corporateLight 3s ease-in-out infinite;
        }

        /* Creative Template */
        .template-preview-item[data-template="creative"] .preview-box {
            background: linear-gradient(135deg, #FC466B, #3F5EFB);
            animation: creativeColors 8s ease infinite;
        }
        
        .template-preview-item[data-template="creative"] .preview-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.2) 0%, transparent 50%);
            animation: creativeBubbles 10s ease-in-out infinite;
        }
        
        .preview-box h3 {
            margin: 0 0 20px 0;
            font-size: 1.6em;
            font-weight: 700;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            transform: translateY(0);
            transition: transform 0.3s ease;
            position: relative;
            z-index: 1;
        }
        
        .preview-box p {
            margin: 0;
            font-size: 1.1em;
            line-height: 1.6;
            color: white;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
            position: relative;
            z-index: 1;
            opacity: 0.95;
        }

        /* Animations */
        @keyframes gradientFlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes floatingShapes {
            0% { background-position: 0 0; }
            100% { background-position: 100px 100px; }
        }

        @keyframes modernGlow {
            0% { box-shadow: 0 5px 25px rgba(81,99,149,0.5); }
            50% { box-shadow: 0 5px 35px rgba(97,67,133,0.8); }
            100% { box-shadow: 0 5px 25px rgba(81,99,149,0.5); }
        }

        @keyframes modernLines {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        @keyframes corporateShine {
            0% { box-shadow: 0 5px 25px rgba(52,152,219,0.3); }
            50% { box-shadow: 0 5px 35px rgba(52,152,219,0.6); }
            100% { box-shadow: 0 5px 25px rgba(52,152,219,0.3); }
        }

        @keyframes corporateLight {
            0% { transform: rotate(45deg) translateX(-100%); }
            100% { transform: rotate(45deg) translateX(100%); }
        }

        @keyframes creativeColors {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes creativeBubbles {
            0% { transform: scale(1); opacity: 0.2; }
            50% { transform: scale(1.2); opacity: 0.3; }
            100% { transform: scale(1); opacity: 0.2; }
        }

        .template-preview-item:hover .preview-box h3 {
            transform: translateY(-5px);
        }

        .template-preview-item:hover .preview-box {
            transform: scale(1.02);
        }
        </style>
            </td>
        </tr>
        <tr id="mwi-upload-row" style="display: <?php echo ($template === 'custom') ? 'table-row' : 'none'; ?>;">
            <th scope="row">Custom HTML index.html</th>
            <td>
                <textarea name="mwi_index_html_content" rows="15" cols="80" style="width: 100%; font-family: monospace; font-size: 14px;" placeholder="Salin dan tempel isi file index.html di sini"><?php
                    $custom_index_path = MWI_PLUGIN_PATH . 'uploads/index.html';
                    if (file_exists($custom_index_path)) {
                        echo esc_textarea(file_get_contents($custom_index_path));
                    }
                ?></textarea>
                <p class="description">Salin dan tempel isi file index.html untuk halaman maintenance custom.</p>
            </td>
        </tr>
    </table>
    
    <?php submit_button('💾 Simpan Pengaturan', 'primary', 'submit', false); ?>
    <a href="<?php echo add_query_arg('mwi_preview', '1', home_url()); ?>" target="_blank" class="button">👁️ Preview</a>
</form>

<script>
jQuery(document).ready(function($) {
    // Initialize color picker
    $('.color-picker').wpColorPicker();
    
    const templateRadios = document.querySelectorAll('input[name="mwi_template"]');
    const uploadRow = document.getElementById('mwi-upload-row');
    const previewContainer = document.getElementById('template-preview');
    const previewItem = previewContainer.querySelector('.template-preview-item');

    function updatePreview(template) {
        previewItem.setAttribute('data-template', template);
        const previewBox = previewItem.querySelector('.preview-box');
        
        let content = '';
        switch(template) {
            case 'default':
                content = `
                    <h3>🇮🇩 Indonesia Classic</h3>
                    <p>Template dengan nuansa Indonesia, floating shapes, dan countdown timer.</p>
                `;
                break;
            case 'modern':
                content = `
                    <h3>✨ Modern Minimalist</h3>
                    <p>Template dengan desain minimalis modern dan elegan.</p>
                `;
                break;
            case 'corporate':
                content = `
                    <h3>🏢 Corporate Professional</h3>
                    <p>Template dengan tampilan profesional untuk website bisnis.</p>
                `;
                break;
            case 'creative':
                content = `
                    <h3>🎨 Creative Colorful</h3>
                    <p>Template dengan desain kreatif dan warna-warni menarik.</p>
                `;
                break;
        }
        
        if (content) {
            previewBox.innerHTML = content;
            previewContainer.style.display = 'block';
        } else {
            previewContainer.style.display = 'none';
        }
    }

    function toggleUploadRow() {
        const selected = document.querySelector('input[name="mwi_template"]:checked').value;
        if (selected === 'custom') {
            uploadRow.style.display = 'table-row';
            previewContainer.style.display = 'none';
        } else {
            uploadRow.style.display = 'none';
            updatePreview(selected);
        }
    }

    templateRadios.forEach(radio => {
        radio.addEventListener('change', toggleUploadRow);
    });

    toggleUploadRow();
});
</script>
            </div>
            
            <div class="mwi-sidebar">
                <div class="mwi-info-box">
                    <h3>📊 Informasi Plugin</h3>
<p><strong>Versi:</strong> 2.5.2</p>
<p><strong>Pembuat:</strong><a href="https://instagram.com/alfinf1rdaus">Alfin firdaus </a></p>
                    <p><strong>Status:</strong> 
                        <?php if ($enabled): ?>
                            <span style="color: #d63638; font-weight: bold;">🔴 Aktif</span>
                        <?php else: ?>
                            <span style="color: #00a32a; font-weight: bold;">🟢 Tidak Aktif</span>
                        <?php endif; ?>
                    </p>
                    
                    <h4>✨ Fitur Plugin:</h4>
                    <ul>
                        <li>✅ Mode maintenance dengan halaman custom</li>
                        <li>✅ Countdown timer otomatis</li>
                        <li>✅ Bypass otomatis untuk admin</li>
                        <li>✅ Customisasi warna dan pesan</li>
                        <li>✅ Preview halaman maintenance</li>
                        <li>✅ Auto-disable saat waktu habis</li>
                        <li>✅ Dashboard widget</li>
                        <li>✅ Admin bar quick access</li>
                    </ul>
                </div>
                
                <div class="mwi-info-box">
                    <h4>📝 Cara Penggunaan:</h4>
                    <ol>
                        <li>Aktifkan "Mode Maintenance"</li>
                        <li>Atur judul dan pesan sesuai kebutuhan</li>
                        <li>Opsional: Set waktu selesai untuk countdown</li>
                        <li>Kustomisasi warna sesuai brand website</li>
                        <li>Klik "Simpan Pengaturan"</li>
                        <li>Preview untuk melihat hasil</li>
                    </ol>
                </div>
                
                <div class="mwi-info-box">
                    <h4>💡 Tips:</h4>
                    <ul>
                        <li>Admin tetap bisa mengakses website</li>
                        <li>Gunakan preview sebelum mengaktifkan</li>
                        <li>Set waktu selesai untuk informasi pengunjung</li>
                        <li>Plugin otomatis nonaktif saat waktu habis</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <style>
    .mwi-admin-container {
        display: flex;
        gap: 20px;
        margin-top: 20px;
    }
    .mwi-main-content {
        flex: 2;
    }
    .mwi-sidebar {
        flex: 1;
        max-width: 300px;
    }
    .mwi-info-box {
        background: #f1f1f1;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 8px;
        border-left: 4px solid #0073aa;
    }
    .mwi-info-box h3, .mwi-info-box h4 {
        margin-top: 0;
        color: #23282d;
    }
    .mwi-info-box ul, .mwi-info-box ol {
        margin-left: 20px;
    }
    .mwi-info-box li {
        margin-bottom: 5px;
    }
    .mwi-toggle-container {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 10px;
    }
    
    .mwi-toggle {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
        flex-shrink: 0;
    }
    
    .mwi-toggle input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .mwi-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.2);
    }
    
    .mwi-slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    input:checked + .mwi-slider {
        background-color: #2271b1;
    }
    
    input:checked + .mwi-slider:before {
        transform: translateX(26px);
    }
    
    .mwi-toggle-label {
        font-size: 14px;
        font-weight: 600;
        color: #1d2327;
    }
    
    input:focus + .mwi-slider {
        box-shadow: 0 0 1px #2271b1;
    }
    /* Template Preview Styles */
    .mwi-template-preview {
        margin-top: 15px;
        display: grid;
        gap: 20px;
    }
    
    .template-preview-item {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .template-preview-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }
    
    .template-preview-item h3 {
        margin: 10px 0;
        font-size: 1.2em;
    }
    
    .template-preview-item p {
        color: #666;
        margin: 5px 0;
    }
    
    @media (max-width: 768px) {
        .mwi-admin-container {
            flex-direction: column;
        }
        .mwi-sidebar {
            max-width: none;
        }
        .mwi-template-preview {
            grid-template-columns: 1fr;
        }
    }
    </style>
    <?php
}
