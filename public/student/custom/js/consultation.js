(function ($) {
    "use strict";
    $('.searchButtonConsultation').on('change', function () {
        $('#consultationDataTable').DataTable().ajax.reload();
    });

    $('#consultationDataTable').DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        searching: true,
        responsive: true,
        ajax: {
            url: $('#consultation-route').val(),
            data: function (data) {
                data.search_key = $('#search-key').val();
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
            search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
        },
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            {"data": "consultant", "name": "consultant"},
            {"data": "date", "name": "date"},
            {"data": "consultation_type", "name": "consultation_type"},
            {"data": "status", "name": "status",searchable: false},
            {"data": "action", searchable: false},
        ]
    });


})(jQuery)
