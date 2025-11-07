(function ($) {
    ("use strict");

    var courseDataTable = $("#courseDataTable").DataTable({
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
        ajax: {
            url: $('#course-route').val(),
            data: function (d) {
                // Add additional parameters for filtering
                d.search_key = $('#search_key').val();
                d.program_id = $('#program_id').val();
            }
        },
        dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            {"data": "thumbnail", "name": "thumbnail", searchable: false, responsivePriority: 1},
            {"data": "title", "name": "title", searchable: false, responsivePriority: 1},
            {"data": "program", "name": "program.title"},
            {"data": "duration", "name": "duration"},
            {"data": "price", "name": "price"},
            {"data": "start_date", "name": "start_date"},
            {"data": "status", "name": "status", searchable: false},
            {"data": "action", searchable: false, responsivePriority: 2},
        ]
    });

    // When the filter button is clicked
    $(document).on('click', '.filterBtn button', function () {
        // Reload the DataTable with new filter parameters
        courseDataTable.ajax.reload();
    });



    $(document).on('click', '#addLearnPoint', function () {
        // Find the first instructor item and clone it
        let newLearnPoint = $('.learn-point-item').first().clone();

        // Generate a unique identifier (e.g., a timestamp or an incrementing counter)
        let uniqueId = Date.now(); // This generates a unique value based on the current timestamp

        // Update the IDs and matching `for` attributes within the cloned item
        newLearnPoint.find('[id]').each(function () {
            let oldId = $(this).attr('id');
            let newId = oldId + '_' + uniqueId;  // Make new ID unique
            $(this).attr('id', newId);

            // Update the corresponding `for` attribute in the label
            newLearnPoint.find(`label[for="${oldId}"]`).attr('for', newId);
        });

        // Reset the values in the cloned fields (to make them empty)
        newLearnPoint.find('input').val('');
        newLearnPoint.find('.text-danger').remove();
        newLearnPoint.find('.is-invalid').removeClass('is-invalid');

        // Add a remove button if it doesn't exist
        if (!newLearnPoint.find('.removeLearnPoint').length) {
            newLearnPoint.find('.text-block').append(`
            <button type="button" class="removeLearnPoint top-0 end-0 bg-transparent border-0 p-0 m-2 rounded-circle d-flex justify-content-center align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M16.25 4.58334L15.7336 12.9376C15.6016 15.072 15.5357 16.1393 15.0007 16.9066C14.7361 17.2859 14.3956 17.6061 14.0006 17.8467C13.2017 18.3333 12.1325 18.3333 9.99392 18.3333C7.8526 18.3333 6.78192 18.3333 5.98254 17.8458C5.58733 17.6048 5.24667 17.284 4.98223 16.904C4.4474 16.1355 4.38287 15.0668 4.25384 12.9293L3.75 4.58334" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M2.5 4.58333H17.5M13.3797 4.58333L12.8109 3.40977C12.433 2.63021 12.244 2.24043 11.9181 1.99734C11.8458 1.94341 11.7693 1.89545 11.6892 1.85391C11.3283 1.66666 10.8951 1.66666 10.0287 1.66666C9.14067 1.66666 8.69667 1.66666 8.32973 1.86176C8.24842 1.90501 8.17082 1.95491 8.09774 2.01097C7.76803 2.26391 7.58386 2.66796 7.21551 3.47605L6.71077 4.58333" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M7.91669 13.75V8.75" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M12.0833 13.75V8.75" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </button>
        `);
        }

        // Append the new newBenefit item to the #benefit-block
        $('#learn-point-block').append(newLearnPoint);
    });

    // Optional: Remove cloned instructor item
    $(document).on('click', '.removeLearnPoint', function () {
        $(this).closest('.learn-point-item').remove();
    });

    $(document).on('click', '#addBenefit', function () {
        // Find the first instructor item and clone it
        let newBenefit = $('.benefit-item').first().clone();

        // Generate a unique identifier (e.g., a timestamp or an incrementing counter)
        let uniqueId = Date.now(); // This generates a unique value based on the current timestamp

        // Update the IDs and matching `for` attributes within the cloned item
        newBenefit.find('[id]').each(function () {
            let oldId = $(this).attr('id');
            let newId = oldId + '_' + uniqueId;  // Make new ID unique
            $(this).attr('id', newId);

            // Update the corresponding `for` attribute in the label
            newBenefit.find(`label[for="${oldId}"]`).attr('for', newId);
        });

        // Reset the values in the cloned fields (to make them empty)
        newBenefit.find('input').val('');
        newBenefit.find('.text-danger').remove();
        newBenefit.find('.is-invalid').removeClass('is-invalid');

        // Add a remove button if it doesn't exist
        if (!newBenefit.find('.removeBenefit').length) {
            newBenefit.find('.value-block').append(`
            <button type="button" class="removeBenefit top-0 end-0 bg-transparent border-0 p-0 m-2 rounded-circle d-flex justify-content-center align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M16.25 4.58334L15.7336 12.9376C15.6016 15.072 15.5357 16.1393 15.0007 16.9066C14.7361 17.2859 14.3956 17.6061 14.0006 17.8467C13.2017 18.3333 12.1325 18.3333 9.99392 18.3333C7.8526 18.3333 6.78192 18.3333 5.98254 17.8458C5.58733 17.6048 5.24667 17.284 4.98223 16.904C4.4474 16.1355 4.38287 15.0668 4.25384 12.9293L3.75 4.58334" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M2.5 4.58333H17.5M13.3797 4.58333L12.8109 3.40977C12.433 2.63021 12.244 2.24043 11.9181 1.99734C11.8458 1.94341 11.7693 1.89545 11.6892 1.85391C11.3283 1.66666 10.8951 1.66666 10.0287 1.66666C9.14067 1.66666 8.69667 1.66666 8.32973 1.86176C8.24842 1.90501 8.17082 1.95491 8.09774 2.01097C7.76803 2.26391 7.58386 2.66796 7.21551 3.47605L6.71077 4.58333" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M7.91669 13.75V8.75" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M12.0833 13.75V8.75" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </button>
        `);
        }

        // Append the new newBenefit item to the #benefit-block
        $('#benefit-block').append(newBenefit);
    });

    // Optional: Remove cloned instructor item
    $(document).on('click', '.removeBenefit', function () {
        $(this).closest('.benefit-item').remove();
    });

    $(document).on('click', '#addInstructor', function () {
        // Find the first instructor item and clone it
        let newInstructor = $('.instructor-item').first().clone();

        // Generate a unique identifier (e.g., a timestamp or an incrementing counter)
        let uniqueId = Date.now(); // This generates a unique value based on the current timestamp

        // Update the IDs and matching `for` attributes within the cloned item
        newInstructor.find('[id]').each(function () {
            let oldId = $(this).attr('id');
            let newId = oldId + '_' + uniqueId;  // Make new ID unique
            $(this).attr('id', newId);

            // Update the corresponding `for` attribute in the label
            newInstructor.find(`label[for="${oldId}"]`).attr('for', newId);
        });

        // Reset the values in the cloned fields (to make them empty)
        newInstructor.find('input').val('');
        newInstructor.find('textarea').val('');
        newInstructor.find('.text-danger').remove();
        newInstructor.find('.is-invalid').removeClass('is-invalid');
        newInstructor.find('.old_instructor_photo').val('');

        // Add a remove button if it doesn't exist
        if (!newInstructor.find('.removeInstructor').length) {
            newInstructor.find('.photo-block').append(`
            <button type="button" class="removeInstructor top-0 end-0 bg-transparent border-0 p-0 m-2 rounded-circle d-flex justify-content-center align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M16.25 4.58334L15.7336 12.9376C15.6016 15.072 15.5357 16.1393 15.0007 16.9066C14.7361 17.2859 14.3956 17.6061 14.0006 17.8467C13.2017 18.3333 12.1325 18.3333 9.99392 18.3333C7.8526 18.3333 6.78192 18.3333 5.98254 17.8458C5.58733 17.6048 5.24667 17.284 4.98223 16.904C4.4474 16.1355 4.38287 15.0668 4.25384 12.9293L3.75 4.58334" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M2.5 4.58333H17.5M13.3797 4.58333L12.8109 3.40977C12.433 2.63021 12.244 2.24043 11.9181 1.99734C11.8458 1.94341 11.7693 1.89545 11.6892 1.85391C11.3283 1.66666 10.8951 1.66666 10.0287 1.66666C9.14067 1.66666 8.69667 1.66666 8.32973 1.86176C8.24842 1.90501 8.17082 1.95491 8.09774 2.01097C7.76803 2.26391 7.58386 2.66796 7.21551 3.47605L6.71077 4.58333" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M7.91669 13.75V8.75" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M12.0833 13.75V8.75" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </button>
        `);
        }

        // Append the new instructor item to the #instructor-block
        $('#instructor-block').append(newInstructor);
        newInstructor.find('input[type=file]').trigger('change');
    });

    // Optional: Remove cloned instructor item
    $(document).on('click', '.removeInstructor', function () {
        $(this).closest('.instructor-item').remove();
    });

    $(document).on('change', '.fileUploadInput', function (){
        $(this).closest('.file-upload-one').find('.old_instructor_photo').val('');
    });

    $(document).on('click', '#addFaq', function () {
        // Find the first FAQ item and clone it
        let newFaq = $('.faq-item').first().clone();

        // Generate a unique identifier (e.g., a timestamp or an incrementing counter)
        let uniqueId = Date.now(); // This generates a unique value based on the current timestamp

        // Update the IDs and `for` attributes within the cloned item
        newFaq.find('[id]').each(function () {
            let oldId = $(this).attr('id');
            let newId = oldId + '_' + uniqueId;
            $(this).attr('id', newId);

            // Update the corresponding `for` attribute in the label
            newFaq.find(`label[for="${oldId}"]`).attr('for', newId);
        });

        // Reset the values in the cloned fields (to make it a blank entry)
        newFaq.find('input').val('');
        newFaq.find('.text-danger').remove();
        newFaq.find('.is-invalid').removeClass('is-invalid');

        // Add a remove button if it doesn't exist
        if (!newFaq.find('.removeFaq').length) {
            newFaq.find('.answer-block').append(`
            <button type="button" class="removeFaq top-0 end-0 bg-transparent border-0 p-0 me-2 rounded-circle d-flex justify-content-center align-items-center position-absolute">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M16.25 4.58334L15.7336 12.9376C15.6016 15.072 15.5357 16.1393 15.0007 16.9066C14.7361 17.2859 14.3956 17.6061 14.0006 17.8467C13.2017 18.3333 12.1325 18.3333 9.99392 18.3333C7.8526 18.3333 6.78192 18.3333 5.98254 17.8458C5.58733 17.6048 5.24667 17.284 4.98223 16.904C4.4474 16.1355 4.38287 15.0668 4.25384 12.9293L3.75 4.58334" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M2.5 4.58333H17.5M13.3797 4.58333L12.8109 3.40977C12.433 2.63021 12.244 2.24043 11.9181 1.99734C11.8458 1.94341 11.7693 1.89545 11.6892 1.85391C11.3283 1.66666 10.8951 1.66666 10.0287 1.66666C9.14067 1.66666 8.69667 1.66666 8.32973 1.86176C8.24842 1.90501 8.17082 1.95491 8.09774 2.01097C7.76803 2.26391 7.58386 2.66796 7.21551 3.47605L6.71077 4.58333" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M7.91669 13.75V8.75" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M12.0833 13.75V8.75" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </button>
        `);
        }

        // Append the new FAQ item to the #faq-block
        $('#faq-block').append(newFaq);
    });

    // Remove FAQ item on click of remove button
    $(document).on('click', '.removeFaq', function () {
        $(this).closest('.faq-item').remove();
    });

    $(document).on('change', '#intro_video_type', function () {
        // Get the selected value
        let selectedType = $(this).val();

        // If the selected type is 1 (Local File), show local file input, hide YouTube input
        if (selectedType == 1) {
            $(document).find('#video-type-local').removeClass('d-none');
            $(document).find('#video-type-youtube-id').addClass('d-none');
        } else {
            // Otherwise, show YouTube input and hide local file input
            $(document).find('#video-type-youtube-id').removeClass('d-none');
            $(document).find('#video-type-local').addClass('d-none');
        }
    });

})(jQuery);
