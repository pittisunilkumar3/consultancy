(function ($) {
    "use strict";

    var courseDataTable = $('#myCourseDataTable').DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        searching: true,
        responsive: true,
        ajax: {
            url: $('#my-course-route').val(),
            data: function (d) {
                d.search_key = $('#search_key').val();
                d.program_id = $('#program_id').val();
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
        columns: [
            {"data": "thumbnail", "name": "thumbnail", searchable: false},
            {"data": "title", "name": "title"},
            {"data": "program_title", "name": "program_title"},
            {"data": "duration", "name": "duration"},
            {"data": "start_date", "name": "start_date"},
            {"data": "status", "name": "status",searchable: false}
        ]
    });

    $(document).on('click', '.filterBtn button', function () {
        // Reload the DataTable with new filter parameters
        courseDataTable.ajax.reload();
    });

})(jQuery)
