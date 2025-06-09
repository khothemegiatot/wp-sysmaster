<?php
namespace WPSysMaster\Common;

if (!defined('ABSPATH')) exit;

/**
 * Class quản lý menu admin
 */
class Menu {
    /**
     * Instance của class
     * Instance of the class
     * @var Menu|null
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
        add_action('admin_menu', [$this, 'registerMenus']);
    }

    /**
     * Lấy instance của class
     * Get class instance
     * @access public
     * @static
     * @return Menu|null
     */
    public static function getInstance(): Menu|null {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Đăng ký các menu trong admin
     * Register menus in admin
     * @access public
     * @return void
     */
    public function registerMenus(): void {
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


        // Upload Settings
        add_submenu_page(
            'wp-sysmaster',
            __('Upload Settings', 'wp-sysmaster'),
            __('Upload', 'wp-sysmaster'),
            'manage_options',
            'wp-sysmaster-upload',
            [$this, 'renderUpload']
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

        // AI Settings
        add_submenu_page(
            'wp-sysmaster',
            __('AI Settings', 'wp-sysmaster'),
            __('AI Settings', 'wp-sysmaster'),
            'manage_options',
            'wp-sysmaster-ai-settings',
            [$this, 'renderAISettings']
        );

        // // Cài đặt
        // add_submenu_page(
        //     'wp-sysmaster',
        //     __('Cài đặt', 'wp-sysmaster'),
        //     __('Cài đặt', 'wp-sysmaster'),
        //     'manage_options',
        //     'wp-sysmaster-settings',
        //     [$this, 'renderSettings']
        // );
    }

    /**
     * Render trang Dashboard
     * Render dashboard page
     * @access public
     * @return void
     */
    public function renderDashboard(): void {
        if (!wp_sysmaster_is_admin()) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'wp-sysmaster'));
        }

        wp_sysmaster_get_template('common/dashboard.php', [
            'ai_status' => $this->getAIStatus(),
            'recent_activity' => $this->getRecentActivity()
        ]);
    }

    /**
     * Render trang SMTP
     * Render SMTP page
     * @access public
     * @return void
     */
    public function renderSMTP(): void {
        if (!wp_sysmaster_is_admin()) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'wp-sysmaster'));
        }

        wp_sysmaster_get_template('common/smtp.php');
    }

    /**
     * Render trang AI Settings
     */
    public function renderAISettings() {
        if (!wp_sysmaster_is_admin()) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'wp-sysmaster'));
        }

        wp_sysmaster_get_template('common/ai-settings.php');
        // $ai_settings = new \WPSysMaster\AI\Settings\AISettingsPage();
        // $ai_settings->renderSettingsPage();
    }

    /**
     * Render trang Cài đặt
     */
    public function renderSettings() {
        if (!wp_sysmaster_is_admin()) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'wp-sysmaster'));
        }

        wp_sysmaster_get_template('common/settings.php');
    }

    /**
     * Render trang Upload
     * Render upload page
     * @access public
     * @return void
     */
    public function renderUpload(): void {
        if (!wp_sysmaster_is_admin()) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'wp-sysmaster'));
        }

        wp_sysmaster_get_template('common/upload.php');
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