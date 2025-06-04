<?php
namespace WPSysMaster\AI\Settings;

if (!defined('ABSPATH')) exit;

/**
 * Class quản lý trang cài đặt AI
 */
class AISettingsPage {
    /**
     * Instance của class
     * @var AISettingsPage
     */
    private static $instance = null;

    /**
     * Constructor
     */
    private function __construct() {
        $this->initHooks();
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
     * Khởi tạo hooks
     */
    private function initHooks() {
        add_action('admin_menu', [$this, 'addSettingsPage']);
        add_action('admin_init', [$this, 'registerSettings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAssets']);
    }

    /**
     * Đăng ký assets
     */
    public function enqueueAssets($hook) {
        if ($hook !== 'wp-sysmaster_page_wp-sysmaster-ai-settings') {
            return;
        }

        wp_enqueue_style(
            'wp-sysmaster-ai-settings',
            plugins_url('/assets/css/admin/ai-settings.css', WP_SYSMASTER_PLUGIN_FILE),
            [],
            WP_SYSMASTER_VERSION
        );
    }

    /**
     * Thêm trang cài đặt
     */
    public function addSettingsPage() {
        // add_submenu_page(
        //     'wp-sysmaster',
        //     __('AI Settings', 'wp-sysmaster'),
        //     __('AI Settings', 'wp-sysmaster'),
        //     'manage_options',
        //     'wp-sysmaster-ai-settings',
        //     [$this, 'renderSettingsPage']
        // );
    }

    /**
     * Đăng ký các cài đặt
     */
    public function registerSettings() {
        register_setting(
            'wp_sysmaster_ai_settings',
            WP_SYSMASTER_AI_OPTIONS_KEY,
            [
                'type' => 'array',
                'sanitize_callback' => [$this, 'sanitizeSettings'],
                'default' => [
                    'openai_model' => 'gpt-3.5-turbo',
                    'embedding_provider' => 'openai',
                    'embedding_post_types' => ['post']
                ]
            ]
        );

        // OpenAI Section
        add_settings_section(
            'wp_sysmaster_openai_section',
            __('OpenAI Settings', 'wp-sysmaster'),
            [$this, 'renderOpenAISection'],
            'wp-sysmaster-ai-settings'
        );

        // API Key
        add_settings_field(
            'openai_api_key',
            __('API Key', 'wp-sysmaster'),
            [$this, 'renderAPIKeyField'],
            'wp-sysmaster-ai-settings',
            'wp_sysmaster_openai_section'
        );

        // Model
        add_settings_field(
            'openai_model',
            __('Model', 'wp-sysmaster'),
            [$this, 'renderModelField'],
            'wp-sysmaster-ai-settings',
            'wp_sysmaster_openai_section'
        );

        // Google Gemini Settings
        add_settings_section(
            'wp_sysmaster_gemini_settings',
            __('Google Gemini Settings', 'wp-sysmaster'),
            [$this, 'renderGeminiDescription'],
            'wp-sysmaster-ai-settings'
        );

        add_settings_field(
            'gemini_api_key',
            __('API Key', 'wp-sysmaster'),
            [$this, 'renderTextField'],
            'wp-sysmaster-ai-settings',
            'wp_sysmaster_gemini_settings',
            [
                'label_for' => 'gemini_api_key',
                'description' => __('Enter your Google Gemini API key', 'wp-sysmaster'),
                'is_password' => true
            ]
        );

        add_settings_field(
            'gemini_model',
            __('Model', 'wp-sysmaster'),
            [$this, 'renderSelectField'],
            'wp-sysmaster-ai-settings',
            'wp_sysmaster_gemini_settings',
            [
                'label_for' => 'gemini_model',
                'options' => [
                    'gemini-pro' => 'Gemini Pro',
                    'gemini-pro-vision' => 'Gemini Pro Vision'
                ]
            ]
        );

        // Local LM Settings
        add_settings_section(
            'wp_sysmaster_locallm_settings',
            __('Local Language Model Settings', 'wp-sysmaster'),
            [$this, 'renderLocalLMDescription'],
            'wp-sysmaster-ai-settings'
        );

        add_settings_field(
            'locallm_endpoint',
            __('API Endpoint', 'wp-sysmaster'),
            [$this, 'renderTextField'],
            'wp-sysmaster-ai-settings',
            'wp_sysmaster_locallm_settings',
            [
                'label_for' => 'locallm_endpoint',
                'description' => __('Enter your Local LM API endpoint (e.g. http://localhost:8080)', 'wp-sysmaster')
            ]
        );

        // Embedding Settings
        add_settings_section(
            'wp_sysmaster_embedding_settings',
            __('Embedding Settings', 'wp-sysmaster'),
            [$this, 'renderEmbeddingDescription'],
            'wp-sysmaster-ai-settings'
        );

        add_settings_field(
            'embedding_provider',
            __('Provider', 'wp-sysmaster'),
            [$this, 'renderSelectField'],
            'wp-sysmaster-ai-settings',
            'wp_sysmaster_embedding_settings',
            [
                'label_for' => 'embedding_provider',
                'options' => [
                    'openai' => 'OpenAI',
                    'locallm' => 'Local LM'
                ]
            ]
        );

        add_settings_field(
            'embedding_post_types',
            __('Post Types', 'wp-sysmaster'),
            [$this, 'renderCheckboxesField'],
            'wp-sysmaster-ai-settings',
            'wp_sysmaster_embedding_settings',
            [
                'label_for' => 'embedding_post_types',
                'options' => $this->getPostTypes()
            ]
        );
    }

    /**
     * Render trang cài đặt
     */
    public function renderSettingsPage() {
        if (!current_user_can('manage_options')) {
            return;
        }

        // Lưu cài đặt
        if (isset($_POST['wp_sysmaster_ai_test'])) {
            check_admin_referer('wp_sysmaster_ai_test');
            $provider = sanitize_text_field($_POST['test_provider']);
            $result = $this->testConnection($provider);
            if ($result['success']) {
                add_settings_error(
                    'wp_sysmaster_ai_settings',
                    'test_success',
                    __('Connection test successful!', 'wp-sysmaster'),
                    'updated'
                );
            } else {
                add_settings_error(
                    'wp_sysmaster_ai_settings',
                    'test_failed',
                    sprintf(__('Connection test failed: %s', 'wp-sysmaster'), $result['message']),
                    'error'
                );
            }
        }

        // Hiển thị thông báo
        settings_errors('wp_sysmaster_ai_settings');

        // Render template
        wp_sysmaster_get_template('admin/ai-settings.php');
    }

    /**
     * Render text field
     */
    public function renderTextField($args) {
        $options = get_option('wp_sysmaster_ai_settings');
        $value = isset($options[$args['label_for']]) ? $options[$args['label_for']] : '';
        $type = isset($args['is_password']) && $args['is_password'] ? 'password' : 'text';
        ?>
        <input
            type="<?php echo $type; ?>"
            id="<?php echo esc_attr($args['label_for']); ?>"
            name="wp_sysmaster_ai_settings[<?php echo esc_attr($args['label_for']); ?>]"
            value="<?php echo esc_attr($value); ?>"
            class="regular-text"
        >
        <?php if (isset($args['description'])): ?>
            <p class="description"><?php echo esc_html($args['description']); ?></p>
        <?php endif; ?>
        <?php
    }

    /**
     * Render select field
     */
    public function renderSelectField($args) {
        $options = get_option('wp_sysmaster_ai_settings');
        $value = isset($options[$args['label_for']]) ? $options[$args['label_for']] : '';
        ?>
        <select
            id="<?php echo esc_attr($args['label_for']); ?>"
            name="wp_sysmaster_ai_settings[<?php echo esc_attr($args['label_for']); ?>]"
        >
            <?php foreach ($args['options'] as $key => $label): ?>
                <option value="<?php echo esc_attr($key); ?>" <?php selected($value, $key); ?>>
                    <?php echo esc_html($label); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php
    }

    /**
     * Render checkboxes field
     */
    public function renderCheckboxesField($args) {
        $options = get_option('wp_sysmaster_ai_settings');
        $values = isset($options[$args['label_for']]) ? $options[$args['label_for']] : [];
        ?>
        <fieldset>
            <?php foreach ($args['options'] as $key => $label): ?>
                <label>
                    <input
                        type="checkbox"
                        name="wp_sysmaster_ai_settings[<?php echo esc_attr($args['label_for']); ?>][]"
                        value="<?php echo esc_attr($key); ?>"
                        <?php checked(in_array($key, $values)); ?>
                    >
                    <?php echo esc_html($label); ?>
                </label><br>
            <?php endforeach; ?>
        </fieldset>
        <?php
    }

    /**
     * Render OpenAI description
     */
    public function renderOpenAISection() {
        ?>
        <p>
            <?php _e('Configure your OpenAI API settings. You can get your API key from the OpenAI dashboard.', 'wp-sysmaster'); ?>
            <a href="https://platform.openai.com/account/api-keys" target="_blank">
                <?php _e('Get API Key', 'wp-sysmaster'); ?>
            </a>
        </p>
        <?php
    }

    /**
     * Render Gemini description
     */
    public function renderGeminiDescription() {
        ?>
        <p>
            <?php _e('Configure your Google Gemini API settings. You can get your API key from the Google AI Studio.', 'wp-sysmaster'); ?>
            <a href="https://makersuite.google.com/app/apikey" target="_blank">
                <?php _e('Get API Key', 'wp-sysmaster'); ?>
            </a>
        </p>
        <?php
    }

    /**
     * Render Local LM description
     */
    public function renderLocalLMDescription() {
        ?>
        <p>
            <?php _e('Configure your Local Language Model settings. Make sure your LM server is running and accessible.', 'wp-sysmaster'); ?>
        </p>
        <?php
    }

    /**
     * Render Embedding description
     */
    public function renderEmbeddingDescription() {
        ?>
        <p>
            <?php _e('Configure settings for content embeddings. These settings affect how your content is processed and stored for AI interactions.', 'wp-sysmaster'); ?>
        </p>
        <?php
    }

    /**
     * Lấy danh sách post types
     */
    protected function getPostTypes() {
        $post_types = get_post_types(['public' => true], 'objects');
        $options = [];
        foreach ($post_types as $post_type) {
            $options[$post_type->name] = $post_type->labels->name;
        }
        return $options;
    }

    /**
     * Test kết nối với provider
     */
    protected function testConnection($provider) {
        try {
            $options = get_option('wp_sysmaster_ai_settings');
            
            switch ($provider) {
                case 'openai':
                    $api_key = $options['openai_api_key'] ?? '';
                    $model = $options['openai_model'] ?? 'gpt-3.5-turbo';
                    $ai = \WPSysMaster\AI\AIProviderFactory::getProvider('openai', $api_key, ['model' => $model]);
                    break;

                case 'gemini':
                    $api_key = $options['gemini_api_key'] ?? '';
                    $model = $options['gemini_model'] ?? 'gemini-pro';
                    $ai = \WPSysMaster\AI\AIProviderFactory::getProvider('gemini', $api_key, ['model' => $model]);
                    break;

                case 'locallm':
                    $endpoint = $options['locallm_endpoint'] ?? 'http://localhost:8080';
                    $ai = \WPSysMaster\AI\AIProviderFactory::getProvider('locallm', null, ['endpoint' => $endpoint]);
                    break;

                default:
                    throw new \Exception('Invalid provider');
            }

            if (!$ai->isReady()) {
                throw new \Exception('Provider not ready');
            }

            // Test với prompt đơn giản
            $response = $ai->generateCompletion('Hello, this is a test.');
            
            return [
                'success' => true,
                'message' => 'Connection successful'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Render API Key field
     */
    public function renderAPIKeyField() {
        $options = get_option(WP_SYSMASTER_AI_OPTIONS_KEY);
        $value = isset($options['openai_api_key']) ? $options['openai_api_key'] : '';
        ?>
        <input 
            type="password" 
            id="openai_api_key" 
            name="wp_sysmaster_ai_settings[openai_api_key]" 
            value="<?php echo esc_attr($value); ?>" 
            class="regular-text"
        >
        <p class="description">
            <?php _e('Enter your OpenAI API key. You can get one from', 'wp-sysmaster'); ?>
            <a href="https://platform.openai.com/account/api-keys" target="_blank">OpenAI Dashboard</a>
        </p>
        <?php
    }

    /**
     * Render Model field
     */
    public function renderModelField() {
        $options = get_option(WP_SYSMASTER_AI_OPTIONS_KEY);
        $value = isset($options['openai_model']) ? $options['openai_model'] : 'gpt-3.5-turbo';
        ?>
        <select 
            id="openai_model" 
            name="wp_sysmaster_ai_settings[openai_model]" 
            class="regular-text"
        >
            <option value="gpt-3.5-turbo" <?php selected($value, 'gpt-3.5-turbo'); ?>>
                GPT-3.5 Turbo
            </option>
            <option value="gpt-4" <?php selected($value, 'gpt-4'); ?>>
                GPT-4
            </option>
        </select>
        <?php
    }

    /**
     * Sanitize settings
     */
    public function sanitizeSettings($input) {
        $sanitized = [];

        // OpenAI Settings
        if (isset($input['openai_api_key'])) {
            $sanitized['openai_api_key'] = sanitize_text_field($input['openai_api_key']);
        }

        if (isset($input['openai_model'])) {
            $sanitized['openai_model'] = sanitize_text_field($input['openai_model']);
        }

        // Gemini Settings
        if (isset($input['gemini_api_key'])) {
            $sanitized['gemini_api_key'] = sanitize_text_field($input['gemini_api_key']);
        }

        if (isset($input['gemini_model'])) {
            $sanitized['gemini_model'] = sanitize_text_field($input['gemini_model']);
        }

        // Local LM Settings
        if (isset($input['locallm_endpoint'])) {
            $sanitized['locallm_endpoint'] = esc_url_raw($input['locallm_endpoint']);
        }

        // Embedding Settings
        if (isset($input['embedding_provider'])) {
            $sanitized['embedding_provider'] = sanitize_text_field($input['embedding_provider']);
        }

        if (isset($input['embedding_post_types']) && is_array($input['embedding_post_types'])) {
            $sanitized['embedding_post_types'] = array_map('sanitize_text_field', $input['embedding_post_types']);
        }

        return $sanitized;
    }
} 