<?php
namespace WPSysMaster\Admin;

if (!defined('ABSPATH')) exit;

/**
 * Class xử lý SMTP
 */
class SMTP {
    /**
     * Instance của class
     * @var SMTP
     */
    private static $instance = null;

    /**
     * Option name cho SMTP settings
     */
    const OPTION_NAME = 'wp_sysmaster_smtp_settings';

    /**
     * Constructor
     */
    private function __construct() {
        add_action('admin_init', [$this, 'registerSettings']);
        add_action('wp_ajax_wp_sysmaster_test_smtp', [$this, 'handleTestEmail']);
        add_action('phpmailer_init', [$this, 'configureSMTP']);
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
            'wp_sysmaster_smtp_settings',
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

        // Host
        $sanitized['host'] = sanitize_text_field($input['host'] ?? '');

        // Port 
        $sanitized['port'] = absint($input['port'] ?? 587);

        // Encryption
        $sanitized['encryption'] = in_array($input['encryption'], ['ssl', 'tls', 'none']) 
            ? $input['encryption'] 
            : 'tls';

        // Username
        $sanitized['username'] = sanitize_text_field($input['username'] ?? '');

        // Password - Mã hóa trước khi lưu
        if (!empty($input['password'])) {
            $sanitized['password'] = $this->encryptPassword($input['password']);
        } else {
            // Giữ lại password cũ nếu không có password mới
            $old_settings = get_option(self::OPTION_NAME, []);
            $sanitized['password'] = $old_settings['password'] ?? '';
        }

        // From email
        $sanitized['from_email'] = sanitize_email($input['from_email'] ?? get_option('admin_email'));

        // From name
        $sanitized['from_name'] = sanitize_text_field($input['from_name'] ?? get_option('blogname'));

        return $sanitized;
    }

    /**
     * Mã hóa password
     */
    private function encryptPassword($password) {
        if (function_exists('sodium_crypto_secretbox')) {
            $key = substr(wp_salt('auth'), 0, SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
            $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $cipher = sodium_crypto_secretbox($password, $nonce, $key);
            return base64_encode($nonce . $cipher);
        }
        return base64_encode($password);
    }

    /**
     * Giải mã password
     */
    private function decryptPassword($encrypted) {
        if (empty($encrypted)) return '';
        
        if (function_exists('sodium_crypto_secretbox')) {
            try {
                $decoded = base64_decode($encrypted);
                if ($decoded === false) return '';
                
                $key = substr(wp_salt('auth'), 0, SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
                $nonce = mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
                $cipher = mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');
                
                $decrypted = sodium_crypto_secretbox_open($cipher, $nonce, $key);
                return $decrypted !== false ? $decrypted : '';
            } catch (\Exception $e) {
                error_log('SMTP password decryption failed: ' . $e->getMessage());
                return '';
            }
        }
        return base64_decode($encrypted);
    }

    /**
     * Configure SMTP cho PHPMailer
     */
    public function configureSMTP($phpmailer) {
        $settings = get_option(self::OPTION_NAME, []);

        // Kiểm tra xem SMTP có được bật không
        if (!isset($settings['enabled']) || $settings['enabled'] !== 'on') {
            return;
        }

        // Configure SMTP
        $phpmailer->isSMTP();
        $phpmailer->Host = $settings['host'] ?? '';
        $phpmailer->Port = $settings['port'] ?? 587;
        
        // Authentication
        if (!empty($settings['username']) && !empty($settings['password'])) {
            $phpmailer->SMTPAuth = true;
            $phpmailer->Username = $settings['username'];
            $phpmailer->Password = $this->decryptPassword($settings['password']);
        }

        // Encryption
        if (isset($settings['encryption']) && $settings['encryption'] !== 'none') {
            $phpmailer->SMTPSecure = $settings['encryption'];
        }

        // From email & name
        if (!empty($settings['from_email'])) {
            $phpmailer->From = $settings['from_email'];
        }
        if (!empty($settings['from_name'])) {
            $phpmailer->FromName = $settings['from_name'];
        }

        // Enable debug if WP_DEBUG is on
        if (defined('WP_DEBUG') && WP_DEBUG) {
            $phpmailer->SMTPDebug = 2;
        }
    }

    /**
     * Xử lý AJAX test email
     */
    public function handleTestEmail() {
        // Verify nonce
        if (!check_ajax_referer('wp_sysmaster_test_smtp', 'nonce', false)) {
            wp_send_json_error(__('Invalid security token.', 'wp-sysmaster'));
        }

        // Verify permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('You do not have permission to perform this action.', 'wp-sysmaster'));
        }

        // Get test email address
        $to = sanitize_email($_POST['email'] ?? '');
        if (empty($to)) {
            wp_send_json_error(__('Please enter a valid email address.', 'wp-sysmaster'));
        }

        // Prepare email
        $subject = sprintf(__('[%s] SMTP Test Email', 'wp-sysmaster'), get_bloginfo('name'));
        $message = sprintf(
            __('This is a test email from %s (%s).', 'wp-sysmaster'),
            get_bloginfo('name'),
            home_url()
        );

        // Add headers
        $headers = array('Content-Type: text/html; charset=UTF-8');

        // Send email
        try {
            $result = wp_mail($to, $subject, $message, $headers);
            
            if ($result) {
                wp_send_json_success(__('Test email sent successfully!', 'wp-sysmaster'));
            } else {
                wp_send_json_error(__('Failed to send test email. Please check your SMTP settings.', 'wp-sysmaster'));
            }
        } catch (\Exception $e) {
            wp_send_json_error(sprintf(
                __('Error sending email: %s', 'wp-sysmaster'),
                $e->getMessage()
            ));
        }
    }

    /**
     * Lấy settings
     */
    public function getSettings() {
        return get_option(self::OPTION_NAME, []);
    }
}