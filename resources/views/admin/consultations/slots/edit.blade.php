<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Update Consultation Slot') }}</h2>
    <div class="mClose">
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>
<form class="ajax reset" action="{{ route('admin.consultations.slots.store', $consultationSlot->id) }}" method="post"
      data-handler="commonResponseForModal">
    @csrf
    @method('POST')
    <input type="hidden" name="id" value="{{ $consultationSlot->id }}">
    <div class="row rg-20 mb-20">
        <div class="col-12">
            <label for="start_time" class="zForm-label-alt">{{ __('Start Time') }}</label>
            <input type="time" name="start_time" value="{{$consultationSlot->start_time}}" class="form-control zForm-control-alt" id="start_time"
                   placeholder="{{ __('Start Time') }}"/>
        </div>
        <div class="col-12">
            <label for="end_time" class="zForm-label-alt">{{ __('End Time') }}</label>
            <input type="time" name="end_time" value="{{$consultationSlot->end_time}}" class="form-control zForm-control-alt" id="end_time"
                   placeholder="{{ __('End Time') }}"/>
        </div>
        <div class="col-md-12">
            <label for="editStatus" class="zForm-label-alt">{{ __('Status') }}</label>
            <select class="sf-select-two" id="editStatus" name="status">
                <option
                    {{ $consultationSlot->status == STATUS_ACTIVE ? 'selected' : '' }} value="{{STATUS_ACTIVE}}">{{ __('Active') }}</option>
                <option
                    {{ $consultationSlot->status == STATUS_DEACTIVATE ? 'selected' : '' }} value="{{STATUS_DEACTIVATE}}">{{ __('Deactivate') }}</option>
            </select>
        </div>
    </div>

    <button type="submit" class="flipBtn sf-flipBtn-primary">
        <span>{{ __('Update') }}</span>
        <span>{{ __('Update') }}</span>
        <span>{{ __('Update') }}</span>
    </button>
</form>
