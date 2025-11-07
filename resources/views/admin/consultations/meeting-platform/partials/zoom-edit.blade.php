<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-400 lh-22 text-title-text">{{ __('Add Zoom Meeting Credentials') }}</h2>
    <div class="mClose">
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>

<form class="ajax reset" action="{{ route('admin.consultations.meeting_platforms.update', [$platform->id]) }}" method="post"
      data-handler="commonResponseForModal">
    @csrf
    <div class="row rg-20 mb-20">
        <!-- Zoom Account ID -->
        <div class="col-md-6">
            <label for="zoomAccountID" class="zForm-label-alt">{{ __('Zoom Account ID') }} <span class="text-danger">*</span></label>
            <input required type="text" id="zoomAccountID" name="account_id" class="form-control zForm-control-alt"
                   placeholder="{{ __('Enter zoom account ID') }}" value="{{ $platform->account_id }}">
        </div>

        <!-- Zoom Client ID -->
        <div class="col-md-6">
            <label for="zoomClientID" class="zForm-label-alt">{{ __('Zoom Client ID') }} <span class="text-danger">*</span></label>
            <input required type="text" id="zoomClientID" name="client_id" class="form-control zForm-control-alt"
                   placeholder="{{ __('Enter zoom client ID') }}" value="{{ $platform->key }}">
        </div>

        <!-- Zoom Client Secret -->
        <div class="col-md-6">
            <label for="zoomClientSecret" class="zForm-label-alt">{{ __('Zoom Client Secret') }} <span class="text-danger">*</span></label>
            <input required type="text" id="zoomClientSecret" name="client_secret" class="form-control zForm-control-alt"
                   placeholder="{{ __('Enter zoom client secret') }}" value="{{ $platform->secret }}">
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

        <!-- Host Video -->
        <div class="col-md-6">
            <label for="googleMeetingHostVideo" class="zForm-label-alt">{{ __('Host Video') }}</label>
            <select required name="host_video" class="sf-select-checkbox">
                <option value="{{ STATUS_ACTIVE }}" {{ $platform->host_video == STATUS_ACTIVE ? 'selected' : '' }}>{{ __('Enable') }}</option>
                <option value="{{ STATUS_DISABLE }}" {{ $platform->host_video == STATUS_DISABLE ? 'selected' : '' }}>{{ __('Disable') }}</option>
            </select>
        </div>

        <!-- Participant Video -->
        <div class="col-md-6">
            <label for="googleMeetingParticipantVideo" class="zForm-label-alt">{{ __('Participant Video') }}</label>
            <select required name="participant_video" class="sf-select-checkbox">
                <option value="{{ STATUS_ACTIVE }}" {{ $platform->participant_video == STATUS_ACTIVE ? 'selected' : '' }}>{{ __('Enable') }}</option>
                <option value="{{ STATUS_DISABLE }}" {{ $platform->participant_video == STATUS_DISABLE ? 'selected' : '' }}>{{ __('Disable') }}</option>
            </select>
        </div>

        <!-- Waiting Room -->
        <div class="col-md-6">
            <label for="googleMeetingWaitingRoom" class="zForm-label-alt">{{ __('Waiting Room') }}</label>
            <select required name="waiting_room" class="sf-select-checkbox">
                <option value="{{ STATUS_ACTIVE }}" {{ $platform->waiting_room == STATUS_ACTIVE ? 'selected' : '' }}>{{ __('Enable') }}</option>
                <option value="{{ STATUS_DISABLE }}" {{ $platform->waiting_room == STATUS_DISABLE ? 'selected' : '' }}>{{ __('Disable') }}</option>
            </select>
        </div>

        <!-- Status -->
        <div class="col-md-6">
            <label for="googleMeetingStatus" class="zForm-label-alt">{{ __('Status') }}</label>
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
