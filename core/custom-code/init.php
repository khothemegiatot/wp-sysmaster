<?php

namespace WPSysMaster\CustomCode;

// Prevent direct access
if (!defined('ABSPATH')) exit;


class Init {

    /**
     * Instance của class
     * Instance of the class
     * @var Init|null
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
        $this->loadDependencies();
        $this->initSettings();
    }

    /**
     * Lấy instance của class
     * Get class instance
     * @access public
     * @static
     * @return Init|null
     */
    public static function getInstance(): Init|null {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Load các dependencies
     * Load dependencies
     * @access private
     * @return void
     */
    private function loadDependencies(): void {
        require_once WP_SYSMASTER_PLUGIN_DIR . 'core/custom-code/CustomCode.php';
    }

    private function initSettings() {
        CustomCode::getInstance();
    }
}

Init::getInstance();