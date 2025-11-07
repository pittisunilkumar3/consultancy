<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Update Language Proficiency') }}</h2>
    <div class="mClose">
        <button type="button" class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>

<form action="{{ route(getOnboardingPrefix().'.onboarding.update', ['section' => 'language_proficiency', 'id' => $proficiencyData->id, 'service_order_id' => encodeId($serviceOrder->id)]) }}"
      method="post" enctype="multipart/form-data" class="ajax reset" data-handler="commonResponseRedirect"
      data-redirect-url="{{ route(getOnboardingPrefix().'.onboarding', ['service_order_id' => encodeId($serviceOrder->id)]) }}">
    @csrf
    <input type="hidden" name="id" value="{{ $proficiencyData->id }}">

    <div class="row rg-20">
        <!-- Proficiency Test -->

        @if(getOnboardingField($formSetting, 'language_proficiency_test_id', 'show'))
            <div class="col-lg-6">
                <label for="language_proficiency_test_id" class="zForm-label-alt">
                    {{ __('Proficiency Test') }}
                    @if(getOnboardingField($formSetting, 'language_proficiency_test_id', 'required'))
                        <span>*</span>
                    @endif
                </label>
                <select name="language_proficiency_test_id" id="language_proficiency_test_id" class="form-control sf-select-checkbox">
                    <option value="">{{ __('Select Proficiency Test') }}</option>
                    @foreach($proficiencyTests as $test)
                        <option {{$proficiencyData->language_proficiency_test_id == $test->id ? 'selected' : ''}} value="{{ $test->id }}">{{ $test->title }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <!-- Score -->
        @if(getOnboardingField($formSetting, 'language_proficiency_score', 'show'))
            <div class="col-lg-6">
                <label for="language_proficiency_score" class="zForm-label-alt">
                    {{ __('Score') }}
                    @if(getOnboardingField($formSetting, 'language_proficiency_score', 'required'))
                        <span>*</span>
                    @endif
                </label>
                <input type="text" name="language_proficiency_score" id="language_proficiency_score"
                       placeholder="{{ __('Enter Score') }}"
                       value="{{ old('language_proficiency_score', $proficiencyData->score) }}"
                       class="form-control zForm-control-alt"/>
            </div>
        @endif

        <!-- Test Date -->
        @if(getOnboardingField($formSetting, 'language_proficiency_test_date', 'show'))
            <div class="col-lg-6">
                <label for="language_proficiency_test_date" class="zForm-label-alt">
                    {{ __('Test Date') }}
                    @if(getOnboardingField($formSetting, 'language_proficiency_test_date', 'required'))
                        <span>*</span>
                    @endif
                </label>
                <input type="date" name="language_proficiency_test_date" id="language_proficiency_test_date"
                       value="{{ old('language_proficiency_test_date', $proficiencyData->test_date) }}"
                       class="form-control zForm-control-alt"/>
            </div>
        @endif

        <!-- Expired Date -->
        @if(getOnboardingField($formSetting, 'language_proficiency_expired_date', 'show'))
            <div class="col-lg-6">
                <label for="language_proficiency_expired_date" class="zForm-label-alt">
                    {{ __('Expired Date') }}
                    @if(getOnboardingField($formSetting, 'language_proficiency_expired_date', 'required'))
                        <span>*</span>
                    @endif
                </label>
                <input type="date" name="language_proficiency_expired_date" id="language_proficiency_expired_date"
                       value="{{ old('language_proficiency_expired_date', $proficiencyData->expired_date) }}"
                       class="form-control zForm-control-alt"/>
            </div>
        @endif

        <!-- Attachment -->
        @if(getOnboardingField($formSetting, 'language_proficiency_attachment', 'show'))
            <div class="col-lg-12">
                <label for="language_proficiency_attachment" class="zForm-label-alt">
                    {{ __('Attachment (JPG, JPEG, PNG, PDF)') }}
                    @if(getOnboardingField($formSetting, 'language_proficiency_attachment', 'required'))
                        <span>*</span>
                    @endif
                    @if($proficiencyData->attachment)
                        <small class="preview-image-div">
                            <a href="{{ getFileUrl($proficiencyData->attachment) }}" target="_blank">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </small>
                    @endif
                </label>
                <div class="file-upload-one">
                    <label for="language_proficiency_attachment">
                        <p class="fileName fs-14 fw-500 lh-24 text-para-text">{{ __('Choose File to upload') }}</p>
                        <p class="fs-15 fw-700 lh-28 text-white">{{ __('Browse File') }}</p>
                    </label>
                    <input type="file" name="language_proficiency_attachment" id="language_proficiency_attachment"
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
