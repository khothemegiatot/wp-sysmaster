<?php

/**
 * Plugin Name: WP SysMaster
 * Description: A plugin containing code snippets used for system customization.
 * Version: 2.3
 * Author: Chanh Phan Xuan
 * Author URI: https://www.phanxuanchanh.com/
 * Network: true
 * Requires at least: 5.0
 * Tested up to: 6.5.5
 * Requires PHP: 8.2
 */

if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Load plugin
 */
function wp_sysmaster_000__load_plugin() {
    load_plugin_textdomain( 'wp_sysmaster_000', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
    
    $pluin_dir_path = plugin_dir_path( __FILE__ );

    require $pluin_dir_path . 'config.php';
    
    // Load core (classes and functions)
    require $pluin_dir_path . 'core/class/class-option-mgr.php';
    require $pluin_dir_path . 'core/functions/func-1.php';

    // Load UI
    require $pluin_dir_path . 'ui/admin-ui.php';

    // Load modules
    require $pluin_dir_path . 'opcache/flush-opcache.php';
    //require $pluin_dir_path . 'terminal/terminal.php';
    //require $pluin_dir_path . 'yomigana/yomigana.php';
    
    // Load add-ons
    require $pluin_dir_path . 'add-ons/buy-me-coffee.php';
    
    // Actions and filters
    require $pluin_dir_path . 'core/actions-filters.php';
}

add_action( 'plugins_loaded', 'wp_sysmaster_000__load_plugin' );
