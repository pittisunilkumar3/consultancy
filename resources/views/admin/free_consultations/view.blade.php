<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Lead Details') }}</h2>
    <div class="mClose">
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>
<form class="ajax reset" action="{{route('admin.free_consultations.change_status', encodeId($consultation->id))}}" method="post"
      data-handler="commonResponseForModal">
    @csrf
    <div class="row rg-20">
        <div class="col-md-6">
            <label for="inputFirstName" class="zForm-label-alt">{{__('First Name')}}
                </label>
            <input disabled type="text" value="{{$consultation->first_name ?? ''}}" class="pe-none form-control zForm-control-alt" name="first_name" id="inputFirstName"
                   placeholder="{{__('Enter First Name')}}"/>
        </div>
        <div class="col-md-6">
            <label for="inputLastName" class="zForm-label-alt">{{__('Last Name')}}</label>
            <input disabled type="text" value="{{$consultation->last_name ?? ''}}" class="pe-none form-control zForm-control-alt" name="last_name" id="inputLastName"
                   placeholder="{{__('Enter Last Name')}}"/>
        </div>
        <div class="col-md-6">
            <label for="inputMobileNumber" class="zForm-label-alt">{{__('Mobile Number')}}
                </label>
            <input disabled type="number"  value="{{$consultation->mobile ?? ''}}" class="pe-none form-control zForm-control-alt" name="mobile" id="inputMobileNumber"
                   placeholder="{{__('Enter Mobile Number')}}"/>
        </div>
        <div class="col-md-6">
            <label for="inputEmailAddress" class="zForm-label-alt">{{__('Email Address')}}
                </label>
            <input disabled type="email" value="{{$consultation->email ?? ''}}" class="pe-none form-control zForm-control-alt" name="email" id="inputEmailAddress"
                   placeholder="{{__('Enter Email Address')}}"/>
        </div>
        <div class="col-md-12">
            <label for="inputPreferredStudyDestination" class="zForm-label-alt">{{ __('Preferred Study Destination') }}</label>
            <input disabled type="text" value="{{ $consultation->country_names ?? '' }}" class="pe-none form-control zForm-control-alt" name="country_names" id="inputPreferredStudyDestination" placeholder="{{ __('Preferred Study Destination') }}" />
        </div>
        <div class="col-md-4">
            <label for="inputChooseMethodOfCounseling"
                   class="zForm-label-alt">{{__('Choose Method of Counseling')}}</label>
            <input disabled type="email" value="{{getConsultationType($consultation->consultation_type) ?? ''}}" class="pe-none form-control zForm-control-alt" name="email" id="inputChooseMethodOfCounseling"
                   placeholder="{{__('Enter Email Address')}}"/>
        </div>
        <div class="col-md-4">
            <label for="inputStudy Level" class="zForm-label-alt">{{__('Study Level')}}
                </label>
            <input disabled type="email" value="{{$consultation->study_level->name ?? ''}}" class="pe-none form-control zForm-control-alt" name="email" id="inputStudy Level"/>
        </div>
        <div class="col-md-4">
            <label for="inputEducationFundType" class="zForm-label-alt">{{__('Education Fund Type')}}
                </label>
            <input disabled type="email" value="{{getFundType($consultation->fund_type) ?? ''}}" class="pe-none form-control zForm-control-alt" name="email" id="inputEducationFundType"
                   placeholder="{{__('Enter Email Address')}}"/>
        </div>
        <div class="col-lg-6">
            <label for="status" class="zForm-label-alt">{{__('Status')}}
                <span>*</span></label>
            <select class="sf-select-checkbox" id="status" name="status">
                <option {{$consultation->status == WORKING_STATUS_PENDING ? 'selected' : ''}} value="{{WORKING_STATUS_PENDING}}">{{__('Pending')}}</option>
                <option {{$consultation->status == WORKING_STATUS_PROCESSING ? 'selected' : ''}} value="{{WORKING_STATUS_PROCESSING}}">{{__('Processing')}}</option>
                <option {{$consultation->status == WORKING_STATUS_CANCELED ? 'selected' : ''}} value="{{WORKING_STATUS_CANCELED}}">{{__('Cancelled')}}</option>
                <option {{$consultation->status == WORKING_STATUS_SUCCEED ? 'selected' : ''}} value="{{WORKING_STATUS_SUCCEED}}">{{__(' Converted To Customer')}}</option>
            </select>
        </div>
        <div class="col-lg-6">
            <label for="assignBy" class="zForm-label-alt">{{__('Assign To')}}
                <span>*</span></label>
            <select class="sf-select-checkbox" id="assignBy" name="assign_by">
                <option value="0">{{__('Select Staff')}}</option>
                @foreach($staffList as $data)
                    <option value="{{ $data->id }}">{{ $data->name  }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-12">
            <label for="status" class="zForm-label-alt">{{__('Narration')}}</label>
            <textarea name="narration" class="form-control zForm-control-alt">{!! $consultation->narration !!}</textarea>
        </div>
        <div class="col-lg-12">
            <button type="submit" class="flipBtn sf-flipBtn-primary flex-shrink-0">
                <span>{{__('Update Status')}}</span>
                <span>{{__('Update Status')}}</span>
                <span>{{__('Update Status')}}</span>
            </button>
        <div>
    </div>
</form>
