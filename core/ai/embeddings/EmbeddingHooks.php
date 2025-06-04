<?php
namespace WPSysMaster\AI\Embeddings;

if (!defined('ABSPATH')) exit;

/**
 * Class quản lý các hooks cho embeddings
 */
class EmbeddingHooks {
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
        $this->initHooks();
    }

    /**
     * Khởi tạo các hooks
     */
    protected function initHooks() {
        // Tự động tạo/cập nhật embedding khi bài viết được lưu
        add_action('save_post', [$this, 'handlePostSave'], 10, 3);

        // Xóa embedding khi bài viết bị xóa
        add_action('before_delete_post', [$this, 'handlePostDelete']);

        // Thêm meta box cho bài viết
        add_action('add_meta_boxes', [$this, 'addEmbeddingMetaBox']);

        // Thêm bulk action
        add_filter('bulk_actions-edit-post', [$this, 'addBulkActions']);
        add_filter('handle_bulk_actions-edit-post', [$this, 'handleBulkActions'], 10, 3);
    }

    /**
     * Xử lý khi bài viết được lưu
     * 
     * @param int $post_id ID của bài viết
     * @param \WP_Post $post Post object
     * @param bool $update Có phải update không
     */
    public function handlePostSave($post_id, $post, $update) {
        // Bỏ qua autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Chỉ xử lý các post types được cấu hình
        $allowed_types = get_option('wp_sysmaster_embedding_post_types', ['post']);
        if (!in_array($post->post_type, $allowed_types)) {
            return;
        }

        // Chỉ xử lý bài viết đã publish
        if ($post->post_status !== 'publish') {
            return;
        }

        // Tạo/cập nhật embedding
        $this->manager->embedPost($post_id);
    }

    /**
     * Xử lý khi bài viết bị xóa
     * 
     * @param int $post_id ID của bài viết
     */
    public function handlePostDelete($post_id) {
        $this->manager->deleteEmbedding($post_id);
    }

    /**
     * Thêm meta box cho bài viết
     */
    public function addEmbeddingMetaBox() {
        add_meta_box(
            'wp-sysmaster-embedding',
            __('AI Embedding', 'wp-sysmaster'),
            [$this, 'renderMetaBox'],
            null,
            'side'
        );
    }

    /**
     * Render meta box
     * 
     * @param \WP_Post $post Post object
     */
    public function renderMetaBox($post) {
        $embedding = $this->manager->getEmbedding($post->ID);
        $has_embedding = !empty($embedding);
        ?>
        <div class="wp-sysmaster-embedding-meta">
            <p>
                <strong><?php _e('Status:', 'wp-sysmaster'); ?></strong>
                <?php if ($has_embedding): ?>
                    <span class="dashicons dashicons-yes-alt" style="color: green;"></span>
                    <?php _e('Embedding created', 'wp-sysmaster'); ?>
                <?php else: ?>
                    <span class="dashicons dashicons-no-alt" style="color: red;"></span>
                    <?php _e('No embedding', 'wp-sysmaster'); ?>
                <?php endif; ?>
            </p>
            <?php if ($has_embedding): ?>
                <p>
                    <strong><?php _e('Last updated:', 'wp-sysmaster'); ?></strong><br>
                    <?php echo esc_html($embedding['metadata']['last_updated']); ?>
                </p>
            <?php endif; ?>
            <p>
                <button type="button" class="button" onclick="wpSysmasterUpdateEmbedding(<?php echo $post->ID; ?>)">
                    <?php _e('Update Embedding', 'wp-sysmaster'); ?>
                </button>
            </p>
        </div>
        <script>
        function wpSysmasterUpdateEmbedding(postId) {
            var button = event.target;
            button.disabled = true;
            
            // Gọi API để cập nhật embedding
            wp.apiRequest({
                path: '/wp-sysmaster/v1/embeddings/create/' + postId,
                method: 'POST'
            }).then(function(response) {
                alert('Embedding updated successfully');
                location.reload();
            }).catch(function(error) {
                alert('Failed to update embedding: ' + error.message);
                button.disabled = false;
            });
        }
        </script>
        <?php
    }

    /**
     * Thêm bulk actions
     * 
     * @param array $actions Danh sách actions
     * @return array
     */
    public function addBulkActions($actions) {
        $actions['create_embeddings'] = __('Create Embeddings', 'wp-sysmaster');
        return $actions;
    }

    /**
     * Xử lý bulk actions
     * 
     * @param string $redirect_to URL redirect
     * @param string $action Action name
     * @param array $post_ids Danh sách post IDs
     * @return string
     */
    public function handleBulkActions($redirect_to, $action, $post_ids) {
        if ($action !== 'create_embeddings') {
            return $redirect_to;
        }

        $processed = 0;
        foreach ($post_ids as $post_id) {
            if ($this->manager->embedPost($post_id)) {
                $processed++;
            }
        }

        return add_query_arg([
            'created_embeddings' => $processed,
            'total_posts' => count($post_ids),
        ], $redirect_to);
    }
} 