<?php

if ( !defined( 'ABSPATH' ) ) exit;

use WPSysMaster\Admin\SMTP;
use WPSysMaster\Admin\Upload;

// Action: Init SMTP handler
add_action('plugins_loaded', function() {
    SMTP::getInstance();
});

// Action: Init Upload handler
add_action('plugins_loaded', function() {
    Upload::getInstance();
});