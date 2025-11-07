(function ($) {
    "use strict";

    window.changeFormSetting = function ($selector, $slug, $key) {
        let value = $($selector).is(':checked') ? 1 : 0;
        let data = new FormData();
        data.append('field_slug', $slug);
        data.append('field_key', $key);
        data.append('field_value', value);
        data.append("_token", $('meta[name="csrf-token"]').attr('content'));

        commonAjax('POST', $('#statusChangeRoute').val(), statusChangeResponse, statusChangeResponse, data);
    }

    window.statusChangeResponse = function (response) {
        $('.error-message').remove();
        $('.is-invalid').removeClass('is-invalid');
        if (response['status'] === true) {
            toastr.success(response['message']);
        } else {
            toastr.error(response['message']);
            // location.reload();
        }
    }

    var formAddFieldSelector = document.getElementById("customField");
    var options = {
        disableHTMLLabels: false,
        controlPosition: "left",
        disabledActionButtons: ["data"],
        onCloseFieldEdit: false,
        disableFields: ["autocomplete", "file", "hidden", "header", "date", "paragraph", "button"],
        controlOrder: ["text", "number", "select", "checkbox-group", "radio-group"],
        showActionButtons: false,
        defaultFields: editFields,
    };
    var formBuilder = $(formAddFieldSelector).formBuilder(options);
    $(document).on('click', '#saveCustomField', function () {
        $(this).closest('form').find('input[name=custom_field]').val(formBuilder.actions.getData('json'))
    });

})(jQuery)

