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

    <!-- Plugin Introduction -->
    <div class="wp-sysmaster-dashboard-grid">
        <div class="wp-sysmaster-card wp-sysmaster-intro">
            <h2><?php _e('Introduction to WP SysMaster', 'wp-sysmaster'); ?></h2>
            <div class="wp-sysmaster-card-content">
                <p>
                    <?php _e('WP SysMaster is a comprehensive plugin that helps you manage and optimize your WordPress website. It provides the following features:', 'wp-sysmaster'); ?>
                </p>
                <ul>
                    <!-- <li><?php _e('Integrate AI to automate tasks', 'wp-sysmaster'); ?></li> -->
                    <li><?php _e('Configure SMTP to send emails', 'wp-sysmaster'); ?></li>
                    <!-- <li><?php _e('Optimize website performance', 'wp-sysmaster'); ?></li>
                    <li><?php _e('Enhance website security', 'wp-sysmaster'); ?></li> -->
                    <li><?php _e('Manage file uploads', 'wp-sysmaster'); ?></li>
                </ul>
            </div>
        </div>

        <div class="wp-sysmaster-card wp-sysmaster-usage">
            <h2><?php _e('Usage guide', 'wp-sysmaster'); ?></h2>
            <div class="wp-sysmaster-card-content">
                <ol>
                    <!-- <li><?php _e('Configure basic settings in the "Settings" section', 'wp-sysmaster'); ?></li> -->
                    <!-- <li><?php _e('Configure AI settings in the "AI Settings" section', 'wp-sysmaster'); ?></li> -->
                    <!-- <li><?php _e('Configure SMTP to ensure emails are sent', 'wp-sysmaster'); ?></li> -->
                    <!-- <li><?php _e('Optimize website through the "Optimization" section', 'wp-sysmaster'); ?></li> -->
                    <!-- <li><?php _e('Check and enhance security in the "Security" section', 'wp-sysmaster'); ?></li> -->
                </ol>
            </div>
        </div>
    </div>

    <!-- Author & Support -->
    <div class="wp-sysmaster-dashboard-grid">
        <div class="wp-sysmaster-card wp-sysmaster-author">
            <h2><?php _e('Author & Support', 'wp-sysmaster'); ?></h2>
            <div class="wp-sysmaster-card-content">
                <p>
                    <?php _e('This plugin is developed by <strong>Phan Xuân Chánh, khothemegiatot</strong>.', 'wp-sysmaster'); ?>
                </p>
                <p>
                    <?php _e('Contact us:', 'wp-sysmaster'); ?>
                    <ul>
                        <li>Email: khothemegiatot@gmail.com</li>
                        <li>Website: <a href="https://www.khothemegiatot.com" target="_blank">https://www.khothemegiatot.com</a></li>
                        <li>Documentation: <a href="https://www.khothemegiatot.com/wp-sysmaster/" target="_blank">https://www.khothemegiatot.com/wp-sysmaster/</a></li>
                    </ul>
                </p>
                <p>
                    <?php _e('If you find this plugin useful, please support us:', 'wp-sysmaster'); ?>
                    <br>
                    <a href="https://www.khothemegiatot.com/wp-sysmaster/ung-ho" class="button button-secondary" target="_blank">
                        <?php _e('Support development', 'wp-sysmaster'); ?>
                    </a>
                </p>
            </div>
        </div>

        <!-- Important Notes -->
        <div class="wp-sysmaster-card wp-sysmaster-notes">
            <h2><?php _e('Important notes', 'wp-sysmaster'); ?></h2>
            <div class="wp-sysmaster-card-content">
                <ul>
                    <li><?php _e('Always backup data before making major changes', 'wp-sysmaster'); ?></li>
                    <li><?php _e('Check compatibility with themes and other plugins', 'wp-sysmaster'); ?></li>
                    <li><?php _e('Update plugin regularly to receive new features and security patches', 'wp-sysmaster'); ?></li>
                    <li><?php _e('Read the documentation carefully before using advanced features', 'wp-sysmaster'); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.wp-sysmaster-dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.wp-sysmaster-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 20px;
}

.wp-sysmaster-card h2 {
    margin-top: 0;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}

.wp-sysmaster-card-content {
    margin-top: 15px;
}

.wp-sysmaster-card-content ul,
.wp-sysmaster-card-content ol {
    margin-left: 20px;
}

.wp-sysmaster-card-content li {
    margin-bottom: 8px;
}

.wp-sysmaster-intro,
.wp-sysmaster-usage,
.wp-sysmaster-author,
.wp-sysmaster-notes {
    grid-column: span 2;
}

@media (max-width: 782px) {
    .wp-sysmaster-intro,
    .wp-sysmaster-usage,
    .wp-sysmaster-author,
    .wp-sysmaster-notes {
        grid-column: span 1;
    }
}
</style> 