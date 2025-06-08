<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

// Load includes
require_once dirname(__FILE__) . '/includes/class-opcache.php';
require_once dirname(__FILE__) . '/includes/class-opcache-statistics.php';

// Load admin
if (is_admin()) {
    require_once dirname(__FILE__) . '/admin/class-opcache-admin.php';
    new WP_SysMaster_OPcache_Admin();
}

// Load CLI
if (defined('WP_CLI') && WP_CLI) {
    require_once dirname(__FILE__) . '/cli/class-opcache-cli.php';
    new WP_SysMaster_OPcache_CLI();
}

// Initialize OPcache module
WP_SysMaster_OPcache::get_instance(); 