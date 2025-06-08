<?php
/**
 * Class xử lý thống kê OPcache
 */
class WP_SysMaster_OPcache_Statistics {
    /**
     * Lấy thông tin thống kê OPcache
     */
    public static function get_statistics() {
        if (!function_exists('opcache_get_status')) {
            return false;
        }

        $status = @opcache_get_status(false);
        if ($status === false) {
            return false;
        }

        return array(
            'enabled' => $status['opcache_enabled'],
            'cache_full' => $status['cache_full'],
            'restart_pending' => $status['restart_pending'],
            'memory_usage' => $status['memory_usage'],
            'opcache_statistics' => $status['opcache_statistics'],
        );
    }

    /**
     * Lấy danh sách files đã cache với phân trang và tìm kiếm
     */
    public static function get_cached_files_paginated($page = 1, $per_page = 20, $search = '', $directory = '') {
        if (!function_exists('opcache_get_status')) {
            return array(
                'items' => array(),
                'total' => 0,
                'total_pages' => 0,
            );
        }

        $status = @opcache_get_status();
        if ($status === false || !isset($status['scripts'])) {
            return array(
                'items' => array(),
                'total' => 0,
                'total_pages' => 0,
            );
        }

        $files = $status['scripts'];

        // Lọc theo thư mục nếu có
        if (!empty($directory)) {
            $files = array_filter($files, function($file) use ($directory) {
                return strpos($file['full_path'], $directory) === 0;
            });
        }

        // Tìm kiếm nếu có
        if (!empty($search)) {
            $files = array_filter($files, function($file) use ($search) {
                return stripos($file['full_path'], $search) !== false;
            });
        }

        // Sắp xếp theo thời gian sử dụng gần nhất
        usort($files, function($a, $b) {
            return $b['last_used_timestamp'] - $a['last_used_timestamp'];
        });

        $total = count($files);
        $total_pages = ceil($total / $per_page);
        $page = max(1, min($page, $total_pages));
        $offset = ($page - 1) * $per_page;

        return array(
            'items' => array_slice($files, $offset, $per_page),
            'total' => $total,
            'total_pages' => $total_pages,
        );
    }

    /**
     * Lấy danh sách thư mục từ các file đã cache
     */
    public static function get_cached_directories() {
        if (!function_exists('opcache_get_status')) {
            return array();
        }

        $status = @opcache_get_status();
        if ($status === false || !isset($status['scripts'])) {
            return array();
        }

        $directories = array();
        foreach ($status['scripts'] as $script) {
            $directory = dirname($script['full_path']);
            if (!in_array($directory, $directories)) {
                $directories[] = $directory;
            }
        }

        sort($directories);
        return $directories;
    }

    /**
     * Format bytes thành dạng dễ đọc
     */
    public static function format_bytes($bytes) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Format số thành phần trăm
     */
    public static function format_percentage($number) {
        return round($number, 2) . '%';
    }
} 