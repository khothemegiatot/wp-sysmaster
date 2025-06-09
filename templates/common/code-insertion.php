<?php
if (!defined('ABSPATH')) exit;

$code_settings = get_option('wp_sysmaster_code_settings', []);
$active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'php';
?>

<div class="wrap">
    <h1><?php _e('Insert Code', 'wp-sysmaster'); ?></h1>

    <h2 class="nav-tab-wrapper">
        <a href="?page=wp-sysmaster-code-injection&tab=php" class="nav-tab <?php echo $active_tab == 'php' ? 'nav-tab-active' : ''; ?>">
            <?php _e('PHP Code', 'wp-sysmaster'); ?>
        </a>
        <a href="?page=wp-sysmaster-code-injection&tab=header" class="nav-tab <?php echo $active_tab == 'header' ? 'nav-tab-active' : ''; ?>">
            <?php _e('Header Scripts', 'wp-sysmaster'); ?>
        </a>
        <a href="?page=wp-sysmaster-code-injection&tab=body" class="nav-tab <?php echo $active_tab == 'body' ? 'nav-tab-active' : ''; ?>">
            <?php _e('Body Scripts', 'wp-sysmaster'); ?>
        </a>
        <a href="?page=wp-sysmaster-code-injection&tab=footer" class="nav-tab <?php echo $active_tab == 'footer' ? 'nav-tab-active' : ''; ?>">
            <?php _e('Footer Scripts', 'wp-sysmaster'); ?>
        </a>
        <a href="?page=wp-sysmaster-code-injection&tab=css" class="nav-tab <?php echo $active_tab == 'css' ? 'nav-tab-active' : ''; ?>">
            <?php _e('Custom CSS', 'wp-sysmaster'); ?>
        </a>
    </h2>

    <form method="post" action="options.php">
        <?php settings_fields('wp_sysmaster_code_settings'); ?>
        
        <div class="wp-sysmaster-code-editor">
            <?php if ($active_tab == 'php'): ?>
            <!-- PHP Code -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('PHP Code', 'wp-sysmaster'); ?></h2>
                <p class="description">
                    <?php _e('PHP code will be executed. No need to use <?php ?> tags.', 'wp-sysmaster'); ?>
                </p>
                <div class="php-code-editor">
                    <div class="editor-actions">
                        <select name="wp_sysmaster_code_settings[php_code_type]" class="code-type">
                            <option value="action" <?php selected($code_settings['php_code_type'] ?? 'action', 'action'); ?>>
                                <?php _e('Action Hook', 'wp-sysmaster'); ?>
                            </option>
                            <option value="function" <?php selected($code_settings['php_code_type'] ?? 'action', 'function'); ?>>
                                <?php _e('Function', 'wp-sysmaster'); ?>
                            </option>
                            <option value="shortcode" <?php selected($code_settings['php_code_type'] ?? 'action', 'shortcode'); ?>>
                                <?php _e('Shortcode', 'wp-sysmaster'); ?>
                            </option>
                        </select>
                        <select name="wp_sysmaster_code_settings[php_code_hook]" class="code-hook">
                            <option value="init" <?php selected($code_settings['php_code_hook'] ?? 'init', 'init'); ?>>
                                init
                            </option>
                            <option value="wp_head" <?php selected($code_settings['php_code_hook'] ?? 'init', 'wp_head'); ?>>
                                wp_head
                            </option>
                            <option value="wp_footer" <?php selected($code_settings['php_code_hook'] ?? 'init', 'wp_footer'); ?>>
                                wp_footer
                            </option>
                            <option value="admin_init" <?php selected($code_settings['php_code_hook'] ?? 'init', 'admin_init'); ?>>
                                admin_init
                            </option>
                            <option value="admin_head" <?php selected($code_settings['php_code_hook'] ?? 'init', 'admin_head'); ?>>
                                admin_head
                            </option>
                            <option value="admin_footer" <?php selected($code_settings['php_code_hook'] ?? 'init', 'admin_footer'); ?>>
                                admin_footer
                            </option>
                        </select>
                        <button type="button" 
                                class="button button-secondary" 
                                id="test_php_code">
                            <?php _e('Test PHP Code', 'wp-sysmaster'); ?>
                        </button>
                    </div>
                    <textarea name="wp_sysmaster_code_settings[php_code]" 
                              rows="15" 
                              class="large-text code"
                              placeholder="// Paste your PHP code here"><?php 
                        echo esc_textarea($code_settings['php_code'] ?? ''); 
                    ?></textarea>
                </div>
                <div id="php_code_result"></div>
            </div>

            <?php elseif ($active_tab == 'header'): ?>
            <!-- Header Scripts -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('Header Scripts', 'wp-sysmaster'); ?></h2>
                <p class="description">
                    <?php _e('The code will be inserted into the <head> tag of the website.', 'wp-sysmaster'); ?>
                </p>
                <textarea name="wp_sysmaster_code_settings[header_scripts]" 
                          rows="15" 
                          class="large-text code"
                          placeholder="<!-- Paste your header scripts here -->"><?php 
                    echo esc_textarea($code_settings['header_scripts'] ?? ''); 
                ?></textarea>
            </div>

            <?php elseif ($active_tab == 'body'): ?>
            <!-- Body Scripts -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('Body Scripts (After <body>)', 'wp-sysmaster'); ?></h2>
                <p class="description">
                    <?php _e('The code will be inserted after the opening <body> tag.', 'wp-sysmaster'); ?>
                </p>
                <textarea name="wp_sysmaster_code_settings[body_scripts]" 
                          rows="15" 
                          class="large-text code"
                          placeholder="<!-- Paste your body scripts here -->"><?php 
                    echo esc_textarea($code_settings['body_scripts'] ?? ''); 
                ?></textarea>
            </div>

            <?php elseif ($active_tab == 'footer'): ?>
            <!-- Footer Scripts -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('Footer Scripts', 'wp-sysmaster'); ?></h2>
                <p class="description">
                    <?php _e('The code will be inserted before the closing </body> tag.', 'wp-sysmaster'); ?>
                </p>
                <textarea name="wp_sysmaster_code_settings[footer_scripts]" 
                          rows="15" 
                          class="large-text code"
                          placeholder="<!-- Paste your footer scripts here -->"><?php 
                    echo esc_textarea($code_settings['footer_scripts'] ?? ''); 
                ?></textarea>
            </div>

            <?php elseif ($active_tab == 'css'): ?>
            <!-- Custom CSS -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('Custom CSS', 'wp-sysmaster'); ?></h2>
                <p class="description">
                    <?php _e('Custom CSS will be inserted into the <head> tag.', 'wp-sysmaster'); ?>
                </p>
                <textarea name="wp_sysmaster_code_settings[custom_css]" 
                          rows="15" 
                          class="large-text code"
                          placeholder="/* Paste your custom CSS here */"><?php 
                    echo esc_textarea($code_settings['custom_css'] ?? ''); 
                ?></textarea>
            </div>
            <?php endif; ?>
        </div>

        <?php submit_button(__('Save Changes', 'wp-sysmaster')); ?>
    </form>
</div>

<style>
.wp-sysmaster-code-editor .wp-sysmaster-card {
    background: #fff;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    padding: 20px;
    margin-top: 20px;
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

.php-code-editor .editor-actions {
    margin-bottom: 10px;
}

.php-code-editor .editor-actions select {
    margin-right: 10px;
}

.code-type {
    min-width: 120px;
}

.code-hook {
    min-width: 150px;
}

#php_code_result {
    margin-top: 10px;
    padding: 10px;
    display: none;
}

#php_code_result.success {
    background: #dff0d8;
    border: 1px solid #d6e9c6;
    color: #3c763d;
}

#php_code_result.error {
    background: #f2dede;
    border: 1px solid #ebccd1;
    color: #a94442;
    white-space: pre-wrap;
}

.code-hook {
    display: none;
}

.code-type[value="action"] ~ .code-hook {
    display: inline-block;
}

/* Tab styles */
.nav-tab-wrapper {
    margin-bottom: 20px;
}

.nav-tab {
    font-size: 14px;
}

.nav-tab-active {
    background: #fff;
    border-bottom: 1px solid #fff;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Hiển thị/ẩn dropdown hook dựa vào loại code
    $('select[name="wp_sysmaster_code_settings[php_code_type]"]').on('change', function() {
        if ($(this).val() === 'action') {
            $('.code-hook').show();
        } else {
            $('.code-hook').hide();
        }
    }).trigger('change');

    // Test PHP code
    $('#test_php_code').on('click', function() {
        var $button = $(this);
        var $result = $('#php_code_result');
        var phpCode = $('textarea[name="wp_sysmaster_code_settings[php_code]"]').val();
        var type = $('select[name="wp_sysmaster_code_settings[php_code_type]"]').val();
        var hook = $('select[name="wp_sysmaster_code_settings[php_code_hook]"]').val();
        
        if (!phpCode) {
            alert('<?php _e('Please enter PHP code to test.', 'wp-sysmaster'); ?>');
            return;
        }
        
        $button.prop('disabled', true);
        $result.removeClass('success error').hide();
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'wp_custom_codes_test_php',
                code: phpCode,
                type: type,
                hook: hook,
                nonce: '<?php echo wp_create_nonce('wp_custom_codes_test_php'); ?>'
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
            error: function() {
                $result.addClass('error')
                       .html('<?php _e('An error occurred while testing the PHP code.', 'wp-sysmaster'); ?>')
                       .show();
            },
            complete: function() {
                $button.prop('disabled', false);
            }
        });
    });
});
</script> 