<?php
namespace WPSysMaster\AI;

use WPSysMaster\AI\Providers\OpenAIProvider;
use WPSysMaster\AI\Providers\GeminiProvider;
use WPSysMaster\AI\Providers\LocalLMProvider;

if (!defined('ABSPATH')) exit;

/**
 * Factory class để tạo AI Provider
 */
class AIProviderFactory {
    /**
     * Danh sách các provider đã được khởi tạo
     * @var array
     */
    private static $instances = [];

    /**
     * Lấy instance của provider
     * 
     * @param string $provider_name Tên provider (openai, gemini, locallm)
     * @param string $api_key API key
     * @param array $config Cấu hình bổ sung
     * @return AIProviderInterface
     * @throws \Exception
     */
    public static function getProvider($provider_name, $api_key = null, array $config = []) {
        $provider_name = strtolower($provider_name);

        // Nếu đã có instance và không cần cập nhật config
        if (isset(self::$instances[$provider_name]) && empty($config) && $api_key === null) {
            return self::$instances[$provider_name];
        }

        // Tạo instance mới
        $provider = self::createProvider($provider_name);

        // Khởi tạo với API key và config
        if ($api_key !== null || !empty($config)) {
            // Nếu không có API key mới, sử dụng key cũ
            if ($api_key === null && isset(self::$instances[$provider_name])) {
                $api_key = self::$instances[$provider_name]->getApiKey();
            }
            
            $provider->initialize($api_key, $config);
        }

        // Lưu instance
        self::$instances[$provider_name] = $provider;

        return $provider;
    }

    /**
     * Tạo instance của provider
     * 
     * @param string $provider_name
     * @return AIProviderInterface
     * @throws \Exception
     */
    private static function createProvider($provider_name) {
        switch ($provider_name) {
            case 'openai':
                return new OpenAIProvider();
            case 'gemini':
                return new GeminiProvider();
            case 'locallm':
                return new LocalLMProvider();
            default:
                throw new \Exception("Provider không hợp lệ: $provider_name");
        }
    }

    /**
     * Kiểm tra provider có tồn tại không
     * 
     * @param string $provider_name
     * @return bool
     */
    public static function hasProvider($provider_name) {
        return isset(self::$instances[strtolower($provider_name)]);
    }

    /**
     * Xóa instance của provider
     * 
     * @param string $provider_name
     * @return void
     */
    public static function removeProvider($provider_name) {
        unset(self::$instances[strtolower($provider_name)]);
    }

    /**
     * Xóa tất cả instances
     * 
     * @return void
     */
    public static function clearProviders() {
        self::$instances = [];
    }
} 