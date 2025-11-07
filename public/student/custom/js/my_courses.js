(function ($) {
    "use strict";

    let debounceTimer;

    // Keyup event with debounce
    $(document).on('keyup', "#search-key", function () {
        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(function () {
            let url = updateUrlWithSearchKey($('#my-course-route').val(), $('#search-key').val());
            searchCourses(url);
        }, 300); // Delay in milliseconds
    });

    // Pagination click event
    $(document).on('click', '.zPaginatation-list a', function (e) {
        e.preventDefault();
        let url = updateUrlWithSearchKey($(this).attr('href'), $('#search-key').val());
        searchCourses(url);
    });

    function searchCourses(url) {
        commonAjax('GET', url, searchResponse, searchResponse);
    }

    function searchResponse(response) {
        if (response.status === 200) {
            $('#course-list-container').html(response.responseText);

            // Update pagination links to include the search key
            let currentSearchKey = $('#search-key').val();
            $('#course-list-container .pagination a').each(function () {
                let newUrl = updateUrlWithSearchKey($(this).attr('href'), currentSearchKey);
                $(this).attr('href', newUrl);
            });
        } else {
            $('#course-list-container').html('');
        }
    }

    function updateUrlWithSearchKey(url, searchKey) {
        let newUrl = new URL(url, window.location.origin);
        if (searchKey) {
            newUrl.searchParams.set('search_key', searchKey); // Add or replace 'search_key' parameter
        } else {
            newUrl.searchParams.delete('search_key'); // Remove 'search_key' if empty
        }
        return newUrl.href;
    }

})(jQuery);
