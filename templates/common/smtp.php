<?php
if (!defined('ABSPATH')) exit;

use WPSysMaster\Common\SMTP;

$smtp = SMTP::getInstance();
$smtp_settings = $smtp->getSettings();
?>

<div class="wrap">
    <h1><?php _e('SMTP Settings', 'wp-sysmaster'); ?></h1>

    <form method="post" action="options.php">
        <?php settings_fields('wp_sysmaster_smtp_settings'); ?>
        
        <div class="wp-sysmaster-smtp-settings">
            <!-- Enable SMTP -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('Enable SMTP', 'wp-sysmaster'); ?></h2>
                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_smtp_settings[enabled]" 
                               <?php checked($smtp_settings['enabled'] ?? '', 'on'); ?>>
                        <?php _e('Kích hoạt SMTP', 'wp-sysmaster'); ?>
                    </label>
                </p>
            </div>

            <!-- SMTP Server Settings -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('SMTP Server', 'wp-sysmaster'); ?></h2>
                
                <!-- Host -->
                <p>
                    <label for="smtp_host"><?php _e('SMTP Host', 'wp-sysmaster'); ?></label>
                    <input type="text" 
                           id="smtp_host"
                           name="wp_sysmaster_smtp_settings[host]" 
                           value="<?php echo esc_attr($smtp_settings['host'] ?? ''); ?>" 
                           class="regular-text">
                    <span class="description">
                        <?php _e('VD: smtp.gmail.com', 'wp-sysmaster'); ?>
                    </span>
                </p>

                <!-- Port -->
                <p>
                    <label for="smtp_port"><?php _e('SMTP Port', 'wp-sysmaster'); ?></label>
                    <input type="number" 
                           id="smtp_port"
                           name="wp_sysmaster_smtp_settings[port]" 
                           value="<?php echo esc_attr($smtp_settings['port'] ?? '587'); ?>" 
                           class="small-text">
                    <span class="description">
                        <?php _e('Example: 587 or 465', 'wp-sysmaster'); ?>
                    </span>
                </p>

                <!-- Encryption -->
                <p>
                    <label for="smtp_encryption"><?php _e('Encryption', 'wp-sysmaster'); ?></label>
                    <select id="smtp_encryption" 
                            name="wp_sysmaster_smtp_settings[encryption]" 
                            class="regular-text">
                        <option value="tls" <?php selected($smtp_settings['encryption'] ?? 'tls', 'tls'); ?>>
                            TLS
                        </option>
                        <option value="ssl" <?php selected($smtp_settings['encryption'] ?? 'tls', 'ssl'); ?>>
                            SSL
                        </option>
                        <option value="none" <?php selected($smtp_settings['encryption'] ?? 'tls', 'none'); ?>>
                            None
                        </option>
                    </select>
                </p>
            </div>

            <!-- Authentication -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('Authentication', 'wp-sysmaster'); ?></h2>
                
                <!-- Username -->
                <p>
                    <label for="smtp_username"><?php _e('SMTP Username', 'wp-sysmaster'); ?></label>
                    <input type="text" 
                           id="smtp_username"
                           name="wp_sysmaster_smtp_settings[username]" 
                           value="<?php echo esc_attr($smtp_settings['username'] ?? ''); ?>" 
                           class="regular-text">
                </p>

                <!-- Password -->
                <p>
                    <label for="smtp_password"><?php _e('SMTP Password', 'wp-sysmaster'); ?></label>
                    <input type="password" 
                           id="smtp_password"
                           name="wp_sysmaster_smtp_settings[password]" 
                           class="regular-text"
                           autocomplete="new-password">
                    <span class="description">
                        <?php _e('Leave empty if you do not want to change the password', 'wp-sysmaster'); ?>
                    </span>
                </p>
            </div>

            <!-- From Email -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('From Email', 'wp-sysmaster'); ?></h2>
                
                <p>
                    <label for="from_email"><?php _e('From Email', 'wp-sysmaster'); ?></label>
                    <input type="email" 
                           id="from_email"
                           name="wp_sysmaster_smtp_settings[from_email]" 
                           value="<?php echo esc_attr($smtp_settings['from_email'] ?? get_option('admin_email')); ?>" 
                           class="regular-text">
                </p>

                <p>
                    <label for="from_name"><?php _e('From Name', 'wp-sysmaster'); ?></label>
                    <input type="text" 
                           id="from_name"
                           name="wp_sysmaster_smtp_settings[from_name]" 
                           value="<?php echo esc_attr($smtp_settings['from_name'] ?? get_option('blogname')); ?>" 
                           class="regular-text">
                </p>
            </div>

            <!-- Test Email -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('Test Email', 'wp-sysmaster'); ?></h2>
                
                <p>
                    <label for="test_email"><?php _e('Send To', 'wp-sysmaster'); ?></label>
                    <input type="email" 
                           id="test_email"
                           name="test_email" 
                           value="<?php echo esc_attr(get_option('admin_email')); ?>" 
                           class="regular-text">
                    <button type="button" 
                            class="button button-secondary" 
                            id="send_test_email">
                        <?php _e('Send Test Email', 'wp-sysmaster'); ?>
                    </button>
                    <span class="spinner" style="float:none;"></span>
                </p>
                <div id="test_email_result"></div>
            </div>
        </div>

        <?php submit_button(__('Lưu thay đổi', 'wp-sysmaster')); ?>
    </form>
</div>

<style>
.wp-sysmaster-smtp-settings .wp-sysmaster-card {
    background: #fff;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    padding: 20px;
    margin-bottom: 20px;
}

.wp-sysmaster-smtp-settings .wp-sysmaster-card h2 {
    margin-top: 0;
    padding-bottom: 12px;
    border-bottom: 1px solid #eee;
}

.wp-sysmaster-smtp-settings label {
    display: inline-block;
    min-width: 120px;
    font-weight: 600;
}

.wp-sysmaster-smtp-settings .description {
    margin-left: 8px;
    color: #666;
}

#test_email_result {
    margin-top: 10px;
    padding: 10px;
    display: none;
}

#test_email_result.success {
    background: #dff0d8;
    border: 1px solid #d6e9c6;
    color: #3c763d;
}

#test_email_result.error {
    background: #f2dede;
    border: 1px solid #ebccd1;
    color: #a94442;
}

.spinner {
    visibility: hidden;
    margin-left: 5px;
}

.spinner.is-active {
    visibility: visible;
}
</style>

<script>
jQuery(document).ready(function($) {
    $('#send_test_email').on('click', function() {
        var $button = $(this);
        var $spinner = $button.next('.spinner');
        var $result = $('#test_email_result');
        var testEmail = $('#test_email').val();
        
        if (!testEmail) {
            alert('<?php _e('Please enter a test email address.', 'wp-sysmaster'); ?>');
            return;
        }
        
        $button.prop('disabled', true);
        $spinner.addClass('is-active');
        $result.removeClass('success error').hide();
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'wp_sysmaster_test_smtp',
                email: testEmail,
                nonce: '<?php echo wp_create_nonce('wp_sysmaster_test_smtp'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    $result.addClass('success')
                           .html(response.data)
                           .show();
                } else {
                    $result.addClass('error')
                           .html(response.data)
                           .show();
                }
            },
            error: function(xhr, status, error) {
                $result.addClass('error')
                       .html('<?php _e('An error occurred while sending the test email.', 'wp-sysmaster'); ?> (' + error + ')')
                       .show();
            },
            complete: function() {
                $button.prop('disabled', false);
                $spinner.removeClass('is-active');
            }
        });
    });
});
</script> 