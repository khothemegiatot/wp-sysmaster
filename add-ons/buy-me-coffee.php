<?php

if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Add buy me coffee
 */

if( defined( 'ENABLE__add_buy_me_coffee_to_post' ) && ENABLE__add_buy_me_coffee_to_post === true ) {
    function wp_custom_codes__add_buy_me_coffee_to_post( $content ) {
        $custom_content = '<script type="text/javascript" src="https://cdnjs.buymeacoffee.com/1.0.0/button.prod.min.js" data-name="bmc-button" data-slug="' . BUY_ME_COFFEE_ID . '" data-color="#FFDD00" data-emoji="" data-font="Arial" data-text="Tặng tôi một ly cà phê" data-outline-color="#000000" data-font-color="#000000" data-coffee-color="#ffffff" ></script>';
        return $content . $custom_content;
    }
    
    add_filter( 'the_content', 'wp_custom_codes__add_buy_me_coffee_to_post' );
}