(function ($) {
    "use strict";

    let coreBenefitsCounter = 1;

    $(document).on('click', '.addUniversityCoreBenefitsBtn', function () {
        let clonedItem = $('.universityCoreBenefitsItems:first').clone();

        clonedItem.find('.text-danger').remove();
        clonedItem.find('.is-invalid').removeClass('is-invalid');

        clonedItem.find('input[name="core_benefits_title[]"]')
            .attr('id', 'core_benefits_title-' + coreBenefitsCounter)
            .val('');

        clonedItem.find('input[name="core_benefits_icon[]"]')
            .attr('id', 'coreBenefitsIcon-' + coreBenefitsCounter)
            .val('');

        clonedItem.find('.old_core_benefits_icon_id').val('');
        clonedItem.find('.preview-image-div').remove();

        clonedItem.find('label[for^="core_benefits_title"]').attr('for', 'core_benefits_title-' + coreBenefitsCounter);
        clonedItem.find('label[for^="coreBenefitsIcon"]').attr('for', 'coreBenefitsIcon-' + coreBenefitsCounter);

        if (!clonedItem.find('.removeUniversityCoreBenefits').length) {
            clonedItem.find('.zImage-upload-details').after(`
                <button type="button" class="removeUniversityCoreBenefits top-0 end-0 bg-transparent border-0 p-0 m-2 rounded-circle d-flex justify-content-center align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M16.25 4.58334L15.7336 12.9376C15.6016 15.072 15.5357 16.1393 15.0007 16.9066C14.7361 17.2859 14.3956 17.6061 14.0006 17.8467C13.2017 18.3333 12.1325 18.3333 9.99392 18.3333C7.8526 18.3333 6.78192 18.3333 5.98254 17.8458C5.58733 17.6048 5.24667 17.284 4.98223 16.904C4.4474 16.1355 4.38287 15.0668 4.25384 12.9293L3.75 4.58334" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M2.5 4.58333H17.5M13.3797 4.58333L12.8109 3.40977C12.433 2.63021 12.244 2.24043 11.9181 1.99734C11.8458 1.94341 11.7693 1.89545 11.6892 1.85391C11.3283 1.66666 10.8951 1.66666 10.0287 1.66666C9.14067 1.66666 8.69667 1.66666 8.32973 1.86176C8.24842 1.90501 8.17082 1.95491 8.09774 2.01097C7.76803 2.26391 7.58386 2.66796 7.21551 3.47605L6.71077 4.58333" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M7.91669 13.75V8.75" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M12.0833 13.75V8.75" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </button>
            `);
        }
        $('.core-benefits-block').append(clonedItem);

        clonedItem.find('input[type=file]').trigger('change');

        coreBenefitsCounter++;
    });

    $(document).on('click', '.removeUniversityCoreBenefits', function () {
        $(this).closest('.universityCoreBenefitsItems').remove();

        $('.universityCoreBenefitsItems').each(function (index) {
            $(this).find('input[name="core_benefits_title[]"]')
                .attr('id', 'core_benefits_title-' + index)

            $(this).find('input[name="core_benefits_icon[]"]')
                .attr('id', 'coreBenefitsIcon-' + index);

            $(this).find('label[for^="core_benefits_title"]').attr('for', 'core_benefits_title-' + index);
            $(this).find('label[for^="coreBenefitsIcon"]').attr('for', 'coreBenefitsIcon-' + index);
        });

        coreBenefitsCounter = $('.universityCoreBenefitsItems').length;
    });

    $(document).on('change', '.fileUploadInput', function (){
        $(this).closest('.file-upload-one').find('.old_core_benefits_icon_id').val('');
    });


    $('#universityDataTable').DataTable({
        pageLength: 10,
        ordering: false,
        serverSide: true,
        processing: true,
        searching: true,
        responsive: true,
        ajax: $('#universities-route').val(),
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
            {"data": "name", "name": "name"},
            {"data": "thumbnail_image", "name": "thumbnail_image", searchable: false},
            {"data": "status", "name": "status", searchable: false},
            {"data": "action", searchable: false},
        ]
    });

    // Handle conditional criteria fields (show/hide based on parent field value)
    function updateConditionalFields() {
        $('.conditional-criteria-field').each(function() {
            const $field = $(this);
            const dependsOn = $field.data('depends-on');
            const dependsValue = $field.data('depends-value');
            
            if (!dependsOn) return;
            
            // Get parent field value - try multiple selectors
            let $parentField = $('#criteria_' + dependsOn);
            let parentValue = '';
            
            // If not found, try finding by name attribute
            if (!$parentField.length) {
                $parentField = $('input[name="criteria_values[' + dependsOn + ']"], select[name="criteria_values[' + dependsOn + ']"]');
            }
            
            if ($parentField.length) {
                if ($parentField.is(':checkbox')) {
                    parentValue = $parentField.is(':checked') ? '1' : '0';
                } else if ($parentField.is('select')) {
                    parentValue = $parentField.val() || '';
                } else {
                    parentValue = $parentField.val() || '';
                }
            }
            
            // Show/hide based on parent value
            // Normalize both values to strings for comparison
            const normalizedParentValue = String(parentValue).trim();
            const normalizedDependsValue = String(dependsValue).trim();
            
            if (normalizedParentValue === normalizedDependsValue) {
                $field.slideDown(200).show().css('display', ''); // Remove inline display:none
                // Enable all inputs in the field
                $field.find('input, select, textarea').not('[type="hidden"]').prop('disabled', false);
                // Enable required fields
                $field.find('input[required], select[required], textarea[required]').prop('required', true);
            } else {
                $field.slideUp(200).hide();
                // Clear and disable all inputs
                $field.find('input, select, textarea').not('[type="hidden"]').val('').prop('disabled', true).prop('required', false);
            }
        });
    }

    // Listen for changes on criteria fields that might have dependents
    // Handle checkboxes (boolean fields)
    $(document).on('change', 'input[name^="criteria_values["]', function() {
        updateConditionalFields();
    });
    
    // Handle select fields
    $(document).on('change', 'select[name^="criteria_values["]', function() {
        updateConditionalFields();
    });
    
    // Handle text inputs
    $(document).on('input change', 'input[type="text"][name^="criteria_values["], input[type="number"][name^="criteria_values["]', function() {
        updateConditionalFields();
    });

    // Initialize conditional fields on page load
    $(document).ready(function() {
        // Small delay to ensure DOM is ready
        setTimeout(function() {
            updateConditionalFields();
        }, 100);
    });
    
    // Also update when form is loaded dynamically (for AJAX forms)
    $(document).on('DOMNodeInserted', function(e) {
        if ($(e.target).find('.conditional-criteria-field').length || $(e.target).hasClass('conditional-criteria-field')) {
            setTimeout(function() {
                updateConditionalFields();
            }, 50);
        }
    });

})(jQuery)
