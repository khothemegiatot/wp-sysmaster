<?php

if ( !defined( 'ABSPATH' ) ) exit;

class OptionMgr {
    private $option_name;
    private $json_string;
    private $json_string_network;
    
    private function default_json_option() { 
        $default_json = '{
            "override_network": "off",
            "rename_uploaded_file_enabled": "off",
            "custom_upload_mimes_enabled": "off",
            "custom_upload_mimes_list": [ 
                { "apk": "application/vnd.android.package-archive" }
            ],
            "plugin_theme_installation_disabled": "off",
            "smtp_enabled": "off",
            "attachement_file_disabled": "off",
            "add_quality_rating_taxonomy_enabled": "off",
            "module_opcache_mgr": "off",
            "module_yomigana": "off",
            "module_terminal": "off"
        }';
        
        return $default_json;
    }
    
    private function get_from_db() {
        if ( is_multisite() ) {
            $this -> json_string_network = get_site_option( $this -> option_name );
            
            if ( $this -> json_string_network == null || $this -> json_string_network == '' ) {
                $this -> json_string_network = $this -> default_json_option();
                update_site_option( $this -> option_name, $this -> json_string_network );
            }
        }
        
        $this -> json_string = get_option( $this -> option_name );
        
        if ( $this -> json_string == null || $this -> json_string == '' ) {
            $this -> json_string = $this -> default_json_option();
            update_option( $this -> option_name, $this -> json_string_network );
        }
    }
    
    
    public function __construct( $option_name ) {
        $this -> option_name = $option_name;
        $this -> get_from_db();
    }

    /**
     * Save option
     * @return bool
     */
    public function save_option ( $network = false ) {
        if ( $network )
            return update_site_option( $this -> option_name, $this -> json_string_network );

        return update_option( $this->option_name, $this -> json_string );
    }

    /**
     * Update option into database
     *
     * @param array $pairs
     * @return void
     */
    public function update_value( array $pairs, $network = false ) {
        $data = '';
        
    	if ( $network )
    	   $data = json_decode( $this -> json_string_network, true );
    	else
    	   $data = json_decode( $this -> json_string, true ); 
            
            foreach ( $pairs as $key => $value )
                $data[ $key ] = $value;
            
    	if ( $network )
            $this -> json_string_network = json_encode( $data );
    	else
            $this -> json_string = json_encode ( $data );
    }

    /**
     * Get value by key
     * @param string $key
     * @param boolean $network
     * @return array
     */
    public function get_value( $key, $network = false ) {
        $array = null;

        if ( $network ){
            $array = json_decode( $this -> json_string_network, true );
            return ( array_key_exists( $key, $array) ) ? $array[ $key ] : '';
        }

        $array = json_decode( $this -> json_string, true );
        return ( array_key_exists( $key, $array ) ) ? $array[ $key ] : '';
    }

    /**
     * Remove option
     * @param
     * @return void
     */
    public function remove_option ( $network = false ) {
        if ( $network )
            delete_site_option( $this -> option_name );
        
        delete_option( $this -> option_name );
    }

    /**
     * Reset option
     * @param
     * @return void
     */
    public function reset_option () {
        if ( is_multisite() ) {
            $this -> json_string_network = $this -> default_json_option();
            update_site_option( $this -> option_name, $this -> json_string_network );
        }
        
        $this -> json_string = $this -> default_json_option();
        update_option( $this -> option_name, $this -> json_string_network );
    }
}
