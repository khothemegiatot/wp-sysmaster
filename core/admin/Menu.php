<?php
namespace WPSysMaster\Admin;

if (!defined('ABSPATH')) exit;

/**
 * Class quản lý menu admin
 */
class Menu {
    /**
     * Instance của class
     * @var Menu
     */
    private static $instance = null;

    /**
     * Constructor
     */
    private function __construct() {
        add_action('admin_menu', [$this, 'registerMenus']);
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
     * Đăng ký các menu trong admin
     */
    public function registerMenus() {
        // Menu chính
        add_menu_page(
            __('WP SysMaster', 'wp-sysmaster'),
            __('WP SysMaster', 'wp-sysmaster'),
            'manage_options',
            'wp-sysmaster',
            [$this, 'renderDashboard'],
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
            [$this, 'renderDashboard']
        );

        // Chèn mã
        add_submenu_page(
            'wp-sysmaster',
            __('Chèn mã', 'wp-sysmaster'),
            __('Chèn mã', 'wp-sysmaster'),
            'manage_options',
            'wp-sysmaster-code-injection',
            [$this, 'renderCodeInjection']
        );

        // SMTP
        add_submenu_page(
            'wp-sysmaster',
            __('SMTP', 'wp-sysmaster'),
            __('SMTP', 'wp-sysmaster'),
            'manage_options',
            'wp-sysmaster-smtp',
            [$this, 'renderSMTP']
        );

        // Tối ưu
        add_submenu_page(
            'wp-sysmaster',
            __('Tối ưu', 'wp-sysmaster'),
            __('Tối ưu', 'wp-sysmaster'),
            'manage_options',
            'wp-sysmaster-optimization',
            [$this, 'renderOptimization']
        );

        // Bảo mật
        add_submenu_page(
            'wp-sysmaster',
            __('Bảo mật', 'wp-sysmaster'),
            __('Bảo mật', 'wp-sysmaster'),
            'manage_options',
            'wp-sysmaster-security',
            [$this, 'renderSecurity']
        );

        // AI Settings
        add_submenu_page(
            'wp-sysmaster',
            __('AI Settings', 'wp-sysmaster'),
            __('AI Settings', 'wp-sysmaster'),
            'manage_options',
            'wp-sysmaster-ai-settings',
            [$this, 'renderAISettings']
        );

        // Cài đặt
        add_submenu_page(
            'wp-sysmaster',
            __('Cài đặt', 'wp-sysmaster'),
            __('Cài đặt', 'wp-sysmaster'),
            'manage_options',
            'wp-sysmaster-settings',
            [$this, 'renderSettings']
        );
    }

    /**
     * Render trang Dashboard
     */
    public function renderDashboard() {
        if (!wp_sysmaster_is_admin()) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'wp-sysmaster'));
        }

        wp_sysmaster_get_template('admin/dashboard.php', [
            'ai_status' => $this->getAIStatus(),
            'recent_activity' => $this->getRecentActivity()
        ]);
    }

    /**
     * Render trang Chèn mã
     */
    public function renderCodeInjection() {
        if (!wp_sysmaster_is_admin()) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'wp-sysmaster'));
        }

        wp_sysmaster_get_template('admin/code-injection.php');
    }

    /**
     * Render trang SMTP
     */
    public function renderSMTP() {
        if (!wp_sysmaster_is_admin()) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'wp-sysmaster'));
        }

        wp_sysmaster_get_template('admin/smtp.php');
    }

    /**
     * Render trang Tối ưu
     */
    public function renderOptimization() {
        if (!wp_sysmaster_is_admin()) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'wp-sysmaster'));
        }

        wp_sysmaster_get_template('admin/optimization.php');
    }

    /**
     * Render trang Bảo mật
     */
    public function renderSecurity() {
        if (!wp_sysmaster_is_admin()) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'wp-sysmaster'));
        }

        wp_sysmaster_get_template('admin/security.php');
    }

    /**
     * Render trang AI Settings
     */
    public function renderAISettings() {
        if (!wp_sysmaster_is_admin()) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'wp-sysmaster'));
        }

        wp_sysmaster_get_template('admin/ai-settings.php');
    }

    /**
     * Render trang Cài đặt
     */
    public function renderSettings() {
        if (!wp_sysmaster_is_admin()) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'wp-sysmaster'));
        }

        wp_sysmaster_get_template('admin/settings.php');
    }

    /**
     * Lấy trạng thái AI
     * 
     * @return array
     */
    protected function getAIStatus() {
        $providers = [
            'openai' => [
                'name' => 'OpenAI',
                'key' => 'openai_api_key'
            ],
            'gemini' => [
                'name' => 'Google Gemini',
                'key' => 'gemini_api_key'
            ],
            'locallm' => [
                'name' => 'Local LM',
                'key' => 'locallm_endpoint'
            ]
        ];

        $status = [];
        foreach ($providers as $provider => $info) {
            $status[$provider] = [
                'name' => $info['name'],
                'configured' => !empty(wp_sysmaster_get_ai_settings($info['key']))
            ];
        }

        return $status;
    }

    /**
     * Lấy hoạt động gần đây
     * 
     * @return array
     */
    protected function getRecentActivity() {
        // TODO: Implement activity logging
        return [];
    }
} 