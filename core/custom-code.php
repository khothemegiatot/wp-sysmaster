<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Chèn mã tùy chỉnh vào header
 */
function wp_sysmaster_insert_header_code() {
    $options = get_option('wp_sysmaster_options', array());
    if (!empty($options['header_code'])) {
        echo wp_kses($options['header_code'], wp_sysmaster_get_allowed_html()) . "\n";
    }
}
add_action('wp_head', 'wp_sysmaster_insert_header_code', 999);

/**
 * Chèn mã tùy chỉnh vào footer
 */
function wp_sysmaster_insert_footer_code() {
    $options = get_option('wp_sysmaster_options', array());
    if (!empty($options['footer_code'])) {
        echo wp_kses($options['footer_code'], wp_sysmaster_get_allowed_html()) . "\n";
    }
}
add_action('wp_footer', 'wp_sysmaster_insert_footer_code', 999);

/**
 * Thực thi mã PHP tùy chỉnh
 */
function wp_sysmaster_execute_php_code() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $options = get_option('wp_sysmaster_options', array());
    if (!empty($options['php_code'])) {
        try {
            eval($options['php_code']);
        } catch (ParseError $e) {
            error_log(sprintf(
                'WP SysMaster PHP Code Error: %s in custom code on line %d',
                $e->getMessage(),
                $e->getLine()
            ));
        } catch (Error $e) {
            error_log(sprintf(
                'WP SysMaster PHP Error: %s in custom code on line %d',
                $e->getMessage(),
                $e->getLine()
            ));
        }
    }
}

/**
 * Kiểm tra cú pháp PHP
 *
 * @param string $code Mã PHP cần kiểm tra
 * @return true|WP_Error True nếu cú pháp hợp lệ, WP_Error nếu có lỗi
 */
function wp_sysmaster_check_php_syntax($code) {
    // Thêm thẻ PHP nếu không có
    if (strpos($code, '<?php') === false) {
        $code = "<?php\n" . $code;
    }

    // Tạo file tạm thời
    $tmp_file = wp_tempnam('php-check-');
    if (!$tmp_file) {
        return new WP_Error('tmp_file_error', __('Không thể tạo file tạm thời', 'wp-sysmaster'));
    }

    // Ghi mã vào file tạm thời
    file_put_contents($tmp_file, $code);

    // Kiểm tra cú pháp
    $output = array();
    $return_var = 0;
    exec(sprintf('php -l %s 2>&1', escapeshellarg($tmp_file)), $output, $return_var);

    // Xóa file tạm thời
    unlink($tmp_file);

    // Kiểm tra kết quả
    if ($return_var !== 0) {
        $error_message = implode("\n", $output);
        return new WP_Error('syntax_error', $error_message);
    }

    return true;
}

/**
 * Lấy danh sách các thẻ HTML và thuộc tính được phép
 *
 * @return array Danh sách các thẻ và thuộc tính được phép
 */
function wp_sysmaster_get_allowed_html() {
    $allowed_html = array(
        'script' => array(
            'type' => true,
            'src' => true,
            'async' => true,
            'defer' => true,
            'charset' => true,
            'id' => true,
            'class' => true
        ),
        'style' => array(
            'type' => true,
            'id' => true,
            'class' => true,
            'media' => true
        ),
        'link' => array(
            'rel' => true,
            'href' => true,
            'type' => true,
            'media' => true,
            'id' => true,
            'class' => true
        ),
        'meta' => array(
            'name' => true,
            'content' => true,
            'property' => true,
            'charset' => true,
            'http-equiv' => true
        ),
        // Thêm các thẻ HTML cơ bản
        'div' => array(
            'id' => true,
            'class' => true,
            'style' => true
        ),
        'span' => array(
            'id' => true,
            'class' => true,
            'style' => true
        ),
        'p' => array(
            'id' => true,
            'class' => true,
            'style' => true
        ),
        'a' => array(
            'href' => true,
            'title' => true,
            'target' => true,
            'id' => true,
            'class' => true,
            'style' => true,
            'rel' => true
        ),
        'img' => array(
            'src' => true,
            'alt' => true,
            'title' => true,
            'width' => true,
            'height' => true,
            'id' => true,
            'class' => true,
            'style' => true
        )
    );

    return $allowed_html;
}

// Thêm hooks để thực thi mã PHP tùy chỉnh
function wp_sysmaster_init_php_execution() {
    $options = get_option('wp_sysmaster_options', array());
    $position = isset($options['php_code_position']) ? $options['php_code_position'] : 'plugins_loaded';
    
    // Thêm action dựa trên vị trí được chọn
    add_action($position, 'wp_sysmaster_execute_php_code', 999);
}
add_action('init', 'wp_sysmaster_init_php_execution', 5); 