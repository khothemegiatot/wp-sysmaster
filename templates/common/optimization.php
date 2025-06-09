<?php
if (!defined('ABSPATH')) exit;

$optimization_settings = get_option('wp_sysmaster_optimization_settings', []);
?>

<div class="wrap">
    <h1><?php _e('Tối ưu', 'wp-sysmaster'); ?></h1>

    <form method="post" action="options.php">
        <?php settings_fields('wp_sysmaster_optimization_settings'); ?>
        
        <div class="wp-sysmaster-optimization-settings">
            <!-- Cache -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('Cache', 'wp-sysmaster'); ?></h2>
                
                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_optimization_settings[enable_page_cache]" 
                               value="1" 
                               <?php checked($optimization_settings['enable_page_cache'] ?? false); ?>>
                        <?php _e('Kích hoạt Page Cache', 'wp-sysmaster'); ?>
                    </label>
                </p>

                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_optimization_settings[enable_browser_cache]" 
                               value="1" 
                               <?php checked($optimization_settings['enable_browser_cache'] ?? false); ?>>
                        <?php _e('Kích hoạt Browser Cache', 'wp-sysmaster'); ?>
                    </label>
                </p>

                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_optimization_settings[enable_object_cache]" 
                               value="1" 
                               <?php checked($optimization_settings['enable_object_cache'] ?? false); ?>>
                        <?php _e('Kích hoạt Object Cache', 'wp-sysmaster'); ?>
                    </label>
                </p>
            </div>

            <!-- Minification -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('Minification', 'wp-sysmaster'); ?></h2>
                
                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_optimization_settings[minify_html]" 
                               value="1" 
                               <?php checked($optimization_settings['minify_html'] ?? false); ?>>
                        <?php _e('Minify HTML', 'wp-sysmaster'); ?>
                    </label>
                </p>

                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_optimization_settings[minify_css]" 
                               value="1" 
                               <?php checked($optimization_settings['minify_css'] ?? false); ?>>
                        <?php _e('Minify CSS', 'wp-sysmaster'); ?>
                    </label>
                </p>

                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_optimization_settings[minify_js]" 
                               value="1" 
                               <?php checked($optimization_settings['minify_js'] ?? false); ?>>
                        <?php _e('Minify JavaScript', 'wp-sysmaster'); ?>
                    </label>
                </p>
            </div>

            <!-- Database -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('Database', 'wp-sysmaster'); ?></h2>
                
                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_optimization_settings[optimize_database]" 
                               value="1" 
                               <?php checked($optimization_settings['optimize_database'] ?? false); ?>>
                        <?php _e('Tự động tối ưu Database', 'wp-sysmaster'); ?>
                    </label>
                </p>

                <p>
                    <button type="button" 
                            class="button button-secondary" 
                            id="optimize_database_now">
                        <?php _e('Tối ưu Database ngay', 'wp-sysmaster'); ?>
                    </button>
                </p>
                <div id="database_optimization_result"></div>
            </div>

            <!-- Media -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('Media', 'wp-sysmaster'); ?></h2>
                
                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_optimization_settings[lazy_load_images]" 
                               value="1" 
                               <?php checked($optimization_settings['lazy_load_images'] ?? false); ?>>
                        <?php _e('Lazy Load Images', 'wp-sysmaster'); ?>
                    </label>
                </p>

                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_optimization_settings[compress_images]" 
                               value="1" 
                               <?php checked($optimization_settings['compress_images'] ?? false); ?>>
                        <?php _e('Tự động nén ảnh khi upload', 'wp-sysmaster'); ?>
                    </label>
                </p>
            </div>
        </div>

        <?php submit_button(__('Lưu thay đổi', 'wp-sysmaster')); ?>
    </form>
</div>

<style>
.wp-sysmaster-optimization-settings .wp-sysmaster-card {
    background: #fff;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    padding: 20px;
    margin-bottom: 20px;
}

.wp-sysmaster-optimization-settings .wp-sysmaster-card h2 {
    margin-top: 0;
    padding-bottom: 12px;
    border-bottom: 1px solid #eee;
}

.wp-sysmaster-optimization-settings label {
    display: block;
    margin-bottom: 8px;
}

#database_optimization_result {
    margin-top: 10px;
    padding: 10px;
    display: none;
}

#database_optimization_result.success {
    background: #dff0d8;
    border: 1px solid #d6e9c6;
    color: #3c763d;
}

#database_optimization_result.error {
    background: #f2dede;
    border: 1px solid #ebccd1;
    color: #a94442;
}
</style>

<script>
jQuery(document).ready(function($) {
    $('#optimize_database_now').on('click', function() {
        var $button = $(this);
        var $result = $('#database_optimization_result');
        
        $button.prop('disabled', true);
        $result.removeClass('success error').hide();
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'wp_sysmaster_optimize_database',
                nonce: '<?php echo wp_create_nonce('wp_sysmaster_optimize_database'); ?>'
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
                       .html('<?php _e('An error occurred while optimizing the database.', 'wp-sysmaster'); ?>')
                       .show();
            },
            complete: function() {
                $button.prop('disabled', false);
            }
        });
    });
});
</script> 