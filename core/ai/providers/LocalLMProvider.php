<?php
namespace WPSysMaster\AI\Providers;

use WPSysMaster\AI\AbstractAIProvider;

if (!defined('ABSPATH')) exit;

/**
 * Provider cho Local Language Models
 * Hỗ trợ các mô hình như Llama, GPT4All, Vicuna, v.v. 
 * chạy cục bộ thông qua API endpoints
 */
class LocalLMProvider extends AbstractAIProvider {
    /**
     * API endpoint mặc định
     * @var string
     */
    protected $api_endpoint = 'http://localhost:8080';

    /**
     * Model đang sử dụng
     * @var string
     */
    protected $model = 'default';

    /**
     * Thông tin về model
     * @var array
     */
    protected $model_info = [];

    /**
     * {@inheritdoc}
     */
    public function initialize($api_key, array $config = []) {
        // LocalLM không yêu cầu API key
        $this->api_key = '';

        // Cập nhật endpoint nếu được cung cấp
        if (isset($config['endpoint'])) {
            $this->api_endpoint = rtrim($config['endpoint'], '/');
        }

        // Cập nhật model nếu được chỉ định
        if (isset($config['model'])) {
            $this->model = $config['model'];
        }

        // Khởi tạo cấu hình
        parent::initialize($this->api_key, $config);

        // Kiểm tra kết nối và lấy thông tin model
        $this->updateModelInfo();
    }

    /**
     * {@inheritdoc}
     */
    protected function validateConfig() {
        try {
            $response = $this->sendRequest($this->api_endpoint . '/health', [], 'GET');
            return isset($response['status']) && $response['status'] === 'ok';
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Cập nhật thông tin về model đang sử dụng
     */
    protected function updateModelInfo() {
        try {
            $response = $this->sendRequest($this->api_endpoint . '/model/info', [], 'GET');
            $this->model_info = $response;
        } catch (\Exception $e) {
            $this->model_info = [
                'name' => $this->model,
                'type' => 'unknown',
                'parameters' => 'unknown'
            ];
        }
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
            'prompt' => $prompt,
            'model' => $this->model,
            'temperature' => 0.7,
            'max_tokens' => 1000,
            'stop' => [],
            'echo' => false,
        ], $options);

        // Gửi request với retry
        $response = $this->withRetry(function() use ($data) {
            return $this->sendRequest(
                $this->api_endpoint . '/v1/completions',
                $data
            );
        });

        // Xử lý response
        $result = [
            'text' => $response['choices'][0]['text'],
            'model' => $this->model,
            'finish_reason' => $response['choices'][0]['finish_reason'] ?? 'length',
        ];

        // Cache response
        $this->cacheResponse($cache_key, $result);

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getModelInfo() {
        return array_merge([
            'name' => $this->model,
            'provider' => 'LocalLM',
            'endpoint' => $this->api_endpoint,
            'capabilities' => [
                'chat' => true,
                'completion' => true,
                'streaming' => true,
            ]
        ], $this->model_info);
    }

    /**
     * Gửi prompt với streaming response
     * 
     * @param string $prompt Prompt text
     * @param callable $callback Callback function để xử lý từng phần của response
     * @param array $options Tùy chọn bổ sung
     * @return void
     */
    public function generateCompletionStream($prompt, callable $callback, array $options = []) {
        // Chuẩn bị request data
        $data = array_merge([
            'prompt' => $prompt,
            'model' => $this->model,
            'temperature' => 0.7,
            'max_tokens' => 1000,
            'stream' => true,
        ], $options);

        // Thiết lập streaming request
        $args = [
            'method' => 'POST',
            'timeout' => 0, // Không timeout cho streaming
            'headers' => $this->getRequestHeaders(),
            'body' => json_encode($data),
            'stream' => true,
        ];

        $response = wp_remote_request($this->api_endpoint . '/v1/completions', $args);

        if (is_wp_error($response)) {
            throw new \Exception($response->get_error_message());
        }

        $body = wp_remote_retrieve_body($response);
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $body);
        rewind($stream);

        // Xử lý từng dòng của response
        while (($line = fgets($stream)) !== false) {
            if (strpos($line, 'data: ') === 0) {
                $json = json_decode(substr($line, 6), true);
                if ($json && isset($json['choices'][0]['text'])) {
                    $callback($json['choices'][0]['text']);
                }
            }
        }

        fclose($stream);
    }

    /**
     * Lấy danh sách các models có sẵn
     * 
     * @return array Danh sách models
     */
    public function getAvailableModels() {
        try {
            $response = $this->sendRequest($this->api_endpoint . '/v1/models', [], 'GET');
            return $response['data'] ?? [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Tải model mới
     * 
     * @param string $model_id ID của model cần tải
     * @return bool Kết quả tải model
     */
    public function loadModel($model_id) {
        try {
            $response = $this->sendRequest(
                $this->api_endpoint . '/model/load',
                ['model_id' => $model_id]
            );
            $this->model = $model_id;
            $this->updateModelInfo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Giải phóng model hiện tại
     * 
     * @return bool Kết quả giải phóng model
     */
    public function unloadModel() {
        try {
            $this->sendRequest($this->api_endpoint . '/model/unload', []);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
} 