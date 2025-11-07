(function ($) {
    "use strict";

    // Initialize DataTable with custom search input
    let transactionTable = $('#transactionDataTable').DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        responsive: true,
        searchable: true,
        ajax: {
            url: $('#transaction-route').val(),
        },
        language: {
            paginate: {
                previous: "<i class='fa-solid fa-angles-left'></i>",
                next: "<i class='fa-solid fa-angles-right'></i>",
            },
        },
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            {"data": "tnxId", "name": "tnxId", responsivePriority: 1},
            {"data": "userName", "name": "user.first_name", responsivePriority: 2},
            {"data": "type", "name": "type"},
            {"data": "purpose", "name": "purpose",searchable: false},
            {"data": "amount", "name": "amount", responsivePriority: 3},
            {"data": "payment_method", "name": "payment_method"},
            {"data": "payment_time", "name": "payment_time", searchable: false},
            {"data": "action", "name": "action", searchable: false, responsivePriority: 2},
        ]
    });

    // Trigger DataTable reload on search input change
    $('#search-key').on('keyup', function () {
        transactionTable.search($(this).val()).draw() ;
    });

})(jQuery);
