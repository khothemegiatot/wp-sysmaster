<?php
if (!defined('ABSPATH')) exit;

$settings = get_option('wp_sysmaster_settings', []);
?>

<div class="wrap">
    <h1><?php _e('Cài đặt', 'wp-sysmaster'); ?></h1>

    <form method="post" action="options.php">
        <?php settings_fields('wp_sysmaster_settings'); ?>
        
        <div class="wp-sysmaster-settings">
            <!-- General Settings -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('Cài đặt chung', 'wp-sysmaster'); ?></h2>
                
                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_settings[enable_debug_mode]" 
                               value="1" 
                               <?php checked($settings['enable_debug_mode'] ?? false); ?>>
                        <?php _e('Kích hoạt chế độ Debug', 'wp-sysmaster'); ?>
                    </label>
                </p>

                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_settings[enable_error_logging]" 
                               value="1" 
                               <?php checked($settings['enable_error_logging'] ?? false); ?>>
                        <?php _e('Kích hoạt Error Logging', 'wp-sysmaster'); ?>
                    </label>
                </p>
            </div>

            <!-- API Settings -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('API Settings', 'wp-sysmaster'); ?></h2>
                
                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_settings[enable_rest_api]" 
                               value="1" 
                               <?php checked($settings['enable_rest_api'] ?? false); ?>>
                        <?php _e('Kích hoạt REST API', 'wp-sysmaster'); ?>
                    </label>
                </p>

                <p>
                    <label for="api_key"><?php _e('API Key', 'wp-sysmaster'); ?></label>
                    <input type="text" 
                           id="api_key"
                           name="wp_sysmaster_settings[api_key]" 
                           value="<?php echo esc_attr($settings['api_key'] ?? ''); ?>" 
                           class="regular-text">
                    <button type="button" 
                            class="button button-secondary" 
                            id="generate_api_key">
                        <?php _e('Tạo API Key mới', 'wp-sysmaster'); ?>
                    </button>
                </p>
            </div>

            <!-- Email Settings -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('Email Settings', 'wp-sysmaster'); ?></h2>
                
                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_settings[enable_email_notifications]" 
                               value="1" 
                               <?php checked($settings['enable_email_notifications'] ?? false); ?>>
                        <?php _e('Kích hoạt thông báo qua email', 'wp-sysmaster'); ?>
                    </label>
                </p>

                <p>
                    <label for="notification_email"><?php _e('Email nhận thông báo', 'wp-sysmaster'); ?></label>
                    <input type="email" 
                           id="notification_email"
                           name="wp_sysmaster_settings[notification_email]" 
                           value="<?php echo esc_attr($settings['notification_email'] ?? get_option('admin_email')); ?>" 
                           class="regular-text">
                </p>
            </div>

            <!-- License -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('License', 'wp-sysmaster'); ?></h2>
                
                <p>
                    <label for="license_key"><?php _e('License Key', 'wp-sysmaster'); ?></label>
                    <input type="text" 
                           id="license_key"
                           name="wp_sysmaster_settings[license_key]" 
                           value="<?php echo esc_attr($settings['license_key'] ?? ''); ?>" 
                           class="regular-text">
                    <button type="button" 
                            class="button button-secondary" 
                            id="verify_license">
                        <?php _e('Xác thực License', 'wp-sysmaster'); ?>
                    </button>
                </p>
                <div id="license_status"></div>
            </div>
        </div>

        <?php submit_button(__('Lưu thay đổi', 'wp-sysmaster')); ?>
    </form>
</div>

<style>
.wp-sysmaster-settings .wp-sysmaster-card {
    background: #fff;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    padding: 20px;
    margin-bottom: 20px;
}

.wp-sysmaster-settings .wp-sysmaster-card h2 {
    margin-top: 0;
    padding-bottom: 12px;
    border-bottom: 1px solid #eee;
}

.wp-sysmaster-settings label {
    display: block;
    margin-bottom: 8px;
}

.wp-sysmaster-settings input[type="text"],
.wp-sysmaster-settings input[type="email"] {
    margin-bottom: 8px;
}

#license_status {
    margin-top: 10px;
    padding: 10px;
    display: none;
}

#license_status.valid {
    background: #dff0d8;
    border: 1px solid #d6e9c6;
    color: #3c763d;
}

#license_status.invalid {
    background: #f2dede;
    border: 1px solid #ebccd1;
    color: #a94442;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Generate API Key
    $('#generate_api_key').on('click', function() {
        var $button = $(this);
        var $input = $('#api_key');
        
        $button.prop('disabled', true);
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'wp_sysmaster_generate_api_key',
                nonce: '<?php echo wp_create_nonce('wp_sysmaster_generate_api_key'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    $input.val(response.data);
                }
            },
            complete: function() {
                $button.prop('disabled', false);
            }
        });
    });

    // Verify License
    $('#verify_license').on('click', function() {
        var $button = $(this);
        var $status = $('#license_status');
        var licenseKey = $('#license_key').val();
        
        if (!licenseKey) {
            alert('<?php _e('Please enter a license key.', 'wp-sysmaster'); ?>');
            return;
        }
        
        $button.prop('disabled', true);
        $status.removeClass('valid invalid').hide();
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'wp_sysmaster_verify_license',
                license: licenseKey,
                nonce: '<?php echo wp_create_nonce('wp_sysmaster_verify_license'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    $status.addClass('valid')
                           .html('<?php _e('License is valid!', 'wp-sysmaster'); ?>')
                           .show();
                } else {
                    $status.addClass('invalid')
                           .html(response.data)
                           .show();
                }
            },
            error: function() {
                $status.addClass('invalid')
                       .html('<?php _e('An error occurred while verifying the license.', 'wp-sysmaster'); ?>')
                       .show();
            },
            complete: function() {
                $button.prop('disabled', false);
            }
        });
    });
});
</script> 