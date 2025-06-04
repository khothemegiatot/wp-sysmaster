# WP SysMaster - WordPress Plugin with AI Integration

A powerful WordPress plugin that enhances your system with AI capabilities and additional features.

## Features

- AI Integration (OpenAI, Google Gemini, Local LM)
- Custom Upload Management
- SMTP Configuration
- Security Enhancements
- Multi-language Support

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
│   ├── admin/             # Admin interface management
│   │   ├── Menu.php       # Admin menu management
│   │   └── init.php       # Admin initialization
│   │
│   ├── ai/                # AI Integration
│   │   ├── providers/     # AI providers
│   │   │   ├── OpenAIProvider.php
│   │   │   ├── GeminiProvider.php
│   │   │   └── LocalLMProvider.php
│   │   │
│   │   ├── embeddings/    # Embeddings handling
│   │   │   ├── EmbeddingAPI.php
│   │   │   ├── EmbeddingManager.php
│   │   │   └── EmbeddingHooks.php
│   │   │
│   │   ├── settings/      # AI settings
│   │   │   ├── AISettingsPage.php
│   │   │   └── init.php
│   │   │
│   │   ├── AIProviderInterface.php
│   │   ├── AbstractAIProvider.php
│   │   └── AIProviderFactory.php
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
├── vendor/               # Third-party libraries (Composer)
├── wp-sysmaster.php     # Main plugin file
├── uninstall.php        # Uninstall handler
└── README.md            # Documentation
```

## Code Organization Principles

1. **Namespace**
   - Uses `WPSysMaster` namespace for the entire plugin
   - Subnamespaces correspond to directory structure
   - Example: `WPSysMaster\AI\Providers\OpenAIProvider`

2. **Autoloading**
   - Uses PSR-4 autoloading
   - Registers autoloader in main plugin file
   - All classes follow PSR-4 naming convention

3. **Dependency Injection**
   - Uses constructor injection
   - Avoids direct object instantiation in classes
   - Uses Factory pattern when needed

4. **Hooks & Filters**
   - Registers hooks in each module's init.php
   - Uses separate methods for hook callbacks
   - Prefixes all hook names with `wp_sysmaster_`

5. **Database**
   - Prefixes all option names with `wp_sysmaster_`
   - Uses WordPress Options API for settings
   - Creates separate tables for complex data

6. **Templates**
   - Separates logic and presentation
   - Uses template files in templates/ directory
   - Allows template override in theme

7. **Security**
   - Checks nonce for all form submissions
   - Escapes output with esc_*() functions
   - Verifies capabilities before actions

## System Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- WordPress admin privileges

## Installation

1. Download the plugin from WordPress.org or this repository
2. Extract and upload `wp-sysmaster` directory to `/wp-content/plugins/`
3. Activate the plugin through WordPress Plugins menu

## Configuration

### AI Settings

1. Go to **WP SysMaster > AI Settings**
2. Configure your AI providers:
   - OpenAI API Key and Model
   - Google Gemini API Key
   - Local LM Endpoint
3. Save settings

### Upload Settings

1. Go to **WP SysMaster > Upload**
2. Configure custom MIME types
3. Set file naming rules
4. Save settings

## Development

### Available Hooks

```php
// Modify default settings
add_filter('wp_sysmaster_default_options', 'your_function');

// Run before saving settings
add_action('wp_sysmaster_before_save_options', 'your_function');

// Run after saving settings
add_action('wp_sysmaster_after_save_options', 'your_function');
```

## Support

If you need help:

1. Check the [documentation](https://www.phanxuanchanh.com/wp-sysmaster)
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