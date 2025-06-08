<?php
namespace WPSysMaster\Admin;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class quản lý chèn mã tùy chỉnh
 * Class to manage custom code insertion
 */
class CustomCode {
    /**
     * Instance của class
     * Instance of the class
     */
    private static $instance = null;

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Lấy instance của class
     * Get class instance
     */
    public static function getInstance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Khởi tạo hooks
     * Initialize hooks
     */
    private function init_hooks() {
        // Hooks cho frontend
        add_action('wp_head', array($this, 'insert_header_code'), 999);
        
        // Thêm body scripts
        if (function_exists('wp_body_open')) {
            add_action('wp_body_open', array($this, 'insert_body_code'), 1);
        } else {
            add_action('after_body_open_tag', array($this, 'insert_body_code'), 1);
            add_filter('template_include', array($this, 'add_body_open_hook'));
        }
        
        add_action('wp_footer', array($this, 'insert_footer_code'), 999);
        add_action('init', array($this, 'init_php_execution'), 5);

        // Hook cho admin
        if (is_admin()) {
            add_action('admin_init', array($this, 'register_settings'));
            add_action('wp_ajax_wp_custom_codes_test_php', array($this, 'ajax_test_php'));
        }
    }

    /**
     * Đăng ký settings
     * Register settings
     */
    public function register_settings() {
        register_setting('wp_sysmaster_code_settings', 'wp_sysmaster_code_settings', array(
            'sanitize_callback' => array($this, 'sanitize_settings')
        ));
    }

    /**
     * Sanitize settings
     */
    public function sanitize_settings($input) {
        $output = array();

        if (isset($input['php_code'])) {
            $output['php_code'] = $input['php_code'];
            $output['php_code_type'] = sanitize_text_field($input['php_code_type']);
            $output['php_code_hook'] = sanitize_text_field($input['php_code_hook']);
        }

        if (isset($input['header_scripts'])) {
            $output['header_scripts'] = $this->sanitize_scripts($input['header_scripts']);
        }

        if (isset($input['body_scripts'])) {
            $output['body_scripts'] = $this->sanitize_scripts($input['body_scripts']);
        }

        if (isset($input['footer_scripts'])) {
            $output['footer_scripts'] = $this->sanitize_scripts($input['footer_scripts']);
        }

        if (isset($input['custom_css'])) {
            $output['custom_css'] = wp_strip_all_tags($input['custom_css']);
        }

        return $output;
    }

    /**
     * Sanitize scripts
     */
    private function sanitize_scripts($content) {
        return wp_kses($content, $this->get_allowed_html());
    }

    /**
     * Chèn mã tùy chỉnh vào header
     * Insert custom code into header
     */
    public function insert_header_code() {
        $settings = get_option('wp_sysmaster_code_settings', array());
        
        // Custom CSS
        if (!empty($settings['custom_css'])) {
            echo '<style type="text/css">' . "\n";
            echo wp_strip_all_tags($settings['custom_css']) . "\n";
            echo '</style>' . "\n";
        }

        // Header scripts
        if (!empty($settings['header_scripts'])) {
            echo $settings['header_scripts'] . "\n";
        }
    }

    /**
     * Chèn mã tùy chỉnh vào sau thẻ body
     */
    public function insert_body_code() {
        $settings = get_option('wp_sysmaster_code_settings', array());
        if (!empty($settings['body_scripts'])) {
            echo $settings['body_scripts'] . "\n";
        }
    }

    /**
     * Chèn mã tùy chỉnh vào footer
     * Insert custom code into footer
     */
    public function insert_footer_code() {
        $settings = get_option('wp_sysmaster_code_settings', array());
        if (!empty($settings['footer_scripts'])) {
            echo $settings['footer_scripts'] . "\n";
        }
    }

    /**
     * Thực thi mã PHP tùy chỉnh
     * Execute custom PHP code
     */
    public function execute_php_code() {
        if (!current_user_can('manage_options')) {
            return;
        }

        $settings = get_option('wp_sysmaster_code_settings', array());
        if (!empty($settings['php_code'])) {
            try {
                if ($settings['php_code_type'] === 'function') {
                    // Wrap code in function
                    $code = 'function wp_sysmaster_custom_function() {' . "\n";
                    $code .= $settings['php_code'] . "\n";
                    $code .= '}' . "\n";
                    $code .= 'wp_sysmaster_custom_function();';
                    eval($code);
                } elseif ($settings['php_code_type'] === 'shortcode') {
                    // Register shortcode
                    add_shortcode('wp_sysmaster_code', function($atts, $content = null) use ($settings) {
                        ob_start();
                        eval($settings['php_code']);
                        return ob_get_clean();
                    });
                } else {
                    // Execute as is
                    eval($settings['php_code']);
                }
            } catch (\ParseError $e) {
                error_log(sprintf(
                    'WP SysMaster PHP Code Error: %s in custom code on line %d',
                    $e->getMessage(),
                    $e->getLine()
                ));
            } catch (\Error $e) {
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
     * Check PHP syntax
     */
    public function check_php_syntax($code) {
        // Thêm thẻ PHP nếu không có
        if (strpos($code, '<?php') === false) {
            $code = "<?php\n" . $code;
        }

        // Tạo file tạm thời
        $tmp_file = wp_tempnam('php-check-');
        if (!$tmp_file) {
            return new \WP_Error('tmp_file_error', __('Không thể tạo file tạm thời', 'wp-sysmaster'));
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
            return new \WP_Error('syntax_error', $error_message);
        }

        return true;
    }

    /**
     * Khởi tạo thực thi mã PHP
     * Initialize PHP execution
     */
    public function init_php_execution() {
        $settings = get_option('wp_sysmaster_code_settings', array());
        
        if (!empty($settings['php_code']) && $settings['php_code_type'] === 'action') {
            $hook = !empty($settings['php_code_hook']) ? $settings['php_code_hook'] : 'init';
            add_action($hook, array($this, 'execute_php_code'), 999);
        } else {
            $this->execute_php_code();
        }
    }

    /**
     * Lấy danh sách các thẻ HTML và thuộc tính được phép
     * Get allowed HTML tags and attributes
     */
    private function get_allowed_html() {
        return array(
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
    }

    /**
     * Thêm hook after_body_open_tag cho các theme cũ
     * Add hook after_body_open_tag for old themes
     */
    public function add_body_open_hook($template) {
        ob_start(function($buffer) {
            $body_position = strpos($buffer, '<body');
            if ($body_position !== false) {
                $body_end = strpos($buffer, '>', $body_position);
                if ($body_end !== false) {
                    $before = substr($buffer, 0, $body_end + 1);
                    $after = substr($buffer, $body_end + 1);
                    $buffer = $before . "\n" . do_action('after_body_open_tag') . $after;
                }
            }
            return $buffer;
        });
        return $template;
    }

    /**
     * AJAX handler cho test PHP code
     * AJAX handler for testing PHP code
     */
    public function ajax_test_php() {
        check_ajax_referer('wp_custom_codes_test_php', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Bạn không có quyền thực hiện thao tác này.', 'wp-sysmaster'));
        }

        $code = isset($_POST['code']) ? wp_unslash($_POST['code']) : '';
        $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : 'action';
        $hook = isset($_POST['hook']) ? sanitize_text_field($_POST['hook']) : 'init';

        if (empty($code)) {
            wp_send_json_error(__('Vui lòng nhập mã PHP để kiểm tra.', 'wp-sysmaster'));
        }

        // Kiểm tra cú pháp
        $syntax_check = $this->check_php_syntax($code);
        if (is_wp_error($syntax_check)) {
            wp_send_json_error($syntax_check->get_error_message());
        }

        // Thử thực thi code
        try {
            ob_start();
            
            if ($type === 'function') {
                $test_code = 'function wp_sysmaster_test_function() {' . "\n";
                $test_code .= $code . "\n";
                $test_code .= '}' . "\n";
                $test_code .= 'wp_sysmaster_test_function();';
                eval($test_code);
            } elseif ($type === 'shortcode') {
                add_shortcode('wp_sysmaster_test', function($atts, $content = null) use ($code) {
                    ob_start();
                    eval($code);
                    return ob_get_clean();
                });
                echo do_shortcode('[wp_sysmaster_test]');
            } else {
                eval($code);
            }

            $output = ob_get_clean();
            wp_send_json_success(sprintf(
                __('Mã PHP hợp lệ và thực thi thành công.%sKết quả:%s%s', 'wp-sysmaster'),
                "\n\n",
                "\n",
                $output
            ));
        } catch (\ParseError $e) {
            wp_send_json_error(sprintf(
                __('Lỗi cú pháp PHP: %s tại dòng %d', 'wp-sysmaster'),
                $e->getMessage(),
                $e->getLine()
            ));
        } catch (\Error $e) {
            wp_send_json_error(sprintf(
                __('Lỗi PHP: %s tại dòng %d', 'wp-sysmaster'),
                $e->getMessage(),
                $e->getLine()
            ));
        }
    }
} 