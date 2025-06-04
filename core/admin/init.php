<?php
namespace WPSysMaster\Admin;

if (!defined('ABSPATH')) exit;

/**
 * Khởi tạo admin
 */
class Init {
    /**
     * Instance của class
     * @var Init
     */
    private static $instance = null;

    /**
     * Constructor
     */
    private function __construct() {
        $this->loadDependencies();
        $this->initializeHooks();
    }

    /**
     * Lấy instance của class
     */
    public static function getInstance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Load các dependencies
     */
    private function loadDependencies() {
        // Load Menu class
        require_once WP_SYSMASTER_PLUGIN_DIR . 'core/admin/Menu.php';
        Menu::getInstance();
    }

    /**
     * Khởi tạo hooks
     */
    private function initializeHooks() {
        // Enqueue admin scripts và styles
        add_action('admin_enqueue_scripts', [$this, 'enqueueAssets']);

        // Register settings
        add_action('admin_init', [$this, 'registerSettings']);
    }

    /**
     * Đăng ký settings
     */
    public function registerSettings() {
        register_setting(
            'wp_sysmaster_ai_settings',
            WP_SYSMASTER_AI_OPTIONS_KEY,
            [
                'type' => 'array',
                'sanitize_callback' => [$this, 'sanitizeAISettings']
            ]
        );
    }

    /**
     * Sanitize AI settings
     */
    public function sanitizeAISettings($input) {
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
     * Enqueue admin scripts và styles
     */
    public function enqueueAssets($hook) {
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
}

// Khởi tạo admin
Init::getInstance(); 