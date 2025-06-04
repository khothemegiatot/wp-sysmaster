<?php
if (!defined('ABSPATH')) exit;

$settings = wp_sysmaster_get_ai_settings();
?>

<div class="wrap">
    <h1><?php _e('AI Settings', 'wp-sysmaster'); ?></h1>

    <form method="post" action="options.php">
        <?php settings_fields('wp_sysmaster_ai_settings'); ?>
        
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="openai_api_key"><?php _e('OpenAI API Key', 'wp-sysmaster'); ?></label>
                </th>
                <td>
                    <input type="password" 
                           id="openai_api_key" 
                           name="wp_sysmaster_ai_settings[openai_api_key]" 
                           value="<?php echo esc_attr($settings['openai_api_key'] ?? ''); ?>" 
                           class="regular-text">
                    <p class="description">
                        <?php _e('Enter your OpenAI API key. You can get one from', 'wp-sysmaster'); ?>
                        <a href="https://platform.openai.com/account/api-keys" target="_blank">OpenAI Dashboard</a>
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="openai_model"><?php _e('OpenAI Model', 'wp-sysmaster'); ?></label>
                </th>
                <td>
                    <select id="openai_model" 
                            name="wp_sysmaster_ai_settings[openai_model]" 
                            class="regular-text">
                        <option value="gpt-3.5-turbo" <?php selected($settings['openai_model'] ?? '', 'gpt-3.5-turbo'); ?>>
                            GPT-3.5 Turbo
                        </option>
                        <option value="gpt-4" <?php selected($settings['openai_model'] ?? '', 'gpt-4'); ?>>
                            GPT-4
                        </option>
                    </select>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="embedding_provider"><?php _e('Embedding Provider', 'wp-sysmaster'); ?></label>
                </th>
                <td>
                    <select id="embedding_provider" 
                            name="wp_sysmaster_ai_settings[embedding_provider]" 
                            class="regular-text">
                        <option value="openai" <?php selected($settings['embedding_provider'] ?? '', 'openai'); ?>>
                            OpenAI
                        </option>
                    </select>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <?php _e('Post Types for Embedding', 'wp-sysmaster'); ?>
                </th>
                <td>
                    <?php
                    $post_types = get_post_types(['public' => true], 'objects');
                    $selected_types = $settings['embedding_post_types'] ?? ['post'];
                    
                    foreach ($post_types as $post_type) :
                        $checked = in_array($post_type->name, $selected_types);
                    ?>
                        <label>
                            <input type="checkbox" 
                                   name="wp_sysmaster_ai_settings[embedding_post_types][]" 
                                   value="<?php echo esc_attr($post_type->name); ?>"
                                   <?php checked($checked); ?>>
                            <?php echo esc_html($post_type->label); ?>
                        </label><br>
                    <?php endforeach; ?>
                </td>
            </tr>
        </table>

        <?php submit_button(); ?>
    </form>
</div> 