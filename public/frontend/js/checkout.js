(function ($) {
    "use strict";

    // Trigger bank details on change of the bank_id
    $('#bank_id').on('change', function () {
        $('#bankDetails').removeClass('d-none');
        $('#bankDetails p').html($(this).find(':selected').data('details'));
    });

    // Handle changes in the selected gateway
    $(document).on('change', ':input[name=gateway]', function () {
        $('#gateway_currency').empty(); // Clear the current gateway currency options
        $('#gatewayCurrencyAmount').text(''); // Clear the displayed currency amount

        // Perform AJAX to get currency based on the selected gateway
        commonAjax('GET', $('#getCurrencyByGatewayRoute').val(), getCurrencyRes, getCurrencyRes, { 'gateway': $(this).val() });

        // Show/Hide bank details section if 'bank' gateway is selected
        if ($(this).val() == 'bank') {
            $('#bankSection').removeClass('d-none');
            $('#bankDetails').removeClass('d-none');
            $('#bank_slip').attr('required', false);
            $('#bank_id').attr('required', true);
        } else {
            $('#bank_slip').attr('required', false);
            $('#bank_id').attr('required', false);
            $('#bankDetails').addClass('d-none');
            $('#bankSection').addClass('d-none');
        }
    });

    // Process the response for the currency based on the selected gateway
    $('#gateway_currency').on('change', function () {
        var selectedCurrencySymbol = $(this).find('option:selected').data('symbol');
        var selectedCurrencyAmount = $(this).find('option:selected').data('value');  // Get the data-value attribute

        // Update the displayed currency amount
        if (selectedCurrencyAmount) {
            $('#gatewayCurrencyAmount').text(`(${selectedCurrencySymbol}${selectedCurrencyAmount})`);
        } else {
            $('#gatewayCurrencyAmount').text('');  // Clear if no currency is selected
        }
    });

    // Process the response for the currency based on the selected gateway
    window.getCurrencyRes = function(response) {
        var invoiceAmount = parseFloat($('#amount').val()).toFixed(2);
        var selectOptions = ``;

        // Loop through the currency data and populate the select dropdown
        Object.entries(response.data).forEach((currency) => {
            let currencyAmount = currency[1].conversion_rate * invoiceAmount;
            selectOptions += `<option value="${currency[1].currency}" data-symbol="${currency[1].symbol}" data-value="${currencyAmount.toFixed(2)}">
                            ${currency[1].currency} - ${currency[1].symbol} ${currencyAmount.toFixed(2)}
                          </option>`;
        });

        // Append the options to the select dropdown
        $('#gateway_currency').html(selectOptions);
        $('#gateway_currency').trigger('change');  // Trigger change event to update the display immediately
    }


    // Trigger the first gateway on page load
    window.addEventListener('load', function(){
        $(document).find(':input[name=gateway]').first().trigger('change');
    });

})(jQuery);
