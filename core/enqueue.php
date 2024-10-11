<?php

/**
 * Enqueue styles
 */
function wp_custom_codes__enqueue_styles() {
    wp_enqueue_style( 'wp_custom_codes__enqueue_styles', plugin_dir_url( __FILE__ ) . 'assets/css/style.css' );
}

add_action( 'wp_enqueue_scripts', 'wp_custom_codes__enqueue_styles' );


/**
 * Admin enqueue styles
 */
function wp_custom_codes__admin_enqueue_styles() {
    global $pagenow;
    if ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) {
        wp_enqueue_style( 'my-editor-style', plugin_dir_url( __FILE__ ) . 'assets/css/admin-style.css' );
    }
}

add_action( 'admin_enqueue_scripts', 'wp_custom_codes__admin_enqueue_styles' );