(function ($) {
    "use strict";

    $('#blogDataTable').DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        searching: true,
        responsive: true,
        ajax: $('#blog-route').val(),
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
            {"data": "banner_image", "name": "banner_image",searchable: false},
            {"data": "title", "name": "title"},
            {"data": "blogCategoryName", "name": "blogCategoryName"},
            {"data": "status", "name": "status",searchable: false },
            {"data": "action", searchable: false},
        ]
    });


})(jQuery)
