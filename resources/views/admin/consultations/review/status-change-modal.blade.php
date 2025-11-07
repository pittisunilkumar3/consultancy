<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Update Consulter review') }}</h2>
    <div class="mClose">
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>
<form class="ajax reset" action="{{ route(getPrefix().'.consultations.review.update-status') }}" method="post"
      data-handler="settingCommonHandler">
    @csrf
    <input type="hidden" value="{{ $consulterReview->id }}" name="review_id">
    <div class="row rg-20">
        <div class="">
            <label for="comment" class="zForm-label-alt">{{ __('Feedback') }} <span
                    class="text-danger">*</span></label>
            <textarea class="form-control zForm-control-alt" name="comment" id="comment" rows="4" cols="50" readonly
                      placeholder="{{ __('Type consultation feedback') }}">{{ $consulterReview->comment }}</textarea>
        </div>
        <div class="">
            <label for="status" class="zForm-label-alt">{{ __('Status') }}
                <span class="text-danger">*</span></label>
            <select class="sf-select-without-search" id="status" name="status">
                <option {{ $consulterReview->status == STATUS_ACTIVE ? 'selected' : '' }} value="{{STATUS_ACTIVE}}">{{ __('Active') }}</option>
                <option {{ $consulterReview->status == STATUS_PENDING ? 'selected' : '' }} value="{{STATUS_PENDING}}">{{ __('Pending') }}</option>
            </select>
        </div>
    </div>
    <div class="bd-c-stroke-2 justify-content-between align-items-center text-end pt-15">
        <button type="submit" class="sf-btn-primary flex-shrink-0">{{__('Submit')}}</button>
    </div>
</form>
