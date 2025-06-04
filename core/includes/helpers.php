<?php
/**
 * Các hàm tiện ích cho plugin
 */

// Ngăn truy cập trực tiếp
if (!defined('ABSPATH')) exit;

/**
 * Lấy template
 * 
 * @param string $template_name Tên template
 * @param array $args Các biến truyền vào template
 * @param string $template_path Đường dẫn tới thư mục template
 * @param string $default_path Đường dẫn mặc định
 */
function wp_sysmaster_get_template($template_name, $args = [], $template_path = '', $default_path = '') {
    if (!empty($args) && is_array($args)) {
        extract($args);
    }

    $located = wp_sysmaster_locate_template($template_name, $template_path, $default_path);

    if (!file_exists($located)) {
        return;
    }

    include $located;
}

/**
 * Tìm template trong theme hoặc plugin
 * 
 * @param string $template_name Tên template
 * @param string $template_path Đường dẫn tới thư mục template
 * @param string $default_path Đường dẫn mặc định
 * @return string
 */
function wp_sysmaster_locate_template($template_name, $template_path = '', $default_path = '') {
    if (!$template_path) {
        $template_path = 'wp-sysmaster/';
    }

    if (!$default_path) {
        $default_path = WP_SYSMASTER_TEMPLATES_DIR;
    }

    // Look within passed path within the theme - this is priority
    $template = locate_template([
        trailingslashit($template_path) . $template_name,
        $template_name
    ]);

    // Get default template
    if (!$template) {
        $template = $default_path . $template_name;
    }

    return $template;
}

/**
 * Lấy đường dẫn tới file asset
 */
function wp_sysmaster_asset_url($path) {
    return WP_SYSMASTER_PLUGIN_URL . 'assets/' . ltrim($path, '/');
}

/**
 * Lấy đường dẫn tới file template
 */
function wp_sysmaster_template_path($template) {
    return WP_SYSMASTER_PLUGIN_DIR . 'templates/' . ltrim($template, '/');
}

/**
 * Kiểm tra xem user có quyền admin không
 * 
 * @return bool
 */
function wp_sysmaster_is_admin() {
    return current_user_can(WP_SYSMASTER_ADMIN_CAPABILITY);
}

/**
 * Log error
 * 
 * @param string $message Message to log
 * @param mixed $data Additional data to log
 */
function wp_sysmaster_log_error($message, $data = null) {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('[WP SysMaster] ' . $message);
        if ($data) {
            error_log(print_r($data, true));
        }
    }
}

/**
 * Lấy cài đặt plugin
 * 
 * @param string $key Key của setting
 * @param mixed $default Giá trị mặc định
 * @return mixed
 */
function wp_sysmaster_get_setting($key, $default = null) {
    $options = get_option(WP_SYSMASTER_OPTIONS_KEY, []);
    return isset($options[$key]) ? $options[$key] : $default;
}

/**
 * Lấy cài đặt AI
 */
function wp_sysmaster_get_ai_settings($key = null) {
    $settings = get_option(WP_SYSMASTER_AI_OPTIONS_KEY, []);
    
    if ($key === null) {
        return $settings;
    }
    
    return isset($settings[$key]) ? $settings[$key] : null;
}

/**
 * Cập nhật cài đặt AI
 */
function wp_sysmaster_update_ai_settings($key, $value) {
    $settings = wp_sysmaster_get_ai_settings();
    $settings[$key] = $value;
    
    return update_option(WP_SYSMASTER_AI_OPTIONS_KEY, $settings);
}

/**
 * Kiểm tra xem có phải trang admin của plugin không
 */
function wp_sysmaster_is_admin_page($page = null) {
    global $pagenow;
    
    if ($pagenow !== 'admin.php') {
        return false;
    }
    
    $current_page = isset($_GET['page']) ? $_GET['page'] : '';
    
    if ($page === null) {
        return strpos($current_page, 'wp-sysmaster') === 0;
    }
    
    return $current_page === $page;
}

/**
 * Tạo nonce cho form
 * 
 * @param string $action Tên action
 * @return string
 */
function wp_sysmaster_nonce_field($action) {
    return wp_nonce_field('wp_sysmaster_' . $action, '_wpnonce', true, false);
}

/**
 * Kiểm tra nonce
 * 
 * @param string $action Tên action
 * @return bool
 */
function wp_sysmaster_verify_nonce($action) {
    return isset($_REQUEST['_wpnonce']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'wp_sysmaster_' . $action);
} 