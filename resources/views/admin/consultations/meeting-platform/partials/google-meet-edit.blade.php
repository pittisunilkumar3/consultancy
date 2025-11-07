<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-400 lh-22 text-title-text">{{ __('Add Google Meet Credentials') }}</h2>
    <div class="mClose">
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>

<form action="{{ route('admin.consultations.meeting_platforms.update', [$platform->id]) }}" method="post"
      data-handler="commonResponseForModal">
    @csrf
    <input type="hidden" name="type" value="{{ MEETING_PLATFORMS_MEET }}">
    <div class="row rg-20 mb-20">
        <!-- Callback URL -->
        <div class="col-12 mb-3">
            <label class="zForm-label-alt">{{ __('Set the callback URL in your app') }}</label>
            <div class="align-items-lg-center align-items-start bd-ra-8 bg-body-secondary cg-35 d-flex flex-column flex-sm-row p-10 rg-10">
                <p class="fs-14 fw-500 lh-24 text-textBlack text-break script-container">
                    {{ route('admin.consultations.meeting_platforms.google_meet_callback') }}
                </p>
                <button type="button" class="copy-button border-0 p-0 bg-transparent flex-shrink-0">
                    <img src="{{ asset('booking/admin/images/icon/clipboard-copy.svg') }}" alt=""/>
                </button>
            </div>
        </div>

        <!-- Google Meet Client ID -->
        <div class="col-md-6">
            <label for="googleMeetingClientID" class="zForm-label-alt">{{ __('Google Meet Client ID') }} <span class="text-danger">*</span></label>
            <input required type="text" name="client_id" id="googleMeetingClientID" class="form-control zForm-control-alt"
                   placeholder="{{ __('Enter Google Meet client ID') }}" value="{{ $platform->key ?? '' }}">
        </div>

        <!-- Google Meet Client Secret -->
        <div class="col-md-6">
            <label for="googleMeetingSecret" class="zForm-label-alt">{{ __('Google Meet Client Secret') }} <span class="text-danger">*</span></label>
            <input required type="text" name="client_secret" id="googleMeetingSecret" class="form-control zForm-control-alt"
                   placeholder="{{ __('Enter Google Meet client secret') }}" value="{{ $platform->secret ?? '' }}">
        </div>

        <!-- Calendar ID -->
        <div class="col-md-6">
            <label for="googleMeetingClientSecret" class="zForm-label-alt">{{ __('Calendar ID') }} <span class="text-danger">*</span></label>
            <input required type="text" name="calender_id" id="googleMeetingClientSecret" class="form-control zForm-control-alt"
                   placeholder="{{ __('Enter Calendar ID') }}" value="{{ $platform->calender_id ?? '' }}">
        </div>

        <!-- Timezone -->
        <div class="col-md-6">
            <label for="googleMeetingTimezone" class="zForm-label-alt">{{ __('Timezone') }}</label>
            <select required name="timezone" class="sf-select-checkbox-search">
                @foreach (getTimeZone() as $timezone)
                    <option value="{{ $timezone }}" {{ $timezone == $platform->timezone ? 'selected' : '' }}>
                        {{ $timezone }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Google Meet Status -->
        <div class="col-md-6">
            <label for="googleMeetingStatus" class="zForm-label-alt">{{ __('Google Meet Status') }}</label>
            <select required name="status" class="sf-select-checkbox">
                <option value="{{ STATUS_ACTIVE }}" {{ $platform->status == STATUS_ACTIVE ? 'selected' : '' }}>{{ __('Enable') }}</option>
                <option value="{{ STATUS_DISABLE }}" {{ $platform->status == STATUS_DISABLE ? 'selected' : '' }}>{{ __('Disable') }}</option>
            </select>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="d-flex g-12 flex-wrap mt-25">
        <button type="submit" class="flipBtn sf-flipBtn-primary flex-shrink-0">
            <span>{{__('Update Now')}}</span>
            <span>{{__('Update Now')}}</span>
            <span>{{__('Update Now')}}</span>
        </button>
    </div>
</form>
