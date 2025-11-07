<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Consultation Feedback') }}</h2>
    <div class="mClose">
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>
<form class="ajax reset" action="{{ route('student.consultation-appointment.review-store') }}" method="post"
      data-handler="commonResponseWithPageLoad">
    @csrf
    <input type="hidden" value="{{ $appointment->id }}" name="appointment_id">
    <div class="row rg-20">
        <div class="">
            <label for="comment" class="zForm-label-alt">{{ __('Feedback') }} <span
                    class="text-danger">*</span></label>
            <textarea class="zForm-control zForm-control-alt" name="comment" id="comment" rows="4" cols="50"
                      placeholder="{{ __('Type consultation feedback') }}"></textarea>
        </div>
    </div>
    <div class="bd-c-stroke-2 justify-content-between align-items-center text-end pt-15">
        <button type="submit" class="sf-btn-primary flex-shrink-0">{{__('Submit')}}</button>
    </div>
</form>
