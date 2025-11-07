<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-black">{{ __('Update Subjects') }}</h2>
    <div class="mClose">
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent text-para-text fs-13"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>
<form class="ajax reset" action="{{ route('admin.subjects.store',$subjectData->id) }}" method="post"
      data-handler="settingCommonHandler">
    @csrf
    <input type="hidden" value="{{ $subjectData->id }}" name="id">
    <div class="row rg-20">
        <div class="col-6">
            <label for="name" class="zForm-label-alt">{{ __('Name') }} <span
                    class="text-danger">*</span></label>
            <input type="text" name="name" id="name" placeholder="{{ __('Type Subject Name') }}" value="{{$subjectData->name}}"
                   class="form-control zForm-control-alt">
        </div>
        <div class="col-6">
            <label for="universityId" class="zForm-label-alt">{{ __('University') }}
                <span class="text-danger">*</span></label>
            <select class="sf-select-checkbox-search" id="universityId" name="university_id">
                @foreach($university as $data)
                    <option {{ $subjectData->university_id  == $data->id ? 'selected' : '' }} value="{{$data->id}}">{{ $data->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6">
            <label for="countryId" class="zForm-label-alt">{{ __('Country') }}
                <span class="text-danger">*</span></label>
            <select class="sf-select-checkbox-search" id="countryId" name="country_id">
                @foreach($country as $data)
                    <option {{ $subjectData->country_id  == $data->id ? 'selected' : '' }} value="{{$data->id}}">{{ $data->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6">
            <label for="subjectCategoryId" class="zForm-label-alt">{{ __('Subject Category') }}
                <span class="text-danger">*</span></label>
            <select class="sf-select-checkbox-search" id="subjectCategoryId" name="subject_category_id">
                @foreach($subjectCategory as $data)
                    <option {{ $subjectData->subject_category_id  == $data->id ? 'selected' : '' }} value="{{$data->id}}">{{ $data->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6">
            <label for="studyLevelId" class="zForm-label-alt">{{ __('Study level') }}
                <span class="text-danger">*</span></label>
            <select class="sf-select-checkbox-search" id="studyLevelId" name="study_level_id">
                @foreach($studyLevel as $data)
                    <option {{ $subjectData->study_level_id  == $data->id ? 'selected' : '' }} value="{{$data->id}}">{{ $data->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6">
            <label for="status" class="zForm-label-alt">{{ __('Status') }}
                <span class="text-danger">*</span></label>
            <select class="sf-select-without-search" id="status" name="status">
                <option {{ $subjectData->status  == STATUS_ACTIVE ? 'selected' : '' }} value="{{STATUS_ACTIVE}}">{{ __('Active') }}</option>
                <option {{ $subjectData->status  == STATUS_DEACTIVATE ? 'selected' : '' }} value="{{STATUS_DEACTIVATE}}">{{ __('Deactivate') }}</option>
            </select>
        </div>
        <div class="col-6">
            <label for="requirementScore" class="zForm-label-alt">{{ __('Requirement Program') }} <span
                    class="text-danger">*</span> </label>
            <input type="text" name="requirement_program" id="requirementProgram" value="{{$subjectData->requirement_program}}"
                   placeholder="{{__('Type Requirement Program')}}"
                   class="form-control zForm-control-alt">
        </div>
        <div class="col-6">
            <label for="requirementScore" class="zForm-label-alt">{{ __('Requirement Score') }} <span
                    class="text-danger">*</span> </label>
            <input type="text" name="requirement_score" id="requirementScore" value="{{$subjectData->requirement_score}}"
                   placeholder="{{__('Type Requirement Score')}}"
                   class="form-control zForm-control-alt">
        </div>
        <div class="col-6">
            <label for="intakeTime" class="zForm-label-alt">{{ __('Intake Time') }} <span class="text-danger">*</span> </label>
            <input type="date" name="intake_time" id="intakeTime" value="{{$subjectData->intake_time}}"
                   class="form-control zForm-control-alt">
        </div>
        <div class="col-6">
            <label for="duration" class="zForm-label-alt">{{ __('Duration') }} <span class="text-danger">*</span> </label>
            <input type="text" name="duration" id="duration" placeholder="{{__('Type Duration')}}" value="{{$subjectData->duration}}"
                   class="form-control zForm-control-alt">
        </div>
        <div class="col-6">
            <label for="TotalFeesAmount" class="zForm-label-alt">{{ __('Total Fees / Amount') }} <span class="text-danger">*</span> </label>
            <input type="number" name="amount" id="TotalFeesAmount" placeholder="{{__('Type Total Fees / Amount')}}" value="{{$subjectData->amount}}"
                   class="form-control zForm-control-alt">
        </div>
        <div class="">
            <label for="details" class="zForm-label-alt">{{ __('Details') }} <span
                    class="text-danger">*</span></label>
            <textarea class="summernoteOne" name="details" id="details"
                      placeholder="{{__('Details')}}">{!! $subjectData->details !!}</textarea>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="primary-form-group">
                <div class="primary-form-group-wrap zImage-upload-details mw-100 mt-12">
                    <div class="zImage-inside">
                        <div class="d-flex pb-12"><img
                                src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt="" />
                        </div>
                        <p class="fs-15 fw-500 lh-16 text-1b1c17">{{ __('Drag & drop files here') }}
                        </p>
                    </div>
                    <label for="bannerImage" class="zForm-label-alt">{{ __('Banner Image') }} <span
                            class="text-mime-type">{{__('(jpeg,png,jpg,svg,webp)')}}</span> <span
                            class="text-danger">*</span></label>
                    <div class="upload-img-box">
                        <img src="{{ getFileUrl($subjectData->banner_image) }}" />
                        <input type="file" name="banner_image" id="bannerImage" accept="image/*"
                               onchange="previewFile(this)" />
                    </div>
                    <span
                        class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 880 px / 420 px")}}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="bd-c-stroke-2 justify-content-between align-items-center text-end pt-15">
        <button type="submit" class="sf-btn-primary flex-shrink-0">
            <span>{{__('Submit')}}</span>
        </button>
    </div>
</form>
