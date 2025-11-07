(function ($) {
    "use strict";

    let transactionTable = $('#reviewDataTable').DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        responsive: true,
        searchable: true,
        ajax: {
            url: $('#review-route').val(),
        },
        language: {
            paginate: {
                previous: "<i class='fa-solid fa-angles-left'></i>",
                next: "<i class='fa-solid fa-angles-right'></i>",
            },
        },
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            {"data": 'DT_RowIndex', "name": 'DT_RowIndex', searchable: false},
            {"data": "consulter", "name": "consulter"},
            {"data": "comment", "name": "comment"},
            {"data": "status", "name": "status"},
        ]
    });

    $('#search-key').on('keyup', function () {
        transactionTable.search($(this).val()).draw() ;
    });

})(jQuery);
