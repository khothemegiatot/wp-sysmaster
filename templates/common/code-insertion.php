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
                        <select name="wp_sysmaster_code_settings[php_code_hook]" class="code-hook">
                            <optgroup label="<?php _e('Plugin Hooks', 'wp-sysmaster'); ?>">
                                <option value="plugins_loaded" <?php selected($code_settings['php_code_hook'] ?? 'init', 'plugins_loaded'); ?>>
                                    plugins_loaded (<?php _e('Earliest hook', 'wp-sysmaster'); ?>)
                                </option>
                                <option value="init" <?php selected($code_settings['php_code_hook'] ?? 'init', 'init'); ?>>
                                    init (<?php _e('Basic WordPress loaded', 'wp-sysmaster'); ?>)
                                </option>
                            </optgroup>

                            <optgroup label="<?php _e('Frontend Hooks', 'wp-sysmaster'); ?>">
                                <option value="wp" <?php selected($code_settings['php_code_hook'] ?? 'init', 'wp'); ?>>
                                    wp (<?php _e('Main WordPress loaded', 'wp-sysmaster'); ?>)
                                </option>
                                <option value="wp_head" <?php selected($code_settings['php_code_hook'] ?? 'init', 'wp_head'); ?>>
                                    wp_head
                                </option>
                                <option value="wp_footer" <?php selected($code_settings['php_code_hook'] ?? 'init', 'wp_footer'); ?>>
                                    wp_footer
                                </option>
                                <option value="template_redirect" <?php selected($code_settings['php_code_hook'] ?? 'init', 'template_redirect'); ?>>
                                    template_redirect
                                </option>
                            </optgroup>

                            <optgroup label="<?php _e('Admin Hooks', 'wp-sysmaster'); ?>">
                                <option value="admin_init" <?php selected($code_settings['php_code_hook'] ?? 'init', 'admin_init'); ?>>
                                    admin_init
                                </option>
                                <option value="admin_menu" <?php selected($code_settings['php_code_hook'] ?? 'init', 'admin_menu'); ?>>
                                    admin_menu
                                </option>
                                <option value="admin_head" <?php selected($code_settings['php_code_hook'] ?? 'init', 'admin_head'); ?>>
                                    admin_head
                                </option>
                                <option value="admin_footer" <?php selected($code_settings['php_code_hook'] ?? 'init', 'admin_footer'); ?>>
                                    admin_footer
                                </option>
                            </optgroup>

                            <optgroup label="<?php _e('Content Hooks', 'wp-sysmaster'); ?>">
                                <option value="the_content" <?php selected($code_settings['php_code_hook'] ?? 'init', 'the_content'); ?>>
                                    the_content
                                </option>
                                <option value="the_title" <?php selected($code_settings['php_code_hook'] ?? 'init', 'the_title'); ?>>
                                    the_title
                                </option>
                                <option value="pre_get_posts" <?php selected($code_settings['php_code_hook'] ?? 'init', 'pre_get_posts'); ?>>
                                    pre_get_posts
                                </option>
                            </optgroup>
                        </select>
                    </div>
                    <textarea name="wp_sysmaster_code_settings[php_code]" 
                              rows="15" 
                              class="large-text code"
                              placeholder="// Paste your PHP code here"><?php 
                        echo esc_textarea($code_settings['php_code'] ?? ''); 
                    ?></textarea>
                </div>
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
    min-width: 200px;
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