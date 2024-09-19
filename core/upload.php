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

if ( get_option( 'wpcc__og1__o1_enable_rename_uploaded_file' ) == 'on' ) {
    add_filter( 'wp_handle_upload_prefilter', 'wp_custom_codes__rename_uploaded_file' );
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

if ( get_option( 'wpcc__og1__o2_enable_custom_upload_mimes' ) == 'on' ) {
    add_filter( 'upload_mimes', 'wp_custom_codes__custom_upload_mimes' );
}