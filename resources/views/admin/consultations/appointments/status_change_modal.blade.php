<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Update Appointment Status') }}</h2>
    <div class="mClose">
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>

<div class="alert bg-tertiary mb-25">
    <p>{{ __('Set status to "Processing" to create a meeting link for the selected virtual meeting platform.') }}</p>
</div>

<form class="ajax reset" action="{{ route(getPrefix().'.consultations.appointments.status_change', $appointment->id) }}" method="post"
      data-handler="commonResponseForModal">
    @csrf
    <div class="row rg-20 mb-20">
        <div class="col-md-12">
            <label for="appointment_id" class="zForm-label-alt">{{ __('Appointment ID') }} <span class="text-danger">*</span></label>
            <input type="text" name="title" id="appointment_id" readonly value="{{ $appointment->appointment_ID }}" class="form-control zForm-control-alt">
        </div>

        <div class="col-md-12">
            <label for="meeting-status" class="zForm-label-alt">{{ __('Status') }} <span class="text-danger">*</span></label>
            <select class="sf-select-without-search" id="meeting-status" name="status">
                <option value="{{ STATUS_PENDING }}" {{ $appointment->status == STATUS_PENDING ? 'selected' : '' }}>{{ __('Pending') }}</option>
                <option value="{{ STATUS_PROCESSING }}" {{ $appointment->status == STATUS_PROCESSING ? 'selected' : '' }}>{{ __('Processing') }}</option>
                <option value="{{ STATUS_ACTIVE }}" {{ $appointment->status == STATUS_ACTIVE ? 'selected' : '' }}>{{ __('Completed') }}</option>
                <option value="{{ STATUS_REFUNDED }}" {{ $appointment->status == STATUS_REFUNDED ? 'selected' : '' }}>{{ __('Refunded') }}</option>
            </select>
        </div>

        @if ($appointment->consultation_type == CONSULTATION_TYPE_VIRTUAL)
            <!-- Show Meeting Platform Selection for Virtual Consultations -->
            <div class="col-md-12" id="meetingPlatformSection" style="display: {{ $appointment->status == STATUS_PROCESSING ? 'block' : 'none' }}">
                <label for="meeting_platform" class="zForm-label-alt">{{ __('Meeting Platform') }} <span class="text-danger">*</span></label>
                <select class="sf-select-without-search" id="meeting_platform" name="meeting_platform">
                    @foreach ($meetingPlatforms as $platform)
                        @if ($platform->type !== MEETING_PLATFORMS_PERSON)
                            <option value="{{ $platform->id }}" {{ $appointment->meeting_platform == $platform->id ? 'selected' : '' }}>{{ $platform->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        @else
            <!-- Hidden Input for Physical Consultations, defaulting to In Person's ID -->
            <input type="hidden" name="meeting_platform" value="{{ $meetingPlatforms->firstWhere('type', MEETING_PLATFORMS_PERSON)->id }}">
        @endif
    </div>
    <div class="bd-c-stroke-2 justify-content-between align-items-center text-end pt-15">
        <button type="submit" class="flipBtn sf-flipBtn-primary flex-shrink-0">
            <div class="spinner-border w-25 h-25 ml-10 d-none" role="status">
                <span class="visually-hidden"></span>
            </div>
            <div class="innerWrap">
                <span>{{__('Update')}}</span>
                <span>{{__('Update')}}</span>
                <span>{{__('Update')}}</span>
            </div>
        </button>
    </div>
</form>
