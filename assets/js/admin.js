jQuery(document).ready(function($) {
    // Xử lý form AI Settings
    var $aiSettingsForm = $('.wp-sysmaster-ai-settings form');
    
    if ($aiSettingsForm.length) {
        $aiSettingsForm.on('submit', function(e) {
            var $submitButton = $(this).find(':submit');
            
            // Disable button
            $submitButton.prop('disabled', true);
            
            // Re-enable after 2s
            setTimeout(function() {
                $submitButton.prop('disabled', false);
            }, 2000);
        });
    }
    
    // Toggle password visibility
    $('.wp-sysmaster-toggle-password').on('click', function(e) {
        e.preventDefault();
        
        var $input = $($(this).data('target'));
        var type = $input.attr('type');
        
        $input.attr('type', type === 'password' ? 'text' : 'password');
        $(this).text(type === 'password' ? 'Hide' : 'Show');
    });
}); 