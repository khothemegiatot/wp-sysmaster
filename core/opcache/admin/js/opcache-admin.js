jQuery(document).ready(function($) {
    $('#wp-sysmaster-flush-opcache').on('click', function(e) {
        e.preventDefault();

        if (!confirm(wpSysMasterOPcache.i18n.confirmFlush)) {
            return;
        }

        $.ajax({
            url: wpSysMasterOPcache.ajaxUrl,
            type: 'POST',
            data: {
                action: 'wp_sysmaster_flush_opcache',
                _ajax_nonce: wpSysMasterOPcache.nonce
            },
            success: function(response) {
                if (response.success) {
                    alert(wpSysMasterOPcache.i18n.flushSuccess);
                    location.reload();
                } else {
                    alert(response.data.message || wpSysMasterOPcache.i18n.flushError);
                }
            },
            error: function() {
                alert(wpSysMasterOPcache.i18n.flushError);
            }
        });
    });
}); 