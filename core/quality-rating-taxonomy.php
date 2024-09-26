<?php

if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Add quality rating taxonomy
 */
if(  get_option( $option_names[ 'option_groups_4' ][2][ 'add_quality_rating_taxonomy' ][0] ) == 'on' ) {
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
}