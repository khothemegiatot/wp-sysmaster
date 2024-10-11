<?php

define( 'FLUSH_OPCACHE_VERSION', '4.2.0' );
define( 'FLUSH_OPCACHE_NAME', 'flush-opcache' );

require plugin_dir_path( __FILE__ ) . 'includes/class-flush-opcache.php';

/** Main function to fire plugin execution */
function run_flush_opcache() {
	$plugin = new Flush_Opcache();
}

run_flush_opcache();