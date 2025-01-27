<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<div class="wrap">
    <h1><?php echo __( 'WP SysMaster by Chanh Xuan Phan', $wp_sysmaster_000__text_domain ); ?></h1>
    
     <!-- Tab menu -->
    <h2 class="nav-tab-wrapper">
        <a href="#tab-1" class="nav-tab nav-tab-active" onclick="openTab(event, 'tab-1')"><?php echo __( 'Files & Upload', $wp_sysmaster_000__text_domain ); ?></a>
        <a href="#tab-2" class="nav-tab" onclick="openTab(event, 'tab-2')"><?php echo __( 'Plugins & Themes', $wp_sysmaster_000__text_domain ); ?></a>
        <a href="#tab-3" class="nav-tab" onclick="openTab(event, 'tab-3')"><?php echo __( 'SMTP', $wp_sysmaster_000__text_domain ); ?></a>
        <a href="#tab-4" class="nav-tab" onclick="openTab(event, 'tab-4')"><?php echo __( 'Quality Rating Taxonomy', $wp_sysmaster_000__text_domain ); ?></a>
        <a href="#tab-5" class="nav-tab" onclick="openTab(event, 'tab-5')"><?php echo __( 'Modules', $wp_sysmaster_000__text_domain ); ?></a>
    </h2>

    <!-- Tab 1 -->
    <div id="tab-1" class="tab-content" style="display: block;">
        <form method="post" action="">
        <?php wp_nonce_field( 'tab1' ); ?>
        <h2><?php echo __( 'web-admin-ui__tab-1_title', $wp_sysmaster_000__text_domain ); ?></h2>
        <?php if ( $override_for_all_sites_enabled == 'on') { ?>
        <p style="color: red;"><?php echo $override_for_all_sites_notice; ?></p>
        <fieldset disabled="disabled"> 
        <?php } ?>
        <input type="hidden" name="tab-id" value="tab-1" />
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php echo __( 'Enable rename uploaded file', $wp_sysmaster_000__text_domain ); ?></th>
                <td>
                    <input type="checkbox" name="rename_uploaded_file_enabled" <?php checked( $rename_uploaded_file_enabled, 'on' ); ?>>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php echo __( 'Enable custom upload mimes', $wp_sysmaster_000__text_domain ) ?></th>
                <td>
                    <input type="checkbox" name="custom_upload_mimes_enabled" <?php checked( $custom_upload_mimes_enabled, 'on' ); ?>>
                </td>
            </tr>
        </table>
        <?php if ( $override_for_all_sites_enabled == 'on') { ?> </fieldset> 
        <?php } else { 
            submit_button();
            }
        ?>
        </form>
    </div>

    <!-- Tab 2 -->
    <div id="tab-2" class="tab-content" style="display: none;">
        <form method="post" action="">
            <?php wp_nonce_field( 'tab2' ); ?>
            <h2><?php echo __( 'web-admin-ui__tab-2_title', $wp_sysmaster_000__text_domain ); ?></h2>
            <?php if ( $override_for_all_sites_enabled == 'on') { ?>
            <p style="color: red;"><?php echo $override_for_all_sites_notice; ?></p>
            <fieldset disabled="disabled"> 
            <?php } ?>
            <input type="hidden" name="tab-id" value="tab-2" />
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php echo __( 'Disable plugin theme installation', $wp_sysmaster_000__text_domain ); ?></th>
                    <td>
                        <input type="checkbox" name="plugin_theme_installation_disabled" <?php checked( $plugin_theme_installation_disabled, 'on' ); ?>>
                    </td>
                </tr>
            </table>
            <?php if ( $override_for_all_sites_enabled == 'on') { ?> </fieldset> 
            <?php } else { 
                submit_button();
                }
            ?>
        </form>
    </div>

    <!-- Tab 3 -->
    <div id="tab-3" class="tab-content" style="display: none;">
        <form method="post" action="">
            <?php wp_nonce_field( 'tab3' ); ?>
            <h2><?php echo __( 'web-admin-ui__tab-2_title', $wp_sysmaster_000__text_domain ); ?></h2>
            <?php if ( $override_for_all_sites_enabled == 'on') { ?>
            <p style="color: red;"><?php echo $override_for_all_sites_notice; ?></p>
            <fieldset disabled="disabled"> 
            <?php } ?>
            <input type="hidden" name="tab-id" value="tab-3" />
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php echo __( 'Enable SMTP', $wp_sysmaster_000__text_domain ); ?></th>
                    <td>
                        <input type="checkbox" name="smtp_enabled" <?php checked( $smtp_enabled, 'on' ); ?>>
                    </td>
                </tr>
            </table>
            <?php if ( $override_for_all_sites_enabled == 'on') { ?> </fieldset> 
            <?php } else { 
                submit_button();
                }
            ?>
        </form>
    </div>
    <!-- Tab 4 -->
    <div id="tab-4" class="tab-content" style="display: none;">
        <form method="post" action="options.php">
            <!-- <?php
                settings_fields( $option_names[ 'option_groups_4' ][0] );
                do_settings_sections( $option_names[ 'option_groups_4' ][0] );
            ?>
            <h2><?php echo __( 'web-admin-ui__tab-4_title', $wp_sysmaster_000__text_domain ); ?></h2>
            <div>
                <label for="<?php echo $option_names[ 'option_groups_4' ][2][ 'add_quality_rating_taxonomy' ][0]; ?>">
                    <?php echo $option_names[ 'option_groups_4' ][2][ 'add_quality_rating_taxonomy' ][1]; ?>
                </label>
                <input type="checkbox" id="<?php echo $option_names[ 'option_groups_4' ][2][ 'add_quality_rating_taxonomy' ][0]; ?>" name="<?php echo $option_names[ 'option_groups_4' ][2][ 'add_quality_rating_taxonomy' ][0]; ?>" <?php if( $add_quality_rating_taxonomy_enabled == 'on' ) echo 'checked'; else echo ''; ?> />
            </div>
            <?php submit_button(); ?> -->
        </form>
    </div>
    <!-- Tab 5 -->
    <div id="tab-5" class="tab-content" style="display: none;">
        <form method="post" action="options.php">
            <?php wp_nonce_field( 'tab3' ); ?>
            <h2><?php echo __( 'web-admin-ui__tab-2_title', $wp_sysmaster_000__text_domain ); ?></h2>
            <?php if ( $override_for_all_sites_enabled == 'on') { ?>
            <p style="color: red;"><?php echo $override_for_all_sites_notice; ?></p>
            <fieldset disabled="disabled"> 
            <?php } ?>
            <input type="hidden" name="tab-id" value="tab-3" />
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php echo __( 'Enable module \'opcache manager\'', $wp_sysmaster_000__text_domain ); ?></th>
                    <td>
                        <input type="checkbox" name="module_opcache_mgr" <?php checked( $module_opcache_mgr_enabled, 'on' ); ?>>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __( 'Enable module \'yomigana\'', $wp_sysmaster_000__text_domain ); ?></th>
                    <td>
                        <input type="checkbox" name="module_yomigana" <?php checked( $module_yomigana_enabled, 'on' ); ?>>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __( 'Enable module \'terminal\'', $wp_sysmaster_000__text_domain ); ?></th>
                    <td>
                        <input type="checkbox" name="module_terminal" <?php checked( $module_terminal_enabled, 'on' ); ?>>
                    </td>
                </tr>
            </table>
            <?php if ( $override_for_all_sites_enabled == 'on') { ?> </fieldset> 
            <?php } else { 
                submit_button();
                }
            ?>
        </form>
    </div>
</div>