<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<div class="wrap">
    <h1><?php echo __( 'WP SysMaster (Network Admin) by Chanh Xuan Phan', $wp_sysmaster_000__text_domain ); ?></h1>
    <!-- Tab menu -->
    <form method="post" action="">
        <?php wp_nonce_field( 'tab0' ); ?>
        <input type="hidden" name="tab-id" value="tab-0" />
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php echo __( 'Override for all sites' ); ?></th>
                <td>
                    <input type="checkbox" name="override_for_all_sites" value="yes" <?php checked( $override_for_all_sites_enabled, 'on' ); ?>>
                </td>
                <td>
                    <?php submit_button(); ?>
                </td>
            </tr>
        </table>
    </form>
    
    <h2 class="nav-tab-wrapper">
        <a href="#tab-1" class="nav-tab nav-tab-active" onclick="openTab(event, 'tab-1')"><?php echo __( 'Files & Upload', $wp_sysmaster_000__text_domain ); ?></a>
        <a href="#tab-2" class="nav-tab" onclick="openTab(event, 'tab-2')"><?php echo __( 'Plugins & Themes', $wp_sysmaster_000__text_domain ); ?></a>
        <a href="#tab-3" class="nav-tab" onclick="openTab(event, 'tab-3')"><?php echo __( 'SMTP', $wp_sysmaster_000__text_domain ); ?></a>
        <a href="#tab-4" class="nav-tab" onclick="openTab(event, 'tab-4')"><?php echo __( 'Quality Rating Taxonomy', $wp_sysmaster_000__text_domain ); ?></a>
        <a href="#tab-5" class="nav-tab" onclick="openTab(event, 'tab-5')"><?php echo __( 'Modules', $wp_sysmaster_000__text_domain ); ?></a>
    </h2>
    
    <div id="wp-custom-codes__tab-1" class="tab-content" style="display: block;">
        <form method="post" action="">
            <?php wp_nonce_field( 'tab1' ); ?>
            <input type="hidden" name="tab-id" value="tab-1" />
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php echo __( 'Enable rename uploaded file', $wp_sysmaster_000__text_domain ); ?></th>
                    <td>
                        <input type="checkbox" name="rename_uploaded_file_enabled" <?php checked( $rename_uploaded_file_enabled, 'on' ); ?>>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __( 'Enable custom upload mimes', $wp_sysmaster_000__text_domain ); ?></th>
                    <td>
                        <input type="checkbox" name="enable_custom_upload_mimes" <?php checked( $custom_upload_mimes_enabled, 'on' ); ?>>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <div id="wp-custom-codes__tab-2" class="tab-content" style="display: none;">
        <form method="post" action="">
            <?php wp_nonce_field( 'wp_custom_codes__network_settings__tab2' ); ?>
            <input type="hidden" name="tab-id" value="wp-custom-codes__tab-2" />
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php echo $option_names[ 'disable_plugin_theme_installation' ][1]; ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo $option_names[ 'disable_plugin_theme_installation' ][0]; ?>" <?php checked( $plugin_theme_installation_disabled, 'on' ); ?>>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <div id="wp-custom-codes__tab-3" class="tab-content" style="display: none;">
        <form method="post" action="">
            <?php wp_nonce_field( 'wp_custom_codes__network_settings__tab3' ); ?>
            <input type="hidden" name="tab-id" value="wp-custom-codes__tab-3" />
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php echo $option_names[ 'enable_smtp' ][1]; ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo $option_names[ 'enable_smtp' ][0]; ?>" <?php checked( $smtp_enabled, 'on' ); ?>>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <div id="wp-custom-codes__tab-4" class="tab-content" style="display: none;">
        <form method="post" action="">
            <?php wp_nonce_field( 'wp_custom_codes__network_settings' ); ?>
            <input type="hidden" name="tab-id" value="wp-custom-codes__tab-4" />
            <p><?php echo __( 'network-settings__not-available-notice' , $wpcc__text_domain); ?></p>
            <?php submit_button(); ?>
        </form>
    </div>
</div>

