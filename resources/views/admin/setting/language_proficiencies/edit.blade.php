<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Update Certification & Degree') }}</h2>
    <div class="mClose">
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>
<form class="ajax reset" action="{{ route('admin.setting.language_proficiencies.store') }}" method="post"
      data-handler="commonResponseForModal">
    @csrf
    <input type="hidden" name="id" value="{{ encodeId($languageProficiency->id) }}">
    <div class="row rg-20">
        <div class="col-12">
            <label for="addTitle" class="zForm-label-alt">{{ __('Title') }}</label>
            <input type="text" name="title" value="{{$languageProficiency->title}}" class="form-control zForm-control-alt" id="addTitle"
                   placeholder="{{ __('Title') }}" />
        </div>
        <div class="col-md-12">
            <label for="addStatus" class="zForm-label-alt">{{ __('Status') }}</label>
            <select class="sf-select-two" id="addStatus" name="status">
                <option {{ $languageProficiency->status == STATUS_ACTIVE ? 'selected' : '' }} value="{{STATUS_ACTIVE}}">{{ __('Active') }}</option>
                <option {{ $languageProficiency->status == STATUS_DEACTIVATE ? 'selected' : '' }} value="{{STATUS_DEACTIVATE}}">{{ __('Deactivate') }}</option>
            </select>
        </div>
    </div>

    <div class="pt-20 mt-20 bd-t-one bd-c-stroke">
        <button type="submit" class="flipBtn sf-flipBtn-primary">
            <span>{{ __('Update') }}</span>
            <span>{{ __('Update') }}</span>
            <span>{{ __('Update') }}</span>
        </button>
    </div>
</form>
