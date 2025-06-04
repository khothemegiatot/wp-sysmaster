<?php
/**
 * WP SysMaster - Uninstall
 *
 * Uninstalling WP SysMaster deletes all plugin options.
 */

if (!defined('ABSPATH')) exit;

// If uninstall not called from WordPress, then exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Options to remove
$options = [
    'wp_sysmaster_settings',          // General settings
    'wp_sysmaster_ai_settings',       // AI settings
    'wp_sysmaster_upload_settings',   // Upload settings
    'wp_sysmaster_smtp_settings',     // SMTP settings
    'wp_sysmaster_security_settings', // Security settings
];

// Remove options
if (is_multisite()) {
    // Remove options from all sites in the network
    $sites = get_sites();
    foreach ($sites as $site) {
        switch_to_blog($site->blog_id);
        foreach ($options as $option) {
            delete_option($option);
        }
        restore_current_blog();
    }

    // Remove network options
    foreach ($options as $option) {
        delete_site_option($option);
    }
} else {
    // Remove options from single site
    foreach ($options as $option) {
        delete_option($option);
    }
}

// Clear any cached data
wp_cache_flush();