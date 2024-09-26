<?php

if ( !defined( 'ABSPATH' ) ) exit;

global $wpcc__text_domain;
$wpcc__text_domain = 'wp-custom-codes';

global $option_names;
$option_names = array (
    'override_for_all_sites' => array(
        'wpcc__common__o0_override_for_all_sites',
        __( 'wpcc__common__o0-override-for-all-sites', $wpcc__text_domain )  
    ),
    'enable_rename_uploaded_file' => array( 
        'wpcc__common__o1_enable_rename_uploaded_file', 
        __( 'wpcc__common__o1-enable-rename-uploaded-file', $wpcc__text_domain )  
    ),
    'enable_custom_upload_mimes' => array( 
        'wpcc__common__o2_enable_custom_upload_mimes',   
        __( 'wpcc__common__o2-enable-custom-upload-mimes', $wpcc__text_domain ) 
    ),
    'disable_plugin_theme_installation' => array(
        'wpcc__common__o3_disable_plugin_theme_installation',
        __( 'wpcc__common__o3-disable-plugin-theme-installation', $wpcc__text_domain ) 
    ),
    'enable_smtp' => array(
        'wpcc__common_o4_enable_smtp',
        __( 'wpcc__common__o4-enable-smtp', $wpcc__text_domain )  
    ),
    'option_groups_1' => array(
        'wpcc__option_group_1',
        __( 'wpcc__option-group-1', $wpcc__text_domain ),
        array(
            'enable_rename_uploaded_file' => array( 
                'wpcc__common__o1_enable_rename_uploaded_file', 
                __( 'wpcc__common__o1-enable-rename-uploaded-file', $wpcc__text_domain )  
            ),
            'enable_custom_upload_mimes' => array( 
                'wpcc__common__o2_enable_custom_upload_mimes',   
                __( 'wpcc__common__o2-enable-custom-upload-mimes', $wpcc__text_domain ) 
            )
        )
    ),
    'option_groups_2' => array(
        'wpcc__option_group_2',
        __( 'wpcc__option-group-2', $wpcc__text_domain ),
        array(
            'disable_plugin_theme_installation' => array(
                'wpcc__common__o3_disable_plugin_theme_installation',
                __( 'wpcc__common__o3-disable-plugin-theme-installation', $wpcc__text_domain ) 
            )
        )
    ),
    'option_groups_3' => array(
        'wpcc__option_group_3',
        __( 'wpcc__option-group-3', $wpcc__text_domain ),
        array(
            'enable_smtp' => array(
                'wpcc__common_o4_enable_smtp',
                __( 'wpcc__common__o4-enable-smtp', $wpcc__text_domain )  
            ),
        )
    ),
    'option_groups_4' => array(
        'wpcc__option_group_4',
        __( 'wpcc__option-group-4', $wpcc__text_domain ),
        array(
            'add_quality_rating_taxonomy' => array(
                'wpcc__o1_add_quality_rating_taxonomy',
                __( 'wpcc__o1-add-quality-rating-taxonomy', $wpcc__text_domain )  
            ),
        )
    ),
);