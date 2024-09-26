<?php 

if ( !defined( 'ABSPATH' ) ) exit;

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

/**
 * Custom upload mimes
 * @param array $mimes
 * @return array
 */
 function wp_custom_codes__custom_upload_mimes( $mimes ) {
    $mimes['apk'] = 'application/vnd.android.package-archive';
    return $mimes;
}


if ( is_multisite() && get_site_option( $option_names[ 'override_for_all_sites' ][0] ) == 'on' ) {
    if ( get_site_option( $option_names[ 'enable_rename_uploaded_file' ][0] ) == 'on' )
        add_filter( 'wp_handle_upload_prefilter', 'wp_custom_codes__rename_uploaded_file' );
    
    if ( get_site_option( $option_names[ 'enable_custom_upload_mimes' ][0] ) == 'on' )
        add_filter( 'upload_mimes', 'wp_custom_codes__custom_upload_mimes' );
        
} else {
    if ( get_option( $option_names[ 'option_groups' ][2][ 'enable_rename_uploaded_file' ][0] ) == 'on' )
        add_filter( 'wp_handle_upload_prefilter', 'wp_custom_codes__rename_uploaded_file' );
    
    if ( get_option( $option_names[ 'option_groups' ][2][ 'enable_custom_upload_mimes' ][0] ) == 'on' )
        add_filter( 'upload_mimes', 'wp_custom_codes__custom_upload_mimes' );
}