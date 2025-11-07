<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-black">{{ __('Update Scholarship') }}</h2>
    <div class="mClose">
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent text-para-text fs-13"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>
<form class="ajax reset" action="{{ route('admin.cms-settings.scholarships.store',$scholarshipData->id) }}" method="post"
      data-handler="settingCommonHandler">
    @csrf
    <input type="hidden" value="{{ $scholarshipData->id }}" name="id">
    <div class="row rg-20">
        <div class="col-md-6">
            <label for="title" class="zForm-label-alt">{{ __('Title') }} <span
                    class="text-danger">*</span></label>
            <input type="text" name="title" id="title" placeholder="{{ __('Type Scholarship Title') }}" value="{{$scholarshipData->title}}"
                   class="form-control zForm-control-alt">
        </div>
        <div class="col-md-6">
            <label for="universityId" class="zForm-label-alt">{{ __('University') }}
                <span class="text-danger">*</span></label>
            <select class="sf-select-checkbox-search" id="universityId" name="university_id">
                @foreach($university as $data)
                    <option {{ $scholarshipData->university_id == $data->id ? 'selected' : '' }} value="{{$data->id}}">{{ $data->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label for="countryId" class="zForm-label-alt">{{ __('Country') }}
                <span class="text-danger">*</span></label>
            <select class="sf-select-checkbox-search" id="countryId" name="country_id">
                @foreach($country as $data)
                    <option {{ $scholarshipData->country_id  == $data->id ? 'selected' : '' }} value="{{$data->id}}">{{ $data->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label for="subjectId" class="zForm-label-alt">{{ __('Subject') }}
                <span class="text-danger">*</span></label>
            <select class="sf-select-checkbox-search" id="subjectId" name="subject_id">
                @foreach($subject as $data)
                    <option {{ $scholarshipData->subject_id  == $data->id ? 'selected' : '' }} value="{{$data->id}}">{{ $data->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label for="studyLevelId" class="zForm-label-alt">{{ __('Study level') }}
                <span class="text-danger">*</span></label>
            <select class="sf-select-checkbox-search" id="studyLevelId" name="study_level_id">
                @foreach($studyLevel as $data)
                    <option {{ $scholarshipData->study_level_id  == $data->id ? 'selected' : '' }} value="{{$data->id}}">{{ $data->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label for="fundingType" class="zForm-label-alt">{{ __('Funding Type') }}
                <span class="text-danger">*</span></label>
            <select class="sf-select-without-search" id="fundingType" name="funding_type">
                <option {{ $scholarshipData->funding_type == SCHOLARSHIP_FUNDING_TYPE_PARTIAL ? 'selected' : '' }} value="{{SCHOLARSHIP_FUNDING_TYPE_PARTIAL}}">{{ __('Partial') }}</option>
                <option {{ $scholarshipData->funding_type == SCHOLARSHIP_FUNDING_TYPE_FULL_FUNDED ? 'selected' : '' }} value="{{SCHOLARSHIP_FUNDING_TYPE_FULL_FUNDED}}">{{ __('Full Funded') }}</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="applicationStartDate" class="zForm-label-alt">{{ __('Application Start Date') }} <span class="text-danger">*</span> </label>
            <input type="date" name="application_start_date" id="applicationStartDate"
                   value="{{ \Carbon\Carbon::parse($scholarshipData->application_start_date)->format('Y-m-d') }}"
                   class="form-control zForm-control-alt">

        </div>
        <div class="col-md-6">
            <label for="applicationEndDate" class="zForm-label-alt">{{ __('Application End Date') }} <span class="text-danger">*</span> </label>
            <input type="date" name="application_end_date" id="applicationEndDate" value="{{ \Carbon\Carbon::parse($scholarshipData->application_end_date)->format('Y-m-d') }}"
                   class="form-control zForm-control-alt">
        </div>
        <div class="col-md-6">
            <label for="offersReceivedFromDate" class="zForm-label-alt">{{ __('Offers Received From Date') }} <span class="text-danger">*</span> </label>
            <input type="date" name="offers_received_from_date" id="offersReceivedFromDate" value="{{ \Carbon\Carbon::parse($scholarshipData->offers_received_from_date)->format('Y-m-d') }}"
                   class="form-control zForm-control-alt">
        </div>
        <div class="col-md-6">
            <label for="status" class="zForm-label-alt">{{ __('Status') }}
                <span class="text-danger">*</span></label>
            <select class="sf-select-without-search" id="status" name="status">
                <option {{ $scholarshipData->status  == STATUS_ACTIVE ? 'selected' : '' }} value="{{STATUS_ACTIVE}}">{{ __('Active') }}</option>
                <option {{ $scholarshipData->status  == STATUS_DEACTIVATE ? 'selected' : '' }} value="{{STATUS_DEACTIVATE}}">{{ __('Deactivate') }}</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="availableAwardNumber" class="zForm-label-alt">{{ __('Available Award Number') }} <span class="text-danger">*</span> </label>
            <input type="number" name="available_award_number" id="availableAwardNumber" value="{{$scholarshipData->available_award_number}}"
                   class="form-control zForm-control-alt">
        </div>

        <div class="">
            <label for="details" class="zForm-label-alt">{{ __('Details') }} <span
                    class="text-danger">*</span></label>
            <textarea class="summernoteOne" name="details" id="details"
                      placeholder="{{__('Details')}}">{!! $scholarshipData->details !!}</textarea>
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
                        <img src="{{ getFileUrl($scholarshipData->banner_image) }}" />
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
        <button type="submit" class="flipBtn sf-flipBtn-primary flex-shrink-0">
            <span>{{__('Submit')}}</span>
            <span>{{__('Submit')}}</span>
            <span>{{__('Submit')}}</span>
        </button>
    </div>
</form>
