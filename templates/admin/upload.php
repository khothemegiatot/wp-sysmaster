<?php
if (!defined('ABSPATH')) exit;

use WPSysMaster\Admin\Upload;

$upload = Upload::getInstance();
$settings = $upload->getSettings();
?>

<div class="wrap">
    <h1><?php _e('Upload Settings', 'wp-sysmaster'); ?></h1>

    <form method="post" action="options.php" id="upload-settings-form">
        <?php settings_fields('wp_sysmaster_upload_settings'); ?>
        
        <div class="wp-sysmaster-upload-settings">
            <!-- Enable Upload Features -->
            <div class="wp-sysmaster-card">
                <h2><?php _e('Upload Features', 'wp-sysmaster'); ?></h2>
                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_upload_settings[enabled]" 
                               <?php checked($settings['enabled'] ?? '', 'on'); ?>>
                        <?php _e('Enable custom upload features', 'wp-sysmaster'); ?>
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" 
                               name="wp_sysmaster_upload_settings[rename_files]" 
                               <?php checked($settings['rename_files'] ?? '', 'on'); ?>>
                        <?php _e('Auto rename uploaded files', 'wp-sysmaster'); ?>
                    </label>
                    <span class="description">
                        <?php _e('Files will be renamed using format: timestamp-random-originalname', 'wp-sysmaster'); ?>
                    </span>
                </p>
            </div>

            <!-- MIME Types -->
            <div class="wp-sysmaster-card">
                <h2>
                    <?php _e('Custom MIME Types', 'wp-sysmaster'); ?>
                    <button type="button" class="add-mime-type button button-secondary">
                        <?php _e('Add MIME Type', 'wp-sysmaster'); ?>
                    </button>
                </h2>

                <div class="mime-types-list">
                    <?php if (!empty($settings['mime_types'])): ?>
                        <?php foreach ($settings['mime_types'] as $index => $mime): ?>
                            <div class="mime-type-row">
                                <input type="text" 
                                       name="wp_sysmaster_upload_settings[mime_types][<?php echo $index; ?>][extension]" 
                                       value="<?php echo esc_attr($mime['extension']); ?>"
                                       placeholder="<?php _e('File extension (e.g. pdf)', 'wp-sysmaster'); ?>"
                                       class="mime-extension">
                                
                                <input type="text" 
                                       name="wp_sysmaster_upload_settings[mime_types][<?php echo $index; ?>][type]" 
                                       value="<?php echo esc_attr($mime['type']); ?>"
                                       placeholder="<?php _e('MIME type (e.g. application/pdf)', 'wp-sysmaster'); ?>"
                                       class="mime-type">
                                
                                <button type="button" class="button button-link-delete remove-mime-type">
                                    <span class="dashicons dashicons-trash"></span>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Template for new MIME type row -->
                <script type="text/template" id="mime-type-template">
                    <div class="mime-type-row">
                        <input type="text" 
                               name="wp_sysmaster_upload_settings[mime_types][{{index}}][extension]" 
                               placeholder="<?php _e('File extension (e.g. pdf)', 'wp-sysmaster'); ?>"
                               class="mime-extension">
                        
                        <input type="text" 
                               name="wp_sysmaster_upload_settings[mime_types][{{index}}][type]" 
                               placeholder="<?php _e('MIME type (e.g. application/pdf)', 'wp-sysmaster'); ?>"
                               class="mime-type">
                        
                        <button type="button" class="button button-link-delete remove-mime-type">
                            <span class="dashicons dashicons-trash"></span>
                        </button>
                    </div>
                </script>
            </div>
        </div>

        <?php submit_button(__('Save Changes', 'wp-sysmaster')); ?>
    </form>
</div>

<style>
.wp-sysmaster-upload-settings .wp-sysmaster-card {
    background: #fff;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    padding: 20px;
    margin-bottom: 20px;
}

.wp-sysmaster-upload-settings .wp-sysmaster-card h2 {
    margin-top: 0;
    padding-bottom: 12px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.wp-sysmaster-upload-settings label {
    display: inline-block;
    min-width: 120px;
    font-weight: 600;
}

.wp-sysmaster-upload-settings .description {
    margin-left: 8px;
    color: #666;
}

.mime-type-row {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    gap: 10px;
}

.mime-type-row input {
    flex: 1;
}

.mime-type-row .mime-extension {
    width: 150px;
    flex: 0 0 150px;
}

.mime-type-row .button-link-delete {
    padding: 0;
    color: #b32d2e;
}

.mime-type-row .button-link-delete:hover {
    color: #dc3232;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Add new MIME type
    $('.add-mime-type').on('click', function() {
        var template = $('#mime-type-template').html();
        var index = $('.mime-type-row').length;
        template = template.replace(/{{index}}/g, index);
        $('.mime-types-list').append(template);
    });

    // Remove MIME type
    $(document).on('click', '.remove-mime-type', function() {
        $(this).closest('.mime-type-row').remove();
        reindexMimeTypes();
    });

    // Reindex MIME types after removal
    function reindexMimeTypes() {
        $('.mime-type-row').each(function(index) {
            $(this).find('input').each(function() {
                var name = $(this).attr('name');
                name = name.replace(/\[\d+\]/, '[' + index + ']');
                $(this).attr('name', name);
            });
        });
    }

    // Form validation
    $('#upload-settings-form').on('submit', function(e) {
        var valid = true;
        var firstError = null;

        $('.mime-type-row').each(function() {
            var $row = $(this);
            var extension = $row.find('.mime-extension').val();
            var mimeType = $row.find('.mime-type').val();

            if ((extension && !mimeType) || (!extension && mimeType)) {
                valid = false;
                if (!firstError) firstError = $row;
                $row.find('input').css('border-color', '#dc3232');
            } else {
                $row.find('input').css('border-color', '');
            }
        });

        if (!valid) {
            e.preventDefault();
            alert('<?php _e('Please fill in both extension and MIME type for each row.', 'wp-sysmaster'); ?>');
            if (firstError) {
                $('html, body').animate({
                    scrollTop: firstError.offset().top - 100
                }, 500);
            }
        }
    });
});
</script> 