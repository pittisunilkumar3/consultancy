(function ($) {
    ("use strict");
    $("#consulterDataTable").DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        searching: true,
        responsive: true,
        language: {
            paginate: {
                previous: "<i class='fa-solid fa-angles-left'></i>",
                next: "<i class='fa-solid fa-angles-right'></i>",
            }
        },
        ajax: $('#consulterIndexRoute').val(),
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            {"data": "image", "name": "image", responsivePriority: 1},
            {"data": "name", "name": "name"},
            {"data": "email", "name": "email"},
            {"data": "mobile", "name": "mobile"},
            {"data": "off_days", "name": "off_days"},
            {"data": "status", "name": "status", searchable: false,},
            {"data": "action", searchable: false, responsivePriority: 2},
        ]
    });
})(jQuery);
