<?php
namespace WPSysMaster\AI;

if (!defined('ABSPATH')) exit;

/**
 * Abstract class cho AI Provider
 */
abstract class AbstractAIProvider implements AIProviderInterface {
    /**
     * API key
     * @var string
     */
    protected $api_key;

    /**
     * Cấu hình
     * @var array
     */
    protected $config;

    /**
     * Trạng thái sẵn sàng
     * @var bool
     */
    protected $is_ready = false;

    /**
     * Cache cho responses
     * @var array
     */
    protected $response_cache = [];

    /**
     * Constructor
     */
    public function __construct() {
        $this->config = [
            'timeout' => 30,
            'cache_enabled' => true,
            'cache_ttl' => 3600,
            'retry_attempts' => 3,
            'retry_delay' => 1000, // milliseconds
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function initialize($api_key, array $config = []) {
        $this->api_key = $api_key;
        $this->config = array_merge($this->config, $config);
        $this->is_ready = $this->validateConfig();
    }

    /**
     * {@inheritdoc}
     */
    public function isReady() {
        return $this->is_ready;
    }

    /**
     * {@inheritdoc}
     */
    public function generateBatchCompletions(array $prompts, array $options = []) {
        $responses = [];
        foreach ($prompts as $prompt) {
            $responses[] = $this->generateCompletion($prompt, $options);
        }
        return $responses;
    }

    /**
     * Kiểm tra cấu hình
     * @return bool
     */
    protected function validateConfig() {
        return !empty($this->api_key);
    }

    /**
     * Gửi request HTTP
     * 
     * @param string $endpoint
     * @param array $data
     * @param string $method
     * @return array
     */
    protected function sendRequest($endpoint, $data = [], $method = 'POST') {
        $args = [
            'method' => $method,
            'timeout' => $this->config['timeout'],
            'headers' => $this->getRequestHeaders(),
            'body' => json_encode($data),
        ];

        $response = wp_remote_request($endpoint, $args);

        if (is_wp_error($response)) {
            throw new \Exception($response->get_error_message());
        }

        $body = wp_remote_retrieve_body($response);
        return json_decode($body, true);
    }

    /**
     * Lấy headers cho request
     * 
     * @return array
     */
    protected function getRequestHeaders() {
        return [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->api_key,
        ];
    }

    /**
     * Cache response
     * 
     * @param string $key
     * @param mixed $data
     * @return void
     */
    protected function cacheResponse($key, $data) {
        if (!$this->config['cache_enabled']) {
            return;
        }

        set_transient(
            'wp_sysmaster_ai_' . md5($key),
            $data,
            $this->config['cache_ttl']
        );
    }

    /**
     * Lấy response từ cache
     * 
     * @param string $key
     * @return mixed|null
     */
    protected function getCachedResponse($key) {
        if (!$this->config['cache_enabled']) {
            return null;
        }

        return get_transient('wp_sysmaster_ai_' . md5($key));
    }

    /**
     * Retry mechanism
     * 
     * @param callable $callback
     * @return mixed
     */
    protected function withRetry($callback) {
        $attempts = 0;
        $lastException = null;

        while ($attempts < $this->config['retry_attempts']) {
            try {
                return $callback();
            } catch (\Exception $e) {
                $lastException = $e;
                $attempts++;
                if ($attempts < $this->config['retry_attempts']) {
                    usleep($this->config['retry_delay'] * 1000);
                }
            }
        }

        throw $lastException;
    }
} 