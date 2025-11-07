<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-400 lh-22 text-title-text">{{__('Add in person meeting Details')}}</h2>
    <div class="mClose">
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>
<form data-handler="commonResponseForModal"
      action="{{route('admin.consultations.meeting_platforms.update',[$platform->id])}}" method="post"
      class="ajax">
    @csrf
    <div class="row rg-20 mb-20">
        <div class="col-md-12">
            <label for="address" class="zForm-label-alt">{{ __('Meeting Address') }} <span
                    class="text-danger">*</span></label>
            <textarea class="zForm-control" name="address" id="address"
                      placeholder="{{ __('Meeting Address') }}">{!! $platform->address !!}</textarea>
        </div>
    </div>
    <div>
        <button type="submit" class="flipBtn sf-flipBtn-primary">
            <span>{{__('Update Now')}}</span>
            <span>{{__('Update Now')}}</span>
            <span>{{__('Update Now')}}</span>
        </button>
    </div>
</form>
