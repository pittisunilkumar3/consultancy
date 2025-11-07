<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Enroll to course') }}</h2>
    <div class="mClose">
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>
<form class="ajax reset" action="{{ route('admin.courses.enroll_user', encodeId($course->id)) }}" method="post" data-handler="commonResponseForModal">
    @csrf
    <div class="row rg-20 mb-20">
        <div class="col-md-12">
            <label for="course_id" class="zForm-label-alt">{{ __('Course') }}
                <span class="text-danger">*</span>
            </label>
            <input readonly type="text" id="course_id"
                   placeholder="{{ __('Type Course') }}" class="form-control zForm-control-alt"
                   value="{{$course->title}}">
        </div>
        <div class="col-md-12">
            <label for="student" class="zForm-label-alt">{{ __('Student') }} <span class="text-danger">*</span></label>
            <select class="sf-select-without-search" id="student" name="student">
                @foreach($students as $student)
                    <option value="{{ $student->id }}">{{ $student->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="bd-c-stroke-2 justify-content-between align-items-center text-end pt-15">
        <button type="submit" class="flipBtn sf-flipBtn-primary flex-shrink-0">
            <div class="spinner-border w-25 h-25 ml-10 d-none" role="status">
                <span class="visually-hidden"></span>
            </div>
            <div class="innerWrap">
                <span>{{__('Enroll Now')}}</span>
                <span>{{__('Enroll Now')}}</span>
                <span>{{__('Enroll Now')}}</span>
            </div>
        </button>
    </div>
</form>
