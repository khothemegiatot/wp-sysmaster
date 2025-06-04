# WP SysMaster - Plugin WordPress tích hợp AI

## Cấu trúc thư mục

```
wp-sysmaster/
├── assets/                 # Tài nguyên tĩnh (CSS, JS, images)
│   ├── css/
│   │   ├── admin/         # CSS cho trang admin
│   │   └── public/        # CSS cho frontend
│   ├── js/
│   │   ├── admin/         # JavaScript cho trang admin
│   │   └── public/        # JavaScript cho frontend
│   └── images/            # Hình ảnh
│
├── core/                   # Mã nguồn chính
│   ├── admin/             # Quản lý giao diện admin
│   │   ├── Menu.php       # Quản lý menu admin
│   │   └── init.php       # Khởi tạo admin
│   │
│   ├── ai/                # Tích hợp AI
│   │   ├── providers/     # Các nhà cung cấp AI
│   │   │   ├── OpenAIProvider.php
│   │   │   ├── GeminiProvider.php
│   │   │   └── LocalLMProvider.php
│   │   │
│   │   ├── embeddings/    # Xử lý embeddings
│   │   │   ├── EmbeddingAPI.php
│   │   │   ├── EmbeddingManager.php
│   │   │   └── EmbeddingHooks.php
│   │   │
│   │   ├── settings/      # Cài đặt AI
│   │   │   ├── AISettingsPage.php
│   │   │   └── init.php
│   │   │
│   │   ├── AIProviderInterface.php
│   │   ├── AbstractAIProvider.php
│   │   └── AIProviderFactory.php
│   │
│   └── includes/          # Các file hỗ trợ
│       ├── helpers.php    # Hàm tiện ích
│       └── constants.php  # Các hằng số
│
├── languages/             # File ngôn ngữ
│   ├── wp-sysmaster.pot
│   └── wp-sysmaster-vi.po
│
├── templates/             # Template files
│   ├── admin/            # Template cho admin
│   └── public/           # Template cho frontend
│
├── vendor/               # Thư viện bên thứ 3 (Composer)
├── wp-sysmaster.php     # File chính của plugin
├── uninstall.php        # Xử lý gỡ cài đặt
└── README.md            # Tài liệu
```

## Nguyên tắc tổ chức code

1. **Namespace**
   - Sử dụng namespace `WPSysMaster` cho toàn bộ plugin
   - Các namespace con tương ứng với cấu trúc thư mục
   - Ví dụ: `WPSysMaster\AI\Providers\OpenAIProvider`

2. **Autoloading**
   - Sử dụng PSR-4 autoloading
   - Đăng ký autoloader trong file chính của plugin
   - Tất cả class phải tuân thủ quy tắc đặt tên PSR-4

3. **Dependency Injection**
   - Sử dụng constructor injection
   - Tránh khởi tạo trực tiếp object trong class
   - Sử dụng Factory pattern khi cần

4. **Hooks & Filters**
   - Đăng ký hooks trong file init.php của mỗi module
   - Sử dụng method riêng cho mỗi hook callback
   - Prefix tất cả hook names với `wp_sysmaster_`

5. **Database**
   - Prefix tất cả option names với `wp_sysmaster_`
   - Sử dụng WordPress Options API cho cài đặt
   - Tạo bảng riêng cho dữ liệu phức tạp

6. **Templates**
   - Tách biệt logic và presentation
   - Sử dụng template files trong thư mục templates/
   - Cho phép override template trong theme

7. **Internationalization**
   - Sử dụng hàm __() cho text
   - Load textdomain trong hook init
   - Cung cấp file .pot cho translation

8. **Security**
   - Kiểm tra nonce cho tất cả form submissions
   - Escape output với các hàm esc_*()
   - Kiểm tra capabilities trước khi thực hiện action

9. **Error Handling**
   - Sử dụng WP_Error cho error handling
   - Log errors với error_log()
   - Hiển thị user-friendly error messages

10. **Assets**
    - Enqueue scripts/styles đúng hook
    - Minify và combine files cho production
    - Sử dụng version number cho cache busting

## Quy tắc code style

1. **PHP**
   - Tuân thủ WordPress Coding Standards
   - Sử dụng PHP DocBlocks cho documentation
   - Indent với 4 spaces

2. **JavaScript**
   - Sử dụng ES6+ features
   - Đóng gói với Webpack
   - Sử dụng JSDoc cho documentation

3. **CSS**
   - Sử dụng BEM naming convention
   - Prefix tất cả class với `wp-sysmaster-`
   - Tổ chức code theo component

Plugin WordPress mạnh mẽ cho phép tùy chỉnh hệ thống và thêm các tính năng bổ sung.

## Tính năng

- Quản lý cấu hình SMTP
- Tối ưu hóa OPCache
- Tích hợp Buy Me a Coffee
- Hỗ trợ đa ngôn ngữ

## Yêu cầu hệ thống

- WordPress 5.0 trở lên
- PHP 7.4 trở lên
- Quyền quản trị WordPress

## Cài đặt

1. Tải plugin từ WordPress.org hoặc tải trực tiếp từ repository này
2. Giải nén và upload thư mục `wp-sysmaster` vào `/wp-content/plugins/`
3. Kích hoạt plugin trong menu Plugins của WordPress

## Cấu hình

### Cấu hình SMTP

1. Truy cập **WP SysMaster > Cài đặt > SMTP**
2. Nhập thông tin máy chủ SMTP:
   - Host
   - Port
   - Username
   - Password
   - From Email
   - From Name
3. Lưu cài đặt

### Tối ưu OPCache

1. Truy cập **WP SysMaster > Công cụ > OPCache**
2. Xem thống kê và quản lý cache
3. Sử dụng nút "Xóa Cache" để làm mới OPCache

### Buy Me a Coffee

1. Truy cập **WP SysMaster > Cài đặt > Buy Me a Coffee**
2. Nhập Buy Me a Coffee ID của bạn
3. Bật/tắt hiển thị nút Buy Me a Coffee trong bài viết

## Phát triển

### Cấu trúc thư mục

```
wp-sysmaster/
├── add-ons/
├── assets/
├── core/
├── languages/
├── opcache/
├── ui/
├── config.php
├── main.php
└── README.md
```

### Hooks và Filters

Plugin cung cấp các hooks và filters sau để mở rộng chức năng:

```php
// Thay đổi cài đặt mặc định
add_filter('wp_sysmaster_default_options', 'your_function');

// Chạy trước khi lưu cài đặt
add_action('wp_sysmaster_before_save_options', 'your_function');

// Chạy sau khi lưu cài đặt
add_action('wp_sysmaster_after_save_options', 'your_function');
```

## Hỗ trợ

Nếu bạn cần hỗ trợ, vui lòng:

1. Kiểm tra [tài liệu](https://www.phanxuanchanh.com/wp-sysmaster)
2. Tạo issue trên GitHub
3. Gửi email hỗ trợ

## Đóng góp

Chúng tôi luôn chào đón đóng góp từ cộng đồng. Để đóng góp:

1. Fork repository
2. Tạo branch mới (`git checkout -b feature/AmazingFeature`)
3. Commit thay đổi (`git commit -m 'Add some AmazingFeature'`)
4. Push lên branch (`git push origin feature/AmazingFeature`)
5. Tạo Pull Request

## Giấy phép

Plugin này được phát hành dưới giấy phép GPL v2 hoặc mới hơn.