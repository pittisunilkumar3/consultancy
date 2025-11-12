(function ($) {
    "use strict";

    $(document).ready(function () {
        // initialize server-side DataTable
        var storeUrl = $('#questionsStoreRoute').val();
        var deleteBase = $('#questionsDeleteBase').val();
        var csrf = $('meta[name="csrf-token"]').attr('content');
        var indexUrl = $('#questionsIndexRoute').val();

        // Get countries route - try to get it dynamically in case modal is loaded later
        function getCountriesRoute() {
            var route = $('#questionsCountriesRoute').val();
            if (!route) {
                // Try to construct it from base URL
                var baseUrl = window.location.origin;
                route = baseUrl + '/admin/questions/countries';
            }
            return route;
        }

        // Show/hide options field based on question type
        $('#type').on('change', function() {
            var type = $(this).val();
            var $options = $('.options-wrapper');
            var useCountries = $('#use_countries').is(':checked');

            if (['select', 'radio', 'checkbox'].includes(type)) {
                $options.removeClass('d-none');
                if (type === 'select') {
                    $options.find('small').text('Enter options and drag to reorder. For select: set value and label.');
                } else {
                    $options.find('small').text('Enter options and drag to reorder.');
                }

                // If use_countries is checked, re-populate countries for the new type
                if (useCountries) {
                    var countriesRoute = getCountriesRoute();
                    if (countriesRoute) {
                        $.get(countriesRoute, function(res) {
                            if (res && res.status && res.data) {
                                $('#optionList').empty();
                                res.data.forEach(function(country) {
                                    if (type === 'select') {
                                        $('#optionList').append(createOptionRow(country.id, country.name));
                                    } else {
                                        $('#optionList').append(createOptionRow('', country.name));
                                    }
                                });
                                updateOptionInputsForType(type);
                                // Make option inputs read-only when using countries
                                $('#optionList .option-label, #optionList .option-value').prop('readonly', true).addClass('bg-light');
                                $('#addOptionBtn').prop('disabled', true).addClass('opacity-50');
                                $('#optionList .remove-option').prop('disabled', true).addClass('opacity-50');
                                // Serialize options immediately
                                serializeOptions();
                            }
                        });
                    }
                } else {
                    // Enable editing when not using countries
                    $('#optionList .option-label, #optionList .option-value').prop('readonly', false).removeClass('bg-light');
                    $('#addOptionBtn').prop('disabled', false).removeClass('opacity-50');
                    $('#optionList .remove-option').prop('disabled', false).removeClass('opacity-50');
                }
            } else {
                $options.addClass('d-none');
                // Uncheck use_countries when options are hidden
                $('#use_countries').prop('checked', false);
                $('#addOptionBtn').prop('disabled', false).removeClass('opacity-50');
            }
            // update option row inputs (show/hide value field for select)
            if (typeof updateOptionInputsForType === 'function') {
                updateOptionInputsForType(type);
            }
        });

        // Handle "Use Countries from Database" checkbox
        $(document).on('change', '#use_countries', function() {
            var isChecked = $(this).is(':checked');
            var type = $('#type').val();

            // Ensure options wrapper is visible
            if (!['select', 'radio', 'checkbox'].includes(type)) {
                toastr.warning('Please select a question type (Select, Radio, or Checkbox) first.');
                $(this).prop('checked', false);
                return;
            }

            // Get countries route dynamically
            var countriesRoute = getCountriesRoute();
            if (!countriesRoute) {
                toastr.error('Countries route not found. Please refresh the page.');
                $(this).prop('checked', false);
                return;
            }

            if (isChecked) {
                // Show loading indicator
                var $optionsWrapper = $('.options-wrapper');
                if ($optionsWrapper.hasClass('d-none')) {
                    $optionsWrapper.removeClass('d-none');
                }

                // Fetch countries from backend
                $.ajax({
                    url: countriesRoute,
                    method: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        if (res && res.status && res.data && res.data.length > 0) {
                            // Clear existing options
                            $('#optionList').empty();

                            // Populate with countries
                            res.data.forEach(function(country) {
                                if (type === 'select') {
                                    // For select type: use country id as value, name as label
                                    $('#optionList').append(createOptionRow(country.id, country.name));
                                } else {
                                    // For radio/checkbox: use country name as option
                                    $('#optionList').append(createOptionRow('', country.name));
                                }
                            });

                            // Update option inputs visibility based on type
                            updateOptionInputsForType(type);

                            // Make option inputs read-only and disable manual add option button when using countries
                            $('#optionList .option-label, #optionList .option-value').prop('readonly', true).addClass('bg-light');
                            $('#addOptionBtn').prop('disabled', true).addClass('opacity-50');
                            // Disable remove buttons for country options
                            $('#optionList .remove-option').prop('disabled', true).addClass('opacity-50');

                            // Serialize options immediately after loading countries
                            serializeOptions();
                        } else {
                            toastr.error('No countries found in the database');
                            $('#use_countries').prop('checked', false);
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Error loading countries from server. Please try again.');
                        $('#use_countries').prop('checked', false);
                    }
                });
            } else {
                // Unchecked: make options editable (keep existing options, just make them editable)
                $('#addOptionBtn').prop('disabled', false).removeClass('opacity-50');
                // Make inputs editable
                $('#optionList .option-label, #optionList .option-value').prop('readonly', false).removeClass('bg-light');
                $('#optionList .remove-option').prop('disabled', false).removeClass('opacity-50');
                // Re-serialize options after making them editable
                serializeOptions();
            }
        });
        // Option list UI: add/remove/reorder and serialize to hidden input before submit
        function createOptionRow(value = '', label = '') {
            var $row = $(
                '<div class="option-item d-flex align-items-center mb-2 p-2 bg-white bd-one bd-ra-6">' +
                    '<span class="handle me-2 text-muted" style="cursor:grab"><i class="fa-solid fa-grip-lines"></i></span>' +
                    '<input type="text" class="form-control form-control-sm option-label me-2" placeholder="Label" value="' + $('<div/>').text(label).html() + '"/>' +
                    '<input type="text" class="form-control form-control-sm option-value me-2 d-none" placeholder="Value" value="' + $('<div/>').text(value).html() + '"/>' +
                    '<div class="btn-group ms-auto" role="group" aria-label="actions">' +
                        '<button type="button" class="btn btn-sm btn-outline-danger remove-option" title="Remove"><i class="fa-solid fa-trash"></i></button>' +
                    '</div>' +
                '</div>'
            );

            return $row;
        }

        // Add option button
        $(document).on('click', '#addOptionBtn', function () {
            var $list = $('#optionList');
            var $row = createOptionRow('', '');
            $list.append($row);
            // focus label
            $row.find('.option-label').focus();
        });

        // Remove option
        $(document).on('click', '.remove-option', function () {
            $(this).closest('.option-item').remove();
            // Re-serialize options after removal
            serializeOptions();
        });

        // Re-serialize options when option values change (for manual edits)
        $(document).on('input change', '.option-label, .option-value', function() {
            // Only serialize if not using countries (countries are readonly)
            if (!$('#use_countries').is(':checked')) {
                serializeOptions();
            }
        });

        // Initialize SortableJS on option list (drag-handle only)
        try {
            if (typeof Sortable !== 'undefined') {
                new Sortable(document.getElementById('optionList'), {
                    handle: '.handle',
                    animation: 150,
                    ghostClass: 'sortable-ghost'
                });
            }
        } catch (e) {
            console.warn('SortableJS not available, falling back to basic ordering.');
        }

        // Toggle showing value input based on select type
        function updateOptionInputsForType(type) {
            var $list = $('#optionList');
            if (type === 'select') {
                $list.find('.option-value').removeClass('d-none');
                $list.find('.option-label').attr('placeholder', 'Label');
            } else {
                $list.find('.option-value').addClass('d-none');
                $list.find('.option-label').attr('placeholder', 'Option');
            }
        }

        // Serialize options to hidden input
        function serializeOptions() {
            var type = $('#type').val();
            if (['select', 'radio', 'checkbox'].includes(type)) {
                var items = [];
                $('#optionList .option-item').each(function () {
                    var $item = $(this);
                    var label = $item.find('.option-label').val();
                    var value = $item.find('.option-value').val();

                    label = label ? String(label).trim() : '';
                    value = value ? String(value).trim() : '';

                    if (!label) return; // skip empty

                    if (type === 'select') {
                        if (!value) value = label;
                        items.push({ value: value, label: label });
                    } else {
                        items.push(label);
                    }
                });

                $('#options').val(JSON.stringify(items));
                return items.length > 0;
            } else {
                $('#options').val('');
                return true;
            }
        }

        // Serialize options into hidden #options before submit
        $('#add-modal form').on('submit', function(e) {
            var type = $('#type').val();
            if (['select', 'radio', 'checkbox'].includes(type)) {
                var hasOptions = serializeOptions();

                if (!hasOptions) {
                    e.preventDefault();
                    e.stopPropagation();
                    toastr.error('Please add at least one option for ' + type + ' type questions.');
                    return false;
                }
            } else {
                $('#options').val('');
            }
        });

        var table = null;
        if ($.fn.DataTable) {
            table = $('#questionsDataTable').DataTable({
                pageLength: 10,
                ordering: false,
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: (function () {
                    if (!indexUrl) {
                        console.error('questionsDataTable: indexUrl is not defined');
                        return null;
                    }

                    return {
                        url: indexUrl,
                        type: 'GET',
                        error: function (xhr, textStatus, errorThrown) {
                            // Log useful info for debugging and show a user-friendly message
                            console.error('DataTable ajax error:', textStatus, errorThrown);
                            console.error(xhr && xhr.responseText ? xhr.responseText : xhr);

                            var msg = 'Error loading table data.';
                            // If session expired or CSRF we may get 419/401 and HTML redirect to login
                            if (xhr && xhr.status === 419) {
                                msg = 'Session expired. Please refresh the page and log in again.';
                                if (window.toastr) toastr.error(msg);
                                setTimeout(function () { location.reload(); }, 1200);
                                return;
                            }

                            if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            } else if (xhr && xhr.status >= 500) {
                                msg = 'Server error while loading list.';
                            }

                            if (window.toastr) toastr.error(msg);
                        }
                    };
                })(),
                dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
                language: {
                    paginate: {
                        previous: "<i class='fa-solid fa-angles-left'></i>",
                        next: "<i class='fa-solid fa-angles-right'></i>",
                    },
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    lengthMenu: "Show _MENU_ entries",
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'question', name: 'question'},
                    {data: 'type', name: 'type'},
                    {data: 'required', name: 'required', orderable: false, searchable: false},
                    {data: 'order', name: 'order'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        }

        // Wire the modal form into the global common.js flow so responses are handled consistently
        // switch existing ajax-custom -> ajax and use the builtin 'settingCommonHandler'
        // ensure the form has the expected classes/handler in case blade didn't set them
        var $modalForm = $('#add-modal form');
        if (!$modalForm.hasClass('ajax')) {
            $modalForm.removeClass('ajax-custom').addClass('ajax').attr('data-handler', 'settingCommonHandler');
        }

        // When the modal is shown, update its title and submit button text depending on Create vs Update
        $('#add-modal').on('show.bs.modal', function () {
            var $form = $(this).find('form.ajax');
            var $title = $(this).find('h5').first();
            var $submitText = $form.find('button[type=submit] span').last();
            var currentAction = $form.prop('action');
            var isCreateMode = !currentAction || currentAction === storeUrl || currentAction.endsWith('/questions/store');

            if (isCreateMode) {
                // Creating new question - ensure form is reset and options hidden
                $form.trigger('reset');
                $form.find('[name="type"]').val('text').trigger('change');
                $form.find('[name="options"]').val('');
                // clear dynamic option rows as well
                $('#optionList').empty();
                // Uncheck use_countries checkbox
                $('#use_countries').prop('checked', false);
                // Enable add option button and make inputs editable
                $('#addOptionBtn').prop('disabled', false).removeClass('opacity-50');
                $('#optionList .option-label, #optionList .option-value').prop('readonly', false).removeClass('bg-light');
                $('#optionList .remove-option').prop('disabled', false).removeClass('opacity-50');
                updateOptionInputsForType('text');
                // Clear criteria field checkboxes ONLY in create mode
                $form.find('.criteria-field-checkbox').prop('checked', false);
                $title.text('Add New Question');
                $submitText.text('Create');
                // update niceSelect UI if available
                try { $('.sf-select-without-search').niceSelect('update'); } catch (e) {}
            } else {
                // Edit mode - don't clear anything, the edit handler will populate everything
                // DO NOT clear checkboxes here - they will be set by the edit handler after modal is shown
                $title.text('Edit Question');
                $submitText.text('Update');
            }
        });

        // Edit handler - populate modal and set form action to update
        $('#questionsDataTable').on('click', '.edit-btn', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            if (!id) return;
            var showUrl = $('#questionsShowBase').val() + '/' + id;
            var $form = $('#add-modal').find('form.ajax');

            // Set update action FIRST so modal show event knows it's edit mode
            $form.prop('action', $('#questionsUpdateBase').val() + '/' + id);
            $form.data('method', 'POST');

            $.get(showUrl, function (res) {
                if (res && res.status) {
                    var data = res.data;

                    // Populate form fields
                    $form.find('[name="question"]').val(data.question);
                    $form.find('[name="type"]').val(data.type).trigger('change');
                    try { $form.find('.sf-select-without-search').niceSelect('update'); } catch(e) {}
                    $form.find('[name="order"]').val(data.order);
                    $form.find('[name="required"]').prop('checked', data.required ? true : false);

                    // Handle options for select/radio/checkbox - populate optionList UI
                    $('#optionList').empty();
                    // Reset use_countries checkbox initially
                    $('#use_countries').prop('checked', false);
                    $('#addOptionBtn').prop('disabled', false).removeClass('opacity-50');
                    // Make inputs editable initially
                    $('#optionList .option-label, #optionList .option-value').prop('readonly', false).removeClass('bg-light');
                    $('#optionList .remove-option').prop('disabled', false).removeClass('opacity-50');

                    if (data.options && ['select', 'radio', 'checkbox'].includes(data.type)) {
                        // First, populate the options as they are
                        if (data.type === 'select') {
                            // data.options expected as array of {value,label}
                            data.options.forEach(function (opt) {
                                var val = (typeof opt === 'object' && opt.value) ? opt.value : (opt || '');
                                var lbl = (typeof opt === 'object' && opt.label) ? opt.label : (opt || '');
                                $('#optionList').append(createOptionRow(val, lbl));
                            });
                        } else {
                            // radio/checkbox: array of strings
                            (Array.isArray(data.options) ? data.options : []).forEach(function (opt) {
                                $('#optionList').append(createOptionRow('', opt));
                            });
                        }
                        // ensure option inputs reflect type (show/hide value inputs)
                        updateOptionInputsForType(data.type);

                        // Check if options match countries from database
                        var countriesRoute = getCountriesRoute();
                        if (countriesRoute) {
                            $.get(countriesRoute, function(countriesRes) {
                                if (countriesRes && countriesRes.status && countriesRes.data) {
                                    var countries = countriesRes.data;
                                    var questionOptions = data.options || [];
                                    var matchesCountries = false;

                                    if (data.type === 'select') {
                                        // For select: check if all question options exist in countries (value = id, label = name)
                                        // Allow for countries to be added/removed, but all question options must match
                                        if (questionOptions.length > 0) {
                                            matchesCountries = true;
                                            for (var i = 0; i < questionOptions.length; i++) {
                                                var opt = questionOptions[i];
                                                var val = (typeof opt === 'object' && opt.value) ? String(opt.value) : String(opt || '');
                                                var lbl = (typeof opt === 'object' && opt.label) ? opt.label : (opt || '');

                                                var found = false;
                                                for (var j = 0; j < countries.length; j++) {
                                                    if (String(countries[j].id) === val && countries[j].name === lbl) {
                                                        found = true;
                                                        break;
                                                    }
                                                }
                                                if (!found) {
                                                    matchesCountries = false;
                                                    break;
                                                }
                                            }
                                            // Also check if all countries are in question options (to ensure it's a complete match)
                                            if (matchesCountries && questionOptions.length === countries.length) {
                                                // Perfect match - all countries are present
                                            } else if (matchesCountries) {
                                                // Partial match - some countries might have been added/removed, but all question options are valid countries
                                                // Still consider it a match if all question options are countries
                                            }
                                        }
                                    } else {
                                        // For radio/checkbox: check if all question options match country names
                                        if (questionOptions.length > 0) {
                                            var countryNames = countries.map(function(c) { return c.name; });
                                            matchesCountries = true;

                                            for (var k = 0; k < questionOptions.length; k++) {
                                                var optName = typeof questionOptions[k] === 'string' ? questionOptions[k] : (questionOptions[k].label || questionOptions[k].value || questionOptions[k]);
                                                if (countryNames.indexOf(optName) === -1) {
                                                    matchesCountries = false;
                                                    break;
                                                }
                                            }

                                            // If all question options are countries, check if count matches (optional - allows for flexibility)
                                            // We'll consider it a match if all options are valid country names
                                        }
                                    }

                                    // If options match countries, check the checkbox and make read-only
                                    if (matchesCountries) {
                                        $('#use_countries').prop('checked', true);
                                        $('#optionList .option-label, #optionList .option-value').prop('readonly', true).addClass('bg-light');
                                        $('#addOptionBtn').prop('disabled', true).addClass('opacity-50');
                                        $('#optionList .remove-option').prop('disabled', true).addClass('opacity-50');
                                    }
                                }
                            });
                        }
                    }

                    // Show modal FIRST, then set checkboxes after it's fully shown
                    $('#add-modal').modal('show');

                    // Set criteria checkboxes AFTER modal is fully shown to avoid timing issues
                    $('#add-modal').one('shown.bs.modal', function() {
                        // Use a small delay to ensure DOM is fully ready
                        setTimeout(function() {
                            var $modalForm = $('#add-modal').find('form.ajax');

                            // Clear all first
                            $modalForm.find('.criteria-field-checkbox').prop('checked', false);

                            // Then set the mapped ones
                            if (data.mapped_criteria_fields && Array.isArray(data.mapped_criteria_fields) && data.mapped_criteria_fields.length > 0) {
                                data.mapped_criteria_fields.forEach(function(criteriaFieldId) {
                                    // Convert to string to ensure matching
                                    var criteriaId = String(criteriaFieldId);
                                    var checkboxId = 'criteria_field_' + criteriaId;

                                    // Try multiple ways to find the checkbox
                                    var $checkbox = $('#add-modal').find('#' + checkboxId);

                                    if ($checkbox.length === 0) {
                                        // Try finding by value attribute
                                        $checkbox = $('#add-modal').find('.criteria-field-checkbox[value="' + criteriaId + '"]');
                                    }

                                    if ($checkbox.length === 0) {
                                        // Try finding by value attribute with number
                                        $checkbox = $('#add-modal').find('.criteria-field-checkbox[value="' + parseInt(criteriaId) + '"]');
                                    }

                                    if ($checkbox.length) {
                                        $checkbox.prop('checked', true);
                                        // Force trigger change event to ensure UI updates
                                        $checkbox.trigger('change');
                                    }
                                });
                            }
                        }, 50);
                    });
                } else {
                    if (window.toastr) toastr.error('Could not load question');
                }
            }).fail(function () {
                if (window.toastr) toastr.error('Could not load question');
            });
        });

        // Delete handler with SweetAlert confirmation
        $('#questionsDataTable').on('click', '.delete-btn', function (e) {
            e.preventDefault();
            var id = $(this).data('question');
            if (!id) return;

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: deleteBase + '/' + id,
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': csrf },
                        success: function (res) {
                            if (res && res.status) {
                                Swal.fire(
                                    'Deleted!',
                                    res.message || 'Question has been deleted.',
                                    'success'
                                );
                                if (table) table.ajax.reload(null, false);
                                else location.reload();
                            } else {
                                Swal.fire(
                                    'Error!',
                                    (res && res.message) || 'Could not delete question.',
                                    'error'
                                );
                            }
                        },
                        error: function () {
                            Swal.fire(
                                'Error!',
                                'Error communicating with server.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        // Reset modal form when hidden
        $('#add-modal').on('hidden.bs.modal', function () {
            var $form = $(this).find('form.ajax');
            // reset action and method
            $form.prop('action', storeUrl);
            $form.removeData('method');
            // reset fields
            $form.trigger('reset');
            // explicitly hide options and clear options textarea to avoid leftover state
            $form.find('[name="type"]').val('text').trigger('change');
            $form.find('[name="options"]').val('');
            // clear dynamic option rows
            $('#optionList').empty();
            // Reset use_countries checkbox
            $('#use_countries').prop('checked', false);
            // Enable add option button and make inputs editable
            $('#addOptionBtn').prop('disabled', false).removeClass('opacity-50');
            $('#optionList .option-label, #optionList .option-value').prop('readonly', false).removeClass('bg-light');
            $('#optionList .remove-option').prop('disabled', false).removeClass('opacity-50');
            $form.find('.is-invalid').removeClass('is-invalid');
            $form.find('.error-message').remove();
        });
    });
})(jQuery);
