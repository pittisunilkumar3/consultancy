(function ($) {
    "use strict";

    // When a country is selected, load universities based on selected countries
    $(document).on('change', '#interested_area_destination_country_ids', function () {
        const countryIds = $(this).val(); // multiple countries selected
        const baseUrl = $(document).find('#university-country-route').val();
        const url = `${baseUrl}?country_ids=${countryIds && countryIds.length ? countryIds.join(',') : ''}`;

        // Clear previous selections
        $(document).find('#interested_area_university_ids').html(''); // Empty university dropdown
        $(document).find('#interested_area_subject_ids').html(''); // Empty subject dropdown

        // If there are selected countries, make the AJAX call; otherwise, reset universities and subjects
        if (countryIds && countryIds.length > 0) {
            commonAjax('GET', url, loadUniversities, handleError);
        } else {
            initMultiselect('#interested_area_university_ids');
            initMultiselect('#interested_area_subject_ids');
        }
    });

    // When a university is selected, load subjects based on selected universities
    $(document).on('change', '#interested_area_university_ids', function () {
        const universityIds = $(this).val(); // multiple universities selected
        const baseUrl = $(document).find('#university-subject-route').val();
        const url = `${baseUrl}?university_ids=${universityIds && universityIds.length ? universityIds.join(',') : ''}`;

        // Clear previous subject options
        $(document).find('#interested_area_subject_ids').html(''); // Empty subject dropdown

        // If there are selected universities, make the AJAX call; otherwise, reset subjects
        if (universityIds && universityIds.length > 0) {
            commonAjax('GET', url, loadSubjects, handleError);
        } else {
            initMultiselect('#interested_area_subject_ids');
        }
    });

    // Callback to load universities based on selected countries
    function loadUniversities(response) {
        let universityOptions = '';
        if (response.status) {
            response.data.forEach(function (university) {
                universityOptions += `<option value="${university.id}">${university.name}</option>`;
            });
            $(document).find('#interested_area_university_ids').html(universityOptions);
        }

        // Reinitialize multiselect if needed
        initMultiselect('#interested_area_university_ids');
    }

    // Callback to load subjects based on selected universities
    function loadSubjects(response) {
        let subjectOptions = '';
        if (response.status) {
            response.data.forEach(function (subject) {
                subjectOptions += `<option value="${subject.id}">${subject.name}</option>`;
            });
            $(document).find('#interested_area_subject_ids').html(subjectOptions);
        }

        // Reinitialize multiselect if needed
        initMultiselect('#interested_area_subject_ids');
    }

    // Reinitialize multiselect with updated options
    function initMultiselect(selector) {
        if ($(document).find(selector).data('multiselect')) {
            $(document).find(selector).multiselect('destroy');
        }
        $(document).find(selector).multiselect({
            buttonClass: "form-select sf-select-checkbox-btn",
            enableFiltering: false,
            maxHeight: 322,
            templates: {
                button: '<button type="button" class="multiselect dropdown-toggle" data-bs-toggle="dropdown"><span class="multiselect-selected-text"></span></button>',
            },
        });
    }

    // Error handler for AJAX requests
    function handleError() {
        alert('Failed to load data. Please try again.');
    }

    if ($('#customFormRender').length) {
        var formRenderOptions = {
            disableInjectedStyle: 'bootstrap',
            formData: custom_field_form
        }

        var formRenderInstance = $('#customFormRender').formRender(formRenderOptions);
        $(document).on('click', '#saveCustomForm', function () {
            $(this).closest('form').find('input[name=custom_fields]').val(JSON.stringify(formRenderInstance.userData))
        });
    }

    window.finalizeOnboarding = function (url){

        Swal.fire({
            title: 'Sure! You want to finish?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Do It!'
        }).then((result) => {
            if (result.value) {
                var formData = new FormData();

                // CSRF token
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                formData.append('_token', csrfToken);

                // AJAX to get slots
                commonAjax('POST', url, onboardingFinishResponse, onboardingFinishResponse, formData);
            }
        })

    }

    window.onboardingFinishResponse = function (response){
        if (response.status === true) {
            toastr.success(response.message);
            setTimeout(function (){
                location.replace(response.data.url)
            }, 500)
        }else{
            toastr.success(response.message);
        }
    }


    window.getFileSaveModal =  function ($fileId){
        $(document).find('#fileSaveModal').modal('show'); // Open the modal
        $(document).find('#fileSaveForm')[0].reset(); // Clear any previous input
        $(document).find('#fileSaveForm').find('#file-id').val($fileId); // Store file ID for saving
    }

    window.responseForFileSave  = function (response){
        if (response.status == true) {
            alertAjaxMessage('success', response.message)
            $(document).find('#fileSaveModal').modal('hide');
            $(document).find(':input[type=submit]').find('.spinner-border').addClass('d-none');
            $(document).find(':input[type=submit]').removeAttr('disabled');
        } else {
            alertAjaxMessage('error', response.message)
        }
    }

})(jQuery);
