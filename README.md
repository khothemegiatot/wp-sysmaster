# WP SysMaster - WordPress Plugin

A powerful WordPress plugin that enhances your system with custom functionality and security features.

## Features

- Dashboard Overview
- Upload Management
  - Custom MIME types
  - File upload handling
- SMTP Configuration
  - Email server settings
  - Test email functionality
- OPcache Management
  - Cache status monitoring
  - Cache optimization
- Code Injection
  - PHP Code execution
  - Header Scripts
  - Body Scripts
  - Footer Scripts
  - Custom CSS

## Directory Structure

```
wp-sysmaster/
├── assets/                 # Static resources (CSS, JS, images)
│   ├── css/
│   │   ├── admin/         # Admin CSS
│   │   └── public/        # Frontend CSS
│   ├── js/
│   │   ├── admin/         # Admin JavaScript
│   │   └── public/        # Frontend JavaScript
│   └── images/            # Images
│
├── core/                   # Core source code
│   ├── common/            # Common functionality
│   │   ├── Upload.php     # Upload management
│   │   ├── SMTP.php       # SMTP configuration
│   │   └── CustomCode.php # Code injection
│   │
│   ├── opcache/           # OPcache management
│   │   └── admin/         # OPcache admin interface
│   │
│   └── includes/          # Helper files
│       ├── helpers.php    # Utility functions
│       └── constants.php  # Constants
│
├── languages/             # Language files
│   ├── wp-sysmaster.pot
│   └── wp-sysmaster-vi.po
│
├── templates/             # Template files
│   ├── admin/            # Admin templates
│   └── public/           # Frontend templates
│
├── main.php              # Main plugin file
├── uninstall.php         # Uninstall handler
└── README.md            # Documentation
```

## Code Organization Principles

1. **Security**
   - Checks ABSPATH constant
   - Implements secure file handling
   - Uses WordPress security functions

2. **Hooks & Filters**
   - Prefixes all hook names with `wp_sysmaster_`
   - Uses WordPress standard hooks
   - Implements custom filters

3. **Database**
   - Uses WordPress Options API
   - Implements custom settings
   - Follows WordPress data structure

4. **Templates**
   - Separates logic and presentation
   - Uses template files in templates/ directory
   - Allows template override in theme

## System Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- WordPress admin privileges

## Installation

1. Download the plugin from WordPress.org or this repository
2. Extract and upload `wp-sysmaster` directory to `/wp-content/plugins/`
3. Activate the plugin through WordPress Plugins menu

## Features

### Dashboard

The main dashboard provides an overview of the plugin's functionality and system status.

### Upload Management

Configure custom MIME types and manage file uploads with enhanced security features.

### SMTP Configuration

Set up and test SMTP email server settings for reliable email delivery.

### OPcache Management

Monitor and optimize PHP OPcache for improved performance.

### Code Injection

Add custom code to different parts of your WordPress site:
- PHP Code execution
- Header Scripts
- Body Scripts
- Footer Scripts
- Custom CSS

## Development

### Available Hooks

```php
// Modify upload settings
add_filter('wp_sysmaster_upload_settings', 'your_function');

// Customize SMTP configuration
add_filter('wp_sysmaster_smtp_settings', 'your_function');

// Modify code injection
add_filter('wp_sysmaster_code_settings', 'your_function');
```

## Support

If you need help:

1. Check the documentation
2. Create an issue on GitHub
3. Send support email

## Contributing

We welcome contributions from the community. To contribute:

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This plugin is released under the GNU General Public License v2 or later (GPL v2 or later). This means you can:

- Use the plugin for any purpose
- Modify the plugin and distribute your modifications
- Redistribute the plugin
- All derivative works must also be licensed under GPL v2 or later

For more details, see [GNU General Public License v2](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)