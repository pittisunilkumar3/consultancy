(function ($) {
    "use strict";

    let contactUsTable = $('#contactUsDataTable').DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        responsive: true,
        searchable: true,
        ajax: {
            url: $('#contact-us-route').val(),
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
            {"data": "userName", "name": "userName"},
            {"data": "email", "name": "email"},
            {"data": "message", "name": "message",searchable: false},
            {"data": "mobile", "name": "mobile"},

        ]
    });

    // Trigger DataTable reload on search input change
    $('#search-key').on('keyup', function () {
        contactUsTable.search($(this).val()).draw() ;
    });

})(jQuery);
