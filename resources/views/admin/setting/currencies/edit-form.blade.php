<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Update Currency') }}</h2>
    <div class="mClose">
        <button type="button" class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text" data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>
<form class="ajax reset" action="{{ route('admin.setting.currencies.update', $currency->id) }}" method="post"
      data-handler="settingCommonHandler">
    @csrf
    @method('PATCH')

        <div class="row rg-15">
            <div class="col-12">
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap">
                        <label for="currency_code" class="form-label">{{ __('Currency ISO Code') }} <span
                                class="text-danger">*</span></label>
                        <select class="sf-select-edit-modal primary-form-control" id="currency_code"
                                name="currency_code">
                            @foreach(getCurrency() as $code => $currencyItem)
                                <option
                                    value="{{$code}}" {{ $code == $currency->currency_code ? 'selected' : '' }}>{{$currencyItem}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="">
                <label for="symbol" class="zForm-label-alt">{{ __('Symbol') }} <span
                        class="text-danger">*</span></label>
                <input type="text" name="symbol" id="symbol" placeholder="{{ __('Type Symbol') }}"
                       class="form-control zForm-control-alt" value="{{ $currency->symbol }}">
            </div>
            <div class="col-12">
                <div class="primary-form-group">
                    <div class="primary-form-group-wrap">
                        <label for="currency_placement" class="form-label">{{ __('Currency Placement') }}<span
                                class="text-danger">*</span></label>
                        <select class="sf-select-without-search primary-form-control" name="currency_placement">
                            <option value="">--{{ __('Select Option') }}--</option>
                            <option
                                {{ $currency->currency_placement == "before" ? 'selected' : '' }} value="before">{{ __('Before Amount') }}</option>
                            <option
                                {{ $currency->currency_placement == "after" ? 'selected' : '' }} value="after">{{ __('After Amount') }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-4">
                <div class="d-flex form-check ps-0 mb-0 pl-0">
                    <div class="zCheck form-switch">
                        <input class="form-check-input mt-0" value="1" name="current_currency"
                               {{ $currency->current_currency == STATUS_ACTIVE ? 'checked' : '' }}
                               type="checkbox" id="flexCheckChecked--{{$currency->id}}">
                    </div>
                    <label class="form-check-label ps-3 d-flex" for="flexCheckChecked-{{ $currency->id }}">
                        {{ __('Current Currency') }}
                    </label>
                </div>
            </div>
        </div>

    <div class="pt-20 mt-20 bd-t-one bd-c-stroke">
        <button type="submit" class="flipBtn sf-flipBtn-primary">
            <span>{{ __('Update') }}</span>
            <span>{{ __('Update') }}</span>
            <span>{{ __('Update') }}</span>
        </button>
    </div>
</form>
