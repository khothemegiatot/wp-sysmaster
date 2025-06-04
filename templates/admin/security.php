<?php
if (!defined('ABSPATH')) exit;

$security_settings = get_option('wp_sysmaster_security_settings', []);
?>

<div class="wrap">
    <h1><?php _e('Bảo mật', 'wp-sysmaster'); ?></h1>

    <form method="post" action="options.php">
        <?php settings_fields('wp_sysmaster_security_settings'); ?>
        
        <div class="wp-sysmaster-security-settings">
            <!-- Login Security -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('Bảo mật đăng nhập', 'wp-sysmaster'); ?></h2>
                
                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_security_settings[enable_login_security]" 
                               value="1" 
                               <?php checked($security_settings['enable_login_security'] ?? false); ?>>
                        <?php _e('Kích hoạt bảo mật đăng nhập', 'wp-sysmaster'); ?>
                    </label>
                </p>

                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_security_settings[enable_2fa]" 
                               value="1" 
                               <?php checked($security_settings['enable_2fa'] ?? false); ?>>
                        <?php _e('Kích hoạt xác thực 2 yếu tố', 'wp-sysmaster'); ?>
                    </label>
                </p>

                <p>
                    <label for="login_attempts"><?php _e('Số lần đăng nhập thất bại tối đa', 'wp-sysmaster'); ?></label>
                    <input type="number" 
                           id="login_attempts"
                           name="wp_sysmaster_security_settings[max_login_attempts]" 
                           value="<?php echo esc_attr($security_settings['max_login_attempts'] ?? 5); ?>" 
                           min="1" 
                           max="10" 
                           class="small-text">
                </p>

                <p>
                    <label for="lockout_duration"><?php _e('Thời gian khóa (phút)', 'wp-sysmaster'); ?></label>
                    <input type="number" 
                           id="lockout_duration"
                           name="wp_sysmaster_security_settings[lockout_duration]" 
                           value="<?php echo esc_attr($security_settings['lockout_duration'] ?? 30); ?>" 
                           min="5" 
                           class="small-text">
                </p>
            </div>

            <!-- Firewall -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('Firewall', 'wp-sysmaster'); ?></h2>
                
                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_security_settings[enable_firewall]" 
                               value="1" 
                               <?php checked($security_settings['enable_firewall'] ?? false); ?>>
                        <?php _e('Kích hoạt Firewall', 'wp-sysmaster'); ?>
                    </label>
                </p>

                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_security_settings[block_suspicious_requests]" 
                               value="1" 
                               <?php checked($security_settings['block_suspicious_requests'] ?? false); ?>>
                        <?php _e('Chặn các request đáng ngờ', 'wp-sysmaster'); ?>
                    </label>
                </p>

                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_security_settings[block_fake_bots]" 
                               value="1" 
                               <?php checked($security_settings['block_fake_bots'] ?? false); ?>>
                        <?php _e('Chặn fake bots', 'wp-sysmaster'); ?>
                    </label>
                </p>
            </div>

            <!-- File Protection -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('Bảo vệ file', 'wp-sysmaster'); ?></h2>
                
                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_security_settings[protect_wp_config]" 
                               value="1" 
                               <?php checked($security_settings['protect_wp_config'] ?? false); ?>>
                        <?php _e('Bảo vệ wp-config.php', 'wp-sysmaster'); ?>
                    </label>
                </p>

                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_security_settings[protect_htaccess]" 
                               value="1" 
                               <?php checked($security_settings['protect_htaccess'] ?? false); ?>>
                        <?php _e('Bảo vệ .htaccess', 'wp-sysmaster'); ?>
                    </label>
                </p>

                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_security_settings[disable_file_editor]" 
                               value="1" 
                               <?php checked($security_settings['disable_file_editor'] ?? false); ?>>
                        <?php _e('Tắt File Editor', 'wp-sysmaster'); ?>
                    </label>
                </p>
            </div>

            <!-- Security Headers -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('Security Headers', 'wp-sysmaster'); ?></h2>
                
                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_security_settings[enable_security_headers]" 
                               value="1" 
                               <?php checked($security_settings['enable_security_headers'] ?? false); ?>>
                        <?php _e('Kích hoạt Security Headers', 'wp-sysmaster'); ?>
                    </label>
                </p>

                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_security_settings[enable_hsts]" 
                               value="1" 
                               <?php checked($security_settings['enable_hsts'] ?? false); ?>>
                        <?php _e('Kích hoạt HSTS', 'wp-sysmaster'); ?>
                    </label>
                </p>

                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_security_settings[enable_xss_protection]" 
                               value="1" 
                               <?php checked($security_settings['enable_xss_protection'] ?? false); ?>>
                        <?php _e('Kích hoạt XSS Protection', 'wp-sysmaster'); ?>
                    </label>
                </p>
            </div>
        </div>

        <?php submit_button(__('Lưu thay đổi', 'wp-sysmaster')); ?>
    </form>
</div>

<style>
.wp-sysmaster-security-settings .wp-sysmaster-card {
    background: #fff;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    padding: 20px;
    margin-bottom: 20px;
}

.wp-sysmaster-security-settings .wp-sysmaster-card h2 {
    margin-top: 0;
    padding-bottom: 12px;
    border-bottom: 1px solid #eee;
}

.wp-sysmaster-security-settings label {
    display: block;
    margin-bottom: 8px;
}

.wp-sysmaster-security-settings input[type="number"] {
    width: 80px;
}
</style> 