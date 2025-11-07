(function ($) {
    "use strict";

    $(document).ready(function () {
        loadServiceOrderInvoiceDataTable('All');

        // Event handler for the filter button
        $('#filterButton').on('click', function () {
            var status = $('.serviceOrderInvoiceStatusTab.active').data('status');
            $('#serviceOrderInvoiceDataTable' + status).DataTable().ajax.reload();
        });
    });

    // Event handler for tab status change
    $(document).on('click', '.serviceOrderInvoiceStatusTab', function (e) {
        e.preventDefault();
        var status = $(this).data('status');
        loadServiceOrderInvoiceDataTable(status);
    });

    function loadServiceOrderInvoiceDataTable(status) {
        $("#serviceOrderInvoiceDataTable" + status).DataTable({
            pageLength: 10,
            ordering: false,
            serverSide: true,
            processing: true,
            searching: false,
            responsive: {
                breakpoints: [
                    {name: "desktop", width: Infinity},
                    {name: "tablet", width: 1400},
                    {name: "fablet", width: 768},
                    {name: "phone", width: 480}
                ],
            },
            ajax: {
                url: $('#serviceOrderInvoiceIndexRoute').val(),
                data: function (data) {
                    data.status = status;
                    data.search_key = $('#search_key').val();
                    data.service_id = $('#service_id').val();
                },
                error: function (xhr) {
                    console.log('Error: ', xhr.responseText);
                    alert('An error occurred while loading service orders. Please try again.');
                }
            },
            language: {
                paginate: {
                    previous: "<i class='fa-solid fa-angles-left'></i>",
                    next: "<i class='fa-solid fa-angles-right'></i>"
                },
            },
            dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
            columns: [
                {"data": "invoiceID", responsivePriority: 1},
                {"data": "orderID", responsivePriority: 2},
                {"data": "student"},
                {"data": "due_date"},
                {"data": "total"},
                {"data": "payment_info"},
                {"data": "payment_status"},
                {"data": "action", responsivePriority: 2} // Action column included for all statuses
            ],
            stateSave: true,
            "bDestroy": true
        });
    }

    window.updateServiceOrder = function (el, suffix) {
        const student_id = $(el).val(); // Get the student ID from the current element
        const baseUrl = $('#get-service-order-route').val(); // Get the base URL with the placeholder
        const url = baseUrl.replace('__STUDENT_ID__', student_id); // Replace the placeholder

        if (student_id) {
            commonAjax('GET', url, function (response) {
                serviceOrderResponse(suffix, response); // Pass the response to the callback
            });
        } else {
            $(this).closest('.row').find('.service-order').html('');
        }
    };

    function serviceOrderResponse(suffix, response) {
        if (response.status) {
            let serviceOrderOption = '';
            response.data.forEach(function (serviceOrder) {
                serviceOrderOption += `<option value="${serviceOrder.id}">${serviceOrder.orderID}</option>`;
            });

            const serviceOrderElement = $(document).find(`#service_order_id${suffix}`);
            serviceOrderElement.html(serviceOrderOption);

            if (serviceOrderElement.data('multiselect')) {
                serviceOrderElement.multiselect('destroy');
            }

            serviceOrderElement.multiselect({
                buttonClass: "form-select sf-select-checkbox-btn",
                enableFiltering: false,
                maxHeight: 322,
                templates: {
                    button: '<button type="button" class="multiselect dropdown-toggle" data-bs-toggle="dropdown"><span class="multiselect-selected-text"></span></button>',
                },
            });
        } else {
            $(document).find(`#service_order_id${suffix}`).html('');
        }
    }

    var price = 0;
    // Handle changes in the selected gateway
    $(document).on('change', '#change-status-gateway', function () {
        $(document).find('#change-status-gateway-currency').empty(); // Clear current options
        price = parseFloat($(document).find('#pay-amount').val() || 0);

        // Perform AJAX to get currency based on selected gateway
        commonAjax('GET', $('#getCurrencyByGatewayRoute').val(), getCurrencyRes, getCurrencyRes, {'gateway': $(this).val()});
    });

    // Process the response for the currency based on the selected gateway
    window.getCurrencyRes = function (response) {
        let selectOptions = ``;

        // Loop through the currency data and populate the select dropdown
        Object.entries(response.data).forEach((currency) => {
            let currencyAmount = currency[1].conversion_rate * price;
            selectOptions += `<option value="${currency[1].currency}" data-symbol="${currency[1].symbol}" data-value="${currencyAmount.toFixed(2)}">
                                ${currency[1].currency} - ${currency[1].symbol} ${currencyAmount.toFixed(2)}
                              </option>`;
        });

        // Append the options to the select dropdown
        $(document).find('#change-status-gateway-currency').html(selectOptions);
    };

    $(document).on('change', '#status-change-payment-status', function () {
        if ($(this).val() == 1) {
            $(document).find('#change-status-gateway-block').show();
            $(document).find('#change-status-gateway-currency-block').show();
        } else {
            $(document).find('#change-status-gateway-block').hide();
            $(document).find('#change-status-gateway-currency-block').hide();
        }
    });

})(jQuery);
