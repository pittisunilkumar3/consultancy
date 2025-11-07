(function ($) {
    "use strict";

    $('#eventDataTable').DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        searching: true,
        responsive: true,
        ajax: $('#event-route').val(),
        language: {
            paginate: {
                previous: "<i class='fa-solid fa-angles-left'></i>",
                next: "<i class='fa-solid fa-angles-right'></i>",
            },
        },
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            {"data": "image", "name": "image", responsivePriority: 1},
            {"data": "title", "name": "title", responsivePriority: 2},
            {"data": "study_levels", "name": "study_levels"},
            {"data": "type", "name": "type"},
            {"data": "date_time", "name": "date_time"},
            {"data": "status", "name": "status", searchable: false},
            {"data": "action", searchable: false, responsivePriority: 2},
        ]
    });

    $(document).ready(function() {
        const suffix = $(document).find('#event-form-edit').length ? '-edit' : ''; // Check if we are in edit mode
        toggleFields(suffix); // Initial call to set up fields based on type
        fetchUniversitiesOnEdit(suffix); // Fetch universities if in edit mode

        // Use `.on()` for dynamic binding to toggle fields based on type change
        $(document).on('change', `#type${suffix}`, function() {
            toggleFields(suffix);
        });

        // Dynamic binding for country selection to fetch universities
        $(document).on('change', `#country_ids${suffix}`, function() {
            fetchUniversities(suffix);
        });
    });

    function toggleFields(suffix) {
        const type = $(document).find(`#type${suffix}`).val();
        if (type == 1) {
            $(document).find(`#locationField${suffix}`).show();
            $(document).find(`#linkField${suffix}`).hide();
        } else {
            $(document).find(`#locationField${suffix}`).hide();
            $(document).find(`#linkField${suffix}`).show();
        }
    }

    function fetchUniversities(suffix) {
        const countryIds = $(document).find(`#country_ids${suffix}`).val();
        const url = $(document).find(`#get-university-route`).val(); // Assume this hidden input holds the URL

        if (countryIds.length > 0 && url) {
            const requestData = { country_ids: countryIds };

            commonAjax(
                'GET', // HTTP method
                url, // URL endpoint
                function (data) { // Success handler
                    const universitySelect = $(document).find(`#university_ids${suffix}`);
                    universitySelect.empty();
                    $.each(data, function (index, university) {
                        universitySelect.append(new Option(university.title, university.id));
                    });
                },
                function (error) { // Error handler
                    console.error('Error fetching universities:', error);
                    toastr.error('Error fetching universities. Please try again.');
                },
                requestData // Data to be sent
            );
        } else {
            $(document).find(`#university_ids${suffix}`).empty();
        }
    }

// Function to call fetchUniversities if editing the form
    function fetchUniversitiesOnEdit(suffix) {
        if ($(document).find(`#country_ids${suffix}`).val().length > 0) {
            fetchUniversities(suffix);
        }
    }

})(jQuery)
