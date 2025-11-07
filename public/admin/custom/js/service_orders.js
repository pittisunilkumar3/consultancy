(function ($) {
    "use strict";

    const isStudent = userRole === USER_ROLE_STUDENT;

    $(document).ready(function () {
        loadServiceOrderDataTable('All');

        // Event handler for the filter button
        $('#filterButton').on('click', function () {
            var status = $('.serviceOrderStatusTab.active').data('status');
            $('#serviceOrderDataTable' + status).DataTable().ajax.reload();
        });
    });

    // Event handler for tab status change
    $(document).on('click', '.serviceOrderStatusTab', function (e) {
        e.preventDefault();
        var status = $(this).data('status');
        loadServiceOrderDataTable(status);
    });

    function loadServiceOrderDataTable(status) {
        $("#serviceOrderDataTable" + status).DataTable({
            pageLength: 10,
            ordering: false,
            serverSide: true,
            processing: true,
            searching: false,
            responsive: {
                breakpoints: [
                    { name: "desktop", width: Infinity },
                    { name: "tablet", width: 1400 },
                    { name: "fablet", width: 768 },
                    { name: "phone", width: 480 }
                ],
            },
            ajax: {
                url: $('#serviceOrderIndexRoute').val(),
                data: function (data) {
                    data.status = status;
                    data.search_key = $('#search_key').val();
                    data.service_id = $('#service').val();
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
                { "data": "orderID",  responsivePriority: 1},
                { "data": "student" },
                { "data": "service" },
                { "data": "transaction_amount" },
                { "data": "payment_status" },
                { "data": "status" },
                { "data": "date" },
                { "data": "order_board", responsivePriority: 1},
                {
                    "data": "action",
                    responsivePriority: 2,
                    visible: !isStudent // Hide this column for students
                }
            ],
            stateSave: true,
            "bDestroy": true
        });
    }

    // Function to update amount based on selected service and calculate subtotal
    window.updateAmountAndSubtotal = function (suffix) {
        // Get the selected service price
        const price = $(document).find(`#service_id${suffix}`).find(':selected').data('price') || 0;

        // Update the amount field
        $(document).find(`#amount${suffix}`).val(price);

        // Recalculate subtotal
        calculateSubtotal(suffix);
    }

    // Function to calculate subtotal
    window.calculateSubtotal = function (suffix) {
        // Get values of amount and discount
        const amount = parseFloat($(document).find(`#amount${suffix}`).val()) || 0;
        const discount = parseFloat($(document).find(`#discount${suffix}`).val()) || 0;

        // Calculate the subtotal
        const subtotal = Math.max(amount - discount, 0);

        // Update the subtotal field
        $(document).find(`#sub_total${suffix}`).val(subtotal.toFixed(2));
    }

})(jQuery);
