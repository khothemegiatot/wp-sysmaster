<?php
/**
 * Plugin Name: WP Custom Codes
 * Description: A plugin containing code snippets used for system customization.
 * Version: 1.1.0
 * Author: Phan Xuan Chanh
 * Author URI: https://www.phanxuanchanh.com
 * Network: true
 * Requires PHP: 7.4
 */

if ( !defined( 'ABSPATH' ) )
	exit;

function custom_rename_uploaded_file( $file ) {
    $upload_dir = wp_upload_dir();
    $file_info = pathinfo( $file['name'] );
    
    // {random string}.{file extension}
    $random_string = bin2hex( random_bytes( 16 ) );
	$new_file_name = strtolower( $random_string ) . '.' . $file_info['extension'];
	
    $file['name'] = $new_file_name;
    $file['path'] = $upload_dir['path'] . '/' . $new_file_name;
    $file['url'] = $upload_dir['url'] . '/' . $new_file_name;
    
    return $file;
}

add_filter( 'wp_handle_upload_prefilter', 'custom_rename_uploaded_file' );


function configure_smtp_email( $phpmailer ) {
	$phpmailer->isSMTP();     
	$phpmailer->Host = SMTP_HOST;
	$phpmailer->SMTPAuth = SMTP_AUTH;
	$phpmailer->Port = SMTP_PORT;
	$phpmailer->Username = SMTP_USER;
	$phpmailer->Password = SMTP_PASS;
	$phpmailer->SMTPSecure = SMTP_SECURE;
	$phpmailer->From = SMTP_FROM;
	$phpmailer->FromName = SMTP_NAME;
}

add_action( 'phpmailer_init', 'configure_smtp_email' );

/**
 * Disable new plugin and theme installation
 *
 * @param array $options
 * @return array
 */
function disable_new_plugin_and_theme_installation( $options ) {
    if (
        isset( $options['hook_extra']['action'] ) &&
        $options['hook_extra']['action'] === 'install' &&
        isset( $options['hook_extra']['type'] ) &&
        in_array( $options['hook_extra']['type'], [ 'plugin', 'theme' ] )
    ) {
        die( 'Plugins & Themes installation disabled!' );
    }

    return $options;
}

add_filter( 'upgrader_package_options', 'disable_new_plugin_and_theme_installation' );