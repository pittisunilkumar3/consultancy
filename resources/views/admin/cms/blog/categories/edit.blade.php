<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-black">{{ __('Update Blog Category') }}</h2>
    <div class="mClose">
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent text-para-text fs-13"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>
<form class="ajax reset" action="{{ route('admin.cms-settings.blogs.categories.store', $blogCategoriesData->id) }}" method="post"
      data-handler="settingCommonHandler">
    @csrf
    <input type="hidden" value="{{$blogCategoriesData->id}}" name="id">
    <div class="row rg-20">
        <div class="">
            <label for="name" class="zForm-label-alt">{{ __('Category') }} <span
                    class="text-danger">*</span></label>
            <input type="text" name="name" id="name" placeholder="{{ __('Type Blog Category') }}" value="{{$blogCategoriesData->name}}"
                   class="form-control zForm-control-alt">
        </div>
        <div class="">
            <label for="status" class="zForm-label-alt">{{ __('Status') }}
                <span class="text-danger">*</span></label>
            <select class="sf-select-without-search" id="status" name="status">
                <option {{ $blogCategoriesData->status == STATUS_ACTIVE ? 'selected' : '' }} value="{{STATUS_ACTIVE}}">{{ __('Active') }}</option>
                <option {{ $blogCategoriesData->status == STATUS_DEACTIVATE ? 'selected' : '' }} value="{{STATUS_DEACTIVATE}}">{{ __('Deactivate') }}</option>
            </select>
        </div>
    </div>
    <div class="bd-c-stroke-2 justify-content-between align-items-center text-end pt-15">
        <button type="submit" class="flipBtn sf-flipBtn-primary flex-shrink-0">
            <span>{{__('Submit')}}</span>
            <span>{{__('Submit')}}</span>
            <span>{{__('Submit')}}</span>
        </button>
    </div>
</form>
