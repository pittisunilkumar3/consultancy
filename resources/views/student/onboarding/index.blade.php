@extends('layouts.app')
@push('title')
    {{ __($pageTitle) }}
@endpush
@section('content')
    <div class="p-sm-30 p-15">
        <div class="row rg-20">
            <div class="col-xl-3">
                <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                    <ul class="nav nav-pills zTab-reset zTab-four zList-three flex-xl-column" id="pills-tab"
                        role="tablist">
                        @if(getOnboardingField($formSetting, 'section_show_basic', 'show'))
                            <li class="nav-item" role="presentation">
                                <button
                                    class="nav-link d-flex justify-content-between align-items-center cg-10 w-100 active"
                                    id="pills-basicInformation-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-basicInformation" type="button" role="tab"
                                    aria-controls="pills-basicInformation" aria-selected="true">
                                    <span class="fs-16 fw-600 lh-22">{{ __('Basic Information') }}</span>
                                    <div class="d-flex"><i class="fa-solid fa-angle-right"></i></div>
                                </button>
                            </li>
                        @endif
                        @if(getOnboardingField($formSetting, 'section_show_academic', 'show'))
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex justify-content-between align-items-center cg-10 w-100"
                                        id="pills-academicInfo-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-academicInfo" type="button" role="tab"
                                        aria-controls="pills-academicInfo" aria-selected="false">
                                    <span class="fs-16 fw-600 lh-22">{{ __('Academic Info') }}</span>
                                    <div class="d-flex"><i class="fa-solid fa-angle-right"></i></div>
                                </button>
                            </li>
                        @endif
                        @if(getOnboardingField($formSetting, 'section_show_work_experience', 'show'))
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex justify-content-between align-items-center cg-10 w-100"
                                        id="pills-workExperience-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-workExperience" type="button" role="tab"
                                        aria-controls="pills-workExperience" aria-selected="false">
                                    <span class="fs-16 fw-600 lh-22">{{ __('Work Experience') }}</span>
                                    <div class="d-flex"><i class="fa-solid fa-angle-right"></i></div>
                                </button>
                            </li>
                        @endif
                        @if(getOnboardingField($formSetting, 'section_show_extra_curriculum_activity', 'show'))
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex justify-content-between align-items-center cg-10 w-100"
                                        id="pills-extraCurricularActivities-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-extraCurricularActivities" type="button" role="tab"
                                        aria-controls="pills-extraCurricularActivities" aria-selected="false">
                                    <span class="fs-16 fw-600 lh-22">{{ __('Extra Curricular Activities') }}</span>
                                    <div class="d-flex"><i class="fa-solid fa-angle-right"></i></div>
                                </button>
                            </li>
                        @endif
                        @if(getOnboardingField($formSetting, 'section_show_language_proficiency', 'show'))
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex justify-content-between align-items-center cg-10 w-100"
                                        id="pills-languageProficiency-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-languageProficiency" type="button" role="tab"
                                        aria-controls="pills-languageProficiency" aria-selected="false">
                                    <span class="fs-16 fw-600 lh-22">{{ __('Language Proficiency') }}</span>
                                    <div class="d-flex"><i class="fa-solid fa-angle-right"></i></div>
                                </button>
                            </li>
                        @endif
                        @if(getOnboardingField($formSetting, 'section_show_interested_area', 'show'))
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex justify-content-between align-items-center cg-10 w-100"
                                        id="pills-interestedArea-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-interestedArea" type="button" role="tab"
                                        aria-controls="pills-interestedArea" aria-selected="false">
                                    <span class="fs-16 fw-600 lh-22">{{ __('Interested Area') }}</span>
                                    <div class="d-flex"><i class="fa-solid fa-angle-right"></i></div>
                                </button>
                            </li>
                        @endif
                        @if(getOnboardingField($formSetting, 'section_show_custom_filed', 'show'))
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex justify-content-between align-items-center cg-10 w-100"
                                        id="pills-othersInformation-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-othersInformation" type="button" role="tab"
                                        aria-controls="pills-othersInformation" aria-selected="false">
                                    <span class="fs-16 fw-600 lh-22">{{ __('Others Information') }}</span>
                                    <div class="d-flex"><i class="fa-solid fa-angle-right"></i></div>
                                </button>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="col-xl-9">
                <div class="tab-content" id="pills-tabContent">
                    <!-- Check if the 'Basic Information' section should be shown -->
                    @if(getOnboardingField($formSetting, 'section_show_basic', 'show'))
                        <div class="tab-pane fade show active" id="pills-basicInformation" role="tabpanel"
                             aria-labelledby="pills-basicInformation-tab" tabindex="0">
                            <div class="">
                                <h4 class="mb-15 fs-18 fw-700 lh-24 text-title-text">{{ __('Basic Information') }}</h4>
                                <div
                                    class="position-relative p-lg-50 p-md-30 p-sm-25 p-15 bd-one bd-c-stroke bd-ra-5 bg-white mb-15">
                                    <button type="button" title="{{__('Edit')}}"
                                            onclick="getEditModal('{{ route(getOnboardingPrefix().'.onboarding.edit-modal', ['service_order_id' => encodeId($serviceOrder->id), 'section' => 'basic', 'id' => null]) }}', '#edit-modal')"
                                            class="position-absolute top-10 right-10 w-25 h-25 bd-one bd-c-stroke rounded-circle bg-white d-flex justify-content-center align-items-center">
                                        @include('partials.icons.edit')
                                    </button>
                                    <div class="row rg-20">

                                        <!-- First Name -->
                                        @if(getOnboardingField($formSetting, 'basic_first_name', 'show'))
                                            <div class="col-md-6">
                                                <label class="zForm-label-alt">{{ __('First Name') }}</label>
                                                <input type="text"
                                                       value="{{ $serviceOrder->student_basic_info->first_name ?? __('N/A') }}"
                                                       class="form-control zForm-control-alt pe-none" readonly/>
                                            </div>
                                        @endif

                                        <!-- Last Name -->
                                        @if(getOnboardingField($formSetting, 'basic_last_name', 'show'))
                                            <div class="col-md-6">
                                                <label class="zForm-label-alt">
                                                    {{ __('Last Name') }}
                                                </label>
                                                <input type="text"
                                                       value="{{ $serviceOrder->student_basic_info->last_name ?? __('N/A') }}"
                                                       class="form-control zForm-control-alt pe-none" readonly/>
                                            </div>
                                        @endif

                                        <!-- Email Address -->
                                        @if(getOnboardingField($formSetting, 'basic_email', 'show'))
                                            <div class="col-md-6">
                                                <label class="zForm-label-alt">
                                                    {{ __('Email Address') }}
                                                </label>
                                                <input type="text"
                                                       value="{{ $serviceOrder->student_basic_info->email ?? __('N/A') }}"
                                                       class="form-control zForm-control-alt pe-none" readonly/>
                                            </div>
                                        @endif

                                        <!-- Phone Number -->
                                        @if(getOnboardingField($formSetting, 'basic_phone', 'show'))
                                            <div class="col-md-6">
                                                <label class="zForm-label-alt">
                                                    {{ __('Phone Number') }}
                                                </label>
                                                <input type="text"
                                                       value="{{ $serviceOrder->student_basic_info->mobile ?? __('N/A') }}"
                                                       class="form-control zForm-control-alt pe-none" readonly/>
                                            </div>
                                        @endif

                                        <!-- Gender -->
                                        @if(getOnboardingField($formSetting, 'basic_gender', 'show'))
                                            <div class="col-md-6">
                                                <label class="zForm-label-alt">
                                                    {{ __('Gender') }}
                                                </label>
                                                <input type="text"
                                                       value="{{ $serviceOrder->student_basic_info->gender ?? __('N/A') }}"
                                                       class="form-control zForm-control-alt pe-none" readonly/>
                                            </div>
                                        @endif

                                        <!-- Date of Birth -->
                                        @if(getOnboardingField($formSetting, 'basic_date_of_birth', 'show'))
                                            <div class="col-md-6">
                                                <label class="zForm-label-alt">
                                                    {{ __('Date of Birth') }}
                                                </label>
                                                <input type="text"
                                                       value="{{ $serviceOrder->student_basic_info->date_of_birth ?? __('N/A') }}"
                                                       class="form-control zForm-control-alt pe-none" readonly/>
                                            </div>
                                        @endif

                                        <!-- Present Address -->
                                        @if(getOnboardingField($formSetting, 'basic_present_address', 'show'))
                                            <div class="col-md-6">
                                                <label class="zForm-label-alt">{{ __('Present Address') }}</label>
                                                <input type="text"
                                                       value="{{ $serviceOrder->student_basic_info->present_address ?? __('N/A') }}"
                                                       class="form-control zForm-control-alt pe-none" readonly/>
                                            </div>
                                        @endif

                                        <!-- Permanent Address -->
                                        @if(getOnboardingField($formSetting, 'basic_permanent_address', 'show'))
                                            <div class="col-md-6">
                                                <label class="zForm-label-alt">
                                                    {{ __('Permanent Address') }}
                                                </label>
                                                <input type="text"
                                                       value="{{ $serviceOrder->student_basic_info->permanent_address ?? __('N/A') }}"
                                                       class="form-control zForm-control-alt pe-none" readonly/>
                                            </div>
                                        @endif

                                        <!-- Passport Number -->
                                        @if(getOnboardingField($formSetting, 'basic_passport_number', 'show'))
                                            <div class="col-md-6">
                                                <label class="zForm-label-alt">
                                                    {{ __('Passport Number') }}
                                                </label>
                                                <input type="text"
                                                       value="{{ $serviceOrder->student_basic_info->passport_number ?? __('N/A') }}"
                                                       class="form-control zForm-control-alt pe-none" readonly/>
                                            </div>
                                        @endif

                                        <!-- Passport Attachment (Display as Text) -->
                                        @if(getOnboardingField($formSetting, 'basic_passport_attachment', 'show'))
                                            <div class="col-lg-6">
                                                @if($serviceOrder->student_basic_info->passport_attachment ?? null)
                                                    <div class="align-items-center d-flex g-10 h-100">
                                                        <label class="zForm-label-alt mb-0">
                                                            {{ __('Passport Attachment') }}
                                                        </label>
                                                        <a href="{{getFileUrl($serviceOrder->student_basic_info->passport_attachment)}}"
                                                           target="__blank">
                                                            {{__('View')}}
                                                        </a>
                                                        <button class="message-file-save rounded-5 sf-btn-brand" title="{{__('Save the file')}}"
                                                                onclick="getFileSaveModal({{$serviceOrder->student_basic_info->passport_attachment}})">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                                                <path d="M1.5 8.9904V6.35375C1.5 4.53708 1.5 3.62874 2.04918 3.06437C2.59835 2.5 3.48223 2.5 5.25 2.5C7.01775 2.5 7.90165 2.5 8.45085 3.06437C9 3.62874 9 4.53708 9 6.35375V8.9904C9 10.1434 9 10.7198 8.63775 10.9262C7.9362 11.3257 6.62025 9.9926 5.9953 9.5912C5.63285 9.3584 5.45165 9.242 5.25 9.242C5.04835 9.242 4.86713 9.3584 4.50469 9.5912C3.87975 9.9926 2.56381 11.3257 1.86227 10.9262C1.5 10.7198 1.5 10.1434 1.5 8.9904Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                                <path d="M4.5 1H5.5C7.857 1 9.03555 1 9.76775 1.73223C10.5 2.46446 10.5 3.64297 10.5 6V9" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @else
                                                    <div class="align-items-center d-flex g-10 h-100">
                                                        <label class="zForm-label-alt mb-0">
                                                            {{ __('Passport Attachment') }}
                                                        </label>
                                                        <p>{{__('N/A')}}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Academic Information Section -->
                    @if(getOnboardingField($formSetting, 'section_show_academic', 'show'))
                        <div class="tab-pane fade" id="pills-academicInfo" role="tabpanel"
                             aria-labelledby="pills-academicInfo-tab" tabindex="0">
                            <div class="">
                                <h4 class="mb-15 fs-18 fw-700 lh-24 text-title-text">{{ __('Academic Info') }}</h4>

                                <!-- Loop through each academic entry -->
                                @forelse($serviceOrder->student_academic_info as $academic)
                                    <div
                                        class="position-relative p-lg-50 p-md-30 p-sm-25 p-15 bd-one bd-c-stroke bd-ra-5 bg-white mb-15">
                                        <div
                                            class="d-flex g-6 justify-content-center position-absolute right-10 top-10">
                                            <button type="button" title="{{__('Edit')}}"
                                                    onclick="getEditModal('{{ route(getOnboardingPrefix().'.onboarding.edit-modal', ['service_order_id' => encodeId($serviceOrder->id), 'section' => 'academic', 'id' => $academic->id]) }}', '#edit-modal')"
                                                    class="w-25 h-25 bd-one bd-c-stroke rounded-circle bg-white d-flex justify-content-center align-items-center">
                                                @include('partials.icons.edit')
                                            </button>
                                            <button type="button" title="{{__('Delete')}}"
                                                    onclick="deleteItem('{{route(getOnboardingPrefix().'.onboarding.delete', ['service_order_id' => encodeId($serviceOrder->id), 'section' => 'academic', 'id' => $academic->id])}}')"
                                                    class="w-25 h-25 bd-one bd-c-stroke rounded-circle bg-white d-flex justify-content-center align-items-center">
                                                <img src="{{ asset('assets/images/icon/delete.svg') }}" alt=""/>
                                            </button>
                                        </div>

                                        <div class="row rg-20">
                                            <!-- Degree Name (Certificate Type Dropdown) -->
                                            @if(getOnboardingField($formSetting, 'academic_certificate_type_id', 'show'))
                                                <div class="col-lg-6">
                                                    <label class="zForm-label-alt">{{ __('Degree Name') }}</label>
                                                    <input type="text" class="form-control zForm-control-alt pe-none"
                                                           readonly
                                                           value="{{ $academic->certificate_type->title ?? __('N/A') }}"/>
                                                </div>
                                            @endif

                                            <!-- Institution Name -->
                                            @if(getOnboardingField($formSetting, 'academic_institution', 'show'))
                                                <div class="col-lg-6">
                                                    <label class="zForm-label-alt">{{ __('Institution Name') }}</label>
                                                    <input type="text" class="form-control zForm-control-alt pe-none"
                                                           readonly
                                                           value="{{ $academic->institution ?? __('N/A') }}"/>
                                                </div>
                                            @endif

                                            <!-- Academic Year -->
                                            @if(getOnboardingField($formSetting, 'academic_academic_year', 'show'))
                                                <div class="col-lg-6">
                                                    <label class="zForm-label-alt">{{ __('Academic Year') }}</label>
                                                    <input type="text" class="form-control zForm-control-alt pe-none"
                                                           readonly
                                                           value="{{ $academic->academic_year ?? __('N/A') }}"/>
                                                </div>
                                            @endif

                                            <!-- Passing Year -->
                                            @if(getOnboardingField($formSetting, 'academic_passing_year', 'show'))
                                                <div class="col-lg-6">
                                                    <label class="zForm-label-alt">{{ __('Passing Year') }}</label>
                                                    <input type="text" class="form-control zForm-control-alt pe-none"
                                                           readonly
                                                           value="{{ $academic->passing_year ?? __('N/A') }}"/>
                                                </div>
                                            @endif

                                            <!-- Result -->
                                            @if(getOnboardingField($formSetting, 'academic_result', 'show'))
                                                <div class="col-lg-6">
                                                    <label class="zForm-label-alt">{{ __('Result') }}</label>
                                                    <input type="text" class="form-control zForm-control-alt pe-none"
                                                           readonly
                                                           value="{{ $academic->result ?? __('N/A') }}"/>
                                                </div>
                                            @endif

                                            <!-- Attachment (File Display) -->
                                            @if(getOnboardingField($formSetting, 'academic_attachment', 'show'))
                                                <div class="col-lg-6">
                                                    @if($academic->attachment)
                                                        <div class="align-items-center d-flex g-10 h-100">
                                                            <label
                                                                class="zForm-label-alt mb-0">{{ __('Attachment') }}</label>
                                                            <a href="{{ getFileUrl($academic->attachment) }}"
                                                               target="_blank">{{ __('View') }}</a>
                                                            <button class="message-file-save rounded-5 sf-btn-brand" title="{{__('Save the file')}}"
                                                                    onclick="getFileSaveModal({{$academic->attachment}})">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                                                    <path d="M1.5 8.9904V6.35375C1.5 4.53708 1.5 3.62874 2.04918 3.06437C2.59835 2.5 3.48223 2.5 5.25 2.5C7.01775 2.5 7.90165 2.5 8.45085 3.06437C9 3.62874 9 4.53708 9 6.35375V8.9904C9 10.1434 9 10.7198 8.63775 10.9262C7.9362 11.3257 6.62025 9.9926 5.9953 9.5912C5.63285 9.3584 5.45165 9.242 5.25 9.242C5.04835 9.242 4.86713 9.3584 4.50469 9.5912C3.87975 9.9926 2.56381 11.3257 1.86227 10.9262C1.5 10.7198 1.5 10.1434 1.5 8.9904Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    <path d="M4.5 1H5.5C7.857 1 9.03555 1 9.76775 1.73223C10.5 2.46446 10.5 3.64297 10.5 6V9" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <div class="align-items-center d-flex g-10 h-100">
                                                            <label class="zForm-label-alt mb-0">{{ __('Attachment') }}</label>
                                                           <p>{{__('N/A')}}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div
                                        class="position-relative p-lg-50 p-md-30 p-sm-25 p-15 bd-one bd-c-stroke bd-ra-5 bg-white mb-15">
                                        {{ __('No academic record found') }}
                                    </div>
                                @endforelse

                                <!-- Button to Add New Academic Entry -->
                                <button type="button" data-bs-toggle="modal" data-bs-target="#add-academy-modal"
                                        class="fs-15 fw-600 lh-20 text-brand-primary text-decoration-underline p-0 bg-transparent border-0">
                                    {{ __('Add New +') }}
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Work Experience Section -->
                    @if(getOnboardingField($formSetting, 'section_show_work_experience', 'show'))
                        <div class="tab-pane fade" id="pills-workExperience" role="tabpanel"
                             aria-labelledby="pills-workExperience-tab" tabindex="0">
                            <div class="">
                                <h4 class="mb-15 fs-18 fw-700 lh-24 text-title-text">{{ __('Work Experience') }}</h4>

                                <!-- Loop through each work experience entry -->
                                @forelse($serviceOrder->student_work_experiences as $experience)
                                    <div
                                        class="position-relative p-lg-50 p-md-30 p-sm-25 p-15 bd-one bd-c-stroke bd-ra-5 bg-white mb-15">
                                        <div
                                            class="d-flex g-6 justify-content-center position-absolute right-10 top-10">
                                            <button type="button" title="{{ __('Edit') }}"
                                                    onclick="getEditModal('{{ route(getOnboardingPrefix().'.onboarding.edit-modal', ['service_order_id' => encodeId($serviceOrder->id), 'section' => 'work_experience', 'id' => $experience->id]) }}', '#edit-modal')"
                                                    class="w-25 h-25 bd-one bd-c-stroke rounded-circle bg-white d-flex justify-content-center align-items-center">
                                                @include('partials.icons.edit')
                                            </button>
                                            <button type="button" title="{{ __('Delete') }}"
                                                    onclick="deleteItem('{{ route(getOnboardingPrefix().'.onboarding.delete', ['service_order_id' => encodeId($serviceOrder->id), 'section' => 'work_experience', 'id' => $experience->id]) }}')"
                                                    class="w-25 h-25 bd-one bd-c-stroke rounded-circle bg-white d-flex justify-content-center align-items-center">
                                                <img src="{{ asset('assets/images/icon/delete.svg') }}" alt=""/>
                                            </button>
                                        </div>

                                        <div class="row rg-20">
                                            <!-- Title -->
                                            @if(getOnboardingField($formSetting, 'work_experience_title', 'show'))
                                                <div class="col-lg-6">
                                                    <label class="zForm-label-alt">{{ __('Title') }}</label>
                                                    <input type="text" class="form-control zForm-control-alt pe-none"
                                                           readonly
                                                           value="{{ $experience->title ?? __('N/A') }}"/>
                                                </div>
                                            @endif

                                            <!-- Company -->
                                            @if(getOnboardingField($formSetting, 'work_experience_company', 'show'))
                                                <div class="col-lg-6">
                                                    <label class="zForm-label-alt">{{ __('Company') }}</label>
                                                    <input type="text" class="form-control zForm-control-alt pe-none"
                                                           readonly
                                                           value="{{ $experience->company ?? __('N/A') }}"/>
                                                </div>
                                            @endif

                                            <!-- Designation -->
                                            @if(getOnboardingField($formSetting, 'work_experience_designation', 'show'))
                                                <div class="col-lg-6">
                                                    <label class="zForm-label-alt">{{ __('Designation') }}</label>
                                                    <input type="text" class="form-control zForm-control-alt pe-none"
                                                           readonly
                                                           value="{{ $experience->designation ?? __('N/A') }}"/>
                                                </div>
                                            @endif

                                            <!-- Start Date -->
                                            @if(getOnboardingField($formSetting, 'work_experience_start_date', 'show'))
                                                <div class="col-lg-6">
                                                    <label class="zForm-label-alt">{{ __('Start Date') }}</label>
                                                    <input type="text" class="form-control zForm-control-alt pe-none"
                                                           readonly
                                                           value="{{ $experience->start_date ?? __('N/A') }}"/>
                                                </div>
                                            @endif

                                            <!-- End Date -->
                                            @if(getOnboardingField($formSetting, 'work_experience_end_date', 'show'))
                                                <div class="col-lg-6">
                                                    <label class="zForm-label-alt">{{ __('End Date') }}</label>
                                                    <input type="text" class="form-control zForm-control-alt pe-none"
                                                           readonly
                                                           value="{{ $experience->end_date ?? __('N/A') }}"/>
                                                </div>
                                            @endif

                                            <!-- Description -->
                                            @if(getOnboardingField($formSetting, 'work_experience_description', 'show'))
                                                <div class="col-lg-6">
                                                    <label class="zForm-label-alt">{{ __('Description') }}</label>
                                                    <input type="text" class="form-control zForm-control-alt pe-none"
                                                           readonly
                                                           value="{{ $experience->description ?? __('N/A') }}"/>
                                                </div>
                                            @endif

                                            <!-- Attachment (File Display) -->
                                            @if(getOnboardingField($formSetting, 'work_experience_attachment', 'show'))
                                                <div class="col-lg-6">
                                                    @if($experience->attachment)
                                                        <div class="align-items-center d-flex g-10 h-100">
                                                            <label
                                                                class="zForm-label-alt mb-0">{{ __('Attachment') }}</label>
                                                            <a href="{{ getFileUrl($experience->attachment) }}"
                                                               target="_blank">{{ __('View') }}</a>
                                                            <button class="message-file-save rounded-5 sf-btn-brand" title="{{__('Save the file')}}"
                                                                    onclick="getFileSaveModal({{$experience->attachment}})">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                                                    <path d="M1.5 8.9904V6.35375C1.5 4.53708 1.5 3.62874 2.04918 3.06437C2.59835 2.5 3.48223 2.5 5.25 2.5C7.01775 2.5 7.90165 2.5 8.45085 3.06437C9 3.62874 9 4.53708 9 6.35375V8.9904C9 10.1434 9 10.7198 8.63775 10.9262C7.9362 11.3257 6.62025 9.9926 5.9953 9.5912C5.63285 9.3584 5.45165 9.242 5.25 9.242C5.04835 9.242 4.86713 9.3584 4.50469 9.5912C3.87975 9.9926 2.56381 11.3257 1.86227 10.9262C1.5 10.7198 1.5 10.1434 1.5 8.9904Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    <path d="M4.5 1H5.5C7.857 1 9.03555 1 9.76775 1.73223C10.5 2.46446 10.5 3.64297 10.5 6V9" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <div class="align-items-center d-flex g-10 h-100">
                                                            <label class="zForm-label-alt mb-0">{{ __('Attachment') }}</label>
                                                            <p>{{__('N/A')}}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div
                                        class="position-relative p-lg-50 p-md-30 p-sm-25 p-15 bd-one bd-c-stroke bd-ra-5 bg-white mb-15">
                                        {{ __('No work experience record found') }}
                                    </div>
                                @endforelse

                                <!-- Button to Add New Work Experience Entry -->
                                <button type="button" data-bs-toggle="modal" data-bs-target="#work-experience-modal"
                                        class="fs-15 fw-600 lh-20 text-brand-primary text-decoration-underline p-0 bg-transparent border-0">
                                    {{ __('Add New +') }}
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Extra Curricular Activities Section -->
                    @if(getOnboardingField($formSetting, 'section_show_extra_curriculum_activity', 'show'))
                        <div class="tab-pane fade" id="pills-extraCurricularActivities" role="tabpanel"
                             aria-labelledby="pills-extraCurricularActivities-tab" tabindex="0">
                            <div class="">
                                <h4 class="mb-15 fs-18 fw-700 lh-24 text-title-text">{{ __('Extra Curricular Activities') }}</h4>

                                <!-- Loop through each extra-curricular activity entry -->
                                @forelse($serviceOrder->student_extra_curriculum_activities as $activity)
                                    <div
                                        class="position-relative p-lg-50 p-md-30 p-sm-25 p-15 bd-one bd-c-stroke bd-ra-5 bg-white mb-15">
                                        <div
                                            class="d-flex g-6 justify-content-center position-absolute right-10 top-10">
                                            <button type="button" title="{{ __('Edit') }}"
                                                    onclick="getEditModal('{{ route(getOnboardingPrefix().'.onboarding.edit-modal', ['service_order_id' => encodeId($serviceOrder->id), 'section' => 'extra_curriculum_activity', 'id' => $activity->id]) }}', '#edit-modal')"
                                                    class="w-25 h-25 bd-one bd-c-stroke rounded-circle bg-white d-flex justify-content-center align-items-center">
                                                @include('partials.icons.edit')
                                            </button>
                                            <button type="button" title="{{ __('Delete') }}"
                                                    onclick="deleteItem('{{ route(getOnboardingPrefix().'.onboarding.delete', ['service_order_id' => encodeId($serviceOrder->id), 'section' => 'extra_curriculum_activity', 'id' => $activity->id]) }}')"
                                                    class="w-25 h-25 bd-one bd-c-stroke rounded-circle bg-white d-flex justify-content-center align-items-center">
                                                <img src="{{ asset('assets/images/icon/delete.svg') }}" alt=""/>
                                            </button>
                                        </div>

                                        <div class="row rg-20">
                                            <!-- Title -->
                                            @if(getOnboardingField($formSetting, 'extra_curriculum_activity_title', 'show'))
                                                <div class="col-lg-6">
                                                    <label class="zForm-label-alt">{{ __('Title') }}</label>
                                                    <input type="text" class="form-control zForm-control-alt pe-none"
                                                           readonly
                                                           value="{{ $activity->title ?? __('N/A') }}"/>
                                                </div>
                                            @endif

                                            <!-- Description -->
                                            @if(getOnboardingField($formSetting, 'extra_curriculum_activity_description', 'show'))
                                                <div class="col-lg-6">
                                                    <label class="zForm-label-alt">{{ __('Description') }}</label>
                                                    <input type="text" class="form-control zForm-control-alt pe-none"
                                                           readonly
                                                           value="{{ $activity->description ?? __('N/A') }}"/>
                                                </div>
                                            @endif

                                            <!-- Attachment (File Display) -->
                                            @if(getOnboardingField($formSetting, 'extra_curriculum_activity_attachment', 'show'))
                                                <div class="col-lg-6">
                                                    @if($activity->attachment)
                                                        <div class="align-items-center d-flex g-10 h-100">
                                                            <label
                                                                class="zForm-label-alt mb-0">{{ __('Attachment') }}</label>
                                                            <a href="{{ getFileUrl($activity->attachment) }}"
                                                               target="_blank">{{ __('View') }}</a>
                                                            <button class="message-file-save rounded-5 sf-btn-brand" title="{{__('Save the file')}}"
                                                                    onclick="getFileSaveModal({{$activity->attachment}})">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                                                    <path d="M1.5 8.9904V6.35375C1.5 4.53708 1.5 3.62874 2.04918 3.06437C2.59835 2.5 3.48223 2.5 5.25 2.5C7.01775 2.5 7.90165 2.5 8.45085 3.06437C9 3.62874 9 4.53708 9 6.35375V8.9904C9 10.1434 9 10.7198 8.63775 10.9262C7.9362 11.3257 6.62025 9.9926 5.9953 9.5912C5.63285 9.3584 5.45165 9.242 5.25 9.242C5.04835 9.242 4.86713 9.3584 4.50469 9.5912C3.87975 9.9926 2.56381 11.3257 1.86227 10.9262C1.5 10.7198 1.5 10.1434 1.5 8.9904Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    <path d="M4.5 1H5.5C7.857 1 9.03555 1 9.76775 1.73223C10.5 2.46446 10.5 3.64297 10.5 6V9" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <div class="align-items-center d-flex g-10 h-100">
                                                            <label class="zForm-label-alt mb-0">{{ __('Attachment') }}</label>
                                                            <p>{{__('N/A')}}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div
                                        class="position-relative p-lg-50 p-md-30 p-sm-25 p-15 bd-one bd-c-stroke bd-ra-5 bg-white mb-15">
                                        {{ __('No extra-curricular activity record found') }}
                                    </div>
                                @endforelse

                                <!-- Button to Add New Extra Curricular Activity Entry -->
                                <button type="button" data-bs-toggle="modal" data-bs-target="#extra-curriculum-modal"
                                        class="fs-15 fw-600 lh-20 text-brand-primary text-decoration-underline p-0 bg-transparent border-0">
                                    {{ __('Add New +') }}
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Language Proficiency Section -->
                    @if(getOnboardingField($formSetting, 'section_show_language_proficiency', 'show'))
                        <div class="tab-pane fade" id="pills-languageProficiency" role="tabpanel"
                             aria-labelledby="pills-languageProficiency-tab" tabindex="0">
                            <div class="">
                                <h4 class="mb-15 fs-18 fw-700 lh-24 text-title-text">{{ __('Language Proficiency') }}</h4>

                                <!-- Loop through each language proficiency entry -->
                                @forelse($serviceOrder->student_language_proficiencies as $proficiency)
                                    <div
                                        class="position-relative p-lg-50 p-md-30 p-sm-25 p-15 bd-one bd-c-stroke bd-ra-5 bg-white mb-15">
                                        <div
                                            class="d-flex g-6 justify-content-center position-absolute right-10 top-10">
                                            <button type="button" title="{{ __('Edit') }}"
                                                    onclick="getEditModal('{{ route(getOnboardingPrefix().'.onboarding.edit-modal', ['service_order_id' => encodeId($serviceOrder->id), 'section' => 'language_proficiency', 'id' => $proficiency->id]) }}', '#edit-modal')"
                                                    class="w-25 h-25 bd-one bd-c-stroke rounded-circle bg-white d-flex justify-content-center align-items-center">
                                                @include('partials.icons.edit')
                                            </button>
                                            <button type="button" title="{{ __('Delete') }}"
                                                    onclick="deleteItem('{{ route(getOnboardingPrefix().'.onboarding.delete', ['service_order_id' => encodeId($serviceOrder->id), 'section' => 'language_proficiency', 'id' => $proficiency->id]) }}')"
                                                    class="w-25 h-25 bd-one bd-c-stroke rounded-circle bg-white d-flex justify-content-center align-items-center">
                                                <img src="{{ asset('assets/images/icon/delete.svg') }}" alt=""/>
                                            </button>
                                        </div>

                                        <div class="row rg-20">
                                            <!-- Proficiency Test -->
                                            @if(getOnboardingField($formSetting, 'language_proficiency_test_id', 'show'))
                                                <div class="col-lg-6">
                                                    <label class="zForm-label-alt">{{ __('Proficiency Test') }}</label>
                                                    <input type="text" class="form-control zForm-control-alt pe-none"
                                                           readonly
                                                           value="{{ $proficiency->test->title ?? __('N/A') }}"/>
                                                </div>
                                            @endif

                                            <!-- Score -->
                                            @if(getOnboardingField($formSetting, 'language_proficiency_score', 'show'))
                                                <div class="col-lg-6">
                                                    <label class="zForm-label-alt">{{ __('Score') }}</label>
                                                    <input type="text" class="form-control zForm-control-alt pe-none"
                                                           readonly
                                                           value="{{ $proficiency->score ?? __('N/A') }}"/>
                                                </div>
                                            @endif

                                            <!-- Test Date -->
                                            @if(getOnboardingField($formSetting, 'language_proficiency_test_date', 'show'))
                                                <div class="col-lg-6">
                                                    <label class="zForm-label-alt">{{ __('Test Date') }}</label>
                                                    <input type="text" class="form-control zForm-control-alt pe-none"
                                                           readonly
                                                           value="{{ $proficiency->test_date ?? __('N/A') }}"/>
                                                </div>
                                            @endif

                                            <!-- Expired Date -->
                                            @if(getOnboardingField($formSetting, 'language_proficiency_expired_date', 'show'))
                                                <div class="col-lg-6">
                                                    <label class="zForm-label-alt">{{ __('Expired Date') }}</label>
                                                    <input type="text" class="form-control zForm-control-alt pe-none"
                                                           readonly
                                                           value="{{ $proficiency->expired_date ?? __('N/A') }}"/>
                                                </div>
                                            @endif

                                            <!-- Attachment (File Display) -->
                                            @if(getOnboardingField($formSetting, 'language_proficiency_attachment', 'show'))
                                                <div class="col-lg-6">
                                                    @if($proficiency->attachment)
                                                        <div class="align-items-center d-flex g-10 h-100">
                                                            <label
                                                                class="zForm-label-alt mb-0">{{ __('Attachment') }}</label>
                                                            <a href="{{ getFileUrl($proficiency->attachment) }}"
                                                               target="_blank">{{ __('View') }}</a>
                                                            <button class="message-file-save rounded-5 sf-btn-brand" title="{{__('Save the file')}}"
                                                                    onclick="getFileSaveModal({{$proficiency->attachment}})">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                                                    <path d="M1.5 8.9904V6.35375C1.5 4.53708 1.5 3.62874 2.04918 3.06437C2.59835 2.5 3.48223 2.5 5.25 2.5C7.01775 2.5 7.90165 2.5 8.45085 3.06437C9 3.62874 9 4.53708 9 6.35375V8.9904C9 10.1434 9 10.7198 8.63775 10.9262C7.9362 11.3257 6.62025 9.9926 5.9953 9.5912C5.63285 9.3584 5.45165 9.242 5.25 9.242C5.04835 9.242 4.86713 9.3584 4.50469 9.5912C3.87975 9.9926 2.56381 11.3257 1.86227 10.9262C1.5 10.7198 1.5 10.1434 1.5 8.9904Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    <path d="M4.5 1H5.5C7.857 1 9.03555 1 9.76775 1.73223C10.5 2.46446 10.5 3.64297 10.5 6V9" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <div class="align-items-center d-flex g-10 h-100">
                                                            <label class="zForm-label-alt mb-0">{{ __('Attachment') }}</label>
                                                            <p>{{__('N/A')}}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div
                                        class="position-relative p-lg-50 p-md-30 p-sm-25 p-15 bd-one bd-c-stroke bd-ra-5 bg-white mb-15">
                                        {{ __('No language proficiency record found') }}
                                    </div>
                                @endforelse

                                <!-- Button to Add New Language Proficiency Entry -->
                                <button type="button" data-bs-toggle="modal"
                                        data-bs-target="#language-proficiency-modal"
                                        class="fs-15 fw-600 lh-20 text-brand-primary text-decoration-underline p-0 bg-transparent border-0">
                                    {{ __('Add New +') }}
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Check if the 'Interested area' section should be shown -->
                    @if(getOnboardingField($formSetting, 'section_show_interested_area', 'show'))
                        <div class="tab-pane fade" id="pills-interestedArea" role="tabpanel"
                             aria-labelledby="pills-interestedArea-tab" tabindex="0">
                            <div class="">
                                <h4 class="mb-15 fs-18 fw-700 lh-24 text-title-text">{{ __('Interested Area') }}</h4>
                                <div
                                    class="position-relative p-lg-50 p-md-30 p-sm-25 p-15 bd-one bd-c-stroke bd-ra-5 bg-white mb-15">
                                    <button type="button" title="{{ __('Edit') }}"
                                            onclick="getEditModal('{{ route(getOnboardingPrefix().'.onboarding.edit-modal', ['service_order_id' => encodeId($serviceOrder->id), 'section' => 'interested_area', 'id' => null]) }}', '#edit-modal')"
                                            class="position-absolute top-10 right-10 w-25 h-25 bd-one bd-c-stroke rounded-circle bg-white d-flex justify-content-center align-items-center">
                                        @include('partials.icons.edit')
                                    </button>
                                    <div class="row rg-20">
                                        <!-- Preferable Country -->
                                        @if(getOnboardingField($formSetting, 'interested_area_destination_country_ids', 'show'))
                                            <div class="col-md-6">
                                                <label class="zForm-label-alt">{{ __('Preferable Country') }}</label>
                                                <input type="text"
                                                       value="{{ $serviceOrder->intersted_countries ?? __('N/A') }}"
                                                       class="form-control zForm-control-alt pe-none" readonly/>
                                            </div>
                                        @endif

                                        <!-- Preferable University -->
                                        @if(getOnboardingField($formSetting, 'interested_area_university_ids', 'show'))
                                            <div class="col-md-6">
                                                <label class="zForm-label-alt">{{ __('Preferable University') }}</label>
                                                <input type="text"
                                                       value="{{ $serviceOrder->intersted_universities ?? __('N/A') }}"
                                                       class="form-control zForm-control-alt pe-none" readonly/>
                                            </div>
                                        @endif

                                        <!-- Preferable Subject -->
                                        @if(getOnboardingField($formSetting, 'interested_area_subject_ids', 'show'))
                                            <div class="col-md-6">
                                                <label class="zForm-label-alt">{{ __('Preferable Subject') }}</label>
                                                <input type="text"
                                                       value="{{ $serviceOrder->intersted_subjects ?? __('N/A') }}"
                                                       class="form-control zForm-control-alt pe-none" readonly/>
                                            </div>
                                        @endif

                                        <!-- Preferable Intake Year/Session -->
                                        @if(getOnboardingField($formSetting, 'interested_area_admission_period', 'show'))
                                            <div class="col-md-6">
                                                <label
                                                    class="zForm-label-alt">{{ __('Preferable Intake Year/Session') }}</label>
                                                <input type="text"
                                                       value="{{ $serviceOrder->student_basic_info->admission_period ?? __('N/A') }}"
                                                       class="form-control zForm-control-alt pe-none" readonly/>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(getOnboardingField($formSetting, 'section_show_custom_filed', 'show'))
                        <div class="tab-pane fade" id="pills-othersInformation" role="tabpanel"
                             aria-labelledby="pills-othersInformation-tab" tabindex="0">
                            <div class="">
                                <h4 class="mb-15 fs-18 fw-700 lh-24 text-title-text">{{ __('Others Information') }}</h4>
                                <div
                                    class="position-relative p-lg-50 p-md-30 p-sm-25 p-15 bd-one bd-c-stroke bd-ra-5 bg-white mb-15">
                                    <div class="row rg-20">
                                        <div id="customFormRender" class="sf-sortable-form"></div>
                                    </div>
                                    <div class="pt-20 mt-20 bd-t-one bd-c-stroke">
                                        <form
                                            action="{{ route(getOnboardingPrefix().'.onboarding.update', ['service_order_id' => encodeId($serviceOrder->id), 'section' => 'custom_field']) }}"
                                            method="post"
                                            enctype="multipart/form-data" class="ajax reset"
                                            data-handler="commonResponseRedirect"
                                            data-redirect-url="{{ route(getOnboardingPrefix().'.onboarding', ['service_order_id' => encodeId($serviceOrder->id)]) }}">
                                            @csrf
                                            <input type="hidden" name="custom_fields" id="custom_fields">
                                            <button type="submit" id="saveCustomForm"
                                                    class="flipBtn sf-flipBtn-primary">{{ __('Save') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                @if(auth()->user()->role == USER_ROLE_STUDENT)
                    <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8 mt-20">
                        <div class="d-flex rg-20 flex-column">
                            <p class="text-para-text">{{ __('Please review all the data before finalizing. Once submitted, the onboarding process will be marked as complete, and no further changes can be made. The admin will be notified after submission.') }}</p>
                            <button
                                class="sf-btn-primary"
                                onclick="finalizeOnboarding('{{ route('student.service_orders.onboarding.finish', encodeId($serviceOrder->id)) }}')"
                                type="button">
                                {{ __('Finalize and Submit') }}
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @include('student.onboarding.add_modal')

    <!-- Add Note Modal -->
    <div class="modal fade" id="fileSaveModal" tabindex="-1" aria-labelledby="fileSaveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bd-c-stroke bd-one bd-ra-10">
                <div class="modal-body p-sm-25 p-15">
                    <!-- Header -->
                    <div
                        class="d-flex justify-content-between align-items-center g-10 pb-20 mb-17 bd-b-one bd-c-stroke">
                        <h4 class="fs-18 fw-600 lh-22 text-title-text">{{__('Add File to Manager')}}</h4>
                        <button type="button"
                                class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent text-para-text fs-13"
                                data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                    </div>
                    <!-- Body -->
                    <form method="POST" id="fileSaveForm" class="ajax reset"
                          action="{{route('admin.service_orders.save-file', $serviceOrder->id)}}"
                          data-handler="responseForFileSave">
                        @csrf
                        <input type="hidden" name="file" id="file-id">
                        <div class="pb-25">
                            <label for="file_name" class="zForm-label-alt">{{__("File Name")}}</label>
                            <input name="file_name" type="text" class="form-control zForm-control-alt" id="file_name"
                                   placeholder="{{ __('File Name') }}"/>
                        </div>
                        <!-- Button -->
                        <div class="d-flex g-12">
                            <button type="submit" class="flipBtn sf-flipBtn-primary">{{__("Save File")}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="university-country-route" value="{{ route('get_universities') }}">
    <input type="hidden" id="university-subject-route" value="{{ route('get_subjects') }}">
@endsection
@push('script')
    <script>
        @if(getOnboardingField($formSetting, 'section_show_custom_filed', 'show'))
        var custom_field_form = {!! $customFieldData !!};
        @endif
    </script>
    <script src="{{ asset('admin/custom/js/onboarding.js') }}"></script>
@endpush

