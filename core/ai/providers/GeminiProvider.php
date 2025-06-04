<?php
namespace WPSysMaster\AI\Providers;

use WPSysMaster\AI\AbstractAIProvider;

if (!defined('ABSPATH')) exit;

/**
 * Google Gemini Provider
 */
class GeminiProvider extends AbstractAIProvider {
    /**
     * API endpoint
     * @var string
     */
    protected $api_endpoint = 'https://generativelanguage.googleapis.com/v1beta';

    /**
     * Model đang sử dụng
     * @var string
     */
    protected $model = 'gemini-pro';

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
    protected function getRequestHeaders() {
        return [
            'Content-Type' => 'application/json',
            'x-goog-api-key' => $this->api_key,
        ];
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
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 1000,
            ],
        ], $options);

        // Gửi request với retry
        $response = $this->withRetry(function() use ($data) {
            return $this->sendRequest(
                $this->api_endpoint . '/models/' . $this->model . ':generateContent',
                $data
            );
        });

        // Xử lý response
        $result = [
            'text' => $response['candidates'][0]['content']['parts'][0]['text'],
            'model' => $this->model,
            'finish_reason' => $response['candidates'][0]['finishReason'],
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
            'provider' => 'Google Gemini',
            'capabilities' => [
                'chat' => true,
                'completion' => true,
                'vision' => $this->model === 'gemini-pro-vision',
            ]
        ];
    }

    /**
     * Gửi prompt với hình ảnh
     * 
     * @param string $prompt Prompt text
     * @param string $image_path Đường dẫn đến file ảnh
     * @param array $options Tùy chọn bổ sung
     * @return array Response
     */
    public function generateCompletionWithImage($prompt, $image_path, array $options = []) {
        if ($this->model !== 'gemini-pro-vision') {
            throw new \Exception('Model hiện tại không hỗ trợ xử lý hình ảnh');
        }

        // Đọc và encode hình ảnh
        $image_data = base64_encode(file_get_contents($image_path));
        $mime_type = mime_content_type($image_path);

        $data = array_merge([
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $prompt
                        ],
                        [
                            'inline_data' => [
                                'mime_type' => $mime_type,
                                'data' => $image_data
                            ]
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 1000,
            ],
        ], $options);

        $response = $this->withRetry(function() use ($data) {
            return $this->sendRequest(
                $this->api_endpoint . '/models/' . $this->model . ':generateContent',
                $data
            );
        });

        return [
            'text' => $response['candidates'][0]['content']['parts'][0]['text'],
            'model' => $this->model,
            'finish_reason' => $response['candidates'][0]['finishReason'],
        ];
    }

    /**
     * Đếm tokens trong văn bản
     * 
     * @param string $text Văn bản cần đếm
     * @return array Thông tin về tokens
     */
    public function countTokens($text) {
        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $text]
                    ]
                ]
            ]
        ];

        $response = $this->withRetry(function() use ($data) {
            return $this->sendRequest(
                $this->api_endpoint . '/models/' . $this->model . ':countTokens',
                $data
            );
        });

        return [
            'total_tokens' => $response['totalTokens'],
        ];
    }
} 