<?php

if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Add network admin menu
 */
function wp_custom_codes__add_network_admin_menu() {
    add_submenu_page(
        'settings.php',
        __( 'network-admin-ui__page-title', 'wp-custom-codes' ),
        __( 'network-admin-ui__menu-title', 'wp-custom-codes' ),
        'manage_network_options', 
        'wp-custom-codes__network-settings',
        'wp_custom_codes__network_options_page'
    );
}

add_action('network_admin_menu', 'wp_custom_codes__add_network_admin_menu');

/**
 * Add newwork options page
 */
function wp_custom_codes__network_options_page() {
    global $wpcc__text_domain;
    global $option_names;
    
    $override_for_all_sites = null;
    $rename_uploaded_file_enabled = null;
    $custom_upload_mimes_enabled = null;
    $plugin_theme_installation_disabled = null;
    $smtp_enabled = null;
    
    if( !isset( $_POST['submit'] ) || !isset ( $_POST['tab-id'] ) ) {
        $override_for_all_sites_enabled = get_site_option( $option_names[ 'override_for_all_sites' ][0] );
        $rename_uploaded_file_enabled = get_site_option( $option_names[ 'enable_rename_uploaded_file' ][0] );
        $custom_upload_mimes_enabled = get_site_option( $option_names[ 'enable_custom_upload_mimes' ][0] );
        $plugin_theme_installation_disabled = get_site_option( $option_names[ 'disable_plugin_theme_installation' ][0] );
        $smtp_enabled = get_site_option( $option_names[ 'enable_smtp' ][0] );
        
        require 'parts/network-settings-page.php';
        require 'parts/css-js.php';
        
        return;
    }
    
    if ( $_POST['tab-id'] == 'wp-custom-codes__tab-0' ) {
        check_admin_referer( 'wp_custom_codes__network_settings__tab0' );
        $override_for_all_sites_enabled = isset( $_POST[ $option_names[ 'override_for_all_sites' ][0] ] ) ? 'on' : 'off';
        update_site_option( $option_names[ 'override_for_all_sites' ][0], $override_for_all_sites_enabled );
    }
    
    if ( $_POST['tab-id'] == 'wp-custom-codes__tab-1' ) {
        check_admin_referer( 'wp_custom_codes__network_settings__tab1' );
        
        $rename_uploaded_file_enabled = isset( $_POST[ $option_names[ 'enable_rename_uploaded_file' ][0] ] ) ? 'on' : 'off';
        $custom_upload_mimes_enabled = isset( $_POST[ $option_names[ 'enable_custom_upload_mimes' ][0] ] ) ? 'on' : 'off';
        
        update_site_option( $option_names[ 'enable_rename_uploaded_file' ][0], $rename_uploaded_file_enabled );
        update_site_option( $option_names[ 'enable_custom_upload_mimes' ][0], $custom_upload_mimes_enabled );
    }
    
    if ( $_POST['tab-id'] == 'wp-custom-codes__tab-2' ) {
        check_admin_referer( 'wp_custom_codes__network_settings__tab2' );
        
        $plugin_theme_installation_disabled = isset( $_POST[ $option_names[ 'disable_plugin_theme_installation' ][0] ] ) ? 'on' : 'off';
        
        update_site_option( $option_names[ 'disable_plugin_theme_installation' ][0], $plugin_theme_installation_disabled );
        
    }
    
    if ( $_POST['tab-id'] == 'wp-custom-codes__tab-3' ) {
        check_admin_referer( 'wp_custom_codes__network_settings__tab3' );
        
        $smtp_enabled = isset( $_POST[ $option_names[ 'enable_smtp' ][0] ] ) ? 'on' : 'off';
        
        update_site_option( $option_names[ 'enable_smtp' ][0], $smtp_enabled );
        
    }
    
    if ( $_POST['tab-id'] == 'wp-custom-codes__tab-4' ) {
        check_admin_referer( 'wp_custom_codes__network_settings__tab4' );
    }
    
    settings_errors();
    echo '<div class="updated"><p>' . esc_html__( 'Settings saved.' ) . '</p></div>';
    
    require 'parts/network-settings-page.php';
    require 'parts/css-js.php';
}