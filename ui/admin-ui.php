<?php

if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Add admin menu
 * 
 */
function wp_sysmaster_000__add_admin_menu() {
    global $wp_sysmaster_000__text_domain;

    add_options_page(
        __( 'WP SysMaster by Chanh Xuan Phan', $wp_sysmaster_000__text_domain ),
        __( 'WP SysMaster', $wp_sysmaster_000__text_domain ),
        'manage_options',
        'wp_sysmaster_000', //slug
        'wp_sysmaster_000__options_page'
    );
}

add_action( 'admin_menu', 'wp_sysmaster_000__add_admin_menu' );

/**
 * Options page
 */
function wp_sysmaster_000__options_page() {
    global $wp_sysmaster_000__option_name;
    global $wp_sysmaster_000__text_domain;

    $option_mgr = new OptionMgr( $wp_sysmaster_000__option_name );
    
    
    $override_for_all_sites_enabled = 'off';
    $override_for_all_sites_notice = '';
    
    $rename_uploaded_file_enabled = 'off';
    $custom_upload_mimes_enabled = 'off';
    $plugin_theme_installation_disabled = 'off';
    $smtp_enabled = 'off';
    $module_opcache_mgr_enabled = 'off';
    $module_yomigana_enabled = 'off';
    $module_terminal_enabled = 'off';
    
    if ( !isset( $_POST['submit'] ) || !isset ( $_POST['tab-id'] )) {
        if ( is_multisite() ) {
            $override_for_all_sites_enabled = $option_mgr -> get_value( 'override_network', $network = true );
            $override_for_all_sites_notice = __( 'Override notice' , $wp_sysmaster_000__text_domain);
            
            $rename_uploaded_file_enabled = $option_mgr -> get_value( 'rename_uploaded_file_enabled', $network = true );
            $custom_upload_mimes_enabled = $option_mgr -> get_value( 'custom_upload_mimes_enabled', $network = true );
            $plugin_theme_installation_disabled = $option_mgr -> get_value( 'plugin_theme_installation_disabled', $network = true );
            $smtp_enabled = $option_mgr -> get_value( 'smtp_enabled', $network = true );
            $module_opcache_mgr_enabled = $option_mgr -> get_value( 'module_opcache_mgr', $network = true );
            $module_yomigana_enabled = $option_mgr -> get_value( 'module_yomigana', $network = true );
            $module_terminal_enabled = $option_mgr -> get_value( 'module_terminal', $network = true );
        } else {
            $rename_uploaded_file_enabled = $option_mgr -> get_value( 'rename_uploaded_file_enabled' );
            $custom_upload_mimes_enabled = $option_mgr -> get_value( 'custom_upload_mimes_enabled' );
            $plugin_theme_installation_disabled = $option_mgr -> get_value( 'plugin_theme_installation_disabled' );
            $smtp_enabled = $option_mgr -> get_value( 'smtp_enabled' );
            $module_opcache_mgr_enabled = $option_mgr -> get_value( 'module_opcache_mgr' );
            $module_yomigana_enabled = $option_mgr -> get_value( 'module_yomigana' );
            $module_terminal_enabled = $option_mgr -> get_value( 'module_terminal' );
        }
        
        $add_quality_rating_taxonomy_enabled = $option_mgr -> get_value( 'add_quality_rating_taxonomy_enabled' );

        require 'parts/settings-page.php';
        require 'parts/css-js.php';

        return;
    }

    if ( $_POST['tab-id'] == 'tab-0' ) {
        check_admin_referer( 'tab0' );
        $override_for_all_sites_enabled = isset( $_POST[ 'override_for_all_sites' ] ) ? 'on' : 'off';
        $option_mgr -> update_value( array( "override_network" => $override_for_all_sites_enabled ) );
    }
    
    if ( $_POST['tab-id'] == 'tab-1' ) {
        check_admin_referer( 'tab1' );
        
        $rename_uploaded_file_enabled = isset( $_POST[ 'rename_uploaded_file_enabled' ] ) ? 'on' : 'off';
        $custom_upload_mimes_enabled = isset( $_POST[ 'custom_upload_mimes_enabled' ] ) ? 'on' : 'off';

        $option_mgr -> update_value( array( 'rename_uploaded_file_enabled' => $rename_uploaded_file_enabled ) );
        $option_mgr -> update_value( array( 'custom_upload_mimes_enabled' => $custom_upload_mimes_enabled ) );
    }
    
    if ( $_POST['tab-id'] == 'tab-2' ) {
        check_admin_referer( 'tab2' );
        
        $plugin_theme_installation_disabled = isset( $_POST[ 'plugin_theme_installation_disabled' ] ) ? 'on' : 'off';
        
        $option_mgr -> update_value( array( 'plugin_theme_installation_disabled' => $plugin_theme_installation_disabled ) );
    }
    
    if ( $_POST['tab-id'] == 'tab-3' ) {
        check_admin_referer( 'tab3' );
        
        $smtp_enabled = isset( $_POST[ 'enable_smtp' ] ) ? 'on' : 'off';
        
        $option_mgr -> update_value( array( 'smtp_enabled' => $smtp_enabled ) );
        
    }
    
    if ( $_POST['tab-id'] == 'tab-4' ) {
        check_admin_referer( 'tab4' );
    }
    
    $option_mgr -> save_option();
    settings_errors();
    echo '<div class="updated"><p>' . esc_html__( 'Settings saved.' ) . '</p></div>';
    
    require 'parts/settings-page.php';
    require 'parts/css-js.php';
}

/**
 * Add network admin menu
 */
function wp_sysmaster_000__add_network_admin_menu() {
    global $wp_sysmaster_000__text_domain;

    add_submenu_page(
        'settings.php',
        __( 'WP SysMaster (Network Admin) by Chanh Xuan Phan', $wp_sysmaster_000__text_domain ),
        __( 'WP SysMaster (Network Admin)', $wp_sysmaster_000__text_domain ),
        'manage_network_options', 
        'wp_sysmaster_000__network-settings',
        'wp_sysmaster_000__network_options_page'
    );
}

/**
 * Add newwork options page
 */
function wp_sysmaster_000__network_options_page() {
    global $wp_sysmaster_000__option_name;
    global $wp_sysmaster_000__text_domain;

    $option_mgr = new OptionMgr( $wp_sysmaster_000__option_name );
    
    $override_for_all_sites = 'off';
    $rename_uploaded_file_enabled = null;
    $custom_upload_mimes_enabled = null;
    $plugin_theme_installation_disabled = null;
    $smtp_enabled = null;
    
    if( !isset( $_POST['submit'] ) || !isset ( $_POST['tab-id'] ) ) {
        $override_for_all_sites_enabled = $option_mgr -> get_value( 'override_network', $network = true );
        $rename_uploaded_file_enabled = $option_mgr -> get_value( 'rename_uploaded_file_enabled', $network = true );
        $custom_upload_mimes_enabled = $option_mgr -> get_value( 'custom_upload_mimes_enabled', $network = true );
        $plugin_theme_installation_disabled = $option_mgr -> get_value( 'plugin_theme_installation_disabled', $network = true );
        $smtp_enabled = $option_mgr -> get_value( 'smtp_enabled', $network = true );
        
        require 'parts/network-settings-page.php';
        require 'parts/css-js.php';
        
        return;
    }
    
    if ( $_POST['tab-id'] == 'tab-0' ) {
        check_admin_referer( 'tab0' );
        $override_for_all_sites_enabled = isset( $_POST[ 'override_for_all_sites' ] ) ? 'on' : 'off';
        $option_mgr -> update_value( array( 'override_network' => $override_for_all_sites_enabled ) );
    }
    
    if ( $_POST['tab-id'] == 'wp-custom-codes__tab-1' ) {
        check_admin_referer( 'wp_custom_codes__network_settings__tab1' );
        
        $rename_uploaded_file_enabled = isset( $_POST[ 'rename_uploaded_file_enabled' ] ) ? 'on' : 'off';
        $custom_upload_mimes_enabled = isset( $_POST[ 'custom_upload_mimes_enabled' ] ) ? 'on' : 'off';

        $option_mgr -> update_value( array( 'rename_uploaded_file_enabled' => $rename_uploaded_file_enabled ) );
        $option_mgr -> update_value( array( 'custom_upload_mimes_enabled' => $custom_upload_mimes_enabled ) );
    }
    
    if ( $_POST['tab-id'] == 'wp-custom-codes__tab-2' ) {
        check_admin_referer( 'wp_custom_codes__network_settings__tab2' );
        $plugin_theme_installation_disabled = isset( $_POST[ 'plugin_theme_installation_disabled' ] ) ? 'on' : 'off';
        $option_mgr -> update_value( array( 'plugin_theme_installation_disabled' => $plugin_theme_installation_disabled ) );
        
    }
    
    if ( $_POST['tab-id'] == 'wp-custom-codes__tab-3' ) {
        check_admin_referer( 'wp_custom_codes__network_settings__tab3' );
        $smtp_enabled = isset( $_POST[ 'enable_smtp' ] ) ? 'on' : 'off';
        $option_mgr -> update_value( array( 'smtp_enabled' => $smtp_enabled ) );
    }
    
    if ( $_POST['tab-id'] == 'wp-custom-codes__tab-4' ) {
        check_admin_referer( 'wp_custom_codes__network_settings__tab4' );
    }
    
    $option_mgr -> save_option( $network = true );
    settings_errors();
    echo '<div class="updated"><p>' . esc_html__( 'Settings saved.' ) . '</p></div>';
    
    require 'parts/network-settings-page.php';
    require 'parts/css-js.php';
}


if ( is_multisite() )
    add_action('network_admin_menu', 'wp_sysmaster_000__add_network_admin_menu');