(function ($) {
    ("use strict");

    $("#consulterReviewDataTable").DataTable({
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
        ajax: $('#consulterReviewRoute').val(),
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            {"data": 'DT_RowIndex', "name": 'DT_RowIndex', searchable: false},
            {"data": "consulter", "name": "consulter"},
            {"data": "student", "name": "student"},
            {"data": "comment", "name": "comment"},
            {"data": "status", "name": "status"},
            {"data": "action", "name": "action"}
        ]
    });
})(jQuery);
