<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Thêm menu quản lý mã tùy chỉnh
 */
function wp_sysmaster_add_custom_code_menu() {
    add_submenu_page(
        'wp-sysmaster',
        __('Mã tùy chỉnh', 'wp-sysmaster'),
        __('Mã tùy chỉnh', 'wp-sysmaster'),
        'manage_options',
        'wp-sysmaster-custom-code',
        'wp_sysmaster_custom_code_page'
    );
}
add_action('admin_menu', 'wp_sysmaster_add_custom_code_menu');

/**
 * Hiển thị trang quản lý mã tùy chỉnh
 */
function wp_sysmaster_custom_code_page() {
    // Lưu cài đặt
    if (isset($_POST['wp_sysmaster_save_custom_code'])) {
        if (!check_admin_referer('wp_sysmaster_custom_code_nonce')) {
            wp_die(__('Invalid nonce', 'wp-sysmaster'));
        }

        $header_code = isset($_POST['wp_sysmaster_header_code']) ? wp_unslash($_POST['wp_sysmaster_header_code']) : '';
        $footer_code = isset($_POST['wp_sysmaster_footer_code']) ? wp_unslash($_POST['wp_sysmaster_footer_code']) : '';
        $php_code = isset($_POST['wp_sysmaster_php_code']) ? wp_unslash($_POST['wp_sysmaster_php_code']) : '';
        $php_code_position = isset($_POST['wp_sysmaster_php_code_position']) ? sanitize_text_field($_POST['wp_sysmaster_php_code_position']) : 'plugins_loaded';

        // Kiểm tra cú pháp PHP
        if (!empty($php_code)) {
            $syntax_check = wp_sysmaster_check_php_syntax($php_code);
            if (is_wp_error($syntax_check)) {
                add_settings_error(
                    'wp_sysmaster_messages',
                    'wp_sysmaster_message',
                    sprintf(__('Lỗi cú pháp PHP: %s', 'wp-sysmaster'), $syntax_check->get_error_message()),
                    'error'
                );
                $php_code = $options['php_code'] ?? ''; // Giữ lại mã cũ nếu có lỗi
            }
        }

        // Lưu cài đặt
        $options = get_option('wp_sysmaster_options', array());
        $options['header_code'] = $header_code;
        $options['footer_code'] = $footer_code;
        $options['php_code'] = $php_code;
        $options['php_code_position'] = $php_code_position;
        update_option('wp_sysmaster_options', $options);

        add_settings_error(
            'wp_sysmaster_messages',
            'wp_sysmaster_message',
            __('Cài đặt đã được lưu.', 'wp-sysmaster'),
            'updated'
        );
    }

    // Lấy giá trị hiện tại
    $options = get_option('wp_sysmaster_options', array());
    $header_code = isset($options['header_code']) ? $options['header_code'] : '';
    $footer_code = isset($options['footer_code']) ? $options['footer_code'] : '';
    $php_code = isset($options['php_code']) ? $options['php_code'] : '';
    $php_code_position = isset($options['php_code_position']) ? $options['php_code_position'] : 'plugins_loaded';

    // Hiển thị thông báo
    settings_errors('wp_sysmaster_messages');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Mã tùy chỉnh', 'wp-sysmaster'); ?></h1>
        
        <h2 class="nav-tab-wrapper">
            <a href="#html-css-js" class="nav-tab nav-tab-active"><?php echo esc_html__('HTML/CSS/JS', 'wp-sysmaster'); ?></a>
            <a href="#php-code" class="nav-tab"><?php echo esc_html__('PHP Code', 'wp-sysmaster'); ?></a>
        </h2>

        <form method="post" action="">
            <?php wp_nonce_field('wp_sysmaster_custom_code_nonce'); ?>
            
            <div id="html-css-js" class="tab-content">
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="wp_sysmaster_header_code">
                                <?php echo esc_html__('Mã Header', 'wp-sysmaster'); ?>
                            </label>
                        </th>
                        <td>
                            <textarea name="wp_sysmaster_header_code" id="wp_sysmaster_header_code" 
                                    rows="10" class="large-text code" 
                                    placeholder="<?php echo esc_attr__('Nhập mã HTML, CSS hoặc JavaScript để chèn vào header', 'wp-sysmaster'); ?>"
                            ><?php echo esc_textarea($header_code); ?></textarea>
                            <p class="description">
                                <?php echo esc_html__('Mã này sẽ được chèn vào trong thẻ <head> của trang.', 'wp-sysmaster'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="wp_sysmaster_footer_code">
                                <?php echo esc_html__('Mã Footer', 'wp-sysmaster'); ?>
                            </label>
                        </th>
                        <td>
                            <textarea name="wp_sysmaster_footer_code" id="wp_sysmaster_footer_code" 
                                    rows="10" class="large-text code"
                                    placeholder="<?php echo esc_attr__('Nhập mã HTML, CSS hoặc JavaScript để chèn vào footer', 'wp-sysmaster'); ?>"
                            ><?php echo esc_textarea($footer_code); ?></textarea>
                            <p class="description">
                                <?php echo esc_html__('Mã này sẽ được chèn vào trước thẻ </body> đóng.', 'wp-sysmaster'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>

            <div id="php-code" class="tab-content" style="display: none;">
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="wp_sysmaster_php_code">
                                <?php echo esc_html__('Mã PHP', 'wp-sysmaster'); ?>
                            </label>
                        </th>
                        <td>
                            <textarea name="wp_sysmaster_php_code" id="wp_sysmaster_php_code" 
                                    rows="15" class="large-text code"
                                    placeholder="<?php echo esc_attr__('Nhập mã PHP của bạn ở đây', 'wp-sysmaster'); ?>"
                            ><?php echo esc_textarea($php_code); ?></textarea>
                            <p class="description">
                                <?php echo esc_html__('Nhập mã PHP của bạn ở đây. Không cần thẻ mở <?php.', 'wp-sysmaster'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="wp_sysmaster_php_code_position">
                                <?php echo esc_html__('Vị trí thực thi', 'wp-sysmaster'); ?>
                            </label>
                        </th>
                        <td>
                            <select name="wp_sysmaster_php_code_position" id="wp_sysmaster_php_code_position">
                                <option value="plugins_loaded" <?php selected($php_code_position, 'plugins_loaded'); ?>>
                                    <?php echo esc_html__('Sau khi tất cả plugins được load', 'wp-sysmaster'); ?>
                                </option>
                                <option value="init" <?php selected($php_code_position, 'init'); ?>>
                                    <?php echo esc_html__('Khi WordPress khởi tạo', 'wp-sysmaster'); ?>
                                </option>
                                <option value="wp_loaded" <?php selected($php_code_position, 'wp_loaded'); ?>>
                                    <?php echo esc_html__('Sau khi WordPress đã load hoàn toàn', 'wp-sysmaster'); ?>
                                </option>
                            </select>
                            <p class="description">
                                <?php echo esc_html__('Chọn thời điểm thực thi mã PHP của bạn.', 'wp-sysmaster'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>

            <p class="submit">
                <input type="submit" name="wp_sysmaster_save_custom_code" class="button button-primary" 
                       value="<?php echo esc_attr__('Lưu thay đổi', 'wp-sysmaster'); ?>">
            </p>
        </form>
    </div>

    <style>
    .CodeMirror {
        border: 1px solid #ddd;
        height: auto !important;
    }
    .nav-tab-wrapper {
        margin-bottom: 20px;
    }
    </style>

    <script>
    jQuery(document).ready(function($) {
        // Tab switching
        $('.nav-tab').click(function(e) {
            e.preventDefault();
            var targetId = $(this).attr('href');
            
            // Update active tab
            $('.nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            
            // Show/hide content
            $('.tab-content').hide();
            $(targetId).show();
            
            // Refresh CodeMirror instances
            if (headerEditor) headerEditor.refresh();
            if (footerEditor) footerEditor.refresh();
            if (phpEditor) phpEditor.refresh();
        });

        // Khởi tạo CodeMirror cho header
        var headerEditor = CodeMirror.fromTextArea(document.getElementById('wp_sysmaster_header_code'), {
            lineNumbers: true,
            mode: 'htmlmixed',
            theme: 'default',
            autoCloseTags: true,
            autoCloseBrackets: true,
            matchBrackets: true,
            indentUnit: 4,
            lineWrapping: true
        });

        // Khởi tạo CodeMirror cho footer
        var footerEditor = CodeMirror.fromTextArea(document.getElementById('wp_sysmaster_footer_code'), {
            lineNumbers: true,
            mode: 'htmlmixed',
            theme: 'default',
            autoCloseTags: true,
            autoCloseBrackets: true,
            matchBrackets: true,
            indentUnit: 4,
            lineWrapping: true
        });

        // Khởi tạo CodeMirror cho PHP
        var phpEditor = CodeMirror.fromTextArea(document.getElementById('wp_sysmaster_php_code'), {
            lineNumbers: true,
            mode: 'php',
            theme: 'default',
            autoCloseTags: true,
            autoCloseBrackets: true,
            matchBrackets: true,
            indentUnit: 4,
            lineWrapping: true
        });
    });
    </script>
    <?php
}

/**
 * Thêm CSS và JavaScript cho CodeMirror
 */
function wp_sysmaster_enqueue_custom_code_assets($hook) {
    if ('wp-sysmaster_page_wp-sysmaster-custom-code' !== $hook) {
        return;
    }

    // CodeMirror
    wp_enqueue_code_editor(array('type' => 'text/html'));
    wp_enqueue_code_editor(array('type' => 'application/x-httpd-php'));
    wp_enqueue_script('wp-theme-plugin-editor');
    wp_enqueue_style('wp-codemirror');
}
add_action('admin_enqueue_scripts', 'wp_sysmaster_enqueue_custom_code_assets'); 