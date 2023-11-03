<?php

/**
 * Plugin Name: WP Custom Codes
 * Description: A plugin containing code snippets used for system customization.
 * Version: 1.3.0
 * Author: NiranTeam
 * Author URI: https://niranteam.com/
 * Network: true
 * Requires PHP: 7.4
 */

if ( !defined( 'ABSPATH' ) ) exit;


/**
 * Load textdomain
 */
function wp_custom_codes__load_textdomain() {
    load_plugin_textdomain( 'wp-custom-codes', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'wp_custom_codes__load_textdomain' );


/**
 * Rename uploaded file
 * @param array $file
 * @return array
 */
function wp_custom_codes__rename_uploaded_file( $file ) {
    $upload_dir = wp_upload_dir();
    $file_info = pathinfo( $file['name'] );

    // {random string}.{file extension}
    $random_string = bin2hex( random_bytes(16) );
    $new_file_name = strtolower( $random_string ) . '.' . $file_info['extension'];

    $file['name'] = $new_file_name;
    $file['path'] = $upload_dir['path'] . '/' . $new_file_name;
    $file['url'] = $upload_dir['url'] . '/' . $new_file_name;

    return $file;
}

add_filter( 'wp_handle_upload_prefilter', 'wp_custom_codes__rename_uploaded_file' );


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

add_action( 'phpmailer_init', 'wp_custom_codes__configure_smtp' );


/**
 * Disable new plugin and theme installation
 *
 * @param array $options
 * @return array
 */
function wp_custom_codes__disable_new_plugin_and_theme_installation( $options )
{
    if ( isset( $options['hook_extra']['action'] ) && $options['hook_extra']['action'] === 'install' &&
        isset( $options['hook_extra']['type'] ) && in_array( $options['hook_extra']['type'], ['plugin', 'theme'] ) ) {
        die( 'Plugins & Themes installation disabled!' );
    }

    return $options;
}

add_filter( 'upgrader_package_options', 'wp_custom_codes__disable_new_plugin_and_theme_installation' );


/**
 * Add quality rating taxonomy
 */
function wp_custom_codes__add_quality_rating_taxonomy() {
    $labels = array(
        'name' => __( 'quality_rating_taxonomy__name', 'wp-custom-codes' ),
        'singular_name' => __( 'quality_rating_taxonomy__singular_name', 'wp-custom-codes' ),
        'search_items' => __( 'quality_rating_taxonomy__search_items', 'wp-custom-codes' ),
        'all_items' => __( 'quality_rating_taxonomy__all_items', 'wp-custom-codes' ),
        'parent_item' => __( 'quality_rating_taxonomy__parent_item', 'wp-custom-codes' ),
        'parent_item_colon' => __( 'quality_rating_taxonomy__parent_item_colon', 'wp-custom-codes' ),
        'edit_item' => __( 'quality_rating_taxonomy__edit_item', 'wp-custom-codes' ),
        'update_item' => __( 'quality_rating_taxonomy__update_item', 'wp-custom-codes' ),
        'add_new_item' => __( 'quality_rating_taxonomy__add_new_item', 'wp-custom-codes' ),
        'new_item_name' => __( 'quality_rating_taxonomy__new_item_name', 'wp-custom-codes' ),
        'menu_name' => __( 'quality_rating_taxonomy__menu_name', 'wp-custom-codes' ),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'show_in_rest' => true,
        'rewrite' => array( 'slug' => 'quality-rating' ),
    );

    register_taxonomy( 'quality_rating', 'post', $args );
}

add_action( 'init', 'wp_custom_codes__add_quality_rating_taxonomy' );


/**
 * Add a dropdown filter for quality rating taxonomy
 */
function wp_custom_codes__quality_rating_taxonomy__dropdown_filter() {
    global $typenow;

    if ( $typenow == 'post' ) {
        $taxonomy = 'quality_rating';
        $selected = isset( $_GET[$taxonomy] ) ? $_GET[$taxonomy] : '';

        $terms = get_terms( array(
            'taxonomy' => $taxonomy,
            'hide_empty' => true,
        ) );

        echo '<select name="' . $taxonomy . '">';
        echo '<option value="">' . __( 'quality_rating_taxonomy__all_items', 'wp-custom-codes' ) . '</option>';

        foreach ( $terms as $term ) {
            $selected_attr = ( $selected == $term->slug ) ? 'selected="selected"' : '';
            echo '<option value="' . esc_attr( $term->slug ) . '" ' . $selected_attr . '>' . esc_html( $term->name ) . '</option>';
        }

        echo '</select>';
    }
}

function wp_custom_codes__taxonomy_filter_admin() {
    add_action( 'restrict_manage_posts', 'wp_custom_codes__quality_rating_taxonomy__dropdown_filter' );
}

add_action( 'admin_init', 'wp_custom_codes__taxonomy_filter_admin' );