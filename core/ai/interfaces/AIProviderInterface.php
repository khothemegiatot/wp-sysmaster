<?php
namespace WPSysMaster\AI;

if (!defined('ABSPATH')) exit;

/**
 * Interface cho các AI Provider
 */
interface AIProviderInterface {
    /**
     * Khởi tạo provider với API key và các cài đặt
     * 
     * @param string $api_key API key của provider
     * @param array $config Cấu hình bổ sung
     * @return void
     */
    public function initialize($api_key, array $config = []);

    /**
     * Kiểm tra xem provider có sẵn sàng không
     * 
     * @return bool
     */
    public function isReady();

    /**
     * Gửi prompt đến AI và nhận phản hồi
     * 
     * @param string $prompt Prompt gửi đến AI
     * @param array $options Tùy chọn bổ sung
     * @return array Response từ AI
     */
    public function generateCompletion($prompt, array $options = []);

    /**
     * Gửi nhiều prompt cùng lúc và nhận phản hồi
     * 
     * @param array $prompts Danh sách các prompt
     * @param array $options Tùy chọn bổ sung
     * @return array Danh sách các response
     */
    public function generateBatchCompletions(array $prompts, array $options = []);

    /**
     * Lấy thông tin về model đang sử dụng
     * 
     * @return array Thông tin về model
     */
    public function getModelInfo();
} 