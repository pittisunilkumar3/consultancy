var getCurrencySymbol = $('#getCurrencySymbol').val();
var allCurrency = JSON.parse($('#allCurrency').val());

(function ($) {
    "use strict";
    $(document).on('click', '.addCurrencyBtn', function (e) {
        var html = '';
        var options = '';
        Object.entries(allCurrency).forEach((currency) => {
            options += '<option value="' + currency[0] + '">' + currency[1] + '</option>';
        });
        html += `<li class="d-flex justify-content-between align-items-center g-10 paymentConversionRate">
                    <div class="flex-grow-1 d-flex flex-wrap flex-sm-nowrap left">
                        <select class="sf-select currency" name="currency[]">${options}</select>
                        <p class="p-13 fs-14 fw-400 lh-22 text-title-black bg-body-bg text-nowrap bd-l-one bd-r-one bd-c-stroke">1${getCurrencySymbol} = </p>
                        <input type="text" name="conversion_rate[]" class="form-control zForm-control" id="" placeholder="1.00" />
                        <p class="p-13 fs-14 fw-400 lh-22 text-title-black bg-body-bg text-nowrap bd-l-one bd-c-stroke append_currency"></p>
                    </div>
                    <button type="submit" class="flex-shrink-0 bd-one bd-c-stroke rounded-circle w-25 h-25 fs-12 d-flex justify-content-center align-items-center bg-transparent text-danger removedCurrencyBtn">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </li>`;
        $(document).find('#currencyConversionRateSection').append(html);
        $(document).find('.currency').trigger("change");
        $(document).find(".sf-select").select2({
            dropdownCssClass: "sf-select-dropdown",
            selectionCssClass: "sf-select-section",
        });
    })

    $(document).on('click', '.removedCurrencyBtn', function () {
        $(this).closest('li').remove();
    });

    $(document).on('change', '.currency', function () {
        $(this).closest('li').find('.append_currency').text($(this).val())
    });

    // Bank
    $(document).on('click', '.addBankBtn', function (e) {
        $(document).find('.bankItemLists').append(addBank());
    });

    $(document).on('click', '.removedBankBtn', function () {
        $(this).closest('li').remove()
    });

    window.addBank = function () {
        return `<li class="d-flex justify-content-between align-items-center g-10">
                    <div class="flex-grow-1 d-flex flex-wrap flex-sm-nowrap g-10 left">
                        <div class="flex-grow-1">
                            <input type="text" class="form-control zForm-control" name="bank[name][]" placeholder="Name">
                        </div>
                        <div class="flex-grow-1">
                            <textarea name="bank[details][]" class="form-control zForm-control" placeholder="Details"></textarea>
                        </div>
                    </div>
                    <button type="button"
                        class="flex-shrink-0 bd-one bd-c-stroke rounded-circle w-25 h-25 d-flex justify-content-center align-items-center bg-transparent text-danger removedBankBtn">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </li>`;
    }


})(jQuery);
