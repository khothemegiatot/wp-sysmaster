<?php
namespace WPSysMaster\Core\Common;

require_once WP_SYSMASTER_PLUGIN_DIR . 'core/abstracts/core-abstract.php';
use WPSysMaster\Core\Abstracts\CoreAbstract;

if (!defined('ABSPATH')) exit;

/**
 * Class quản lý chèn mã tùy chỉnh
 * Class to manage custom code insertion
 */
class CustomCode extends CoreAbstract {
    /**
     * Instance của class
     * Instance of the class
     * @var CustomCode|null
     * @access private
     * @static
     */
    private static $instance = null;

    /**
     * Constructor
     * @access private
     * @return void
     */
    protected function __construct() {
        $this->initHooks();
    }

    /**
     * Lấy instance của class
     * Get class instance
     * @access public
     * @static
     * @return CustomCode|null
     */
    public static function getInstance(): CustomCode|null {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Render trang Chèn mã
     * @access public
     * @return void
     */
    public function renderView(): void {
        if (!wp_sysmaster_is_admin()) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'wp-sysmaster'));
        }

        wp_sysmaster_get_template('common/code-insertion.php');
    }

    /**
     * Khởi tạo hooks
     * Initialize hooks
     * @access private
     * @return void
     */
    protected function initHooks(): void {
        // Hooks cho frontend
        add_action('wp_head', array($this, 'insertHeaderCode'), 999);
        
        // Thêm body scripts
        if (function_exists('wp_body_open')) {
            add_action('wp_body_open', array($this, 'insertBodyCode'), 1);
        } else {
            add_action('after_body_open_tag', array($this, 'insertBodyCode'), 1);
            add_filter('template_include', array($this, 'addBodyOpenHook'));
        }
        
        add_action('wp_footer', array($this, 'insertFooterCode'), 999);

        // Hook cho admin
        if (is_admin()) {
            add_action('admin_enqueue_scripts', [$this, 'enqueueAssets']);
            add_action('admin_init', array($this, 'registerSettings'));
            add_action('admin_menu', function(){
                // Chèn mã
                add_submenu_page(
                    'wp-sysmaster',
                    __('Insert code', 'wp-sysmaster'),
                    __('Insert code', 'wp-sysmaster'),
                    'manage_options',
                    'wp-sysmaster-code-injection',
                    [$this, 'renderView']
                );
            });
        }

        // Khởi tạo hook cho PHP code
        $this->initPhpCodeHooks();
    }

    /**
     * Khởi tạo các hook cho PHP code
     * Initialize hooks for PHP code
     * @access private
     * @return void
     */
    private function initPhpCodeHooks(): void {
        $settings = get_option('wp_sysmaster_code_settings', array());
        
        if (!empty($settings['php_code'])) {
            $hook = !empty($settings['php_code_hook']) ? $settings['php_code_hook'] : 'init';
            
            // Content hooks cần filter
            if (in_array($hook, ['the_content', 'the_title'])) {
                add_filter($hook, array($this, 'executePhpCodeFilter'), 999);
            } else {
                add_action($hook, array($this, 'executePhpCode'), 999);
            }
        }
    }

    /**
     * Đăng ký settings
     * Register settings
     * @access public
     * @return void
     */
    public function registerSettings(): void {
        register_setting('wp_sysmaster_code_settings', 'wp_sysmaster_code_settings', array(
            'sanitize_callback' => array($this, 'sanitizeSettings')
        ));
    }

    /**
     * Sanitize settings
     * @access public
     * @param array $input
     * @return array
     */
    public function sanitizeSettings($input): array {
        // Lấy các giá trị cũ
        $output = get_option('wp_sysmaster_code_settings', array());

        // Cập nhật PHP code nếu có
        if (isset($input['php_code'])) {
            $output['php_code'] = $input['php_code'];
            $output['php_code_hook'] = sanitize_text_field($input['php_code_hook']);
        }

        // Cập nhật header scripts nếu có
        if (isset($input['header_scripts'])) {
            $output['header_scripts'] = $this->sanitizeScripts($input['header_scripts']);
        }

        // Cập nhật body scripts nếu có  
        if (isset($input['body_scripts'])) {
            $output['body_scripts'] = $this->sanitizeScripts($input['body_scripts']);
        }

        // Cập nhật footer scripts nếu có
        if (isset($input['footer_scripts'])) {
            $output['footer_scripts'] = $this->sanitizeScripts($input['footer_scripts']);
        }

        // Cập nhật custom CSS nếu có
        if (isset($input['custom_css'])) {
            $output['custom_css'] = wp_strip_all_tags($input['custom_css']);
        }

        return $output;
    }

    /**
     * Sanitize scripts
     * @access private
     * @param string $content
     * @return string
     */
    private function sanitizeScripts($content): string {
        return wp_kses($content, $this->get_allowed_html());
    }

    /**
     * Enqueue scripts và styles cho code editor
     * @access public
     * @param string $hook Hook suffix của trang admin
     * @return void
     */
    public function enqueueAssets(string $hook): void {
        if ('wp-sysmaster_page_wp-sysmaster-code-injection' !== $hook) {
            return;
        }

        // CodeMirror core
        wp_enqueue_script('codemirror', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js', array('jquery'), '5.65.2', true);
        wp_enqueue_style('codemirror', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css', array(), '5.65.2');

        // CodeMirror modes
        wp_enqueue_script('codemirror-mode-xml', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/xml/xml.min.js', array('codemirror'), '5.65.2', true);
        wp_enqueue_script('codemirror-mode-javascript', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/javascript/javascript.min.js', array('codemirror'), '5.65.2', true);
        wp_enqueue_script('codemirror-mode-css', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/css/css.min.js', array('codemirror'), '5.65.2', true);
        wp_enqueue_script('codemirror-mode-htmlmixed', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/htmlmixed/htmlmixed.min.js', array('codemirror'), '5.65.2', true);
        wp_enqueue_script('codemirror-mode-php', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/php/php.min.js', array('codemirror'), '5.65.2', true);
        wp_enqueue_script('codemirror-mode-clike', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/clike/clike.min.js', array('codemirror'), '5.65.2', true);

        // CodeMirror addons
        wp_enqueue_script('codemirror-addon-edit-matchbrackets', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/matchbrackets.min.js', array('codemirror'), '5.65.2', true);
        wp_enqueue_script('codemirror-addon-edit-closebrackets', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/closebrackets.min.js', array('codemirror'), '5.65.2', true);
        wp_enqueue_script('codemirror-addon-edit-closetag', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/closetag.min.js', array('codemirror'), '5.65.2', true);
        wp_enqueue_script('codemirror-addon-fold-foldcode', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/foldcode.min.js', array('codemirror'), '5.65.2', true);
        wp_enqueue_script('codemirror-addon-fold-foldgutter', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/foldgutter.min.js', array('codemirror'), '5.65.2', true);
        wp_enqueue_script('codemirror-addon-fold-brace-fold', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/brace-fold.min.js', array('codemirror'), '5.65.2', true);
        wp_enqueue_script('codemirror-addon-fold-xml-fold', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/xml-fold.min.js', array('codemirror'), '5.65.2', true);
        wp_enqueue_script('codemirror-addon-hint-show-hint', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/hint/show-hint.min.js', array('codemirror'), '5.65.2', true);

        wp_enqueue_style('codemirror-addon-fold', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/foldgutter.min.css', array('codemirror'), '5.65.2');
        wp_enqueue_style('codemirror-addon-hint', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/hint/show-hint.min.css', array('codemirror'), '5.65.2');

        // Custom code editor assets
        wp_enqueue_style(
            'wp-sysmaster-code-editor',
            plugin_dir_url(dirname(__DIR__)) . '/assets/common/css/code-editor.css',
            array('codemirror'),
            WP_SYSMASTER_VERSION
        );

        wp_enqueue_script(
            'wp-sysmaster-code-editor',
            plugin_dir_url(dirname(__DIR__)) . '/assets/common/js/code-editor.js',
            array('codemirror'),
            WP_SYSMASTER_VERSION,
            true
        );
    }

    /**
     * Chèn mã tùy chỉnh vào header
     * Insert custom code into header
     * @access public
     * @return void
     */
    public function insertHeaderCode(): void {
        $settings = get_option('wp_sysmaster_code_settings', array());

        // Custom CSS
        if (!empty($settings['custom_css'])) {
            echo '<style type="text/css" id="wp-sysmaster-custom-css">' . "\n";
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
     * Insert custom code after body tag
     * @access public
     * @return void
     */
    public function insertBodyCode(): void {
        $settings = get_option('wp_sysmaster_code_settings', array());
        if (!empty($settings['body_scripts'])) {
            echo $settings['body_scripts'] . "\n";
        }
    }

    /**
     * Chèn mã tùy chỉnh vào footer
     * Insert custom code into footer
     * @access public
     * @return void
     */
    public function insertFooterCode(): void {
        $settings = get_option('wp_sysmaster_code_settings', array());
        if (!empty($settings['footer_scripts'])) {
            echo $settings['footer_scripts'] . "\n";
        }
    }

    /**
     * Thực thi mã PHP tùy chỉnh
     * Execute custom PHP code
     * @access public
     * @return void
     */
    public function executePhpCode(): void {
        if (!current_user_can('manage_options')) {
            return;
        }

        $settings = get_option('wp_sysmaster_code_settings', array());
        if (!empty($settings['php_code'])) {
            try {
                eval($settings['php_code']);
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
     * Thực thi mã PHP tùy chỉnh dạng filter
     * Execute custom PHP code as filter
     * @access public
     * @param string $content
     * @return string
     */
    public function executePhpCodeFilter($content): string {
        if (!current_user_can('manage_options')) {
            return $content;
        }

        $settings = get_option('wp_sysmaster_code_settings', array());
        if (!empty($settings['php_code'])) {
            try {
                ob_start();
                eval($settings['php_code']);
                $output = ob_get_clean();
                return $output ?: $content;
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

        return $content;
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
     * @access public
     * @param string $template
     * @return string
     */
    public function addBodyOpenHook($template): string {
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
} 