<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Update Status') }}</h2>
    <div class="mClose">
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>

<form class="ajax reset" action="{{ route('admin.students.status_change', encodeId($user->id)) }}" method="post"
      data-handler="commonResponseForModal">
    @csrf
    <div class="row rg-20 mb-20">
        <div class="col-md-12">
            <label for="name" class="zForm-label-alt">{{ __('Name') }} <span class="text-danger">*</span></label>
            <input type="text" id="name" readonly value="{{ $user->name }}" class="form-control zForm-control-alt pe-none">
        </div>
        <div class="col-md-12">
            <label for="email" class="zForm-label-alt">{{ __('Email') }} <span class="text-danger">*</span></label>
            <input type="text" id="email" readonly value="{{ $user->email }}" class="form-control zForm-control-alt pe-none">
        </div>

        <div class="col-md-12">
            <label for="meeting-status" class="zForm-label-alt">{{ __('Status') }} <span class="text-danger">*</span></label>
            <select class="sf-select-without-search" id="meeting-status" name="status">
                <option value="{{ STATUS_ACTIVE }}" {{ $user->status == STATUS_ACTIVE ? 'selected' : '' }}>{{ __('Active') }}</option>
                <option value="{{ STATUS_DEACTIVATE }}" {{ $user->status == STATUS_DEACTIVATE ? 'selected' : '' }}>{{ __('Deactivate') }}</option>
                <option value="{{ STATUS_SUSPENDED }}" {{ $user->status == STATUS_SUSPENDED ? 'selected' : '' }}>{{ __('Suspend') }}</option>
            </select>
        </div>
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
