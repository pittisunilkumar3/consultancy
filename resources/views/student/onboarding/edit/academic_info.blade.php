<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Update Academic Information') }}</h2>
    <div class="mClose">
        <button type="button" class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>

<form action="{{ route(getOnboardingPrefix().'.onboarding.update', ['section' => 'academic', 'id' => $academyData->id, 'service_order_id' => encodeId($serviceOrder->id)]) }}" method="post"
      enctype="multipart/form-data" class="ajax reset" data-handler="commonResponseRedirect" data-redirect-url="{{ route(getOnboardingPrefix().'.onboarding', ['service_order_id' => encodeId($serviceOrder->id)]) }}">
    @csrf
    <input type="hidden" name="id" value="{{$academyData->id}}">
    <div class="row rg-20">
        <!-- Certificate Type -->
        @if(getOnboardingField($formSetting, 'academic_certificate_type_id', 'show'))
            <div class="col-lg-6">
                <label for="academic_certificate_type_id" class="zForm-label-alt">
                    {{ __('Certificate Name') }}
                    @if(getOnboardingField($formSetting, 'academic_certificate_type_id', 'required'))
                        <span>*</span>
                    @endif
                </label>
                <select name="academic_certificate_type_id" id="academic_certificate_type_id" class="form-control sf-select-checkbox">
                    <option value="">{{ __('Select Certificate Type') }}</option>
                    @foreach($certificateTypes as $type)
                        <option value="{{ $type->id }}" {{ old('academic_certificate_type_id', $academyData->certificate_type_id) == $type->id ? 'selected' : '' }}>
                            {{ $type->title }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <!-- Institution Name -->
        @if(getOnboardingField($formSetting, 'academic_institution', 'show'))
            <div class="col-lg-6">
                <label for="academic_institution" class="zForm-label-alt">
                    {{ __('Institute Name') }}
                    @if(getOnboardingField($formSetting, 'academic_institution', 'required'))
                        <span>*</span>
                    @endif
                </label>
                <input type="text" name="academic_institution" id="academic_institution"
                       placeholder="{{ __('Enter Institution Name') }}"
                       value="{{ old('academic_institution', $academyData->institution) }}"
                       class="form-control zForm-control-alt"/>
            </div>
        @endif

        <!-- Academic Year -->
        @if(getOnboardingField($formSetting, 'academic_academic_year', 'show'))
            <div class="col-lg-6">
                <label for="academic_academic_year" class="zForm-label-alt">
                    {{ __('Academic Year') }}
                    @if(getOnboardingField($formSetting, 'academic_academic_year', 'required'))
                        <span>*</span>
                    @endif
                </label>
                <input type="text" name="academic_academic_year" id="academic_academic_year"
                       placeholder="{{ __('Enter Academic Year') }}"
                       value="{{ old('academic_academic_year', $academyData->academic_year) }}"
                       class="form-control zForm-control-alt"/>
            </div>
        @endif

        <!-- Passing Year -->
        @if(getOnboardingField($formSetting, 'academic_passing_year', 'show'))
            <div class="col-lg-6">
                <label for="academic_passing_year" class="zForm-label-alt">
                    {{ __('Passing Year') }}
                    @if(getOnboardingField($formSetting, 'academic_passing_year', 'required'))
                        <span>*</span>
                    @endif
                </label>
                <input type="text" name="academic_passing_year" id="academic_passing_year"
                       placeholder="{{ __('Enter Passing Year') }}"
                       value="{{ old('academic_passing_year', $academyData->passing_year) }}"
                       class="form-control zForm-control-alt"/>
            </div>
        @endif

        <!-- Result -->
        @if(getOnboardingField($formSetting, 'academic_result', 'show'))
            <div class="col-lg-6">
                <label for="academic_result" class="zForm-label-alt">
                    {{ __('Result') }}
                    @if(getOnboardingField($formSetting, 'academic_result', 'required'))
                        <span>*</span>
                    @endif
                </label>
                <input type="text" name="academic_result" id="academic_result"
                       placeholder="{{ __('Enter Result') }}"
                       value="{{ old('academic_result', $academyData->result) }}"
                       class="form-control zForm-control-alt"/>
            </div>
        @endif

        <!-- Attachment -->
        @if(getOnboardingField($formSetting, 'academic_attachment', 'show'))
            <div class="col-lg-6">
                <label for="academic_attachment" class="zForm-label-alt">
                    {{ __('Attachment (JPG, JPEG, PNG, PDF)') }}
                    @if(getOnboardingField($formSetting, 'academic_attachment', 'required'))
                        <span>*</span>
                    @endif
                    @if($academyData->attachment)
                        <small class="preview-image-div">
                            <a href="{{getFileUrl($academyData->attachment)}}"
                               target="_blank"><i class="fa-solid fa-eye"></i></a>
                        </small>
                    @endif
                </label>
                <div class="file-upload-one">
                    <label for="academic_attachment">
                        <p class="fileName fs-14 fw-500 lh-24 text-para-text">{{ __('Choose Image to upload') }}</p>
                        <p class="fs-15 fw-700 lh-28 text-white">{{ __('Browse File') }}</p>
                    </label>
                    <input type="file" name="academic_attachment" id="academic_attachment"
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
