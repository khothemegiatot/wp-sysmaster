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

if ( get_option( 'wpcc__og3__o1_enable_smtp' ) == 'on' ) {
    add_action( 'phpmailer_init', 'wp_custom_codes__configure_smtp' );
}