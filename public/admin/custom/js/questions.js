(function ($) {
    "use strict";

    $(document).ready(function () {
        // initialize server-side DataTable
        var storeUrl = $('#questionsStoreRoute').val();
        var deleteBase = $('#questionsDeleteBase').val();
        var csrf = $('meta[name="csrf-token"]').attr('content');
        var indexUrl = $('#questionsIndexRoute').val();

        // Show/hide options field based on question type
        $('#type').on('change', function() {
            var type = $(this).val();
            var $options = $('.options-wrapper');
            if (['select', 'radio', 'checkbox'].includes(type)) {
                $options.removeClass('d-none');
                if (type === 'select') {
                    $options.find('small').text('Enter each option on a new line. For key-value pairs, use pipe (|) separator: e.g., "value|Display Text"');
                } else {
                    $options.find('small').text('Enter each option on a new line.');
                }
            } else {
                $options.addClass('d-none');
            }
        });

        // Process options before form submit
        $('#add-modal form').on('submit', function() {
            var type = $('#type').val();
            if (['select', 'radio', 'checkbox'].includes(type)) {
                var options = $('#options').val().split('\n')
                    .map(line => line.trim())
                    .filter(line => line.length > 0);
                
                if (options.length === 0) {
                    toastr.error('Please add at least one option for ' + type + ' type questions.');
                    return false;
                }

                // Convert to array of objects for select type with key|value format
                if (type === 'select') {
                    options = options.map(line => {
                        var parts = line.split('|');
                        if (parts.length === 2) {
                            return { value: parts[0].trim(), label: parts[1].trim() };
                        }
                        return { value: line, label: line };
                    });
                }

                // Store as JSON string
                $('#options').val(JSON.stringify(options));
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
            if ($form.prop('action') === storeUrl || !$form.prop('action')) {
                $title.text('Add New Question');
                $submitText.text('Create');
            } else {
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
            $.get(showUrl, function (res) {
                if (res && res.status) {
                    var data = res.data;
                    var $form = $('#add-modal').find('form.ajax');
                    $form.find('[name="question"]').val(data.question);
                    $form.find('[name="type"]').val(data.type).trigger('change');
                    $form.find('[name="order"]').val(data.order);
                    $form.find('[name="required"]').prop('checked', data.required ? true : false);
                    
                    // Handle options for select/radio/checkbox
                    if (data.options && ['select', 'radio', 'checkbox'].includes(data.type)) {
                        var optionsText = '';
                        if (data.type === 'select') {
                            // Convert array of objects back to key|value format
                            optionsText = data.options.map(opt => {
                                if (typeof opt === 'object' && opt.value && opt.label) {
                                    return opt.value + '|' + opt.label;
                                }
                                return opt.value || opt;
                            }).join('\n');
                        } else {
                            // For radio/checkbox, just one option per line
                            optionsText = Array.isArray(data.options) ? data.options.join('\n') : '';
                        }
                        $form.find('[name="options"]').val(optionsText);
                    }
                    // set update action
                    $form.prop('action', $('#questionsUpdateBase').val() + '/' + id);
                    $form.data('method', 'POST');
                    $('#add-modal').modal('show');
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
            $form.find('.is-invalid').removeClass('is-invalid');
            $form.find('.error-message').remove();
        });
    });
})(jQuery);
