<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Update Work Experience') }}</h2>
    <div class="mClose">
        <button type="button" class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>

<form action="{{ route(getOnboardingPrefix().'.onboarding.update', ['section' => 'work_experience', 'id' => $experienceData->id, 'service_order_id' => encodeId($serviceOrder->id)]) }}" method="post"
      enctype="multipart/form-data" class="ajax reset" data-handler="commonResponseRedirect" data-redirect-url="{{ route(getOnboardingPrefix().'.onboarding', ['service_order_id' => encodeId($serviceOrder->id)]) }}">
    @csrf
    <input type="hidden" name="id" value="{{$experienceData->id}}">
    <div class="row rg-20">
        <!-- Title -->
        @if(getOnboardingField($formSetting, 'work_experience_title', 'show'))
            <div class="col-lg-6">
                <label for="work_experience_title" class="zForm-label-alt">
                    {{ __('Title') }}
                    @if(getOnboardingField($formSetting, 'work_experience_title', 'required'))
                        <span>*</span>
                    @endif
                </label>
                <input type="text" name="work_experience_title" id="work_experience_title"
                       placeholder="{{ __('Enter Title') }}"
                       value="{{ old('work_experience_title', $experienceData->title) }}"
                       class="form-control zForm-control-alt"/>
            </div>
        @endif

        <!-- Company -->
        @if(getOnboardingField($formSetting, 'work_experience_company', 'show'))
            <div class="col-lg-6">
                <label for="work_experience_company" class="zForm-label-alt">
                    {{ __('Company') }}
                    @if(getOnboardingField($formSetting, 'work_experience_company', 'required'))
                        <span>*</span>
                    @endif
                </label>
                <input type="text" name="work_experience_company" id="work_experience_company"
                       placeholder="{{ __('Enter Company Name') }}"
                       value="{{ old('work_experience_company', $experienceData->company) }}"
                       class="form-control zForm-control-alt"/>
            </div>
        @endif

        <!-- Designation -->
        @if(getOnboardingField($formSetting, 'work_experience_designation', 'show'))
            <div class="col-lg-6">
                <label for="work_experience_designation" class="zForm-label-alt">
                    {{ __('Designation') }}
                    @if(getOnboardingField($formSetting, 'work_experience_designation', 'required'))
                        <span>*</span>
                    @endif
                </label>
                <input type="text" name="work_experience_designation" id="work_experience_designation"
                       placeholder="{{ __('Enter Designation') }}"
                       value="{{ old('work_experience_designation', $experienceData->designation) }}"
                       class="form-control zForm-control-alt"/>
            </div>
        @endif

        <!-- Start Date -->
        @if(getOnboardingField($formSetting, 'work_experience_start_date', 'show'))
            <div class="col-lg-6">
                <label for="work_experience_start_date" class="zForm-label-alt">
                    {{ __('Start Date') }}
                    @if(getOnboardingField($formSetting, 'work_experience_start_date', 'required'))
                        <span>*</span>
                    @endif
                </label>
                <input type="date" name="work_experience_start_date" id="work_experience_start_date"
                       value="{{ old('work_experience_start_date', $experienceData->start_date) }}"
                       class="form-control zForm-control-alt"/>
            </div>
        @endif

        <!-- End Date -->
        @if(getOnboardingField($formSetting, 'work_experience_end_date', 'show'))
            <div class="col-lg-6">
                <label for="work_experience_end_date" class="zForm-label-alt">
                    {{ __('End Date') }}
                    @if(getOnboardingField($formSetting, 'work_experience_end_date', 'required'))
                        <span>*</span>
                    @endif
                </label>
                <input type="date" name="work_experience_end_date" id="work_experience_end_date"
                       value="{{ old('work_experience_end_date', $experienceData->end_date) }}"
                       class="form-control zForm-control-alt"/>
            </div>
        @endif

        <!-- Attachment -->
        @if(getOnboardingField($formSetting, 'work_experience_attachment', 'show'))
            <div class="col-lg-6">
                <label for="work_experience_attachment" class="zForm-label-alt">
                    {{ __('Attachment (JPG, JPEG, PNG, PDF)') }}
                    @if(getOnboardingField($formSetting, 'work_experience_attachment', 'required'))
                        <span>*</span>
                    @endif
                    @if($experienceData->attachment)
                        <small class="preview-image-div">
                            <a href="{{ getFileUrl($experienceData->attachment) }}" target="_blank">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </small>
                    @endif
                </label>
                <div class="file-upload-one">
                    <label for="work_experience_attachment">
                        <p class="fileName fs-14 fw-500 lh-24 text-para-text">{{ __('Choose File to upload') }}</p>
                        <p class="fs-15 fw-700 lh-28 text-white">{{ __('Browse File') }}</p>
                    </label>
                    <input type="file" name="work_experience_attachment" id="work_experience_attachment"
                           class="fileUploadInput invisible position-absolute"/>
                </div>
            </div>
        @endif

        <!-- Description -->
        @if(getOnboardingField($formSetting, 'work_experience_description', 'show'))
            <div class="col-lg-12">
                <label for="work_experience_description" class="zForm-label-alt">
                    {{ __('Description') }}
                    @if(getOnboardingField($formSetting, 'work_experience_description', 'required'))
                        <span>*</span>
                    @endif
                </label>
                <textarea name="work_experience_description" id="work_experience_description"
                          placeholder="{{ __('Enter Description') }}"
                          class="form-control zForm-control-alt">{{ old('work_experience_description', $experienceData->description) }}</textarea>
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
