(function ($) {
    "use strict";

    $(document).ready(function () {
        const orderType = $('#orderType').val(); // Get order type from hidden input
        allOrderDataTable('All', orderType); // Load all orders by default

        $('#filterButton').on('click', function () {
            var status = $('.orderStatusTab.active').data('status');
            $('#orderDataTable' + status).DataTable().ajax.reload();
        });
    });

    // Trigger export when clicking the custom CSV button
    $(document).on('click', '#exportCsv', function () {
        var table = $('#orderDataTableAll').DataTable();  // Make sure to use the correct table ID
        table.button('.buttons-csv').trigger();
    });

    // Trigger export when clicking the custom Excel button
    $(document).on('click', '#exportExcel', function () {
        var table = $('#orderDataTableAll').DataTable();  // Use the correct table ID
        table.button('.buttons-excel').trigger();
    });

    $(document).on('click', '.orderStatusTab', function (e) {
        e.preventDefault();
        var status = $(this).data('status');
        const orderType = $('#orderType').val();
        allOrderDataTable(status, orderType);
    });

    function allOrderDataTable(status, orderType) {
        var dataTableColumns = [
            {"data": "tnxId"},
            {"data": "customerName"},
            {"data": "customerEmail"},
            {"data": "itemTitle"},
            {"data": "amount"},
            {"data": "gatewayName"},
            {"data": "date"},
            {"data": "status"}
        ];

        if (status === 'Pending') {
            var gatewayNameIndex = dataTableColumns.findIndex(column => column.data === "gatewayName");
            if (gatewayNameIndex !== -1) {
                dataTableColumns.splice(gatewayNameIndex + 1, 0, {"data": "payment_info", responsivePriority: 2});
            }
            var statusIndex = dataTableColumns.findIndex(column => column.data === "status");
            if (statusIndex !== -1) {
                dataTableColumns.splice(statusIndex + 1, 0, {"data": "action", responsivePriority: 2});
            }

        }

        $("#orderDataTable" + status).DataTable({
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
                    {name: "phone", width: 480},
                ],
            },
            ajax: {
                url: $('#ordersRoute').val(),
                data: function (data) {
                    data.status = status;
                    data.orderType = orderType; // Pass orderType to the server
                    data.search_key = $('#search-key').val();
                    data.item_id = $('#item_id').val();
                },
                error: function (xhr) {
                    console.log('Error: ', xhr.responseText);
                    alert('An error occurred while loading orders. Please try again.');
                }
            },
            language: {
                paginate: {
                    previous: "<i class='fa-solid fa-angles-left'></i>",
                    next: "<i class='fa-solid fa-angles-right'></i>",
                },
            },
            dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
            buttons: [
                'csv',
                'excel'
            ],
            columns: dataTableColumns,
            stateSave: true,
            "bDestroy": true
        });
    }

})(jQuery);
