<?php
/**
 * Template hiển thị dashboard
 * 
 * @var array $ai_status Trạng thái các AI providers
 * @var array $recent_activity Hoạt động gần đây
 */

if (!defined('ABSPATH')) exit;
?>

<div class="wrap wp-sysmaster-dashboard">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <div class="wp-sysmaster-dashboard-grid">
        <!-- AI Status -->
        <div class="wp-sysmaster-card">
            <h2><?php _e('AI Status', 'wp-sysmaster'); ?></h2>
            <div class="wp-sysmaster-card-content">
                <p>
                    <?php _e('OpenAI Model:', 'wp-sysmaster'); ?>
                    <strong><?php echo esc_html(wp_sysmaster_get_ai_settings('openai_model')); ?></strong>
                </p>
                <p>
                    <?php _e('Embedding Provider:', 'wp-sysmaster'); ?>
                    <strong><?php echo esc_html(wp_sysmaster_get_ai_settings('embedding_provider')); ?></strong>
                </p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="wp-sysmaster-card">
            <h2><?php _e('Quick Actions', 'wp-sysmaster'); ?></h2>
            <div class="wp-sysmaster-card-content">
                <a href="<?php echo admin_url('admin.php?page=wp-sysmaster-ai-settings'); ?>" class="button button-primary">
                    <?php _e('Configure AI Settings', 'wp-sysmaster'); ?>
                </a>
            </div>
        </div>
    </div>
</div> 