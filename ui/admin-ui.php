<?php

if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Thêm menu chính của plugin
 */
function wp_sysmaster_add_admin_menu() {
    global $wp_sysmaster_000__text_domain;

    // Thêm menu chính
    add_menu_page(
        __('WP SysMaster', 'wp-sysmaster'),
        __('WP SysMaster', 'wp-sysmaster'),
        'manage_options',
        'wp-sysmaster',
        'wp_sysmaster_main_page',
        'dashicons-admin-tools',
        30
    );

    // Thêm submenu "Tổng quan"
    add_submenu_page(
        'wp-sysmaster',
        __('Tổng quan', 'wp-sysmaster'),
        __('Tổng quan', 'wp-sysmaster'),
        'manage_options',
        'wp-sysmaster',
        'wp_sysmaster_main_page'
    );

    // Thêm submenu "Mã tùy chỉnh"
    add_submenu_page(
        'wp-sysmaster',
        __('Mã tùy chỉnh', 'wp-sysmaster'),
        __('Mã tùy chỉnh', 'wp-sysmaster'),
        'manage_options',
        'wp-sysmaster-custom-code',
        'wp_sysmaster_custom_code_page'
    );

    // Thêm submenu "Tải lên"
    add_submenu_page(
        'wp-sysmaster',
        __('Tải lên', 'wp-sysmaster'),
        __('Tải lên', 'wp-sysmaster'),
        'manage_options',
        'wp-sysmaster-upload',
        'wp_sysmaster_upload_page'
    );

    // Thêm submenu "Cài đặt Plugin & Theme"
    add_submenu_page(
        'wp-sysmaster',
        __('Cài đặt Plugin & Theme', 'wp-sysmaster'),
        __('Cài đặt Plugin & Theme', 'wp-sysmaster'),
        'manage_options',
        'wp-sysmaster-installation',
        'wp_sysmaster_installation_page'
    );

    // Thêm submenu "SMTP"
    add_submenu_page(
        'wp-sysmaster',
        __('SMTP', 'wp-sysmaster'),
        __('SMTP', 'wp-sysmaster'),
        'manage_options',
        'wp-sysmaster-smtp',
        'wp_sysmaster_smtp_page'
    );

    // Thêm submenu "OPCache"
    add_submenu_page(
        'wp-sysmaster',
        __('OPCache', 'wp-sysmaster'),
        __('OPCache', 'wp-sysmaster'),
        'manage_options',
        'wp-sysmaster-opcache',
        'wp_sysmaster_opcache_page'
    );
}
add_action('admin_menu', 'wp_sysmaster_add_admin_menu');

/**
 * Hiển thị trang chính của plugin
 */
function wp_sysmaster_main_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('WP SysMaster - Tổng quan', 'wp-sysmaster'); ?></h1>
        
        <div class="card">
            <h2><?php echo esc_html__('Chào mừng đến với WP SysMaster!', 'wp-sysmaster'); ?></h2>
            <p><?php echo esc_html__('Plugin này cung cấp các công cụ mạnh mẽ để tùy chỉnh và quản lý WordPress của bạn.', 'wp-sysmaster'); ?></p>
        </div>

        <div class="dashboard-widgets-wrap">
            <div id="dashboard-widgets" class="metabox-holder">
                <div class="postbox-container" style="width: 49%;">
                    <div class="meta-box-sortables">
                        <div class="postbox">
                            <h2 class="hndle"><span><?php echo esc_html__('Tính năng chính', 'wp-sysmaster'); ?></span></h2>
                            <div class="inside">
                                <ul>
                                    <li>✨ <?php echo esc_html__('Chèn mã tùy chỉnh vào header và footer', 'wp-sysmaster'); ?></li>
                                    <li>🔧 <?php echo esc_html__('Thực thi mã PHP tùy chỉnh', 'wp-sysmaster'); ?></li>
                                    <li>📧 <?php echo esc_html__('Cấu hình SMTP', 'wp-sysmaster'); ?></li>
                                    <li>⚡ <?php echo esc_html__('Quản lý OPCache', 'wp-sysmaster'); ?></li>
                                    <li>📁 <?php echo esc_html__('Quản lý tải lên file', 'wp-sysmaster'); ?></li>
                                    <li>🔒 <?php echo esc_html__('Kiểm soát cài đặt plugin và theme', 'wp-sysmaster'); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="postbox-container" style="width: 49%;">
                    <div class="meta-box-sortables">
                        <div class="postbox">
                            <h2 class="hndle"><span><?php echo esc_html__('Truy cập nhanh', 'wp-sysmaster'); ?></span></h2>
                            <div class="inside">
                                <p>
                                    <a href="<?php echo esc_url(admin_url('admin.php?page=wp-sysmaster-custom-code')); ?>" 
                                       class="button button-primary">
                                        <?php echo esc_html__('Quản lý mã tùy chỉnh', 'wp-sysmaster'); ?>
                                    </a>
                                </p>
                                <p>
                                    <a href="<?php echo esc_url(admin_url('admin.php?page=wp-sysmaster-smtp')); ?>" 
                                       class="button">
                                        <?php echo esc_html__('Cấu hình SMTP', 'wp-sysmaster'); ?>
                                    </a>
                                </p>
                                <p>
                                    <a href="<?php echo esc_url(admin_url('admin.php?page=wp-sysmaster-opcache')); ?>" 
                                       class="button">
                                        <?php echo esc_html__('Quản lý OPCache', 'wp-sysmaster'); ?>
                                    </a>
                                </p>
                                <p>
                                    <a href="<?php echo esc_url(admin_url('admin.php?page=wp-sysmaster-upload')); ?>" 
                                       class="button">
                                        <?php echo esc_html__('Cài đặt tải lên', 'wp-sysmaster'); ?>
                                    </a>
                                </p>
                                <p>
                                    <a href="<?php echo esc_url(admin_url('admin.php?page=wp-sysmaster-installation')); ?>" 
                                       class="button">
                                        <?php echo esc_html__('Cài đặt Plugin & Theme', 'wp-sysmaster'); ?>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <h2><?php echo esc_html__('Hỗ trợ và tài liệu', 'wp-sysmaster'); ?></h2>
            <p>
                <?php echo esc_html__('Nếu bạn cần hỗ trợ hoặc muốn tìm hiểu thêm về cách sử dụng plugin:', 'wp-sysmaster'); ?>
            </p>
            <ul>
                <li>📚 <a href="https://www.phanxuanchanh.com/wp-sysmaster" target="_blank">
                    <?php echo esc_html__('Xem tài liệu hướng dẫn', 'wp-sysmaster'); ?>
                </a></li>
                <li>🚀 <a href="https://github.com/username/wp-sysmaster/issues" target="_blank">
                    <?php echo esc_html__('Báo cáo lỗi hoặc đề xuất tính năng', 'wp-sysmaster'); ?>
                </a></li>
            </ul>
        </div>
    </div>

    <style>
    .dashboard-widgets-wrap {
        margin-top: 20px;
    }
    .postbox-container {
        padding-right: 1%;
    }
    .inside ul {
        margin-left: 1.5em;
    }
    .card {
        max-width: none;
        margin-top: 20px;
    }
    </style>
    <?php
}

/**
 * Thêm liên kết cài đặt trong trang plugins
 */
function wp_sysmaster_add_action_links($links) {
    $settings_link = '<a href="' . esc_url(admin_url('admin.php?page=wp-sysmaster')) . '">' 
        . esc_html__('Cài đặt', 'wp-sysmaster') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(WP_SYSMASTER_PLUGIN_DIR . 'main.php'), 'wp_sysmaster_add_action_links');

// Xóa menu cũ trong Settings
remove_action('admin_menu', 'wp_sysmaster_000__add_admin_menu');