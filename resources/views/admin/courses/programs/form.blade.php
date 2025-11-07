<div class="row rg-20 mb-20">
    <div class="col-md-6">
        <label for="title" class="zForm-label-alt">{{ __('Title') }} <span class="text-danger">*</span></label>
        <input type="text" name="title" id="title" placeholder="{{ __('Type Title') }}"
               value="{{ $program->title ?? '' }}"
               class="form-control zForm-control-alt">
    </div>
    <div class="col-6">
        <label for="status" class="zForm-label-alt">{{ __('Status') }} <span class="text-danger">*</span></label>
        <select class="sf-select-without-search" id="eventType" name="status">
            <option value="{{ STATUS_ACTIVE }}" {{ (isset($program) && $program->status == STATUS_ACTIVE) ? 'selected' : '' }}>
                {{ __('Active') }}
            </option>
            <option value="{{ STATUS_DEACTIVATE }}" {{ (isset($program) && $program->status == STATUS_DEACTIVATE) ? 'selected' : '' }}>
                {{ __('Deactivate') }}
            </option>
        </select>
    </div>
</div>

<div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white mb-20">
    <div class="row rg-20">
        <div class="col-md-12">
            <h6 class="bd-b-one bd-c-stroke-2 fw-700 pb-10">{{ __('Top Section') }}</h6>
        </div>
        <div class="col-md-6">
            <label for="top_section_title_{{ $program->id ?? '' }}" class="zForm-label-alt">{{ __('Title') }} <span class="text-danger">*</span></label>
            <input type="text" name="top_section[title]" id="top_section_title_{{ $program->id ?? '' }}" placeholder="{{ __('Type Title') }}"
                   value="{{ $program->top_section['title'] ?? '' }}"
                   class="top_section_title form-control zForm-control-alt">
        </div>
        <div class="col-md-6">
            <label for="top_section_image_{{ $program->id ?? '' }}" class="zForm-label-alt">{{ __('Image') }}
                <span class="text-danger">*</span>
                <span class="text-mime-type">{{ __('(jpeg,png,jpg,svg,webp)') }}</span>
            </label>

            @if(isset($program->top_section['image']) && $program->top_section['image'])
                <small>
                    <a href="{{ getFileUrl($program->top_section['image']) }}" target="_blank" class="view-image-link">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                </small>
            @endif

            <div class="file-upload-one">
                <label for="top_section_image_{{ $program->id ?? '' }}">
                    <p class="fileName fs-14 fw-500 lh-24 text-para-text">{{ __('Choose Image to upload') }}</p>
                    <p class="fs-15 fw-700 lh-28 text-white">{{ __('Browse File') }}</p>
                </label>
                <input type="file" name="top_section[image]" id="top_section_image_{{ $program->id ?? '' }}"
                       class="fileUploadInput invisible position-absolute top_section_image"
                       accept="image/jpeg,image/png,image/jpg,image/svg+xml,image/webp">
            </div>
            <span
                class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 535 px / 485 px")}}</span>
        </div>
        <div class="col-md-12">
            <label for="top_section_details_{{ $program->id ?? '' }}" class="zForm-label-alt">{{ __('Details') }} <span class="text-danger">*</span></label>
            <textarea class="top_section_details summernoteOne" name="top_section[details]" id="top_section_details_{{ $program->id ?? '' }}"
                      placeholder="{{__('Details')}}">{{ $program->top_section['details'] ?? '' }}</textarea>
        </div>
    </div>
</div>

<div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white mb-20">
    <div class="row rg-20">
        <div class="col-md-12">
            <h6 class="bd-b-one bd-c-stroke-2 fw-700 pb-10">{{ __('Step Section') }}</h6>
        </div>
        <div class="col-md-6">
            <label for="step_section_title_{{ $program->id ?? '' }}" class="zForm-label-alt">{{ __('Title') }} <span class="text-danger">*</span></label>
            <input type="text" name="step_section[title]" id="step_section_title_{{ $program->id ?? '' }}" placeholder="{{ __('Type Title') }}"
                   value="{{ $program->step_section['title'] ?? '' }}"
                   class="step_section_title form-control zForm-control-alt">
        </div>
        <div class="col-md-6">
            <label for="step_section_image_{{ $program->id ?? '' }}" class="zForm-label-alt">{{ __('Image') }}
                <span class="text-danger">*</span>
                <span class="text-mime-type">{{ __('(jpeg,png,jpg,svg,webp)') }}</span>
            </label>

            @if(isset($program->step_section['image']) && $program->step_section['image'])
                <small>
                    <a href="{{ getFileUrl($program->step_section['image']) }}" target="_blank" class="view-image-link">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                </small>
            @endif

            <div class="file-upload-one">
                <label for="step_section_image_{{ $program->id ?? '' }}">
                    <p class="fileName fs-14 fw-500 lh-24 text-para-text">{{ __('Choose Image to upload') }}</p>
                    <p class="fs-15 fw-700 lh-28 text-white">{{ __('Browse File') }}</p>
                </label>
                <input type="file" name="step_section[image]" id="step_section_image_{{ $program->id ?? '' }}"
                       class="fileUploadInput invisible position-absolute step_section_image"
                       accept="image/jpeg,image/png,image/jpg,image/svg+xml,image/webp">
            </div>
            <span
                class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 535 px / 485 px")}}</span>
        </div>
        <div class="col-md-12">
            <label for="step_section_details_{{ $program->id ?? '' }}" class="zForm-label-alt">{{ __('Details') }} <span class="text-danger">*</span></label>
            <textarea class="step_section_details summernoteOne" name="step_section[details]" id="step_section_details_{{ $program->id ?? '' }}"
                      placeholder="{{__('Details')}}">{{ $program->step_section['details'] ?? '' }}</textarea>
        </div>
    </div>
</div>
