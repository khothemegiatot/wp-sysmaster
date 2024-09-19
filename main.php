<?php

/**
 * Plugin Name: WP Custom Codes
 * Description: A plugin containing code snippets used for system customization.
 * Version: 2.0
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
 * Load textdomain
 */
function wp_custom_codes__load_textdomain() {
    load_plugin_textdomain( 'wp-custom-codes', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'wp_custom_codes__load_textdomain' );


/**
 * Enqueue styles
 */
function wp_custom_codes__enqueue_styles() {
    wp_enqueue_style( 'wp_custom_codes__enqueue_styles', plugin_dir_url( __FILE__ ) . 'assets/css/style.css' );
}

add_action( 'wp_enqueue_scripts', 'wp_custom_codes__enqueue_styles' );


/**
 * Admin enqueue styles
 */
function wp_custom_codes__admin_enqueue_styles() {
    global $pagenow;
    if ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) {
        wp_enqueue_style( 'my-editor-style', plugin_dir_url( __FILE__ ) . 'assets/css/admin-style.css' );
    }
}

add_action( 'admin_enqueue_scripts', 'wp_custom_codes__admin_enqueue_styles' );

/**
 * 
 */
require 'web-admin-ui.php';
require 'core/upload.php';
require 'core/smtp.php';
require 'core/plugins-and-themes.php';
require 'core/quality-rating-taxonomy.php';

require 'add-ons/buy-me-coffee.php';