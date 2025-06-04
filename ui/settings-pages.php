<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Hiển thị trang cài đặt tải lên
 */
function wp_sysmaster_upload_page() {
    global $wp_sysmaster_000__option_name;
    $option_mgr = new OptionMgr($wp_sysmaster_000__option_name);

    if (isset($_POST['submit'])) {
        check_admin_referer('wp_sysmaster_upload_nonce');
        
        $rename_uploaded_file_enabled = isset($_POST['rename_uploaded_file_enabled']) ? 'on' : 'off';
        $custom_upload_mimes_enabled = isset($_POST['custom_upload_mimes_enabled']) ? 'on' : 'off';

        $option_mgr->update_value(array(
            'rename_uploaded_file_enabled' => $rename_uploaded_file_enabled,
            'custom_upload_mimes_enabled' => $custom_upload_mimes_enabled
        ));
        
        $option_mgr->save_option();
        echo '<div class="updated"><p>' . esc_html__('Cài đặt đã được lưu.', 'wp-sysmaster') . '</p></div>';
    }

    $rename_uploaded_file_enabled = $option_mgr->get_value('rename_uploaded_file_enabled');
    $custom_upload_mimes_enabled = $option_mgr->get_value('custom_upload_mimes_enabled');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Cài đặt tải lên', 'wp-sysmaster'); ?></h1>
        <form method="post" action="">
            <?php wp_nonce_field('wp_sysmaster_upload_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php echo esc_html__('Đổi tên file tải lên', 'wp-sysmaster'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="rename_uploaded_file_enabled" 
                                   <?php checked($rename_uploaded_file_enabled, 'on'); ?>>
                            <?php echo esc_html__('Tự động đổi tên file khi tải lên', 'wp-sysmaster'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Tùy chỉnh MIME types', 'wp-sysmaster'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="custom_upload_mimes_enabled" 
                                   <?php checked($custom_upload_mimes_enabled, 'on'); ?>>
                            <?php echo esc_html__('Cho phép tùy chỉnh các loại file được phép tải lên', 'wp-sysmaster'); ?>
                        </label>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/**
 * Hiển thị trang cài đặt Plugin & Theme
 */
function wp_sysmaster_installation_page() {
    global $wp_sysmaster_000__option_name;
    $option_mgr = new OptionMgr($wp_sysmaster_000__option_name);

    if (isset($_POST['submit'])) {
        check_admin_referer('wp_sysmaster_installation_nonce');
        
        $plugin_theme_installation_disabled = isset($_POST['plugin_theme_installation_disabled']) ? 'on' : 'off';

        $option_mgr->update_value(array(
            'plugin_theme_installation_disabled' => $plugin_theme_installation_disabled
        ));
        
        $option_mgr->save_option();
        echo '<div class="updated"><p>' . esc_html__('Cài đặt đã được lưu.', 'wp-sysmaster') . '</p></div>';
    }

    $plugin_theme_installation_disabled = $option_mgr->get_value('plugin_theme_installation_disabled');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Cài đặt Plugin & Theme', 'wp-sysmaster'); ?></h1>
        <form method="post" action="">
            <?php wp_nonce_field('wp_sysmaster_installation_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php echo esc_html__('Vô hiệu hóa cài đặt', 'wp-sysmaster'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="plugin_theme_installation_disabled" 
                                   <?php checked($plugin_theme_installation_disabled, 'on'); ?>>
                            <?php echo esc_html__('Chặn cài đặt plugin và theme mới', 'wp-sysmaster'); ?>
                        </label>
                        <p class="description">
                            <?php echo esc_html__('Khi bật, người dùng sẽ không thể cài đặt, cập nhật hoặc xóa plugins và themes.', 'wp-sysmaster'); ?>
                        </p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/**
 * Hiển thị trang cài đặt SMTP
 */
function wp_sysmaster_smtp_page() {
    global $wp_sysmaster_000__option_name;
    $option_mgr = new OptionMgr($wp_sysmaster_000__option_name);

    if (isset($_POST['submit'])) {
        check_admin_referer('wp_sysmaster_smtp_nonce');
        
        $smtp_enabled = isset($_POST['enable_smtp']) ? 'on' : 'off';

        $option_mgr->update_value(array(
            'smtp_enabled' => $smtp_enabled
        ));
        
        $option_mgr->save_option();
        echo '<div class="updated"><p>' . esc_html__('Cài đặt đã được lưu.', 'wp-sysmaster') . '</p></div>';
    }

    $smtp_enabled = $option_mgr->get_value('smtp_enabled');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Cài đặt SMTP', 'wp-sysmaster'); ?></h1>
        <form method="post" action="">
            <?php wp_nonce_field('wp_sysmaster_smtp_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php echo esc_html__('Kích hoạt SMTP', 'wp-sysmaster'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="enable_smtp" 
                                   <?php checked($smtp_enabled, 'on'); ?>>
                            <?php echo esc_html__('Sử dụng SMTP để gửi email', 'wp-sysmaster'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Máy chủ SMTP', 'wp-sysmaster'); ?></th>
                    <td>
                        <input type="text" name="smtp_host" class="regular-text" 
                               value="<?php echo esc_attr(defined('SMTP_HOST') ? SMTP_HOST : ''); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Cổng SMTP', 'wp-sysmaster'); ?></th>
                    <td>
                        <input type="number" name="smtp_port" class="small-text" 
                               value="<?php echo esc_attr(defined('SMTP_PORT') ? SMTP_PORT : ''); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Tài khoản SMTP', 'wp-sysmaster'); ?></th>
                    <td>
                        <input type="text" name="smtp_user" class="regular-text" 
                               value="<?php echo esc_attr(defined('SMTP_USER') ? SMTP_USER : ''); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Mật khẩu SMTP', 'wp-sysmaster'); ?></th>
                    <td>
                        <input type="password" name="smtp_pass" class="regular-text" 
                               value="<?php echo esc_attr(defined('SMTP_PASS') ? SMTP_PASS : ''); ?>">
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
} 