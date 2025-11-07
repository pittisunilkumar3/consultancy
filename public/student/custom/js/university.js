(function ($) {
    "use strict";

    $(document).ready(function () {
        // Search functionality
        $('#search-key').on('keyup', function () {
            let searchKey = $(this).val();
            let url = $('#university-route').val();

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    search_key: searchKey
                },
                dataType: "html",
                success: function (response) {
                    $('#university-list-container').html(response);
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });

        // Pagination click event
        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            let url = $(this).attr('href');
            let searchKey = $('#search-key').val();

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    search_key: searchKey
                },
                dataType: "html",
                success: function (response) {
                    $('#university-list-container').html(response);
                    $('html, body').animate({
                        scrollTop: $("#university-list-container").offset().top - 100
                    }, 500);
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });
    });

})(jQuery);
