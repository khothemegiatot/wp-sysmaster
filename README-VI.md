# WP SysMaster - Plugin WordPress

Plugin WordPress mạnh mẽ cho phép tùy chỉnh hệ thống và tăng cường bảo mật.

## Tính năng

- Tổng quan Dashboard
- Quản lý Upload
  - Tùy chỉnh MIME types
  - Xử lý tải lên file
- Cấu hình SMTP
  - Cài đặt máy chủ email
  - Chức năng kiểm tra email
- Quản lý OPcache
  - Giám sát trạng thái cache
  - Tối ưu hóa cache
- Chèn mã
  - Thực thi mã PHP
  - Scripts Header
  - Scripts Body
  - Scripts Footer
  - CSS tùy chỉnh

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
│   ├── common/            # Chức năng chung
│   │   ├── Upload.php     # Quản lý upload
│   │   ├── SMTP.php       # Cấu hình SMTP
│   │   └── CustomCode.php # Chèn mã
│   │
│   ├── opcache/           # Quản lý OPcache
│   │   └── admin/         # Giao diện quản lý OPcache
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
├── main.php              # File chính của plugin
├── uninstall.php         # Xử lý gỡ cài đặt
└── README.md            # Tài liệu
```

## Nguyên tắc tổ chức code

1. **Bảo mật**
   - Kiểm tra hằng số ABSPATH
   - Xử lý file an toàn
   - Sử dụng các hàm bảo mật của WordPress

2. **Hooks & Filters**
   - Prefix tất cả hook names với `wp_sysmaster_`
   - Sử dụng hooks tiêu chuẩn của WordPress
   - Triển khai các filter tùy chỉnh

3. **Database**
   - Sử dụng WordPress Options API
   - Triển khai cài đặt tùy chỉnh
   - Tuân thủ cấu trúc dữ liệu WordPress

4. **Templates**
   - Tách biệt logic và presentation
   - Sử dụng template files trong thư mục templates/
   - Cho phép override template trong theme

## Yêu cầu hệ thống

- WordPress 5.0 trở lên
- PHP 7.4 trở lên
- Quyền quản trị WordPress

## Cài đặt

1. Tải plugin từ WordPress.org hoặc tải trực tiếp từ repository này
2. Giải nén và upload thư mục `wp-sysmaster` vào `/wp-content/plugins/`
3. Kích hoạt plugin trong menu Plugins của WordPress

## Tính năng

### Dashboard

Dashboard chính cung cấp tổng quan về chức năng của plugin và trạng thái hệ thống.

### Quản lý Upload

Cấu hình MIME types tùy chỉnh và quản lý tải lên file với các tính năng bảo mật nâng cao.

### Cấu hình SMTP

Thiết lập và kiểm tra cài đặt máy chủ SMTP để đảm bảo gửi email đáng tin cậy.

### Quản lý OPcache

Giám sát và tối ưu hóa PHP OPcache để cải thiện hiệu suất.

### Chèn mã

Thêm mã tùy chỉnh vào các phần khác nhau của trang WordPress:
- Thực thi mã PHP
- Scripts Header
- Scripts Body
- Scripts Footer
- CSS tùy chỉnh

## Phát triển

### Hooks có sẵn

```php
// Tùy chỉnh cài đặt upload
add_filter('wp_sysmaster_upload_settings', 'your_function');

// Tùy chỉnh cấu hình SMTP
add_filter('wp_sysmaster_smtp_settings', 'your_function');

// Tùy chỉnh chèn mã
add_filter('wp_sysmaster_code_settings', 'your_function');
```

## Hỗ trợ

Nếu bạn cần hỗ trợ:

1. Kiểm tra tài liệu
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