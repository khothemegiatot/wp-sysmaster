<?php

if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Configure SMTP
 */
function wp_custom_codes__configure_smtp( $phpmailer )
{
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

// Add actions and filters
if ( is_multisite() && get_site_option( $option_names[ 'override_for_all_sites' ][0] ) == 'on' ) {
    if ( get_site_option( $option_names[ 'enable_smtp' ][0] ) == 'on' )
        add_action( 'phpmailer_init', 'wp_custom_codes__configure_smtp' );
        
} else {
    if ( get_option( $option_names[ 'option_groups' ][2][ 'enable_smtp' ][0] ) == 'on' )
        add_action( 'phpmailer_init', 'wp_custom_codes__configure_smtp' );
}