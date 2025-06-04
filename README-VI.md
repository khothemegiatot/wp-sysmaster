# WP SysMaster - Plugin WordPress tích hợp AI

Plugin WordPress mạnh mẽ cho phép tùy chỉnh hệ thống và tích hợp các tính năng AI.

## Tính năng

- Tích hợp AI (OpenAI, Google Gemini, Local LM)
- Quản lý Upload tùy chỉnh
- Cấu hình SMTP
- Tăng cường bảo mật
- Hỗ trợ đa ngôn ngữ

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

7. **Security**
   - Kiểm tra nonce cho tất cả form submissions
   - Escape output với các hàm esc_*()
   - Kiểm tra capabilities trước khi thực hiện action

## Yêu cầu hệ thống

- WordPress 5.0 trở lên
- PHP 7.4 trở lên
- Quyền quản trị WordPress

## Cài đặt

1. Tải plugin từ WordPress.org hoặc tải trực tiếp từ repository này
2. Giải nén và upload thư mục `wp-sysmaster` vào `/wp-content/plugins/`
3. Kích hoạt plugin trong menu Plugins của WordPress

## Cấu hình

### Cài đặt AI

1. Truy cập **WP SysMaster > AI Settings**
2. Cấu hình các nhà cung cấp AI:
   - OpenAI API Key và Model
   - Google Gemini API Key
   - Local LM Endpoint
3. Lưu cài đặt

### Cài đặt Upload

1. Truy cập **WP SysMaster > Upload**
2. Cấu hình MIME types tùy chỉnh
3. Thiết lập quy tắc đặt tên file
4. Lưu cài đặt

## Phát triển

### Hooks có sẵn

```php
// Thay đổi cài đặt mặc định
add_filter('wp_sysmaster_default_options', 'your_function');

// Chạy trước khi lưu cài đặt
add_action('wp_sysmaster_before_save_options', 'your_function');

// Chạy sau khi lưu cài đặt
add_action('wp_sysmaster_after_save_options', 'your_function');
```

## Hỗ trợ

Nếu bạn cần hỗ trợ:

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

Plugin này được phát hành dưới Giấy phép Công cộng GNU phiên bản 2 hoặc mới hơn (GPL v2 or later). Điều này có nghĩa bạn có thể:

- Sử dụng plugin cho bất kỳ mục đích nào
- Sửa đổi plugin và phân phối các sửa đổi của bạn
- Phân phối lại plugin
- Tất cả các phiên bản phái sinh cũng phải sử dụng giấy phép GPL v2 hoặc mới hơn

Xem thêm chi tiết tại [Giấy phép Công cộng GNU v2](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)