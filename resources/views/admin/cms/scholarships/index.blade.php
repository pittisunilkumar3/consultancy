@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center flex-wrap g-20">
        <h4 class="fs-18 fw-700 lh-24 text-title-text">{{$pageTitle}}</h4>
        <button data-bs-toggle="modal" data-bs-target="#add-modal" class="flipBtn sf-flipBtn-primary flex-shrink-0">
            + {{__('Add Scholarship')}}</button>
    </div>
    <div class="p-sm-30 p-15">
        <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
            <div class="">
                <table class="table zTable zTable-last-item-right pt-15" id="scholarshipDataTable">
                    <thead>
                    <tr>
                        <th>
                            <div>{{__('Image')}}</div>
                        </th>
                        <th>
                            <div>{{__('Title')}}</div>
                        </th>
                        <th>
                            <div>{{__('Country')}}</div>
                        </th>
                        <th>
                            <div>{{__('University')}}</div>
                        </th>
                        <th>
                            <div>{{__('Study Level')}}</div>
                        </th>
                        <th>
                            <div>{{__('Status')}}</div>
                        </th>
                        <th>
                            <div>{{__('Action')}}</div>
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- Page content area end -->
    <!-- Add Modal section start -->
    <div class="modal fade" id="add-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">
                <div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
                    <h2 class="fs-18 fw-600 lh-22 text-title-black">{{ __('Add Scholarship') }}</h2>
                    <div class="mClose">
                        <button type="button"
                                class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent text-para-text fs-13"
                                data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                </div>
                <form class="ajax reset" action="{{ route('admin.cms-settings.scholarships.store') }}" method="post"
                      data-handler="settingCommonHandler">
                    @csrf
                    <div class="row rg-20">
                        <div class="col-md-6">
                            <label for="title" class="zForm-label-alt">{{ __('Title') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" placeholder="{{ __('Type Scholarship Title') }}"
                                   class="form-control zForm-control-alt">
                        </div>
                        <div class="col-md-6">
                            <label for="universityId" class="zForm-label-alt">{{ __('University') }}
                                <span class="text-danger">*</span></label>
                            <select class="sf-select-checkbox-search" id="universityId" name="university_id">
                                @foreach($university as $data)
                                    <option value="{{$data->id}}">{{ $data->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="countryId" class="zForm-label-alt">{{ __('Country') }}
                                <span class="text-danger">*</span></label>
                            <select class="sf-select-checkbox-search" id="countryId" name="country_id">
                                @foreach($country as $data)
                                    <option value="{{$data->id}}">{{ $data->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="subjectId" class="zForm-label-alt">{{ __('Subject') }}
                                <span class="text-danger">*</span></label>
                            <select class="sf-select-checkbox-search" id="subjectId" name="subject_id">
                                @foreach($subject as $data)
                                    <option value="{{$data->id}}">{{ $data->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="studyLevelId" class="zForm-label-alt">{{ __('Study level') }}
                                <span class="text-danger">*</span></label>
                            <select class="sf-select-checkbox-search" id="studyLevelId" name="study_level_id">
                                @foreach($studyLevel as $data)
                                    <option value="{{$data->id}}">{{ $data->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="fundingType" class="zForm-label-alt">{{ __('Funding Type') }}
                                <span class="text-danger">*</span></label>
                            <select class="sf-select-without-search" id="fundingType" name="funding_type">
                                <option value="{{SCHOLARSHIP_FUNDING_TYPE_PARTIAL}}">{{ __('Partial') }}</option>
                                <option value="{{SCHOLARSHIP_FUNDING_TYPE_FULL_FUNDED}}">{{ __('Full Funded') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="applicationStartDate" class="zForm-label-alt">{{ __('Application Start Date') }} <span class="text-danger">*</span> </label>
                            <input type="date" name="application_start_date" id="applicationStartDate"
                                   class="form-control zForm-control-alt">
                        </div>
                        <div class="col-md-6">
                            <label for="applicationEndDate" class="zForm-label-alt">{{ __('Application End Date') }} <span class="text-danger">*</span> </label>
                            <input type="date" name="application_end_date" id="applicationEndDate"
                                   class="form-control zForm-control-alt">
                        </div>
                        <div class="col-md-6">
                            <label for="offersReceivedFromDate" class="zForm-label-alt">{{ __('Offers Received From Date') }} <span class="text-danger">*</span> </label>
                            <input type="date" name="offers_received_from_date" id="offersReceivedFromDate"
                                   class="form-control zForm-control-alt">
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="zForm-label-alt">{{ __('Status') }}
                                <span class="text-danger">*</span></label>
                            <select class="sf-select-without-search" id="status" name="status">
                                <option value="{{STATUS_ACTIVE}}">{{ __('Active') }}</option>
                                <option value="{{STATUS_DEACTIVATE}}">{{ __('Deactivate') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="availableAwardNumber" class="zForm-label-alt">{{ __('Available Award Number') }} <span class="text-danger">*</span> </label>
                            <input type="number" name="available_award_number" id="availableAwardNumber"
                                   class="form-control zForm-control-alt">
                        </div>

                        <div class="">
                            <label for="details" class="zForm-label-alt">{{ __('Details') }} <span
                                    class="text-danger">*</span></label>
                            <textarea class="summernoteOne" name="details" id="details"
                                      placeholder="{{__('Details')}}"></textarea>
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
                                        <img src="" />
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
                        <button type="submit" class="flipBtn sf-flipBtn-primary flex-shrink-0">{{__('Submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Modal section end -->
    <!-- Edit Modal section start -->
    <div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">

            </div>
        </div>
    </div>
    <!-- Edit Modal section end -->

    <input type="hidden" id="scholarship-route" value="{{ route('admin.cms-settings.scholarships.index') }}">
@endsection
@push('script')
    <script src="{{asset('admin/custom/js/cms/scholarship.js')}}"></script>
@endpush
