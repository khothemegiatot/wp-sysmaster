<?php
if (!defined('ABSPATH')) exit;

$code_settings = get_option('wp_sysmaster_code_settings', []);
?>

<div class="wrap">
    <h1><?php _e('Chèn mã', 'wp-sysmaster'); ?></h1>

    <form method="post" action="options.php">
        <?php settings_fields('wp_sysmaster_code_settings'); ?>
        
        <div class="wp-sysmaster-code-editor">
            <!-- Header Scripts -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('Header Scripts', 'wp-sysmaster'); ?></h2>
                <p class="description">
                    <?php _e('Các đoạn mã sẽ được chèn vào thẻ <head> của website.', 'wp-sysmaster'); ?>
                </p>
                <textarea name="wp_sysmaster_code_settings[header_scripts]" 
                          rows="10" 
                          class="large-text code"
                          placeholder="<!-- Paste your header scripts here -->"><?php 
                    echo esc_textarea($code_settings['header_scripts'] ?? ''); 
                ?></textarea>
            </div>

            <!-- Body Scripts (After <body>) -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('Body Scripts (After <body>)', 'wp-sysmaster'); ?></h2>
                <p class="description">
                    <?php _e('Các đoạn mã sẽ được chèn ngay sau thẻ mở <body>.', 'wp-sysmaster'); ?>
                </p>
                <textarea name="wp_sysmaster_code_settings[body_scripts]" 
                          rows="10" 
                          class="large-text code"
                          placeholder="<!-- Paste your body scripts here -->"><?php 
                    echo esc_textarea($code_settings['body_scripts'] ?? ''); 
                ?></textarea>
            </div>

            <!-- Footer Scripts -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('Footer Scripts', 'wp-sysmaster'); ?></h2>
                <p class="description">
                    <?php _e('Các đoạn mã sẽ được chèn trước thẻ đóng </body>.', 'wp-sysmaster'); ?>
                </p>
                <textarea name="wp_sysmaster_code_settings[footer_scripts]" 
                          rows="10" 
                          class="large-text code"
                          placeholder="<!-- Paste your footer scripts here -->"><?php 
                    echo esc_textarea($code_settings['footer_scripts'] ?? ''); 
                ?></textarea>
            </div>

            <!-- Custom CSS -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('Custom CSS', 'wp-sysmaster'); ?></h2>
                <p class="description">
                    <?php _e('CSS tùy chỉnh sẽ được chèn vào thẻ <head>.', 'wp-sysmaster'); ?>
                </p>
                <textarea name="wp_sysmaster_code_settings[custom_css]" 
                          rows="10" 
                          class="large-text code"
                          placeholder="/* Paste your custom CSS here */"><?php 
                    echo esc_textarea($code_settings['custom_css'] ?? ''); 
                ?></textarea>
            </div>
        </div>

        <?php submit_button(__('Lưu thay đổi', 'wp-sysmaster')); ?>
    </form>
</div>

<style>
.wp-sysmaster-code-editor .wp-sysmaster-card {
    background: #fff;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    padding: 20px;
    margin-bottom: 20px;
}

.wp-sysmaster-code-editor .wp-sysmaster-card h2 {
    margin-top: 0;
    padding-bottom: 12px;
    border-bottom: 1px solid #eee;
}

.wp-sysmaster-code-editor textarea {
    font-family: monospace;
    margin-top: 10px;
}
</style> 