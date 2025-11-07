(function ($) {
    ("use strict");

    // Event listener for the "Add Lecture" button
    $(document).on('click', '.add-lecture-btn', function () {
        // Get the lesson_id from the clicked button
        let lessonId = $(this).data('lesson_id');

        // Update the form action with the dynamic lesson_id
        let actionUrl = $('#add-lecture-modal form').attr('action'); // Get current action URL
        actionUrl = actionUrl.replace('LESSON_ID', lessonId);      // Replace the placeholder with actual lesson ID

        // Set the updated action URL in the form
        $('#add-lecture-modal form').attr('action', actionUrl);

        // Open the modal
        $('#add-lecture-modal').modal('show');
    });

    // Event listener for the "Add Lecture" button
    $(document).on('click', '.add-resource-btn', function () {
        // Get the lesson_id from the clicked button
        let lessonId = $(this).data('lesson_id');
        let lecture_id = $(this).data('lecture_id');

        // Update the form action with the dynamic lesson_id
        let actionUrl = $('#add-resource-modal form').attr('action'); // Get current action URL
        actionUrl = actionUrl.replace('LESSON_ID', lessonId);      // Replace the placeholder with actual lesson ID
        actionUrl = actionUrl.replace('LECTURE_ID', lecture_id);      // Replace the placeholder with actual lesson ID

        // Set the updated action URL in the form
        $('#add-resource-modal form').attr('action', actionUrl);

        // Open the modal
        $('#add-resource-modal').modal('show');
    });


    // Initially hide fields that are not relevant
    toggleResourceFields($('#resource_type').val());

    // Listen for changes in the resource_type dropdown
    $(document).on('change', '#resource_type', function () {
        let resourceType = $(this).val();
        toggleResourceFields(resourceType);
    });

    function toggleResourceFields(resourceType) {
        // Hide all fields initially
        $(document).find('#resource-file').closest('.col-md-12').hide();
        $(document).find('#resource-youtube_id').closest('.col-md-12').hide();
        $(document).find('#resource-google_slide_link').closest('.col-md-12').hide();

        // Show fields based on resource type
        switch (parseInt(resourceType)) {
            case 1:
            case 3:
            case 4:
            case 5: // Audio
                $(document).find('#resource-file').closest('.col-md-12').show();
                break;
            case 2: // YouTube
                $(document).find('#resource-youtube_id').closest('.col-md-12').show();
                break;
            case 6: // Google Slide
                $(document).find('#resource-google_slide_link').closest('.col-md-12').show();
                break;
            default:
                break;
        }
    }


})(jQuery);
