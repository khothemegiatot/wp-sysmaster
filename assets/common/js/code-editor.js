jQuery(document).ready(function($) {
    // Khởi tạo CodeMirror cho từng loại code
    function initCodeEditor(selector, mode) {
        var editor = CodeMirror.fromTextArea(document.querySelector(selector), {
            lineNumbers: true,
            matchBrackets: true,
            mode: mode,
            indentUnit: 4,
            indentWithTabs: false,
            enterMode: "keep",
            tabMode: "shift",
            theme: 'default',
            autoCloseBrackets: true,
            autoCloseTags: true,
            foldGutter: true,
            gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
            extraKeys: {
                "Ctrl-Q": function(cm) {
                    cm.foldCode(cm.getCursor());
                },
                "Ctrl-Space": "autocomplete"
            },
            hintOptions: {
                completeSingle: false
            }
        });

        // Tự động format code khi nhấn Ctrl-F
        editor.addKeyMap({
            "Ctrl-F": function(cm) {
                var pos = cm.getCursor();
                cm.setValue(cm.getValue());
                cm.setCursor(pos);
            }
        });

        // Lưu vị trí con trỏ khi focus ra ngoài
        editor.on("blur", function() {
            editor.save();
        });

        return editor;
    }

    // Khởi tạo editor cho từng tab
    var activeTab = $('h2.nav-tab-wrapper .nav-tab-active').attr('href');
    if (activeTab) {
        var tabId = activeTab.split('tab=')[1];
        
        switch(tabId) {
            case 'php':
                initCodeEditor('textarea[name="wp_sysmaster_code_settings[php_code]"]', 'text/x-php');
                break;
            case 'css':
                initCodeEditor('textarea[name="wp_sysmaster_code_settings[custom_css]"]', 'text/css');
                break;
            case 'header':
            case 'body':
            case 'footer':
                var selector = 'textarea[name="wp_sysmaster_code_settings[' + tabId + '_scripts]"]';
                initCodeEditor(selector, 'text/html');
                break;
        }
    }

    // Xử lý chuyển tab
    $('.nav-tab-wrapper .nav-tab').on('click', function() {
        var href = $(this).attr('href');
        if (href) {
            var tabId = href.split('tab=')[1];
            // Khởi tạo lại editor cho tab mới
            setTimeout(function() {
                switch(tabId) {
                    case 'php':
                        initCodeEditor('textarea[name="wp_sysmaster_code_settings[php_code]"]', 'text/x-php');
                        break;
                    case 'css':
                        initCodeEditor('textarea[name="wp_sysmaster_code_settings[custom_css]"]', 'text/css');
                        break;
                    case 'header':
                    case 'body':
                    case 'footer':
                        var selector = 'textarea[name="wp_sysmaster_code_settings[' + tabId + '_scripts]"]';
                        initCodeEditor(selector, 'text/html');
                        break;
                }
            }, 100);
        }
    });
}); 