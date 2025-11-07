<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Update Basic Information') }}</h2>
    <div class="mClose">
        <button type="button" class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>
<form class="ajax reset" action="{{ route(getOnboardingPrefix().'.onboarding.update', ['section' => 'basic', 'service_order_id' => encodeId($serviceOrder->id)]) }}" method="post"
      data-handler="commonResponseRedirect" data-redirect-url="{{route(getOnboardingPrefix().'.onboarding', ['service_order_id' => encodeId($serviceOrder->id)])}}">
    @csrf
    <div class="row rg-20">
        <!-- First Name -->
        @if(getOnboardingField($formSetting, 'basic_first_name', 'show'))
            <div class="col-md-6">
                <label for="inputFirstName" class="zForm-label-alt">
                    {{ __('First Name') }}
                    @if(getOnboardingField($formSetting, 'basic_first_name', 'required'))
                        <span>*</span>
                    @endif
                </label>
                <input type="text" class="form-control zForm-control-alt" id="inputFirstName"
                       name="basic_first_name"
                       placeholder="{{ __('Enter First Name') }}"
                       value="{{ $serviceOrder->student_basic_info->first_name ?? '' }}"/>
            </div>
        @endif

        <!-- Last Name -->
        @if(getOnboardingField($formSetting, 'basic_last_name', 'show'))
            <div class="col-md-6">
                <label for="inputLastName" class="zForm-label-alt">
                    {{ __('Last Name') }}
                    @if(getOnboardingField($formSetting, 'basic_last_name', 'required'))
                        <span>*</span>
                    @endif
                </label>
                <input type="text" class="form-control zForm-control-alt" id="inputLastName"
                       name="basic_last_name"
                       placeholder="{{ __('Enter Last Name') }}"
                       value="{{ $serviceOrder->student_basic_info->last_name ?? '' }}"/>
            </div>
        @endif

        <!-- Email Address -->
        @if(getOnboardingField($formSetting, 'basic_email', 'show'))
            <div class="col-md-6">
                <label for="inputEmailAddress" class="zForm-label-alt">
                    {{ __('Email Address') }}
                    @if(getOnboardingField($formSetting, 'basic_email', 'required'))
                        <span>*</span>
                    @endif
                </label>
                <input type="email" class="form-control zForm-control-alt" id="inputEmailAddress"
                       name="basic_email"
                       placeholder="{{ __('Enter Email Address') }}"
                       value="{{ $serviceOrder->student_basic_info->email ?? '' }}"/>
            </div>
        @endif

        <!-- Phone Number -->
        @if(getOnboardingField($formSetting, 'basic_phone', 'show'))
            <div class="col-md-6">
                <label for="inputPhoneNumber" class="zForm-label-alt">
                    {{ __('Phone Number') }}
                    @if(getOnboardingField($formSetting, 'basic_phone', 'required'))
                        <span>*</span>
                    @endif
                </label>
                <input type="text" class="form-control zForm-control-alt" id="inputPhoneNumber"
                       name="basic_phone"
                       placeholder="{{ __('Enter Phone Number') }}"
                       value="{{ $serviceOrder->student_basic_info->mobile ?? '' }}"/>
            </div>
        @endif

        <!-- Gender -->
        @if(getOnboardingField($formSetting, 'basic_gender', 'show'))
            <div class="col-md-6">
                <label for="basic_gender" class="zForm-label-alt">
                    {{ __('Gender') }}
                    @if(getOnboardingField($formSetting, 'basic_gender', 'required'))
                        <span>*</span>
                    @endif
                </label>
                <select class="sf-select-two form-control zForm-control-alt" name="basic_gender" id="basic_gender">
                    <option value="">{{ __('Select Gender') }}</option>
                    <option value="1" {{ $serviceOrder->student_basic_info?->gender == '1' ? 'selected' : '' }}>{{ __('Male') }}</option>
                    <option value="2" {{ $serviceOrder->student_basic_info?->gender == '2' ? 'selected' : '' }}>{{ __('Female') }}</option>
                    <option value="3" {{ $serviceOrder->student_basic_info?->gender == '3' ? 'selected' : '' }}>{{ __('Other') }}</option>
                </select>
            </div>
        @endif

        <!-- Date of Birth -->
        @if(getOnboardingField($formSetting, 'basic_date_of_birth', 'show'))
            <div class="col-md-6">
                <label for="inputDOB" class="zForm-label-alt">
                    {{ __('Date of Birth') }}
                    @if(getOnboardingField($formSetting, 'basic_date_of_birth', 'required'))
                        <span>*</span>
                    @endif
                </label>
                <input type="date" class="form-control zForm-control-alt" id="inputDOB"
                       name="basic_date_of_birth"
                       value="{{ $serviceOrder->student_basic_info->date_of_birth ?? '' }}"/>
            </div>
        @endif

        <!-- Present Address -->
        @if(getOnboardingField($formSetting, 'basic_present_address', 'show'))
            <div class="col-md-6">
                <label for="inputPresentAddress" class="zForm-label-alt">
                    {{ __('Present Address') }}
                    @if(getOnboardingField($formSetting, 'basic_present_address', 'required'))
                        <span>*</span>
                    @endif
                </label>
                <input type="text" class="form-control zForm-control-alt" id="inputPresentAddress"
                       name="basic_present_address"
                       placeholder="{{ __('Enter Present Address') }}"
                       value="{{ $serviceOrder->student_basic_info->present_address ?? '' }}"/>
            </div>
        @endif

        <!-- Permanent Address -->
        @if(getOnboardingField($formSetting, 'basic_permanent_address', 'show'))
            <div class="col-md-6">
                <label for="inputPermanentAddress" class="zForm-label-alt">
                    {{ __('Permanent Address') }}
                    @if(getOnboardingField($formSetting, 'basic_permanent_address', 'required'))
                        <span>*</span>
                    @endif
                </label>
                <input type="text" class="form-control zForm-control-alt" id="inputPermanentAddress"
                       name="basic_permanent_address"
                       placeholder="{{ __('Enter Permanent Address') }}"
                       value="{{ $serviceOrder->student_basic_info->permanent_address ?? '' }}"/>
            </div>
        @endif

        <!-- Passport Number -->
        @if(getOnboardingField($formSetting, 'basic_passport_number', 'show'))
            <div class="col-md-6">
                <label for="inputPassportNumber" class="zForm-label-alt">
                    {{ __('Passport Number') }}
                    @if(getOnboardingField($formSetting, 'basic_passport_number', 'required'))
                        <span>*</span>
                    @endif
                </label>
                <input type="text" class="form-control zForm-control-alt" id="inputPassportNumber"
                       name="basic_passport_number"
                       placeholder="{{ __('Enter Passport Number') }}"
                       value="{{ $serviceOrder->student_basic_info->passport_number ?? '' }}"/>
            </div>
        @endif

        <!-- Passport Attachment -->
        @if(getOnboardingField($formSetting, 'basic_passport_attachment', 'show'))
            <div class="col-lg-6">
                <label class="zForm-label-alt">
                    {{ __('Passport Attachment (JPG, JPEG, PNG, PDF)') }}
                    @if(getOnboardingField($formSetting, 'basic_passport_attachment', 'required'))
                        <span>*</span>
                    @endif
                    @if($serviceOrder->student_basic_info->passport_attachment ?? null)
                        <input type="hidden" name="has_basic_passport_attachment" value="{{ $serviceOrder->student_basic_info->passport_attachment ? 1 : 0 }}">
                        <small class="preview-image-div">
                            <a href="{{getFileUrl($serviceOrder->student_basic_info->passport_attachment)}}"
                               target="_blank"><i class="fa-solid fa-eye"></i></a>
                        </small>
                    @endif
                </label>
                <div class="file-upload-one">
                    <label for="basic_passport_attachment">
                        <p class="fileName fs-14 fw-500 lh-24 text-para-text">{{ __('Choose Image to upload') }}</p>
                        <p class="fs-15 fw-700 lh-28 text-white">{{ __('Browse File') }}</p>
                    </label>
                    <input type="file" name="basic_passport_attachment" id="basic_passport_attachment"
                           class="fileUploadInput invisible position-absolute"/>
                </div>
            </div>
        @endif
    </div>

    <div class="pt-20 mt-20 bd-t-one bd-c-stroke">
        <button type="submit" class="flipBtn sf-flipBtn-primary">
            <span>{{ __('Update') }}</span>
            <span>{{ __('Update') }}</span>
            <span>{{ __('Update') }}</span>
        </button>
    </div>
</form>
