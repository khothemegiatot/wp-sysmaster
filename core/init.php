<?php

if (!defined('ABSPATH')) exit;

/**
 * Đăng ký các menu trong admin
 * Register menus in admin
 * @return void
 */
function wp_sysmaster_core_register_menus(): void {
    add_menu_page(
        __('WP SysMaster', 'wp-sysmaster'),
        __('WP SysMaster', 'wp-sysmaster'),
        'manage_options',
        'wp-sysmaster',
        'wp_sysmaster_core_render_dashboard',
        'dashicons-superhero',
        30
    );

    // Dashboard
    add_submenu_page(
        'wp-sysmaster',
        __('Dashboard', 'wp-sysmaster'),
        __('Dashboard', 'wp-sysmaster'),
        'manage_options',
        'wp-sysmaster',
        'wp_sysmaster_core_render_dashboard'
    );
}

/**
 * Đăng ký settings
 * Register settings
 * @return void
 */
function wp_sysmaster_core_register_settings(): void {
    register_setting(
        'wp_sysmaster_ai_settings',
        WP_SYSMASTER_AI_OPTIONS_KEY,
        [
            'type' => 'array',
            'sanitize_callback' => 'wp_sysmaster_core_sanitize_ai_settings'
        ]
    );
}

/**
 * Sanitize AI settings
 * @param array $input
 * @return array
 */
function wp_sysmaster_core_sanitize_ai_settings($input): array {
    $sanitized = [];

    if (isset($input['openai_api_key'])) {
        $sanitized['openai_api_key'] = sanitize_text_field($input['openai_api_key']);
    }

    if (isset($input['openai_model'])) {
        $sanitized['openai_model'] = sanitize_text_field($input['openai_model']);
    }

    if (isset($input['embedding_provider'])) {
        $sanitized['embedding_provider'] = sanitize_text_field($input['embedding_provider']);
    }

    if (isset($input['embedding_post_types']) && is_array($input['embedding_post_types'])) {
        $sanitized['embedding_post_types'] = array_map('sanitize_text_field', $input['embedding_post_types']);
    }

    return $sanitized;
}

/**
 * Render trang Dashboard
 * Render dashboard page
 * @return void
 */
function wp_sysmaster_core_render_dashboard(): void {
    if (!wp_sysmaster_is_admin()) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'wp-sysmaster'));
    }

    wp_sysmaster_get_template('common/dashboard.php');
}

/**
 * Enqueue admin scripts và styles
 * Enqueue admin scripts and styles
 * @param string $hook
 * @return void
 */
function wp_sysmaster_core_enqueue_assets($hook): void {
    // Chỉ load trên các trang của plugin
    if (strpos($hook, 'wp-sysmaster') === false) {
        return;
    }

    // CSS
    wp_enqueue_style(
        'wp-sysmaster-admin',
        WP_SYSMASTER_PLUGIN_URL . 'assets/css/admin.css',
        [],
        WP_SYSMASTER_VERSION
    );

    // JavaScript
    wp_enqueue_script(
        'wp-sysmaster-admin',
        WP_SYSMASTER_PLUGIN_URL . 'assets/js/admin.js',
        ['jquery'],
        WP_SYSMASTER_VERSION,
        true
    );

    // Localize script
    wp_localize_script('wp-sysmaster-admin', 'wpSysMaster', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('wp-sysmaster-nonce')
    ]);
}



// Enqueue admin scripts và styles
add_action('admin_enqueue_scripts', 'wp_sysmaster_core_enqueue_assets');
// Register settings
add_action('admin_init', 'wp_sysmaster_core_register_settings');
// Register menus
add_action('admin_menu', 'wp_sysmaster_core_register_menus');

use WPSysMaster\Core\Common\Upload;
use WPSysMaster\Core\Common\SMTP;
use WPSysMaster\Core\Common\CustomCode;

 // Load Upload class
 require_once WP_SYSMASTER_PLUGIN_DIR . 'core/common/Upload.php';
 Upload::getInstance();

 // Load SMTP class
 require_once WP_SYSMASTER_PLUGIN_DIR . 'core/common/SMTP.php';
 SMTP::getInstance();

 // Load Custom Code class
 require_once WP_SYSMASTER_PLUGIN_DIR . 'core/common/CustomCode.php';
 CustomCode::getInstance();