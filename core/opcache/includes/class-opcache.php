<?php
/**
 * Class chính cho module OPcache
 */
class WP_SysMaster_OPcache {
    /**
     * Instance của class
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
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Khởi tạo hooks
     */
    private function init_hooks() {
        add_action('admin_post_wp_sysmaster_flush_opcache', array($this, 'flush_opcache'));
        add_action('wp_ajax_wp_sysmaster_flush_opcache', array($this, 'ajax_flush_opcache'));
    }

    /**
     * Flush OPcache
     */
    public function flush_opcache() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have permission to perform this action.', 'wp-sysmaster'));
        }

        if (function_exists('opcache_reset')) {
            opcache_reset();
            add_settings_error(
                'wp_sysmaster_opcache',
                'wp_sysmaster_opcache_flushed',
                __('OPcache has been flushed successfully.', 'wp-sysmaster'),
                'success'
            );
        } else {
            add_settings_error(
                'wp_sysmaster_opcache',
                'wp_sysmaster_opcache_not_enabled',
                __('OPcache is not enabled on your server.', 'wp-sysmaster'),
                'error'
            );
        }

        wp_redirect(admin_url('admin.php?page=wp-sysmaster-opcache'));
        exit;
    }

    /**
     * Ajax flush OPcache
     */
    public function ajax_flush_opcache() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array(
                'message' => __('You do not have permission to perform this action.', 'wp-sysmaster')
            ));
        }

        if (function_exists('opcache_reset')) {
            opcache_reset();
            wp_send_json_success(array(
                'message' => __('OPcache has been flushed successfully.', 'wp-sysmaster')
            ));
        } else {
            wp_send_json_error(array(
                'message' => __('OPcache is not enabled on your server.', 'wp-sysmaster')
            ));
        }
    }
} 