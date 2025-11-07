(function ($) {
    "use strict";

    $('#testimonialDataTable').DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        searching: true,
        responsive: true,
        ajax: $('#testimonial-route').val(),
        language: {
            paginate: {
                previous: "<i class='fa-solid fa-angles-left'></i>",
                next: "<i class='fa-solid fa-angles-right'></i>",
            },
            search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
        },
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            {"data": 'DT_RowIndex', "name": 'DT_RowIndex', searchable: false},
            {"data": "image", "name": "image",searchable: false},
            {"data": "name", "name": "name"},
            {"data": "review_date", "name": "review_date"},
            {"data": "status", "name": "status",searchable: false , class:"text-end"},
            {"data": "action", searchable: false},
        ]
    });


})(jQuery)
