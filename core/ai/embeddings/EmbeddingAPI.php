<?php
namespace WPSysMaster\AI\Embeddings;

if (!defined('ABSPATH')) exit;

/**
 * Class quản lý REST API cho embeddings
 */
class EmbeddingAPI {
    /**
     * Namespace cho API
     * @var string
     */
    protected $namespace = 'wp-sysmaster/v1';

    /**
     * EmbeddingManager instance
     * @var EmbeddingManager
     */
    protected $manager;

    /**
     * Constructor
     */
    public function __construct() {
        $this->manager = new EmbeddingManager();
        add_action('rest_api_init', [$this, 'registerRoutes']);
    }

    /**
     * Đăng ký các routes
     */
    public function registerRoutes() {
        // Tạo embedding cho bài viết
        register_rest_route($this->namespace, '/embeddings/create/(?P<id>\d+)', [
            'methods' => 'POST',
            'callback' => [$this, 'createEmbedding'],
            'permission_callback' => [$this, 'checkPermission'],
            'args' => [
                'id' => [
                    'validate_callback' => function($param) {
                        return is_numeric($param);
                    }
                ],
            ],
        ]);

        // Tìm kiếm bài viết tương tự
        register_rest_route($this->namespace, '/embeddings/search', [
            'methods' => 'GET',
            'callback' => [$this, 'searchSimilar'],
            'permission_callback' => [$this, 'checkPermission'],
            'args' => [
                'query' => [
                    'required' => true,
                    'type' => 'string',
                ],
                'limit' => [
                    'type' => 'integer',
                    'default' => 5,
                    'sanitize_callback' => 'absint',
                ],
            ],
        ]);

        // Lấy embedding của bài viết
        register_rest_route($this->namespace, '/embeddings/get/(?P<id>\d+)', [
            'methods' => 'GET',
            'callback' => [$this, 'getEmbedding'],
            'permission_callback' => [$this, 'checkPermission'],
            'args' => [
                'id' => [
                    'validate_callback' => function($param) {
                        return is_numeric($param);
                    }
                ],
            ],
        ]);

        // Xóa embedding
        register_rest_route($this->namespace, '/embeddings/delete/(?P<id>\d+)', [
            'methods' => 'DELETE',
            'callback' => [$this, 'deleteEmbedding'],
            'permission_callback' => [$this, 'checkPermission'],
            'args' => [
                'id' => [
                    'validate_callback' => function($param) {
                        return is_numeric($param);
                    }
                ],
            ],
        ]);
    }

    /**
     * Kiểm tra quyền truy cập API
     * 
     * @param \WP_REST_Request $request Request object
     * @return bool
     */
    public function checkPermission($request) {
        // Kiểm tra API key trong header
        $api_key = $request->get_header('X-WP-SysMaster-Key');
        if (empty($api_key)) {
            return false;
        }

        // So sánh với API key đã cấu hình
        $configured_key = get_option('wp_sysmaster_api_key');
        return $api_key === $configured_key;
    }

    /**
     * Tạo embedding cho bài viết
     * 
     * @param \WP_REST_Request $request Request object
     * @return \WP_REST_Response|\WP_Error
     */
    public function createEmbedding($request) {
        $post_id = $request->get_param('id');
        
        try {
            $result = $this->manager->embedPost($post_id);
            if ($result) {
                return new \WP_REST_Response([
                    'success' => true,
                    'message' => 'Embedding created successfully',
                ], 200);
            } else {
                return new \WP_Error(
                    'embedding_failed',
                    'Failed to create embedding',
                    ['status' => 500]
                );
            }
        } catch (\Exception $e) {
            return new \WP_Error(
                'embedding_error',
                $e->getMessage(),
                ['status' => 500]
            );
        }
    }

    /**
     * Tìm kiếm bài viết tương tự
     * 
     * @param \WP_REST_Request $request Request object
     * @return \WP_REST_Response|\WP_Error
     */
    public function searchSimilar($request) {
        $query = $request->get_param('query');
        $limit = $request->get_param('limit');

        try {
            $results = $this->manager->searchSimilar($query, $limit);
            return new \WP_REST_Response([
                'success' => true,
                'results' => $results,
            ], 200);
        } catch (\Exception $e) {
            return new \WP_Error(
                'search_error',
                $e->getMessage(),
                ['status' => 500]
            );
        }
    }

    /**
     * Lấy embedding của bài viết
     * 
     * @param \WP_REST_Request $request Request object
     * @return \WP_REST_Response|\WP_Error
     */
    public function getEmbedding($request) {
        $post_id = $request->get_param('id');
        
        $embedding = $this->manager->getEmbedding($post_id);
        if ($embedding) {
            return new \WP_REST_Response([
                'success' => true,
                'embedding' => $embedding,
            ], 200);
        } else {
            return new \WP_Error(
                'not_found',
                'Embedding not found',
                ['status' => 404]
            );
        }
    }

    /**
     * Xóa embedding
     * 
     * @param \WP_REST_Request $request Request object
     * @return \WP_REST_Response|\WP_Error
     */
    public function deleteEmbedding($request) {
        $post_id = $request->get_param('id');
        
        $result = $this->manager->deleteEmbedding($post_id);
        if ($result) {
            return new \WP_REST_Response([
                'success' => true,
                'message' => 'Embedding deleted successfully',
            ], 200);
        } else {
            return new \WP_Error(
                'delete_failed',
                'Failed to delete embedding',
                ['status' => 500]
            );
        }
    }
} 