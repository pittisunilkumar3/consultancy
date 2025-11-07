(function ($) {
    "use strict";

    // Update available slots based on the selected date
    $(document).on('change', '#inputDateTime', function () {
        var selectedDate = $(this).val();
        var formData = new FormData();
        formData.append('date', selectedDate);

        // CSRF token
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        formData.append('_token', csrfToken);

        // Conditionally add old_slot_id if #consultation_slot_id_edit exists
        if ($('#consultation_slot_id_edit').length) {
            var oldSlotId = $('#consultation_slot_id_edit').val();
            formData.append('old_slot_id', oldSlotId);
        }

        // Clear slot options and error message
        $('#slot-error').text('');
        $('#slot-block').empty();
        $('#dropdown-text').text('-- Select a Time Slot --');
        $('#consultation_slot').val('');

        var selectedConsultantId = $('#consultant').val();
        var slotRoute = $('#get-slot-route').val().replace('CONSULTANT_ID', selectedConsultantId);

        // AJAX to get slots
        commonAjax('POST', slotRoute, updateSlots, updateSlots, formData);
    });


    window.updateSlots = function (response) {
        $('#dropdown-text').text('-- Select a Time Slot --');
        $('#consultation_slot').val('');
        $('#slot-block').html(response.status === 200 && response.responseText ? response.responseText : `<span>No slot found</span>`);
    };

    // Capture the selected consultant's fee when the consultant changes
    let consultantFee = 0;
    $(document).on('change', '#consultant', function () {
        consultantFee = parseFloat($(this).find('option:selected').data('fee') || 0);
        // Trigger currency update when consultant is selected
        $(document).find(':input[name=gateway]').first().trigger('change');
        $(document).find('#inputDateTime').trigger('change');

        if(consultantFee < 1){
            $('.payment-block').addClass('d-none');
        }else{
            $('.payment-block').removeClass('d-none');
        }
    });

    // Handle changes in the selected gateway
    $(document).on('change', ':input[name=gateway]', function () {
        $('#gateway_currency').empty(); // Clear current options
        consultantFee = parseFloat($('#consultant').find('option:selected').data('fee') || 0);

        // Perform AJAX to get currency based on selected gateway
        commonAjax('GET', $('#getCurrencyByGatewayRoute').val(), getCurrencyRes, getCurrencyRes, { 'gateway': $(this).val() });
    });

    // Process the response for the currency based on the selected gateway
    window.getCurrencyRes = function(response) {
        let selectOptions = ``;

        // Loop through the currency data and populate the select dropdown
        Object.entries(response.data).forEach((currency) => {
            let currencyAmount = currency[1].conversion_rate * consultantFee;
            selectOptions += `<option value="${currency[1].currency}" data-symbol="${currency[1].symbol}" data-value="${currencyAmount.toFixed(2)}">
                                ${currency[1].currency} - ${currency[1].symbol} ${currencyAmount.toFixed(2)}
                              </option>`;
        });

        // Append the options to the select dropdown
        $('#gateway_currency').html(selectOptions);
    };

    $(document).on('change', ':input[name=consultation_slot_id]', function (){
        $('#consultation_slot').val($(this).val());
    });

    // Trigger the first gateway on page load
    window.addEventListener('load', function(){
        $(document).find(':input[name=gateway]').first().trigger('change');
    });

})(jQuery);
