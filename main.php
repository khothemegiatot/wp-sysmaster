<?php

/**
 * Plugin Name: WP Custom Codes
 * Description: A plugin containing code snippets used for system customization.
 * Version: 2.2.1
 * Author: Chanh Phan Xuan
 * Author URI: https://www.phanxuanchanh.com/
 * Network: true
 * Requires at least: 5.0
 * Tested up to: 6.5.5
 * Requires PHP: 8.2
 */

if ( !defined( 'ABSPATH' ) ) exit;

require 'config.php';

/**
 * Load plugin
 */
function wp_custom_codes__load_plugin() {
    load_plugin_textdomain( 'wp-custom-codes', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
    
    require 'plugin-variables.php';
    
    // Load ui
    if( is_multisite() )
        require 'ui/network-admin-ui.php';

    require 'ui/web-admin-ui.php';
    
    // Load core
    require 'core/enqueue.php';
    require 'core/upload.php';
    require 'core/smtp.php';
    require 'core/plugins-and-themes.php';
    require 'core/quality-rating-taxonomy.php';
    
    // Load modules
    require 'opcache/flush-opcache.php';
    require 'terminal/terminal.php';
    require 'yomigana/yomigana.php';
    
    // Load add-ons
    require 'add-ons/buy-me-coffee.php';
}

add_action( 'plugins_loaded', 'wp_custom_codes__load_plugin' );