<?php
if (!defined('ABSPATH')) exit;
?>

<div class="wrap">
    <h1><?php echo esc_html__('OPcache Manager', 'wp-sysmaster'); ?></h1>

    <?php settings_errors('wp_sysmaster_opcache'); ?>

    <?php if (!function_exists('opcache_get_status')): ?>
        <div class="notice notice-error">
            <p><?php _e('OPcache is not enabled on your server.', 'wp-sysmaster'); ?></p>
        </div>
    <?php return; endif; ?>

    <?php if ($statistics === false): ?>
        <div class="notice notice-error">
            <p><?php _e('Unable to get OPcache statistics.', 'wp-sysmaster'); ?></p>
        </div>
    <?php return; endif; ?>

    <div class="wp-sysmaster-opcache-actions">
        <a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=wp_sysmaster_flush_opcache'), 'wp_sysmaster_flush_opcache'); ?>" 
           class="button button-primary" 
           id="wp-sysmaster-flush-opcache">
            <?php _e('Flush OPcache', 'wp-sysmaster'); ?>
        </a>
    </div>

    <div class="wp-sysmaster-opcache-status">
        <h2><?php _e('Status', 'wp-sysmaster'); ?></h2>
        <table class="widefat">
            <tbody>
                <tr>
                    <th><?php _e('Status', 'wp-sysmaster'); ?></th>
                    <td>
                        <?php if ($statistics['enabled']): ?>
                            <span class="status-enabled"><?php _e('Enabled', 'wp-sysmaster'); ?></span>
                        <?php else: ?>
                            <span class="status-disabled"><?php _e('Disabled', 'wp-sysmaster'); ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th><?php _e('Cache Full', 'wp-sysmaster'); ?></th>
                    <td><?php echo $statistics['cache_full'] ? __('Yes', 'wp-sysmaster') : __('No', 'wp-sysmaster'); ?></td>
                </tr>
                <tr>
                    <th><?php _e('Restart Pending', 'wp-sysmaster'); ?></th>
                    <td><?php echo $statistics['restart_pending'] ? __('Yes', 'wp-sysmaster') : __('No', 'wp-sysmaster'); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="wp-sysmaster-opcache-memory">
        <h2><?php _e('Memory', 'wp-sysmaster'); ?></h2>
        <table class="widefat">
            <tbody>
                <tr>
                    <th><?php _e('Used', 'wp-sysmaster'); ?></th>
                    <td><?php echo WP_SysMaster_OPcache_Statistics::format_bytes($statistics['memory_usage']['used']); ?></td>
                </tr>
                <tr>
                    <th><?php _e('Free', 'wp-sysmaster'); ?></th>
                    <td><?php echo WP_SysMaster_OPcache_Statistics::format_bytes($statistics['memory_usage']['free']); ?></td>
                </tr>
                <tr>
                    <th><?php _e('Wasted', 'wp-sysmaster'); ?></th>
                    <td>
                        <?php echo WP_SysMaster_OPcache_Statistics::format_bytes($statistics['memory_usage']['wasted']); ?>
                        (<?php echo WP_SysMaster_OPcache_Statistics::format_percentage($statistics['memory_usage']['current_wasted_percentage']); ?>)
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="wp-sysmaster-opcache-statistics">
        <h2><?php _e('Statistics', 'wp-sysmaster'); ?></h2>
        <table class="widefat">
            <tbody>
                <tr>
                    <th><?php _e('Cached Scripts', 'wp-sysmaster'); ?></th>
                    <td><?php echo number_format($statistics['opcache_statistics']['num_cached_scripts']); ?></td>
                </tr>
                <tr>
                    <th><?php _e('Hits', 'wp-sysmaster'); ?></th>
                    <td><?php echo number_format($statistics['opcache_statistics']['hits']); ?></td>
                </tr>
                <tr>
                    <th><?php _e('Misses', 'wp-sysmaster'); ?></th>
                    <td><?php echo number_format($statistics['opcache_statistics']['misses']); ?></td>
                </tr>
                <tr>
                    <th><?php _e('Hit Rate', 'wp-sysmaster'); ?></th>
                    <td><?php echo WP_SysMaster_OPcache_Statistics::format_percentage($statistics['opcache_statistics']['opcache_hit_rate']); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <?php if ($cached_files['items']): ?>
    <div class="wp-sysmaster-opcache-files">
        <h2><?php _e('Cached Files', 'wp-sysmaster'); ?></h2>

        <!-- Form tìm kiếm và lọc -->
        <form method="get" class="wp-sysmaster-opcache-filters">
            <input type="hidden" name="page" value="wp-sysmaster-opcache">
            
            <div class="tablenav top">
                <div class="alignleft actions">
                    <select name="directory" class="postform">
                        <option value=""><?php _e('All Directories', 'wp-sysmaster'); ?></option>
                        <?php foreach ($directories as $dir): ?>
                            <option value="<?php echo esc_attr($dir); ?>" <?php selected($directory, $dir); ?>>
                                <?php echo esc_html($dir); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="per_page" class="postform">
                        <?php foreach (array(10, 20, 50, 100) as $num): ?>
                            <option value="<?php echo $num; ?>" <?php selected($per_page, $num); ?>>
                                <?php printf(__('%d items/page', 'wp-sysmaster'), $num); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <input type="submit" class="button" value="<?php esc_attr_e('Filter', 'wp-sysmaster'); ?>">
                </div>

                <div class="alignright">
                    <p class="search-box">
                        <label class="screen-reader-text" for="post-search-input"><?php _e('Search Files:', 'wp-sysmaster'); ?></label>
                        <input type="search" id="post-search-input" name="s" value="<?php echo esc_attr($search); ?>">
                        <input type="submit" class="button" value="<?php esc_attr_e('Search', 'wp-sysmaster'); ?>">
                    </p>
                </div>

                <br class="clear">
            </div>

            <table class="widefat">
                <thead>
                    <tr>
                        <th><?php _e('File', 'wp-sysmaster'); ?></th>
                        <th><?php _e('Hits', 'wp-sysmaster'); ?></th>
                        <th><?php _e('Memory', 'wp-sysmaster'); ?></th>
                        <th><?php _e('Last Used', 'wp-sysmaster'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cached_files['items'] as $file): ?>
                    <tr>
                        <td><?php echo esc_html($file['full_path']); ?></td>
                        <td><?php echo number_format($file['hits']); ?></td>
                        <td><?php echo WP_SysMaster_OPcache_Statistics::format_bytes($file['memory_consumption']); ?></td>
                        <td><?php echo date('Y-m-d H:i:s', $file['last_used_timestamp']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="tablenav bottom">
                <div class="tablenav-pages">
                    <span class="displaying-num">
                        <?php printf(
                            _n('%s item', '%s items', $cached_files['total'], 'wp-sysmaster'),
                            number_format_i18n($cached_files['total'])
                        ); ?>
                    </span>

                    <?php if ($cached_files['total_pages'] > 1): ?>
                        <span class="pagination-links">
                            <?php
                            echo paginate_links(array(
                                'base' => add_query_arg('paged', '%#%'),
                                'format' => '',
                                'prev_text' => __('&laquo;'),
                                'next_text' => __('&raquo;'),
                                'total' => $cached_files['total_pages'],
                                'current' => $page,
                            ));
                            ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
    <?php endif; ?>
</div> 