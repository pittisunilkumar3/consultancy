(function ($) {
    "use strict";

    $('#appointmentDatatable').DataTable({
        pageLength: 5,
        ordering: false,
        serverSide: true,
        processing: true,
        searching: true,
        responsive: true,
        ajax: $('#consultantIndexRoute').val(),
        language: {
            paginate: {
                previous: "<i class='fa-solid fa-angles-left'></i>",
                next: "<i class='fa-solid fa-angles-right'></i>",
            },
        },
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            {"data": "appointment_ID", "name": "appointment_ID", responsivePriority: 1},
            {"data": "student", "name": "student", responsivePriority: 1},
            {"data": "consultant", "name": "consultant", responsivePriority: 1},
            {"data": "date", "name": "date", responsivePriority: 1},
            {"data": "consultation_type", "name": "consultation_type", responsivePriority: 1},
            {"data": "status", "name": "status", responsivePriority: 1},
        ]
    });

})(jQuery)
