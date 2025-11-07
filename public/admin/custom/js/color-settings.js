(function ($) {
    ("use strict");

    $(document).on('change', '#app_color_design_type', function () {
        if ($(this).val() == 1) {
            $('#custom-color-block').addClass('d-none');
        } else {
            $('#custom-color-block').removeClass('d-none');
        }
    });

})(jQuery);
