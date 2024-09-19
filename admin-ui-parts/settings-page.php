<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<div class="wrap">
    <h1><?php echo __( 'web-admin-ui__page-title', 'wp-custom-codes' ); ?></h1>
    
     <!-- Tab menu -->
    <h2 class="nav-tab-wrapper">
        <a href="#wp-custom-codes__tab-1" class="nav-tab nav-tab-active" onclick="openTab(event, 'wp-custom-codes__tab-1')"><?php echo __( 'web-admin-ui__tab-1_title', 'wp-custom-codes' ); ?></a>
        <a href="#wp-custom-codes__tab-2" class="nav-tab" onclick="openTab(event, 'wp-custom-codes__tab-2')"><?php echo __( 'web-admin-ui__tab-2_title', 'wp-custom-codes' ); ?></a>
        <a href="#wp-custom-codes__tab-3" class="nav-tab" onclick="openTab(event, 'wp-custom-codes__tab-3')"><?php echo __( 'web-admin-ui__tab-3_title', 'wp-custom-codes' ); ?></a>
        <a href="#wp-custom-codes__tab-4" class="nav-tab" onclick="openTab(event, 'wp-custom-codes__tab-4')"><?php echo __( 'web-admin-ui__tab-4_title', 'wp-custom-codes' ); ?></a>
    </h2>

    <!-- Tab 1 -->
    <div id="wp-custom-codes__tab-1" class="tab-content" style="display: block;">
        <form method="post" action="options.php">
            <?php
                settings_fields('wp_custom_codes__options_group_1');
                do_settings_sections('wp_custom_codes__options_group_1');
            ?>
            <h2><?php echo __( 'web-admin-ui__tab-1_title', 'wp-custom-codes' ); ?></h2>
            <div>
                <label for="wpcc__og1__o1_enable_rename_uploaded_file">
                    <?php echo __( 'wpcc__og1__o1-enable-rename-uploaded-file', 'wp-custom-codes' ); ?>
                </label>
                <input type="checkbox" id="wpcc__og1__o1_enable_rename_uploaded_file" name="wpcc__og1__o1_enable_rename_uploaded_file" <?php if( get_option( 'wpcc__og1__o1_enable_rename_uploaded_file' ) == 'on' ) echo 'checked'; else echo ''; ?> />
            </div>
            <div>
                <label for="wpcc__og1__o2_enable_custom_upload_mimes">
                    <?php echo __( 'wpcc__og1__o2-enable-custom-upload-mimes', 'wp-custom-codes' ); ?>
                </label>
                <input type="checkbox" id="wpcc__og1__o2_enable_custom_upload_mimes" name="wpcc__og1__o2_enable_custom_upload_mimes" <?php if( get_option( 'wpcc__og1__o2_enable_custom_upload_mimes' ) == 'on' ) echo 'checked'; else echo ''; ?> />
            </div>
            <?php submit_button(); ?>
        </form>
    </div>

    <!-- Tab 2 -->
    <div id="wp-custom-codes__tab-2" class="tab-content" style="display: none;">
        <form method="post" action="options.php">
            <?php
                settings_fields('wp_custom_codes__options_group_2');
                do_settings_sections('wp_custom_codes__options_group_2');
            ?>
            <h2><?php echo __( 'web-admin-ui__tab-2_title', 'wp-custom-codes' ); ?></h2>
            <div>
                <label for="wpcc__og2__o1_disable_plugin_theme_installation">
                    <?php echo __( 'wpcc__og2__o1-disable_plugin_theme_installation', 'wp-custom-codes' ); ?>
                </label>
                <input type="checkbox" id="wpcc__og2__o1_disable_plugin_theme_installation" name="wpcc__og2__o1_disable_plugin_theme_installation" <?php if( get_option( 'wpcc__og2__o1_disable_plugin_theme_installation' ) == 'on' ) echo 'checked'; else echo ''; ?> />
            </div>
            <?php submit_button(); ?>
        </form>
    </div>

    <!-- Tab 3 -->
    <div id="wp-custom-codes__tab-3" class="tab-content" style="display: none;">
        <form method="post" action="options.php">
            <?php
                settings_fields('wp_custom_codes__options_group_3');
                do_settings_sections('wp_custom_codes__options_group_3');
            ?>
            <h2><?php echo __( 'web-admin-ui__tab-3_title', 'wp-custom-codes' ); ?></h2>
            <div>
                <label for="wpcc__og3__o1_enable_smtp">
                    <?php echo __( 'wpcc__og3__o1-enable-smtp', 'wp-custom-codes' ); ?>
                </label>
                <input type="checkbox" id="wpcc__og3__o1_enable_smtp" name="wpcc__og3__o1_enable_smtp" <?php if( get_option( 'wpcc__og3__o1_enable_smtp' ) == 'on' ) echo 'checked'; else echo ''; ?> />
            </div>
            <?php submit_button(); ?>
        </form>
    </div>
    <!-- Tab 4 -->
    <div id="wp-custom-codes__tab-4" class="tab-content" style="display: none;">
        <form method="post" action="options.php">
            <?php
                settings_fields('wp_custom_codes__options_group_4');
                do_settings_sections('wp_custom_codes__options_group_4');
            ?>
            <h2><?php echo __( 'web-admin-ui__tab-4_title', 'wp-custom-codes' ); ?></h2>
            <div>
                <label for="wpcc__og4__o1_add_quality_rating_taxonomy">
                    <?php echo __( 'wpcc__og4__o1-add-quality-rating-taxonomy', 'wp-custom-codes' ); ?>
                </label>
                <input type="checkbox" id="wpcc__og4__o1_add_quality_rating_taxonomy" name="wpcc__og4__o1_add_quality_rating_taxonomy" <?php if( get_option( 'wpcc__og4__o1_add_quality_rating_taxonomy' ) == 'on' ) echo 'checked'; else echo ''; ?> />
            </div>
            <?php submit_button(); ?>
        </form>
    </div>
</div>

<script>
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("nav-tab");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" nav-tab-active", "");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " nav-tab-active";
    }
</script>

<style>
    .tab-content {
        margin-top: 20px;
    }
</style>