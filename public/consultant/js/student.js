(function ($) {
    "use strict";

    $('#studentDataTable').DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        searching: true,
        responsive: true,
        ajax: $('#consulterStudentRoute').val(),
        language: {
            paginate: {
                previous: "<i class='fa-solid fa-angles-left'></i>",
                next: "<i class='fa-solid fa-angles-right'></i>",
            },
        },
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            {"data": 'DT_RowIndex', "name": 'DT_RowIndex', searchable: false},
            {"data": "image", "name": "image", responsivePriority: 1},
            {"data": "name", "name": "name", responsivePriority: 1},
            {"data": "email", "name": "email", responsivePriority: 1},
            {"data": "mobile", "name": "mobile", responsivePriority: 1},
        ]
    });

})(jQuery)
