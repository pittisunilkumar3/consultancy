<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Update Language') }}</h2>
    <div class="mClose">
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>

<form class="ajax reset" action="{{ route('admin.setting.languages.update', $language->id) }}" method="post"
      data-handler="languageHandler">
    @csrf

    <div class="row rg-20">
        <div class="col-12">
            <label for="currentPassword" class="zForm-label-alt">{{ __('Language') }} <span
                    class="text-danger">*</span></label>
            <input type="text" class="form-control zForm-control-alt" name="language"
                   value="{{ $language->language }}" required placeholder="{{ __('Type Language Name') }}">
        </div>

        <div class="col-12">
            <label for="iso_code" class="zForm-label-alt">{{ __('ISO Code') }} <span
                    class="text-danger">*</span></label>
            <select name="iso_code" class="sf-select-edit-modal primary-form-control">
                <option value="">--{{ __('Select ISO Code') }}--</option>
                @foreach (languageIsoCode() as $code => $isoCountryName)
                    <option value="{{ $code }}" {{ $code==$language->iso_code ? 'selected' : '' }}>
                        {{ $isoCountryName . '-' . $code }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12">
            <div class="primary-form-group">
                <div class="primary-form-group-wrap zImage-upload-details mw-100">
                    <div class="zImage-inside">
                        <div class="d-flex pb-12"><img src="{{ asset('assets/images/icon/upload-img-1.svg') }}"
                                                       alt=""/></div>
                        <p class="fs-15 fw-500 lh-16 text-1b1c17">{{ __('Drag & drop files here') }}</p>
                    </div>
                    <label for="zImageUpload" class="zForm-label-alt">{{ __('Flag') }} <span
                            class="text-mime-type">(jpeg,png,jpg,svg,webp)</span> <span
                            class="text-danger">*</span></label>
                    <div class="upload-img-box">
                        <img src="{{ getFileUrl($language->flag_id) }}"/>
                        <input type="file" name="flag" id="flag" accept="image/*" onchange="previewFile(this)"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="primary-form-group d-none">
            <div class="primary-form-group-wrap">
                <label for="attachmentFile" class="zForm-label-alt">{{ __('Font File') }}
                    @if ($language->font)
                        <a href="{{ getFileUrl($language->font) }}"
                           class="position-absolute top-0 start-100 bg-cdef84 badge border border-light rounded-circle bg-danger p-2"
                           target="_blank">
                            <span class="visually-hidden"></span>
                        </a>
                    @endif
                </label>
                <input type="file" class="form-control zForm-control-alt" id="attachmentFile" accept="application/pdf"
                       name="font">
                @if ($errors->has('font'))
                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                        {{ $errors->first('font') }}</span>
                @endif

            </div>
        </div>
        <div class="col-12">
            <label for="rtl" class="zForm-label-alt">{{ __('RTL Supported') }}</label>
            <select class="sf-select-without-search" name="rtl" required>
                <option {{ $language->rtl == 0 ? 'selected' : '' }} value="0">{{ __('No') }}
                </option>
                <option {{ $language->rtl == 1 ? 'selected' : '' }} value="1">{{ __('Yes') }}
                </option>
            </select>
        </div>

        <div class="col-12">
            <div class="d-flex form-check ps-0">
                <div class="zCheck form-check form-switch">
                    <input class="form-check-input" type="checkbox" value="1" name="default" {{ $language->default
                        == STATUS_ACTIVE ? 'checked' : '' }} role="switch"
                           id="flexSwitchCheckChecked-{{ $language->id }}"/>
                </div>
                <label class="form-check-label ps-3" for="flexCheckChecked-{{ $language->id }}">
                    {{ __('Default Language') }}
                </label>
            </div>
        </div>
    </div>

    <div class="d-flex g-12 flex-wrap mt-25">
        <button class="flipBtn sf-flipBtn-primary" type="submit">
            <span>{{ __('Update') }}</span>
            <span>{{ __('Update') }}</span>
            <span>{{ __('Update') }}</span>
        </button>
    </div>
</form>
