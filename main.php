<?php
/**
 * Plugin Name: WP SysMaster
 * Plugin URI: https://www.phanxuanchanh.com/wp-sysmaster
 * Description: Plugin WordPress mạnh mẽ tích hợp AI và các tính năng nâng cao.
 * Version: 1.0.0
 * Requires at least: 5.0
 * Requires PHP: 7.4
 * Author: Phan Xuân Chánh, khothemegiatot.com
 * Author URI: https://www.phanxuanchanh.com, https://khothemegiatot.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-sysmaster
 * Domain Path: /languages
 */

// Ngăn truy cập trực tiếp
// Prevent direct access
if (!defined('ABSPATH')) exit;

// Composer autoload
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

// Constants
require_once dirname(__FILE__) . '/core/includes/constants.php';

// Helper functions
require_once dirname(__FILE__) . '/core/includes/helpers.php';

/**
 * Class chính của plugin
 */
class WP_SysMaster {
    /**
     * Instance của plugin
     * Instance of the class
     * @var WP_SysMaster|null
     * @access private
     * @static
     */
    private static $instance = null;

    /**
     * Constructor
     * @access private
     * @return void
     */
    private function __construct() {
        $this->initHooks();
        $this->loadDependencies();
    }

    /**
     * Lấy instance của plugin
     * Get class instance
     * @access public
     * @static
     * @return WP_SysMaster|null
     */
    public static function getInstance(): WP_SysMaster|null {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Khởi tạo hooks
     * @access private
     * @return void
     */
    private function initHooks(): void {
        // Plugin activation/deactivation
        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);

        // Init plugin
        add_action('plugins_loaded', [$this, 'init']);

        // Load text domain
        add_action('init', [$this, 'loadTextDomain']);
    }

    /**
     * Load các dependencies
     * @access private
     * @return void
     */
    private function loadDependencies(): void {
        // Load core
        require_once WP_SYSMASTER_PLUGIN_DIR . 'core/init.php';

        // Load OPcache module
        require_once WP_SYSMASTER_PLUGIN_DIR . 'core/opcache/init.php';

        // Load AI module
        require_once WP_SYSMASTER_PLUGIN_DIR . 'core/ai/settings/init.php';
    }

    /**
     * Khởi tạo plugin
     * @access public
     * @return void
     */
    public function init(): void {
        // Kiểm tra version và update nếu cần
        $this->checkVersion();

        // Khởi tạo các module
        do_action('wp_sysmaster_init');
    }

    /**
     * Kích hoạt plugin
     * @access public
     * @return void
     */
    public function activate(): void {
        // Tạo các bảng và dữ liệu mặc định
        $this->createTables();
        $this->addDefaultOptions();

        // Flush rewrite rules
        flush_rewrite_rules();

        do_action('wp_sysmaster_activated');
    }

    /**
     * Hủy kích hoạt plugin
     * Deactivate plugin
     * @access public
     * @return void
     */
    public function deactivate(): void {
        // Flush rewrite rules
        flush_rewrite_rules();

        do_action('wp_sysmaster_deactivated');
    }

    /**
     * Load text domain
     * @access public
     * @return void
     */
    public function loadTextDomain(): void {
        load_plugin_textdomain(
            'wp-sysmaster',
            false,
            dirname(plugin_basename(__FILE__)) . '/languages/'
        );
    }

    /**
     * Kiểm tra version
     * Check version
     * @access private
     * @return void
     */
    private function checkVersion(): void {
        $version = get_option('wp_sysmaster_version');
        
        if ($version !== WP_SYSMASTER_VERSION) {
            // Update version
            update_option('wp_sysmaster_version', WP_SYSMASTER_VERSION);
            
            // Thực hiện update nếu cần
            do_action('wp_sysmaster_updated', $version, WP_SYSMASTER_VERSION);
        }
    }

    /**
     * Tạo các bảng trong database
     * Create tables in database
     * @access private
     * @return void
     */
    private function createTables(): void {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        // Bảng lưu trữ embeddings
        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wp_sysmaster_embeddings (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            post_id bigint(20) NOT NULL,
            embedding longtext NOT NULL,
            metadata longtext,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY post_id (post_id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Thêm các tùy chọn mặc định
     * Add default options
     * @access private
     * @return void
     */
    private function addDefaultOptions(): void {
        // General settings
        add_option('wp_sysmaster_version', WP_SYSMASTER_VERSION);
        
        // AI settings
        $default_ai_settings = [
            'openai_model' => 'gpt-3.5-turbo',
            'embedding_provider' => 'openai',
            'embedding_post_types' => ['post']
        ];
        add_option(WP_SYSMASTER_AI_OPTIONS_KEY, $default_ai_settings);
    }
}

// Khởi tạo plugin
// Initialize plugin
WP_SysMaster::getInstance();