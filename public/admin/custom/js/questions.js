(function ($) {
    "use strict";

    $(document).ready(function () {
        // initialize server-side DataTable
        var storeUrl = $('#questionsStoreRoute').val();
        var deleteBase = $('#questionsDeleteBase').val();
        var csrf = $('meta[name="csrf-token"]').attr('content');
        var indexUrl = $('#questionsIndexRoute').val();

        var table = null;
        if ($.fn.DataTable) {
            table = $('#questionsDataTable').DataTable({
                pageLength: 10,
                ordering: false,
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: indexUrl,
                    type: 'GET'
                },
                dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
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

        // AJAX submit for Add/Update Question form (use specific class so global handlers don't double-submit)
        $('#add-modal').on('submit', 'form.ajax-custom', function (e) {
            e.preventDefault();
            // stop other delegated handlers (like common.js) from running
            e.stopImmediatePropagation();
            var $form = $(this);
            var url = $form.prop('action');
            var method = ($form.data('method') || 'POST').toUpperCase();

            // clear previous validation messages
            $form.find('.is-invalid').removeClass('is-invalid');
            $form.find('.error-message').remove();

            $.ajax({
                url: url,
                method: method,
                data: $form.serialize(),
                headers: { 'X-CSRF-TOKEN': csrf },
                success: function (res) {
                    if (res && res.status) {
                        // close modal (Bootstrap)
                        $('#add-modal').modal('hide');
                        // use toastr for feedback
                        if (window.toastr) toastr.success(res.message || 'Question saved');
                        else alert(res.message || 'Question saved');
                        // reload or refresh datatable
                        if (table) table.ajax.reload(null, false);
                        else location.reload();
                    } else {
                        if (window.toastr) toastr.error((res && res.message) || 'Could not save question');
                        else alert((res && res.message) || 'Could not save question');
                    }
                },
                error: function (xhr) {
                    if (xhr && xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function (field, messages) {
                            var $input = $form.find("[name='" + field + "']");
                            if (!$input.length) {
                                $input = $form.find("[name^='" + field + "']");
                            }
                            $input.addClass('is-invalid');
                            $input.closest('div').append('<span class="text-danger p-2 fs-12 z-index-10 position-relative error-message">' + messages[0] + '</span>');
                        });
                        if (window.toastr) toastr.error('Please review the form and fix errors');
                    } else {
                        var msg = 'Something went wrong';
                        if (xhr && xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                        if (window.toastr) toastr.error(msg);
                        else alert(msg);
                    }
                }
            });
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
                    var $form = $('#add-modal').find('form.ajax-custom');
                    $form.find('[name="question"]').val(data.question);
                    $form.find('[name="type"]').val(data.type);
                    $form.find('[name="order"]').val(data.order);
                    $form.find('[name="required"]').prop('checked', data.required ? true : false);
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
            var $form = $(this).find('form.ajax-custom');
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
