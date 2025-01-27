<?php

if ( !defined( 'ABSPATH' ) ) exit;

require 'config.php';
require 'core/class/class-option-mgr.php';

/**
 * Executed when WordPress tries to delete this plugin.
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) || ! WP_UNINSTALL_PLUGIN ||
	dirname( WP_UNINSTALL_PLUGIN ) != dirname( plugin_basename( __FILE__ ) ) ) {
	exit;
}

$option_mgr = new OptionMgr( $wp_sysmaster_000__option_name );

if ( is_multisite() ) {
    $option_mgr -> remove_option( $network = true );
}

$option_mgr -> remove_option();