<?php

if ( !defined( 'ABSPATH' ) ) exit;

use WPSysMaster\Admin\SMTP;

/**
 * Rename uploaded file
 * @param array $file
 * @return array
 */
function wp_sysmaster_000__rename_uploaded_file( $file ) {
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

/**
 * Custom upload mimes
 * @param array $mimes
 * @return array
 */
 function wp_sysmaster_000__custom_upload_mimes( $mimes ) {
    $mimes['apk'] = 'application/vnd.android.package-archive';
    return $mimes;
}



/**
 * Disable new plugin and theme installation
 *
 * @param array $options
 * @return array
 */
function wp_sysmaster_000__disable_new_plugin_and_theme_installation( $options )
{
    if ( isset( $options['hook_extra']['action'] ) && $options['hook_extra']['action'] === 'install' &&
        isset( $options['hook_extra']['type'] ) && in_array( $options['hook_extra']['type'], ['plugin', 'theme'] ) ) {
        die( 'Plugins & Themes installation disabled!' );
    }

    return $options;
}

/**
 * Disable attachement file
 */
function wp_sysmaster_000__disable_attachement_file() {
	global $post;
	if ( ! is_attachment() || ! isset( $post->post_parent ) || ! is_numeric( $post->post_parent ) ) {
		return;
	}
	// Does the attachment have a parent post?
	// If the post is trashed, fallback to redirect to homepage.
	if ( 0 !== $post->post_parent && 'trash' !== get_post_status( $post->post_parent ) ) {
		// Redirect to the attachment parent.
		wp_safe_redirect( get_permalink( $post->post_parent ), 301 );
	} else {
		// For attachment without a parent redirect to homepage.
		wp_safe_redirect( get_bloginfo( 'wpurl' ), 302 );
	}
	
	exit;
}

/**
 * Add quality rating taxonomy
 */
function wp_sysmaster_000__add_quality_rating_taxonomy() {
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

/**
 * Add a dropdown filter for quality rating taxonomy
 */
function wp_sysmaster_000__quality_rating_taxonomy__dropdown_filter() {
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

function wp_sysmaster_000__taxonomy_filter_admin() {
    add_action( 'restrict_manage_posts', 'wp_sysmaster_000__quality_rating_taxonomy__dropdown_filter' );
}
