<?php

if ( !defined( 'ABSPATH' ) ) exit;

require 'plugin-variables.php';

/**
 * Executed when WordPress tries to delete this plugin.
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) || ! WP_UNINSTALL_PLUGIN ||
	dirname( WP_UNINSTALL_PLUGIN ) != dirname( plugin_basename( __FILE__ ) ) ) {
	exit;
}

if ( is_multisite() ) {
    delete_site_option( $option_names[ 'enable_rename_uploaded_file' ][0] );
    delete_site_option( $option_names[ 'enable_custom_upload_mimes' ][0] );
    delete_site_option( $option_names[ 'disable_plugin_theme_installation' ][0] );
    delete_site_option( $option_names[ 'enable_smtp' ][0] );
}

$option_group = $option_names[ 'option_group_1' ][2];

foreach ( $item as $option_group ) {
    delete_option( $item[0] );
}

$option_group = $option_names[ 'option_group_2' ][2];

foreach ( $item as $option_group ) {
    delete_option( $item[0] );
}

$option_group = $option_names[ 'option_group_3' ][2];

foreach ( $item as $option_group ) {
    delete_option( $item[0] );
}

$option_group = $option_names[ 'option_group_4' ][2];

foreach ( $item as $option_group ) {
    delete_option( $item[0] );
}