<?php
/**
 * Định nghĩa các hằng số cho plugin
 */

// Ngăn truy cập trực tiếp
if (!defined('ABSPATH')) exit;

// Plugin version
define('WP_SYSMASTER_VERSION', '1.0.0');

// Plugin paths
define('WP_SYSMASTER_PLUGIN_FILE', __FILE__);
define('WP_SYSMASTER_PLUGIN_DIR', plugin_dir_path(dirname(dirname(__FILE__))));
define('WP_SYSMASTER_PLUGIN_URL', plugin_dir_url(dirname(dirname(__FILE__))));

// Assets paths
if (!defined('WP_SYSMASTER_ASSETS_URL')) {
    define('WP_SYSMASTER_ASSETS_URL', WP_SYSMASTER_PLUGIN_URL . 'assets/');
}

// Template paths
if (!defined('WP_SYSMASTER_TEMPLATES_DIR')) {
    define('WP_SYSMASTER_TEMPLATES_DIR', WP_SYSMASTER_PLUGIN_DIR . 'templates/');
}

// Option names
if (!defined('WP_SYSMASTER_OPTIONS_KEY')) {
    define('WP_SYSMASTER_OPTIONS_KEY', 'wp_sysmaster_settings');
}

// Database options
define('WP_SYSMASTER_AI_OPTIONS_KEY', 'wp_sysmaster_ai_settings');

// Admin capability
define('WP_SYSMASTER_ADMIN_CAPABILITY', 'manage_options');

// Menu slugs
if (!defined('WP_SYSMASTER_MENU_SLUG')) {
    define('WP_SYSMASTER_MENU_SLUG', 'wp-sysmaster');
}

if (!defined('WP_SYSMASTER_AI_SETTINGS_SLUG')) {
    define('WP_SYSMASTER_AI_SETTINGS_SLUG', 'wp-sysmaster-ai-settings');
} 