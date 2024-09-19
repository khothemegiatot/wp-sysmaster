<?php

/**
 * Add admin menu
 * 
 */
function wp_custom_codes__add_admin_menu() {
    add_options_page(
        __( 'web-admin-ui__page-title', 'wp-custom-codes' ),
        __( 'web-admin-ui__menu-title', 'wp-custom-codes' ),
        'manage_options',
        'wp-custom-codes',
        'wp_custom_codes__options_page'
    );
}

add_action( 'admin_menu', 'wp_custom_codes__add_admin_menu' );

/**
 * Options page
 */
function wp_custom_codes__options_page() {
    require 'admin-ui-parts/settings-page.php';
}

/**
 * 
 */
 function wp_custom_codes__settings_init() {
    register_setting( 'wp_custom_codes__options_group_1', 'wpcc__og1__o1_enable_rename_uploaded_file' );
    register_setting( 'wp_custom_codes__options_group_1', 'wpcc__og1__o2_enable_custom_upload_mimes' );
    
    register_setting( 'wp_custom_codes__options_group_2', 'wpcc__og2__o1_disable_plugin_theme_installation' );
    
    register_setting( 'wp_custom_codes__options_group_3', 'wpcc__og3__o1_enable_smtp' );
    
    register_setting( 'wp_custom_codes__options_group_4', 'wpcc__og4__o1_add_quality_rating_taxonomy' );
}

add_action( 'admin_init', 'wp_custom_codes__settings_init' );