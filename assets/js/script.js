jQuery(document).ready(function($) {
    var currentCondominiumNumber = '';
    
    // Step 1: Handle condominium number submission
    $('#step1-form').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $submitBtn = $form.find('.submit-btn');
        var $message = $('#form-message');
        var condominiumNumber = $('#condominium_number').val();
        
        // Disable submit button
        $submitBtn.prop('disabled', true).text('Tarkistetaan...');
        
        // Clear previous messages
        $message.removeClass('success error').hide();
        
        // Get addresses for the condominium
        var formData = {
            action: 'get_condominium_addresses',
            nonce: wmr_ajax.nonce,
            condominium_number: condominiumNumber
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
                        // Store condominium number for step 2
                        currentCondominiumNumber = condominiumNumber;
                        
                        // Populate address dropdown
                        var $addressSelect = $('#address_select');
                        $addressSelect.empty();
                        $addressSelect.append('<option value="">-- Valitse osoite --</option>');
                        
                        $.each(result.addresses, function(index, address) {
                            $addressSelect.append('<option value="' + address.id + '">' + address.address_text + '</option>');
                        });
                        
                        // Set default date to today
                        var today = new Date().toISOString().split('T')[0];
                        $('#reading_date').val(today);
                        
                        // Show step 2
                        $('#step1-form').hide();
                        $('#step2-form').show();
                        
                        $message.addClass('success').text('Taloyhtiö löydetty! Valitse osoite ja täytä lukemat.').show();
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
                $submitBtn.prop('disabled', false).text('Jatka');
            }
        });
    });
    
    // Step 2: Handle water meter readings submission
    $('#step2-form').on('submit', function(e) {
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
            condominium_number: currentCondominiumNumber,
            address_id: $('#address_select').val(),
            reading_date: $('#reading_date').val(),
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
                        // Reset both forms
                        $('#step1-form')[0].reset();
                        $('#step2-form')[0].reset();
                        // Go back to step 1
                        $('#step2-form').hide();
                        $('#step1-form').show();
                        currentCondominiumNumber = '';
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
    
    // Back button functionality
    $('#back-btn').on('click', function() {
        $('#step2-form').hide();
        $('#step1-form').show();
        $('#form-message').hide();
        currentCondominiumNumber = '';
    });
    
    // Auto-hide messages after 5 seconds
    setTimeout(function() {
        $('#form-message').fadeOut();
    }, 5000);
});
