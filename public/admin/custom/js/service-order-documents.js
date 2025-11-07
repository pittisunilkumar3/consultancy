(function ($) {
    "use strict";

    let debounceTimer;

    $(document).on('keyup', "#search-key", function () {
        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(function () {
            let url = updateUrlWithSearchKey($('#document-route').val(), $('#search-key').val());
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
            $('#document-list-container').html(response.responseText);

            let currentSearchKey = $('#search-key').val();
            $('#document-list-container .pagination a').each(function () {
                let newUrl = updateUrlWithSearchKey($(this).attr('href'), currentSearchKey);
                $(this).attr('href', newUrl);
            });
        } else {
            $('#document-list-container').html('');
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

    $(document).on('click', '.document-link', function (event) {
        // Check if the click originated from Edit or Delete button
        if ($(event.target).closest('.edit-btn').length || $(event.target).closest('.delete-btn').length) {
            event.preventDefault(); // Prevent the default link action
        }
    });


})(jQuery);
