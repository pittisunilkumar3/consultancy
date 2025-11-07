(function ($) {
    "use strict";

    $(document).ready(function () {
        const appointmentType = $('#appointmentType').val(); // Use appointmentType for hidden input reference
        loadAppointmentDataTable('All', appointmentType); // Load all appointments by default

        // Event handler for the filter button
        $('#filterButton').on('click', function () {
            var status = $('.appointmentStatusTab.active').data('status');
            $('#appointmentDataTable' + status).DataTable().ajax.reload();
        });
    });

    // Event handler for tab status change
    $(document).on('click', '.appointmentStatusTab', function (e) {
        e.preventDefault();
        var status = $(this).data('status');
        const appointmentType = $('#appointmentType').val();
        loadAppointmentDataTable(status, appointmentType);
    });

    function loadAppointmentDataTable(status, appointmentType) {
        $("#appointmentDataTable" + status).DataTable({
            pageLength: 10,
            ordering: false,
            serverSide: true,
            processing: true,
            searching: false,
            responsive: {
                breakpoints: [
                    { name: "desktop", width: Infinity },
                    { name: "tablet", width: 1400 },
                    { name: "fablet", width: 768 },
                    { name: "phone", width: 480 }
                ],
            },
            ajax: {
                url: $('#appointmentIndexRoute').val(),
                data: function (data) {
                    data.status = status;
                    data.appointmentType = appointmentType; // Pass appointmentType to the server
                    data.search_key = $('#search_key').val();
                    data.consulter_id = $('#consulter').val();
                },
                error: function (xhr) {
                    console.log('Error: ', xhr.responseText);
                    alert('An error occurred while loading appointments. Please try again.');
                }
            },
            language: {
                paginate: {
                    previous: "<i class='fa-solid fa-angles-left'></i>",
                    next: "<i class='fa-solid fa-angles-right'></i>"
                },
            },
            dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
            columns: [
                { "data": "appointment_ID" },
                { "data": "student" },
                { "data": "consultant" },
                { "data": "date" },
                { "data": "consultation_type" },
                { "data": "status" },
                { "data": "action", responsivePriority: 2 } // Action column included for all statuses
            ],
            stateSave: true,
            "bDestroy": true
        });
    }

    $(document).on('change','#meeting-status', function() {
        const status = $(this).val();
        const platformSection = $(document).find('#meetingPlatformSection');

        // Show platform selection only if status is "Processing"
        platformSection.toggle(status == 2);
    });

})(jQuery);
