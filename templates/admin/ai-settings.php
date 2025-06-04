<?php
if (!defined('ABSPATH')) exit;

$settings = wp_sysmaster_get_ai_settings();
?>

<div class="wrap">
    <h1><?php _e('AI Settings', 'wp-sysmaster'); ?></h1>

    <form method="post" action="options.php">
        <?php 
        settings_fields('wp_sysmaster_ai_settings');
        do_settings_sections('wp-sysmaster-ai-settings');
        submit_button(__('Save Settings', 'wp-sysmaster')); 
        ?>
    </form>

    <h2><?php _e('Test Connections', 'wp-sysmaster'); ?></h2>
    <form action="" method="post">
        <?php wp_nonce_field('wp_sysmaster_ai_test'); ?>
        <table class="form-table">
            <tr>
                <th scope="row"><?php _e('Select Provider', 'wp-sysmaster'); ?></th>
                <td>
                    <select name="test_provider">
                        <option value="openai">OpenAI</option>
                        <option value="gemini">Google Gemini</option>
                        <option value="locallm">Local LM</option>
                    </select>
                    <?php submit_button(__('Test Connection', 'wp-sysmaster'), 'secondary', 'wp_sysmaster_ai_test', false); ?>
                </td>
            </tr>
        </table>
    </form>
</div> 