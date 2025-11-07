@php
    // Check if the form is in edit mode
    $suffix = isset($event) ? '-edit' : '';
@endphp

<div class="col-md-6">
    <label for="title{{ $suffix }}" class="zForm-label-alt">{{ __('Title') }} <span class="text-danger">*</span></label>
    <input type="text" name="title" id="title{{ $suffix }}" placeholder="{{ __('Type Title') }}" value="{{ $event->title ?? '' }}" class="form-control zForm-control-alt">
</div>

<div class="col-md-6">
    <label for="type{{ $suffix }}" class="zForm-label-alt">{{ __('Type') }} <span class="text-danger">*</span></label>
    <select class="sf-select-without-search" id="type{{ $suffix }}" name="type">
        <option value="{{ EVENT_TYPE_PHYSICAL }}" {{ isset($event) && $event->type == EVENT_TYPE_PHYSICAL ? 'selected' : '' }}>{{ __('Physical') }}</option>
        <option value="{{ EVENT_TYPE_VIRTUAL }}" {{ isset($event) && $event->type == EVENT_TYPE_VIRTUAL ? 'selected' : '' }}>{{ __('Virtual') }}</option>
    </select>
</div>

<div class="col-md-6" id="locationField{{ $suffix }}" style="display: {{ isset($event) && $event->type == EVENT_TYPE_PHYSICAL ? 'block' : 'none' }};">
    <label for="location{{ $suffix }}" class="zForm-label-alt">{{ __('Location') }} <span class="text-danger">*</span></label>
    <input type="text" name="location" id="location{{ $suffix }}" placeholder="{{ __('Location') }}" value="{{ $event->location ?? '' }}" class="form-control zForm-control-alt">
</div>

<div class="col-md-6" id="linkField{{ $suffix }}" style="display: {{ isset($event) && $event->type == EVENT_TYPE_VIRTUAL ? 'block' : 'none' }};">
    <label for="link{{ $suffix }}" class="zForm-label-alt">{{ __('Meeting Link') }}</label>
    <input type="text" name="link" id="link{{ $suffix }}" placeholder="{{ __('Meeting Link') }}" value="{{ $event->link ?? '' }}" class="form-control zForm-control-alt">
</div>

<div class="col-md-6">
    <label for="date_time{{ $suffix }}" class="zForm-label-alt">{{ __('Date & Time') }} <span class="text-danger">*</span></label>
    <input type="datetime-local" name="date_time" id="date_time{{ $suffix }}" placeholder="{{ __('Event Date & Time') }}" value="{{ $event->date_time ?? '' }}" class="form-control zForm-control-alt">
</div>

<div class="col-md-6">
    <label for="price{{ $suffix }}" class="zForm-label-alt">{{ __('Price') }}</label>
    <input type="number" name="price" id="price{{ $suffix }}" placeholder="{{ __('Price') }}" value="{{ $event->price ?? '' }}" class="form-control zForm-control-alt">
    <small class="fs-12 fst-italic">{{ __('Write 0 price if it’s free') }}</small>
</div>

<div class="col-md-6">
    <label for="status{{ $suffix }}" class="zForm-label-alt">{{ __('Status') }} <span class="text-danger">*</span></label>
    <select class="sf-select-without-search" id="status{{ $suffix }}" name="status">
        <option value="{{ STATUS_ACTIVE }}" {{ isset($event) && $event->status == STATUS_ACTIVE ? 'selected' : '' }}>{{ __('Active') }}</option>
        <option value="{{ STATUS_DEACTIVATE }}" {{ isset($event) && $event->status == STATUS_DEACTIVATE ? 'selected' : '' }}>{{ __('Deactivate') }}</option>
    </select>
</div>

<div class="col-md-6">
    <label for="study_levels{{ $suffix }}[]" class="zForm-label-alt">{{ __('Study Level') }}</label>
    <select class="sf-select-checkbox-search" id="study_levels{{ $suffix }}[]" multiple="multiple" name="study_levels[]">
        @foreach($studyLevels as $studyLevel)
            <option value="{{ $studyLevel->id }}" {{ isset($event) && in_array($studyLevel->id, $event->study_levels ?? []) ? 'selected' : '' }}>
                {{ $studyLevel->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="col-md-6">
    <label for="country_ids{{ $suffix }}[]" class="zForm-label-alt">{{ __('Country') }}</label>
    <select class="sf-select-checkbox-search" id="country_ids{{ $suffix }}" multiple="multiple" name="country_ids[]">
        @foreach($countries as $country)
            <option value="{{ $country->id }}" {{ isset($event) && in_array($country->id, $event->country_ids ?? []) ? 'selected' : '' }}>
                {{ $country->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="col-md-6">
    <label for="university_ids{{ $suffix }}[]" class="zForm-label-alt">{{ __('University') }}</label>
    <select class="sf-select-checkbox-search" id="university_ids{{ $suffix }}" multiple="multiple" name="university_ids[]">
        @foreach($universities as $university)
            <option value="{{ $university->id }}" {{ isset($event) && in_array($university->id, $event->university_ids ?? []) ? 'selected' : '' }}>
                {{ $university->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="col-md-6">
    <label for="mAttachment-event{{ $suffix }}" class="zForm-label-alt">{{ __('Image') }} <span class="text-danger">*</span>
        @if(isset($event) && $event->image)
            <small class="preview-image-div">
                <a href="{{ getFileUrl($event->image) }}" target="_blank"><i class="fa-solid fa-eye"></i></a>
            </small>
        @endif
    </label>
    <div class="file-upload-one">
        <label for="mAttachment-event{{ $suffix }}">
            <p class="fileName fs-14 fw-50 lh-24 text-para-text">{{ __('Choose Image to upload') }}</p>
            <p class="fs-15 fw-700 lh-28 text-white">{{ __('Browse File') }}</p>
        </label>
        <input type="file" name="image" id="mAttachment-event{{ $suffix }}" class="fileUploadInput invisible position-absolute">
    </div>
    <span
        class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 880 px / 420 px")}}</span>
</div>

<div class="col-md-12">
    <label for="description{{ $suffix }}" class="zForm-label-alt">{{ __('Description') }} <span class="text-danger">*</span></label>
    <textarea class="summernoteOne" name="description" id="description{{ $suffix }}" placeholder="{{ __('Description') }}">{{ $event->description ?? '' }}</textarea>
</div>
