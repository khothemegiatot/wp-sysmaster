<?php
namespace WPSysMaster\AI\Settings;

if (!defined('ABSPATH')) exit;

/**
 * Khởi tạo module AI Settings
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
        $this->initSettings();
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
        require_once WP_SYSMASTER_PLUGIN_DIR . 'core/ai/settings/AISettingsPage.php';
    }

    /**
     * Khởi tạo settings
     */
    private function initSettings() {
        AISettingsPage::getInstance();
    }
}

// Khởi tạo module
Init::getInstance(); 