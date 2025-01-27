<?php

/**
 * Enqueue styles
 */
function wp_sysmaster_000__enqueue_styles() {
    wp_enqueue_style( 'wp_sysmaster_000__enqueue_styles', plugin_dir_url( __FILE__ ) . 'assets/css/style.css' );
}

add_action( 'wp_enqueue_scripts', 'wp_sysmaster_000__enqueue_styles' );


/**
 * Admin enqueue styles
 */
function wp_sysmaster_000__admin_enqueue_styles() {
    global $pagenow;
    if ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) {
        wp_enqueue_style( 'my-editor-style', plugin_dir_url( __FILE__ ) . 'assets/css/admin-style.css' );
    }
}

add_action( 'admin_enqueue_scripts', 'wp_sysmaster_000__admin_enqueue_styles' );



function wp_sysmaster_000__add_for_network_override( $option_mgr ) {
    if ( $option_mgr -> get_value( 'rename_uploaded_file_enabled', $network = true ) == 'on' )
            add_filter( 'wp_handle_upload_prefilter', 'wp_sysmaster_000__rename_uploaded_file' );
        
    if ( $option_mgr -> get_value( 'custom_upload_mimes_enabled', $network = true ) == 'on' )
        add_filter( 'upload_mimes', 'wp_sysmaster_000__custom_upload_mimes' );
    
    if ( $option_mgr -> get_value( 'plugin_theme_installation_disabled', $network = true ) == 'on' )
        add_filter( 'upgrader_package_options', 'wp_sysmaster_000__disable_new_plugin_and_theme_installation' );
    
    if ( $option_mgr -> get_value( 'smtp_enabled', $network = true ) == 'on' )
        add_action( 'phpmailer_init', 'wp_sysmaster_000__configure_smtp' );
        
    if ( $option_mgr -> get_value( 'attachement_file_disabled', $network = true ) == 'on' )
        add_action('template_redirect', 'wp_sysmaster_000__disable_attachement_file'); 

    if ( $option_mgr -> get_value( 'add_quality_rating_taxonomy_enabled', $network = true ) == 'on' ) {
        add_action( 'init', 'wp_sysmaster_000__add_quality_rating_taxonomy' );
        add_action( 'admin_init', 'wp_sysmaster_000__taxonomy_filter_admin' );
    }
}


function wp_sysmaster_000__add_for_each_site( $option_mgr ) {
    if ( $option_mgr -> get_value( 'rename_uploaded_file_enabled' ) == 'on' )
        add_filter( 'wp_handle_upload_prefilter', 'wp_sysmaster_000__rename_uploaded_file' );
        
    if ( $option_mgr -> get_value( 'custom_upload_mimes_enabled' ) == 'on' )
            add_filter( 'upload_mimes', 'wp_sysmaster_000__custom_upload_mimes' );
    
    if ( $option_mgr -> get_value( 'plugin_theme_installation_disabled' ) == 'on' )
        add_filter( 'upgrader_package_options', 'wp_sysmaster_000__disable_new_plugin_and_theme_installation' );
        
    if ( $option_mgr -> get_value( 'smtp_enabled' ) == 'on' )
            add_action( 'phpmailer_init', 'wp_sysmaster_000__configure_smtp' );
    
    if ( $option_mgr -> get_value( 'attachement_file_disabled' ) == 'on' )
        add_action('template_redirect', 'wp_sysmaster_000__disable_attachement_file');

    if ( $option_mgr -> get_value( 'add_quality_rating_taxonomy_enabled' ) == 'on' ) {
        add_action( 'init', 'wp_sysmaster_000__add_quality_rating_taxonomy' );
        add_action( 'admin_init', 'wp_sysmaster_000__taxonomy_filter_admin' );
    }

}

$option_mgr = new OptionMgr( $wp_sysmaster_000__option_name );

if ( is_multisite() ) {
    if( $option_mgr -> get_value( 'override_network' ) == 'on' )
        wp_sysmaster_000__add_for_network_override( $option_mgr );
    else
        wp_sysmaster_000__add_for_network_override( $option_mgr );
} else {
    wp_sysmaster_000__add_for_each_site( $option_mgr );
}