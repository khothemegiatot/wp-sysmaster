<?php
namespace WPSysMaster\Admin;

if (!defined('ABSPATH')) exit;

/**
 * Class xử lý Upload
 */
class Upload {
    /**
     * Instance của class
     * @var Upload
     */
    private static $instance = null;

    /**
     * Option name cho Upload settings
     */
    const OPTION_NAME = 'wp_sysmaster_upload_settings';

    /**
     * Constructor
     */
    private function __construct() {
        add_action('admin_init', [$this, 'registerSettings']);
        add_filter('upload_mimes', [$this, 'customUploadMimes']);
        add_filter('wp_handle_upload_prefilter', [$this, 'handleUpload']);
    }

    /**
     * Lấy instance của class
     */
    public static function getInstance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Đăng ký settings
     */
    public function registerSettings() {
        register_setting(
            'wp_sysmaster_upload_settings',
            self::OPTION_NAME,
            [$this, 'sanitizeSettings']
        );
    }

    /**
     * Sanitize settings trước khi lưu
     */
    public function sanitizeSettings($input) {
        $sanitized = [];

        // Enabled
        $sanitized['enabled'] = isset($input['enabled']) ? 'on' : 'off';

        // Rename files
        $sanitized['rename_files'] = isset($input['rename_files']) ? 'on' : 'off';

        // Custom MIME types
        if (!empty($input['mime_types']) && is_array($input['mime_types'])) {
            $sanitized['mime_types'] = [];
            foreach ($input['mime_types'] as $mime) {
                if (!empty($mime['extension']) && !empty($mime['type'])) {
                    $sanitized['mime_types'][] = [
                        'extension' => sanitize_text_field($mime['extension']),
                        'type' => sanitize_text_field($mime['type'])
                    ];
                }
            }
        }

        return $sanitized;
    }

    /**
     * Thêm custom MIME types
     */
    public function customUploadMimes($mimes) {
        $settings = $this->getSettings();
        
        if ($settings['enabled'] !== 'on') {
            return $mimes;
        }

        if (!empty($settings['mime_types'])) {
            foreach ($settings['mime_types'] as $mime) {
                $mimes[$mime['extension']] = $mime['type'];
            }
        }

        return $mimes;
    }

    /**
     * Xử lý file upload
     */
    public function handleUpload($file) {
        $settings = $this->getSettings();
        
        if ($settings['enabled'] !== 'on' || $settings['rename_files'] !== 'on') {
            return $file;
        }

        $info = pathinfo($file['name']);
        $ext = empty($info['extension']) ? '' : '.' . $info['extension'];
        $name = $info['filename'];

        // Tạo tên file mới
        $new_name = $this->generateFileName($name) . $ext;
        
        // Cập nhật tên file
        $file['name'] = $new_name;

        return $file;
    }

    /**
     * Tạo tên file mới
     */
    private function generateFileName($original_name) {
        // Format: timestamp-random-originalname
        $timestamp = time();
        $random = wp_generate_password(8, false);
        $clean_name = sanitize_file_name($original_name);
        
        return sprintf('%s-%s-%s', $timestamp, $random, $clean_name);
    }

    /**
     * Lấy settings
     */
    public function getSettings() {
        $defaults = [
            'enabled' => 'off',
            'rename_files' => 'off',
            'mime_types' => [
                ['extension' => 'doc', 'type' => 'application/msword'],
                ['extension' => 'docx', 'type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
                ['extension' => 'pdf', 'type' => 'application/pdf'],
                ['extension' => 'zip', 'type' => 'application/zip']
            ]
        ];

        $settings = get_option(self::OPTION_NAME, []);
        return wp_parse_args($settings, $defaults);
    }

    /**
     * Kiểm tra MIME type có hợp lệ không
     */
    public function isValidMimeType($mime_type) {
        return preg_match('/^[a-z0-9\-\.\/\+]+$/i', $mime_type);
    }
} 