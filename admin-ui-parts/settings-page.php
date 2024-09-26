<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<div class="wrap">
    <h1><?php echo __( 'web-admin-ui__page-title', $wpcc__text_domain ); ?></h1>
    
     <!-- Tab menu -->
    <h2 class="nav-tab-wrapper">
        <a href="#wp-custom-codes__tab-1" class="nav-tab nav-tab-active" onclick="openTab(event, 'wp-custom-codes__tab-1')"><?php echo __( 'web-admin-ui__tab-1_title', $wpcc__text_domain ); ?></a>
        <a href="#wp-custom-codes__tab-2" class="nav-tab" onclick="openTab(event, 'wp-custom-codes__tab-2')"><?php echo __( 'web-admin-ui__tab-2_title', $wpcc__text_domain ); ?></a>
        <a href="#wp-custom-codes__tab-3" class="nav-tab" onclick="openTab(event, 'wp-custom-codes__tab-3')"><?php echo __( 'web-admin-ui__tab-3_title', $wpcc__text_domain ); ?></a>
        <a href="#wp-custom-codes__tab-4" class="nav-tab" onclick="openTab(event, 'wp-custom-codes__tab-4')"><?php echo __( 'web-admin-ui__tab-4_title', $wpcc__text_domain ); ?></a>
    </h2>

    <!-- Tab 1 -->
    <div id="wp-custom-codes__tab-1" class="tab-content" style="display: block;">
        <form method="post" action="options.php">
           <?php
                settings_fields( $option_names[ 'option_groups_1' ][0] );
                do_settings_sections( $option_names[ 'option_groups_1' ][0] );
            ?>
            <h2><?php echo __( 'web-admin-ui__tab-1_title', $wpcc__text_domain ); ?></h2>
            <?php if ( $override_for_all_sites_enabled == 'on') { ?>
            <p style="color: red;"><?php echo $override_for_all_sites_notice; ?></p>
            <fieldset disabled="disabled"> 
            <?php } ?>
            <div>
                <label for="<?php echo $option_names[ 'option_groups_1' ][2][ 'enable_rename_uploaded_file' ][0]; ?>">
                    <?php echo $option_names[ 'option_groups_1' ][2][ 'enable_rename_uploaded_file' ][1]; ?>
                </label>
                <input type="checkbox" id="<?php echo $option_names[ 'option_groups_1' ][2][ 'enable_rename_uploaded_file' ][0]; ?>" name="<?php echo $option_names[ 'option_groups_1' ][2][ 'enable_rename_uploaded_file' ][0]; ?>" <?php if( $enable_rename_uploaded_enabled == 'on' ) echo 'checked'; else echo ''; ?> />
            </div>
            <div>
                <label for="<?php echo $option_names[ 'option_groups_1' ][2][ 'enable_custom_upload_mimes' ][0]; ?>">
                    <?php echo $option_names[ 'option_groups_1' ][2][ 'enable_custom_upload_mimes' ][1]; ?>
                </label>
                <input type="checkbox" id="<?php echo $option_names[ 'option_groups_1' ][2][ 'enable_custom_upload_mimes' ][0]; ?>" name="<?php echo $option_names[ 'option_groups_1' ][2][ 'enable_custom_upload_mimes' ][0]; ?>" <?php if( $enable_custom_upload_mimes_enabled == 'on' ) echo 'checked'; else echo ''; ?> />
            </div>
            <?php if ( $override_for_all_sites_enabled == 'on') { ?> </fieldset> 
            <?php } else { 
                submit_button();
                }
            ?>
        </form>
    </div>

    <!-- Tab 2 -->
    <div id="wp-custom-codes__tab-2" class="tab-content" style="display: none;">
        <form method="post" action="options.php">
            <?php
                settings_fields( $option_names[ 'option_groups_2' ][0] );
                do_settings_sections( $option_names[ 'option_groups_2' ][0] );
            ?>
            <h2><?php echo __( 'web-admin-ui__tab-2_title', $wpcc__text_domain ); ?></h2>
            <?php if ( $override_for_all_sites_enabled == 'on') { ?>
            <p style="color: red;"><?php echo $override_for_all_sites_notice; ?></p>
            <fieldset disabled="disabled"> 
            <?php } ?>
            <div>
                <label for="<?php echo $option_names[ 'option_groups_2' ][2][ 'disable_plugin_theme_installation' ][0]; ?>">
                    <?php echo $option_names[ 'option_groups_2' ][2][ 'disable_plugin_theme_installation' ][1]; ?>
                </label>
                <input type="checkbox" id="<?php echo $option_names[ 'option_groups_2' ][2][ 'disable_plugin_theme_installation' ][0]; ?>" name="<?php echo $option_names[ 'option_groups_2' ][2][ 'disable_plugin_theme_installation' ][0]; ?>" <?php if( $disable_plugin_theme_installation_enabled == 'on' ) echo 'checked'; else echo ''; ?> />
            </div>
            <?php if ( $override_for_all_sites_enabled == 'on') { ?> </fieldset> 
            <?php } else { 
                submit_button();
                }
            ?>
        </form>
    </div>

    <!-- Tab 3 -->
    <div id="wp-custom-codes__tab-3" class="tab-content" style="display: none;">
        <form method="post" action="options.php">
            <?php
                settings_fields( $option_names[ 'option_groups_3' ][0] );
                do_settings_sections( $option_names[ 'option_groups_3' ][0] );
            ?>
            <h2><?php echo __( 'web-admin-ui__tab-3_title', $wpcc__text_domain ); ?></h2>
            <?php if ( $override_for_all_sites_enabled == 'on') { ?>
            <p style="color: red;"><?php echo $override_for_all_sites_notice; ?></p>
            <fieldset disabled="disabled"> 
            <?php } ?>
            <div>
                <label for="<?php echo $option_names[ 'option_groups_3' ][2][ 'enable_smtp' ][0]; ?>">
                    <?php echo $option_names[ 'option_groups_3' ][2][ 'enable_smtp' ][1]; ?>
                </label>
                <input type="checkbox" id="<?php echo $option_names[ 'option_groups_3' ][2][ 'enable_smtp' ][0]; ?>" name="<?php echo $option_names[ 'option_groups_3' ][2][ 'enable_smtp' ][0]; ?>" <?php if( $smtp_enabled == 'on' ) echo 'checked'; else echo ''; ?> />
            </div>
            <?php if ( $override_for_all_sites_enabled == 'on') { ?> </fieldset> 
            <?php } else { 
                submit_button();
                }
            ?>
        </form>
    </div>
    <!-- Tab 4 -->
    <div id="wp-custom-codes__tab-4" class="tab-content" style="display: none;">
        <form method="post" action="options.php">
            <?php
                settings_fields( $option_names[ 'option_groups_4' ][0] );
                do_settings_sections( $option_names[ 'option_groups_4' ][0] );
            ?>
            <h2><?php echo __( 'web-admin-ui__tab-4_title', $wpcc__text_domain ); ?></h2>
            <div>
                <label for="<?php echo $option_names[ 'option_groups_4' ][2][ 'add_quality_rating_taxonomy' ][0]; ?>">
                    <?php echo $option_names[ 'option_groups_4' ][2][ 'add_quality_rating_taxonomy' ][1]; ?>
                </label>
                <input type="checkbox" id="<?php echo $option_names[ 'option_groups_4' ][2][ 'add_quality_rating_taxonomy' ][0]; ?>" name="<?php echo $option_names[ 'option_groups_4' ][2][ 'add_quality_rating_taxonomy' ][0]; ?>" <?php if( $add_quality_rating_taxonomy_enabled == 'on' ) echo 'checked'; else echo ''; ?> />
            </div>
            <?php submit_button(); ?>
        </form>
    </div>
</div>