<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-black">{{ __('Update Course Module') }}</h2>
    <div class="mClose">
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent text-para-text fs-13"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>
<form class="ajax reset" action="{{ route('admin.courses.lessons.store', $courseLesson->course_id) }}"
      method="post"
      data-handler="commonResponseRedirect" data-redirect-url="{{route('admin.courses.lessons.index', encodeId($courseLesson->course_id))}}">
    @csrf
    <input type="hidden" name="id" value="{{$courseLesson->id}}">
    <div class="row rg-20">
        <div class="">
            <label for="title" class="zForm-label-alt">{{ __('Title') }} <span
                    class="text-danger">*</span></label>
            <input type="text" value="{{$courseLesson->title}}" name="title" id="title" placeholder="{{ __('Type Title') }}"
                   class="form-control zForm-control-alt">
        </div>
        <div class="">
            <label for="description" class="zForm-label-alt">{{ __('Description') }} <span
                    class="text-danger">*</span></label>
            <textarea name="description" id="description" placeholder="{{ __('Type Description') }}"
                      class="form-control zForm-control-alt">{!! $courseLesson->description !!}</textarea>
        </div>
    </div>
    <div class="bd-c-stroke-2 justify-content-between align-items-center text-end pt-15">
        <button type="submit" class="sf-btn-primary flex-shrink-0">{{__('Update')}}</button>
    </div>
</form>
