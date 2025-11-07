(function ($) {
    "use strict";

    $(document).ready(function () {
        // initialize DataTable if plugin available
        if ($.fn.DataTable) {
            $('#questionsDataTable').DataTable({
                pageLength: 10,
                ordering: false,
                responsive: true,
                dom: '<>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
            });
        }

        var storeUrl = $('#questionsStoreRoute').val();
        var deleteBase = $('#questionsDeleteBase').val();
        var csrf = $('meta[name="csrf-token"]').attr('content');

        // AJAX submit for Add Question form (use specific class so global handlers don't double-submit)
        $('#add-modal').on('submit', 'form.ajax-custom', function (e) {
            e.preventDefault();
            // stop other delegated handlers (like common.js) from running
            e.stopImmediatePropagation();
            var $form = $(this);
            $.ajax({
                url: storeUrl,
                method: 'POST',
                data: $form.serialize(),
                headers: { 'X-CSRF-TOKEN': csrf },
                success: function (res) {
                    if (res && res.status) {
                        // close modal (Bootstrap)
                        $('#add-modal').modal('hide');
                        // simple feedback - replace with your toast system if available
                        alert(res.message || 'Question created');
                        // reload to reflect changes
                        location.reload();
                    } else {
                        alert((res && res.message) || 'Could not create question');
                    }
                },
                error: function (xhr) {
                    var msg = 'Something went wrong';
                    if (xhr && xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                    alert(msg);
                }
            });
        });

        // Delete handler
        $('#questionsDataTable').on('click', '.delete-btn', function (e) {
            e.preventDefault();
            if (!confirm('Are you sure you want to delete this question?')) return;
            var id = $(this).data('question');
            if (!id) return;

            $.ajax({
                url: deleteBase + '/' + id,
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrf },
                success: function (res) {
                    if (res && res.status) {
                        alert(res.message || 'Deleted');
                        location.reload();
                    } else {
                        alert((res && res.message) || 'Could not delete');
                    }
                },
                error: function () {
                    alert('Error communicating with server');
                }
            });
        });
    });
})(jQuery);
