<?php
namespace WPSysMaster\AI\Providers;

use WPSysMaster\AI\AbstractAIProvider;

if (!defined('ABSPATH')) exit;

/**
 * OpenAI Provider
 */
class OpenAIProvider extends AbstractAIProvider {
    /**
     * API endpoint
     * @var string
     */
    protected $api_endpoint = 'https://api.openai.com/v1';

    /**
     * Model đang sử dụng
     * @var string
     */
    protected $model = 'gpt-3.5-turbo';

    /**
     * {@inheritdoc}
     */
    public function initialize($api_key, array $config = []) {
        if (isset($config['model'])) {
            $this->model = $config['model'];
        }
        parent::initialize($api_key, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function generateCompletion($prompt, array $options = []) {
        $cache_key = $this->model . '_' . md5($prompt . serialize($options));
        
        // Kiểm tra cache
        $cached_response = $this->getCachedResponse($cache_key);
        if ($cached_response !== null) {
            return $cached_response;
        }

        // Chuẩn bị request data
        $data = array_merge([
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => 1000,
        ], $options);

        // Gửi request với retry
        $response = $this->withRetry(function() use ($data) {
            return $this->sendRequest(
                $this->api_endpoint . '/chat/completions',
                $data
            );
        });

        // Xử lý response
        $result = [
            'text' => $response['choices'][0]['message']['content'],
            'usage' => $response['usage'],
            'model' => $response['model'],
        ];

        // Cache response
        $this->cacheResponse($cache_key, $result);

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getModelInfo() {
        return [
            'name' => $this->model,
            'provider' => 'OpenAI',
            'capabilities' => [
                'chat' => true,
                'completion' => true,
                'embedding' => true,
            ]
        ];
    }

    /**
     * Tạo embeddings cho văn bản
     * 
     * @param string $text Văn bản cần tạo embedding
     * @return array Vector embedding
     */
    public function createEmbedding($text) {
        $data = [
            'model' => 'text-embedding-ada-002',
            'input' => $text
        ];

        $response = $this->withRetry(function() use ($data) {
            return $this->sendRequest(
                $this->api_endpoint . '/embeddings',
                $data
            );
        });

        return [
            'embedding' => $response['data'][0]['embedding'],
            'usage' => $response['usage']
        ];
    }

    /**
     * Tạo hình ảnh từ mô tả
     * 
     * @param string $prompt Mô tả hình ảnh
     * @param array $options Tùy chọn (size, quality, etc)
     * @return array Thông tin hình ảnh được tạo
     */
    public function generateImage($prompt, array $options = []) {
        $data = array_merge([
            'prompt' => $prompt,
            'n' => 1,
            'size' => '1024x1024',
            'response_format' => 'url'
        ], $options);

        $response = $this->withRetry(function() use ($data) {
            return $this->sendRequest(
                $this->api_endpoint . '/images/generations',
                $data
            );
        });

        return [
            'url' => $response['data'][0]['url'],
            'prompt' => $prompt
        ];
    }
} 