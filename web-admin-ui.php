<?php

if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Add admin menu
 * 
 */
function wp_custom_codes__add_admin_menu() {
    add_options_page(
        __( 'web-admin-ui__page-title', 'wp-custom-codes' ),
        __( 'web-admin-ui__menu-title', 'wp-custom-codes' ),
        'manage_options',
        'wp-custom-codes', //slug
        'wp_custom_codes__options_page'
    );
}

add_action( 'admin_menu', 'wp_custom_codes__add_admin_menu' );

/**
 * Options page
 */
function wp_custom_codes__options_page() {
    global $wpcc__text_domain;
    global $option_names;
    
    $override_for_all_sites_enabled = 'off';
    $override_for_all_sites_notice = '';
    
    $enable_rename_uploaded_enabled = 'off';
    $enable_custom_upload_mimes_enabled = 'off';
    $disable_plugin_theme_installation_enabled = 'off';
    $smtp_enabled = 'off';
    
    if ( is_multisite() ) {
        $override_for_all_sites_enabled = get_site_option( $option_names[ 'override_for_all_sites' ][0] );
        $override_for_all_sites_notice = __( 'web-admin-ui__override-notice' , $wpcc__text_domain);
        
        $enable_rename_uploaded_enabled = get_site_option( $option_names[ 'enable_rename_uploaded_file' ][0] );
        $enable_custom_upload_mimes_enabled = get_site_option( $option_names[ 'enable_custom_upload_mimes' ][0] );
        $disable_plugin_theme_installation_enabled = get_site_option( $option_names[ 'disable_plugin_theme_installation' ][0] );
        $smtp_enabled = get_site_option( $option_names[ 'enable_smtp' ][0] );
    } else {
        $enable_rename_uploaded_enabled = get_option( $option_names[ 'option_groups_1' ][2][ 'enable_rename_uploaded_file' ][0] );
        $enable_custom_upload_mimes_enabled = get_option( $option_names[ 'option_groups_1' ][2][ 'enable_custom_upload_mimes' ][0] );
        $disable_plugin_theme_installation_enabled = get_option( $option_names[ 'option_groups_2' ][2][ 'disable_plugin_theme_installation' ][0] );
        $smtp_enabled = get_option( $option_names[ 'option_groups_3' ][2][ 'enable_smtp' ][0] );
    }
    
    $add_quality_rating_taxonomy_enabled = get_option( $option_names[ 'option_groups_4' ][2][ 'add_quality_rating_taxonomy' ][0] );
    
    require 'admin-ui-parts/settings-page.php';
    require 'admin-ui-parts/css-js.php';
}

/**
 * Settings init
 */
 function wp_custom_codes__settings_init() {
    global $wpcc__text_domain;
    global $option_names;
     
    register_setting( $option_names[ 'option_groups_1' ][0], $option_names[ 'option_groups_1' ][2][ 'enable_rename_uploaded_file' ][0] );
    register_setting( $option_names[ 'option_groups_1' ][0], $option_names[ 'option_groups_1' ][2][ 'enable_custom_upload_mimes' ][0] );
    register_setting( $option_names[ 'option_groups_2' ][0], $option_names[ 'option_groups_2' ][2][ 'disable_plugin_theme_installation' ][0] );
    register_setting( $option_names[ 'option_groups_3' ][0], $option_names[ 'option_groups_3' ][2][ 'enable_smtp' ][0] );
    register_setting( $option_names[ 'option_groups_4' ][0], $option_names[ 'option_groups_4' ][2][ 'add_quality_rating_taxonomy' ][0] );
}

add_action( 'admin_init', 'wp_custom_codes__settings_init' );
