<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Update Interested Area') }}</h2>
    <div class="mClose">
        <button type="button" class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>
<form class="ajax reset" action="{{ route(getOnboardingPrefix().'.onboarding.update', ['section' => 'interested_area', 'service_order_id' => encodeId($serviceOrder->id)]) }}"
      method="post"
      data-handler="commonResponseRedirect" data-redirect-url="{{ route(getOnboardingPrefix().'.onboarding', ['service_order_id' => encodeId($serviceOrder->id)]) }}">
    @csrf
    <div class="row rg-20">
        <!-- Preferable Country -->
        <div class="col-lg-6">
            <label for="interested_area_destination_country_ids" class="zForm-label-alt">
                {{ __('Preferable Country') }}
                @if(getOnboardingField($formSetting, 'interested_area_destination_country_ids', 'required'))
                    <span>*</span>
                @endif
            </label>
            <select multiple name="interested_area_destination_country_ids[]" id="interested_area_destination_country_ids"
                    class="form-control sf-select-checkbox-search">
                @foreach($countries as $country)
                    <option value="{{ $country->id }}" {{ in_array($country->id, $serviceOrder->student_basic_info->destination_country_ids ?? []) ? 'selected' : '' }}>
                        {{ $country->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Preferable University -->
        <div class="col-lg-6">
            <label for="interested_area_university_ids" class="zForm-label-alt">{{ __('Preferable University') }}</label>
            <select multiple name="interested_area_university_ids[]" id="interested_area_university_ids"
                    class="form-control sf-select-checkbox-search">
                @foreach($universities as $university)
                    <option value="{{ $university->id }}" {{ in_array($university->id, $serviceOrder->student_basic_info->university_ids ?? []) ? 'selected' : '' }}>
                        {{ $university->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Preferable Subject -->
        <div class="col-lg-6">
            <label for="interested_area_subject_ids" class="zForm-label-alt">{{ __('Preferable Subject') }}</label>
            <select multiple name="interested_area_subject_ids[]" id="interested_area_subject_ids"
                    class="form-control sf-select-checkbox-search">
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ in_array($subject->id, $serviceOrder->student_basic_info->subject_ids ?? []) ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Preferable Intake Year/Session -->
        <div class="col-lg-6">
            <label for="interested_area_admission_period" class="zForm-label-alt">
                {{ __('Preferable Intake Year/Session') }}
                @if(getOnboardingField($formSetting, 'interested_area_admission_period', 'required'))
                    <span>*</span>
                @endif
            </label>
            <input type="text" name="interested_area_admission_period" id="interested_area_admission_period"
                   placeholder="{{ __('Preferable Intake Year/Session') }}"
                   value="{{ $serviceOrder->student_basic_info->admission_period ?? '' }}"
                   class="form-control zForm-control-alt"/>
        </div>
    </div>

    <div class="pt-20 mt-20 bd-t-one bd-c-stroke">
        <button type="submit" class="flipBtn sf-flipBtn-primary">
            <span>{{ __('Update') }}</span>
            <span>{{ __('Update') }}</span>
            <span>{{ __('Update') }}</span>
        </button>
    </div>
</form>
