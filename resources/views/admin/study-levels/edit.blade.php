<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Update Study Level') }}</h2>
    <div class="mClose">
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>
<form class="ajax reset" action="{{ route('admin.study_levels.store') }}" method="post"
      data-handler="settingCommonHandler">
    @csrf
    @method('POST')
    <input type="hidden" name="id" value="{{ $studyLevel->id }}">
    <div class="row rg-20 mb-20">
        <div class="col-12">
            <label for="name-edit" class="zForm-label-alt">{{ __('Name') }}</label>
            <input type="text" name="name" value="{{$studyLevel->name}}" class="form-control zForm-control-alt"
                   id="name-edit"
                   placeholder="{{ __('Name') }}"/>
        </div>
        <div class="col-md-12">
            <label for="editStatus" class="zForm-label-alt">{{ __('Status') }}</label>
            <select class="sf-select-two" id="editStatus" name="status">
                <option
                    {{ $studyLevel->status == STATUS_ACTIVE ? 'selected' : '' }} value="{{STATUS_ACTIVE}}">{{ __('Active') }}</option>
                <option
                    {{ $studyLevel->status == STATUS_DEACTIVATE ? 'selected' : '' }} value="{{STATUS_DEACTIVATE}}">{{ __('Deactivate') }}</option>
            </select>
        </div>
    </div>

    <button type="submit" class="flipBtn sf-flipBtn-primary">
        <span>{{ __('Update') }}</span>
        <span>{{ __('Update') }}</span>
        <span>{{ __('Update') }}</span>
    </button>
</form>
