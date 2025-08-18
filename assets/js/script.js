jQuery(document).ready(function($) {
    $('#water-meter-form').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $submitBtn = $form.find('.submit-btn');
        var $message = $('#form-message');
        
        // Disable submit button
        $submitBtn.prop('disabled', true).text('Lähetetään...');
        
        // Clear previous messages
        $message.removeClass('success error').hide();
        
        // Get form data
        var formData = {
            action: 'submit_water_reading',
            nonce: wmr_ajax.nonce,
            condominium_number: $('#condominium_number').val(),
            hot_water: $('#hot_water').val(),
            cold_water: $('#cold_water').val(),
            notes: $('#notes').val()
        };
        
        // Submit via AJAX
        $.ajax({
            url: wmr_ajax.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                try {
                    var result = JSON.parse(response);
                    
                    if (result.success) {
                        $message.addClass('success').text(result.message).show();
                        $form[0].reset();
                    } else {
                        $message.addClass('error').text(result.message).show();
                    }
                } catch (e) {
                    $message.addClass('error').text('Virhe vastauksen käsittelyssä.').show();
                }
            },
            error: function() {
                $message.addClass('error').text('Verkkovirhe. Yritä uudelleen.').show();
            },
            complete: function() {
                // Re-enable submit button
                $submitBtn.prop('disabled', false).text('Lähetä lukemat');
            }
        });
    });
    
    // Auto-hide messages after 5 seconds
    setTimeout(function() {
        $('#form-message').fadeOut();
    }, 5000);
});
