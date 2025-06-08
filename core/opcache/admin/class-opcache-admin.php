<?php
/**
 * Class quản lý giao diện admin cho OPcache
 * Class to manage admin interface for OPcache
 */
class WP_SysMaster_OPcache_Admin {
    /**
     * Constructor
     * @access public
     * @return void
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Khởi tạo hooks
     * Initialize hooks
     * @access private
     * @return void
     */
    private function init_hooks(): void {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    /**
     * Thêm menu vào admin
     */
    public function add_admin_menu() {
        add_submenu_page(
            WP_SYSMASTER_MENU_SLUG,
            __('OPcache Manager', 'wp-sysmaster'),
            __('OPcache', 'wp-sysmaster'),
            WP_SYSMASTER_ADMIN_CAPABILITY,
            'wp-sysmaster-opcache',
            array($this, 'render_admin_page')
        );
    }

    /**
     * Enqueue scripts và styles
     * Enqueue scripts and styles
     * @access public
     * @param string $hook
     * @return void
     */
    public function enqueue_scripts($hook): void {
        if ('wp-sysmaster_page_wp-sysmaster-opcache' !== $hook) {
            return;
        }

        wp_enqueue_style(
            'wp-sysmaster-opcache-admin',
            WP_SYSMASTER_PLUGIN_URL . 'core/opcache/admin/css/opcache-admin.css',
            array(),
            WP_SYSMASTER_VERSION
        );

        wp_enqueue_script(
            'wp-sysmaster-opcache-admin',
            WP_SYSMASTER_PLUGIN_URL . 'core/opcache/admin/js/opcache-admin.js',
            array('jquery'),
            WP_SYSMASTER_VERSION,
            true
        );

        wp_localize_script('wp-sysmaster-opcache-admin', 'wpSysMasterOPcache', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wp_sysmaster_opcache_nonce'),
            'i18n' => array(
                'confirmFlush' => __('Are you sure you want to flush OPcache?', 'wp-sysmaster'),
                'flushSuccess' => __('OPcache has been flushed successfully.', 'wp-sysmaster'),
                'flushError' => __('An error occurred while flushing OPcache.', 'wp-sysmaster'),
            ),
        ));
    }

    /**
     * Render trang admin
     * Render admin page
     * @access public
     * @return void
     */
    public function render_admin_page(): void {
        $statistics = WP_SysMaster_OPcache_Statistics::get_statistics();
        
        // Lấy các tham số tìm kiếm và phân trang
        $page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
        $per_page = isset($_GET['per_page']) ? max(10, min(100, intval($_GET['per_page']))) : 20;
        $search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
        $directory = isset($_GET['directory']) ? sanitize_text_field($_GET['directory']) : '';

        // Lấy danh sách files và thư mục
        $cached_files = WP_SysMaster_OPcache_Statistics::get_cached_files_paginated($page, $per_page, $search, $directory);
        $directories = WP_SysMaster_OPcache_Statistics::get_cached_directories();
        
        include dirname(__FILE__) . '/views/opcache-dashboard.php';
    }
} 