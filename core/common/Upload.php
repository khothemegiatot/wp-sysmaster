<?php
namespace WPSysMaster\Common;

if (!defined('ABSPATH')) exit;

/**
 * Class xử lý Upload
 * Class to manage upload settings
 */
class Upload {
    /**
     * Instance của class
     * Instance of the class
     * @var Upload|null
     * @access private
     * @static
     */
    private static $instance = null;

    /**
     * Option name cho Upload settings
     * Option name for upload settings
     * @var string
     * @access private
     */
    const OPTION_NAME = 'wp_sysmaster_upload_settings';

    /**
     * Constructor
     * @access private
     * @return void
     */
    private function __construct() {
        add_action('admin_init', [$this, 'registerSettings']);
        add_filter('upload_mimes', [$this, 'customUploadMimes']);
        add_filter('wp_handle_upload_prefilter', [$this, 'handleUpload']);
    }

    /**
     * Lấy instance của class
     * Get class instance
     * @access public
     * @static
     * @return Upload|null
     */
    public static function getInstance(): Upload|null {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Đăng ký settings
     * Register settings
     * @access public
     * @return void
     */
    public function registerSettings(): void {
        register_setting(
            'wp_sysmaster_upload_settings',
            self::OPTION_NAME,
            [$this, 'sanitizeSettings']
        );
    }

    /**
     * Sanitize settings trước khi lưu
     * Sanitize settings before saving
     * @access public
     * @param array $input
     * @return array
     */
    public function sanitizeSettings($input): array {
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
     * Add custom MIME types
     * @access public
     * @param array $mimes
     * @return array
     */
    public function customUploadMimes($mimes): mixed {
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
     * Handle file upload
     * @access public
     * @param array $file
     * @return array
     */
    public function handleUpload($file): mixed {
        $settings = $this->getSettings();
        
        if ($settings['enabled'] !== 'on' || $settings['rename_files'] !== 'on') {
            return $file;
        }

        $info = pathinfo($file['name']);
        $ext = empty($info['extension']) ? '' : '.' . $info['extension'];
        $name = $info['filename'];

        // Tạo tên file mới
        // Generate new file name
        $new_name = $this->generateFileName($name) . $ext;
        
        // Cập nhật tên file
        // Update file name
        $file['name'] = $new_name;

        return $file;
    }

    /**
     * Tạo tên file mới
     * Generate new file name
     * @access private
     * @param string $original_name
     * @return string
     */
    private function generateFileName($original_name): string {
        // Format: timestamp-random-originalname
        $timestamp = time();
        $random = wp_generate_password(8, false);
        $clean_name = sanitize_file_name($original_name);
        
        return sprintf('%s-%s-%s', $timestamp, $random, $clean_name);
    }

    /**
     * Lấy settings
     * Get settings
     * @access public
     * @return array
     */
    public function getSettings(): mixed {
        $defaults = [
            'enabled' => 'off',
            'rename_files' => 'off',
            'mime_types' => [
            ]
        ];

        $settings = get_option(self::OPTION_NAME, []);
        return wp_parse_args($settings, $defaults);
    }

    /**
     * Kiểm tra MIME type có hợp lệ không
     * Check if MIME type is valid
     * @access public
     * @param string $mime_type
     * @return bool
     */
    public function isValidMimeType($mime_type): bool {
        return preg_match('/^[a-z0-9\-\.\/\+]+$/i', $mime_type);
    }
}