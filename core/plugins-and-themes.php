<?php

if ( !defined( 'ABSPATH' ) ) exit;

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

if ( get_option( 'wpcc__og2__o1_disable_plugin_theme_installation' ) == 'on' ) {
    add_filter( 'upgrader_package_options', 'wp_custom_codes__disable_new_plugin_and_theme_installation' );
}