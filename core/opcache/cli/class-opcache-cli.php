<?php
/**
 * Class xử lý lệnh CLI cho OPcache
 */
class WP_SysMaster_OPcache_CLI {
    /**
     * Constructor
     */
    public function __construct() {
        if (defined('WP_CLI') && WP_CLI) {
            WP_CLI::add_command('wp-sysmaster opcache', array($this, 'handle_command'));
        }
    }

    /**
     * Xử lý lệnh CLI
     */
    public function handle_command($args, $assoc_args) {
        if (empty($args[0])) {
            WP_CLI::error('Vui lòng chỉ định lệnh: status, flush');
        }

        switch ($args[0]) {
            case 'status':
                $this->show_status();
                break;

            case 'flush':
                $this->flush_opcache();
                break;

            default:
                WP_CLI::error('Lệnh không hợp lệ. Các lệnh có sẵn: status, flush');
        }
    }

    /**
     * Hiển thị trạng thái OPcache
     */
    private function show_status() {
        $statistics = WP_SysMaster_OPcache_Statistics::get_statistics();

        if ($statistics === false) {
            WP_CLI::error('Không thể lấy thông tin thống kê OPcache.');
            return;
        }

        WP_CLI::line('=== Trạng thái OPcache ===');
        WP_CLI::line('Trạng thái: ' . ($statistics['enabled'] ? 'Đang hoạt động' : 'Đã tắt'));
        WP_CLI::line('Cache đầy: ' . ($statistics['cache_full'] ? 'Có' : 'Không'));
        WP_CLI::line('Đang chờ khởi động lại: ' . ($statistics['restart_pending'] ? 'Có' : 'Không'));

        WP_CLI::line("\n=== Bộ nhớ ===");
        WP_CLI::line('Đã sử dụng: ' . WP_SysMaster_OPcache_Statistics::format_bytes($statistics['memory_usage']['used']));
        WP_CLI::line('Còn trống: ' . WP_SysMaster_OPcache_Statistics::format_bytes($statistics['memory_usage']['free']));
        WP_CLI::line('Lãng phí: ' . WP_SysMaster_OPcache_Statistics::format_bytes($statistics['memory_usage']['wasted']) . 
                     ' (' . WP_SysMaster_OPcache_Statistics::format_percentage($statistics['memory_usage']['current_wasted_percentage']) . ')');

        WP_CLI::line("\n=== Thống kê ===");
        WP_CLI::line('Số script đã cache: ' . number_format($statistics['opcache_statistics']['num_cached_scripts']));
        WP_CLI::line('Hits: ' . number_format($statistics['opcache_statistics']['hits']));
        WP_CLI::line('Misses: ' . number_format($statistics['opcache_statistics']['misses']));
        WP_CLI::line('Tỷ lệ hit: ' . WP_SysMaster_OPcache_Statistics::format_percentage($statistics['opcache_statistics']['opcache_hit_rate']));
    }

    /**
     * Xóa OPcache
     */
    private function flush_opcache() {
        if (!function_exists('opcache_reset')) {
            WP_CLI::error('OPcache không được bật trên máy chủ.');
            return;
        }

        if (opcache_reset()) {
            WP_CLI::success('OPcache đã được xóa thành công.');
        } else {
            WP_CLI::error('Có lỗi xảy ra khi xóa OPcache.');
        }
    }
} 