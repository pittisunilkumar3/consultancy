(function ($) {
    "use strict";

    window.allowDrop = function (ev) {
        ev.preventDefault();
    }

    window.dragStart = function (ev) {
        ev.dataTransfer.setData("text/plain", ev.target.id);
    }

    window.dropIt = function (ev) {
        ev.preventDefault();
        let sourceId = ev.dataTransfer.getData("text/plain");
        let sourceIdEl = document.getElementById(sourceId);
        let sourceIdParentEl = sourceIdEl.parentElement;

        let targetEl = ev.target;

        // Find the correct drop target parent element
        while (!targetEl.classList.contains('task-column-items')) {
            targetEl = targetEl.parentElement;
        }
        let targetParentEl = targetEl;

        if (targetParentEl.id !== sourceIdParentEl.id) {
            targetParentEl.appendChild(sourceIdEl);

            // Update item counts
            updateItemCount(sourceIdParentEl);
            updateItemCount(targetParentEl);

            // Prepare AJAX call
            let taskId = sourceIdEl.querySelector('input[name="task_id"]').value; // Get task ID from hidden input

            let formData = new FormData();
            formData.append('task_id', taskId);
            formData.append('new_status', targetParentEl.dataset.status);

            // Add CSRF token from meta tag
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            formData.append('_token', csrfToken);

            commonAjax('POST', $('#order_status_change_route').val(), statusChangeResponse, statusChangeResponse, formData);
        }
    }

    function updateItemCount(parentEl) {
        let itemCountEl = parentEl.closest('.task-column').querySelector('.itemCount');
        if (itemCountEl) {
            itemCountEl.textContent = parentEl.children.length;
        }
    }

    $(".sf-select-modal-label").select2({
        tags: true,
        dropdownCssClass: "sf-select-dropdown",
        selectionCssClass: "sf-select-section",
        dropdownParent: $("#addTaskModal"),
    });


    window.statusChangeResponse = function (response) {
        if (response.status !== true) {
            toastr.success(response.message);
        }
    }

    window.editResponse = function (response) {
        $(document).find('#editTaskModal').find('.sf-select-modal-label').select2({
            tags: true,
            dropdownCssClass: "sf-select-dropdown",
            selectionCssClass: "sf-select-section",
            dropdownParent: $("#editTaskModal"),
        });
    }

    $(document).on("change", '.file-input', function (e) {
        const $fileInput = $(this);
        const $filesArea = $fileInput.closest('.file-input-wrapper').find('.files-area');
        const $filesNames = $filesArea.find('.files-names');

        // Check if dt is already attached to the file input, otherwise create a new one
        if (!$fileInput.data('dt')) {
            $fileInput.data('dt', new DataTransfer());
        }

        const dt = $fileInput.data('dt');

        for (let i = 0; i < this.files.length; i++) {
            let fileBloc = $("<span/>", {class: "file-block"}),
                fileName = $("<p/>", {
                    class: "name",
                    text: this.files.item(i).name,
                });
            fileBloc
                .append('<span class="file-icon"><i class="fa-solid fa-file"></i></span>')
                .append(fileName)
                .append('<span class="file-delete"><i class="fa-solid fa-xmark"></i></span>');
            $filesNames.append(fileBloc);

            dt.items.add(this.files.item(i));
        }

        this.files = dt.files;

        $filesNames.on("click", "span.file-delete", function () {
            let name = $(this).siblings("p.name").text();
            $(this).parent().remove();
            for (let i = 0; i < dt.items.length; i++) {
                if (name === dt.items[i].getAsFile().name) {
                    dt.items.remove(i);
                    break;
                }
            }
            $fileInput[0].files = dt.files;
        });
    });

    window.taskViewResponse = function (response) {
        const progress = document.querySelector(".taskProgress");
        const progressNumber = document.getElementById("taskProgress-txt");

        progress.addEventListener("input", function () {
            const value = this.value;
            this.style.background = `linear-gradient(to right, #007aff 0%, #007aff ${value}%, #5d697a ${value}%, #5d697a 100%)`;
            progressNumber.textContent = `${value}%`;
        });
    }

    window.chatResponse = function (response) {
        $('.error-message').remove();
        $('.is-invalid').removeClass('is-invalid');

        if (response['status'] === true) {
            // Clear text and file inputs
            $(document).find(".conversation-text").val('');
            $(document).find(".file-input").val(''); // Reset file input

            // Properly clear files-names container
            $(document).find(".files-area .files-names").html('');

            // Reset the DataTransfer object for file inputs
            $(document).find('.file-input').each(function () {
                const $fileInput = $(this);
                if ($fileInput.data('dt')) {
                    const dt = $fileInput.data('dt');
                    while (dt.items.length > 0) {
                        dt.items.remove(0); // Remove all items
                    }
                    $fileInput[0].files = dt.files; // Update the input
                }
            });

            // Update chat UI
            if (response.data.type == 1) {
                $(document).find(".admin-team-chat").html(response.data.conversationTeamTypeData);
                $(document).find('.admin-team-chat').scrollTop($(document).find('.admin-team-chat')[0]?.scrollHeight);
            } else {
                $(document).find(".admin-client-chat").html(response.data.conversationClientTypeData);
                $(document).find('.admin-client-chat').scrollTop($(document).find('.admin-client-chat')[0]?.scrollHeight);
            }

            // Re-enable the send button
            $(document).find(".send-btn").removeAttr('disabled');
        } else {
            commonHandler(response);
        }
    };

    window.openEditModal = function (url) {
        $('#viewTaskModal').modal('toggle');
        getEditModal(url, '#editTaskModal', 'editResponse');
    }

    window.deleteAttachment = function (url, viewUrl) {
        Swal.fire({
            title: 'Sure! You want to delete?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete It!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        toastr.success(data.message);
                        $('#viewTaskModal').modal('toggle');
                        getEditModal(viewUrl, '#viewTaskModal', 'taskViewResponse');
                    },
                    error: function (error) {
                        toastr.error(error.responseJSON.message)
                    }
                })
            }
        })
    }

    // Debounce function
    function debounce(func, delay) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

// Function to handle progress change
    function handleProgressChange() {
        var id = $(this).data('task_id'),
            progress = $(this).val(),
            route = $('#progress_change_route').val().replace('__ID__', id);

        let formData = new FormData();
        formData.append('progress', progress);
        // Add CSRF token from meta tag
        let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formData.append('_token', csrfToken);

        commonAjax('POST', route, progressChangeResponse, progressChangeResponse, formData);
    }

// Debounced version of the progress change handler
    var debouncedProgressChange = debounce(handleProgressChange, 500);

    $(document).on('input', ".taskProgress", debouncedProgressChange);


    window.progressChangeResponse = function (response) {
        if(response.status){
            toastr.success(response.message);
        }
    }

    $(document).on('click', '.assign-member', function (e) {
        var checkedStatus = 0;
        if ($(this).prop('checked') == true) {
            checkedStatus = 1;
        }
        commonAjax('GET', $('#assignMemberRoute').val(), assigneeResponse, assigneeResponse, {
            'member_id': $(this).val(),
            'checked_status': checkedStatus,
            'order_id': $(this).data('order'),
        });
    });

    function assigneeResponse(response) {
        if (response['status'] === true) {
            toastr.success(response['message']);
            location.reload();
        } else {
            commonHandler(response)
        }
    }

    $(document).on('click', '#noteAddModal', function (e) {
        $("#orderIdField").val($(this).data("order_id"));
    });
    $(document).on('click', '#noteEditModal', function (e) {
        $("#orderIdField").val($(this).data("order_id"));
        $("#noteDetails").val($(this).data("details"));
        $("#noteIdField").val($(this).data("id"));
    });

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

    window.enableStatusOnboarding = function (url) {
        // Reference the switch element
        let $switchElement = $('#enableOnboarding');

        // Get the current state of the switch
        let originalState = $switchElement.prop('checked');

        // Create FormData
        let formData = new FormData();
        let csrfToken = $('meta[name="csrf-token"]').attr('content');
        formData.append('_token', csrfToken);

        // Call commonAjax
        commonAjax(
            'POST',
            url,
            function (response) {
                // Success handler
                if (response.status === true) {
                    toastr.success(response.message); // Show success notification
                } else {
                    toastr.error(response.message); // Show error notification
                    $switchElement.prop('checked', originalState); // Reset switch
                }
            },
            function () {
                // Error handler
                toastr.error('An error occurred while processing the request.');
                $switchElement.prop('checked', originalState); // Reset switch
            },
            formData
        );
    };

})(jQuery);
