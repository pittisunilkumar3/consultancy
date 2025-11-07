(function ($) {
    "use strict";

    let debounceTimer;

    $(document).on('keyup', "#search-key", function () {
        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(function () {
            let url = updateUrlWithSearchKey($('#my-event-route').val(), $('#search-key').val());
            searchCourses(url);
        }, 300);
    });

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
            $('#event-list-container').html(response.responseText);

            let currentSearchKey = $('#search-key').val();
            $('#event-list-container .pagination a').each(function () {
                let newUrl = updateUrlWithSearchKey($(this).attr('href'), currentSearchKey);
                $(this).attr('href', newUrl);
            });
        } else {
            $('#event-list-container').html('');
        }
    }

    function updateUrlWithSearchKey(url, searchKey) {
        let newUrl = new URL(url, window.location.origin);
        if (searchKey) {
            newUrl.searchParams.set('search_key', searchKey);
        } else {
            newUrl.searchParams.delete('search_key');
        }
        return newUrl.href;
    }

})(jQuery);
