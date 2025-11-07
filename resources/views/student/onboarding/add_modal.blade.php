
<!-- Edit Modal section start -->
<div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">

        </div>
    </div>
</div>

<!-- Add Academic Information Modal -->
<div class="modal fade" id="add-academy-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">
            <div
                class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
                <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Add Academic Information') }}</h2>
                <div class="mClose">
                    <button type="button"
                            class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                            data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
            </div>
            <form action="{{ route(getOnboardingPrefix().'.onboarding.update', ['service_order_id' => encodeId($serviceOrder->id), 'section' => 'academic']) }}" method="post"
                  enctype="multipart/form-data" class="ajax reset" data-handler="commonResponseRedirect"
                  data-redirect-url="{{route(getOnboardingPrefix().'.onboarding', ['service_order_id' => encodeId($serviceOrder->id)])}}">
                @csrf
                <div class="row rg-20">
                    <!-- Certificate Type -->
                    @if(getOnboardingField($formSetting, 'academic_certificate_type_id', 'show'))
                        <div class="col-lg-6">
                            <label for="academic_certificate_type_id"
                                   class="zForm-label-alt">{{ __('Certificate Name') }}
                                @if(getOnboardingField($formSetting, 'academic_certificate_type_id', 'required'))
                                    <span>*</span>
                                @endif
                            </label>
                            <select name="academic_certificate_type_id" id="academic_certificate_type_id"
                                    class="form-control sf-select-checkbox">
                                <option value="">{{ __('Select Certificate Type') }}</option>
                                @foreach($certificateTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Institution Name -->
                    @if(getOnboardingField($formSetting, 'academic_institution', 'show'))
                        <div class="col-lg-6">
                            <label for="academic_institution" class="zForm-label-alt">{{ __('Institute Name') }}
                                @if(getOnboardingField($formSetting, 'academic_institution', 'required'))
                                    <span>*</span>
                                @endif
                            </label>
                            <input type="text" name="academic_institution" id="academic_institution"
                                   placeholder="{{ __('Enter Institution Name') }}"
                                   class="form-control zForm-control-alt"/>
                        </div>
                    @endif

                    <!-- Academic Year -->
                    @if(getOnboardingField($formSetting, 'academic_academic_year', 'show'))
                        <div class="col-lg-6">
                            <label for="academic_academic_year" class="zForm-label-alt">{{ __('Academic Year') }}
                                @if(getOnboardingField($formSetting, 'academic_academic_year', 'required'))
                                    <span>*</span>
                                @endif
                            </label>
                            <input type="text" name="academic_academic_year" id="academic_academic_year"
                                   placeholder="{{ __('Enter Academic Year') }}"
                                   class="form-control zForm-control-alt"/>
                        </div>
                    @endif

                    <!-- Passing Year -->
                    @if(getOnboardingField($formSetting, 'academic_passing_year', 'show'))
                        <div class="col-lg-6">
                            <label for="academic_passing_year" class="zForm-label-alt">{{ __('Passing Year') }}
                                @if(getOnboardingField($formSetting, 'academic_passing_year', 'required'))
                                    <span>*</span>
                                @endif
                            </label>
                            <input type="text" name="academic_passing_year" id="academic_passing_year"
                                   placeholder="{{ __('Enter Passing Year') }}"
                                   class="form-control zForm-control-alt"/>
                        </div>
                    @endif

                    <!-- Result -->
                    @if(getOnboardingField($formSetting, 'academic_result', 'show'))
                        <div class="col-lg-6">
                            <label for="academic_result" class="zForm-label-alt">{{ __('Result') }}
                                @if(getOnboardingField($formSetting, 'academic_result', 'required'))
                                    <span>*</span>
                                @endif
                            </label>
                            <input type="text" name="academic_result" id="academic_result"
                                   placeholder="{{ __('Enter Result') }}" class="form-control zForm-control-alt"/>
                        </div>
                    @endif

                    <!-- Attachment -->
                    @if(getOnboardingField($formSetting, 'academic_attachment', 'show'))
                        <div class="col-lg-6">
                            <label for="add_academic_attachment"
                                   class="zForm-label-alt">{{ __('Attachment (JPG, JPEG, PNG, PDF') }}
                                @if(getOnboardingField($formSetting, 'academic_attachment', 'required'))
                                    <span>*</span>
                                @endif
                            </label>
                            <div class="file-upload-one">
                                <label for="add_academic_attachment">
                                    <p class="fileName fs-14 fw-500 lh-24 text-para-text">{{ __('Choose Image to upload') }}</p>
                                    <p class="fs-15 fw-700 lh-28 text-white">{{ __('Browse File') }}</p>
                                </label>
                                <input type="file" name="academic_attachment" id="add_academic_attachment"
                                       class="fileUploadInput invisible position-absolute"/>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="pt-20 mt-20 bd-t-one bd-c-stroke">
                    <button type="submit" class="flipBtn sf-flipBtn-primary">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add work experience Modal -->
<div class="modal fade" id="work-experience-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">
            <div
                class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
                <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Add Academic Information') }}</h2>
                <div class="mClose">
                    <button type="button"
                            class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                            data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
            </div>
            <form action="{{ route(getOnboardingPrefix().'.onboarding.update', ['service_order_id' => encodeId($serviceOrder->id), 'section' => 'work_experience']) }}" method="post"
                  enctype="multipart/form-data" class="ajax reset" data-handler="commonResponseRedirect"
                  data-redirect-url="{{route(getOnboardingPrefix().'.onboarding', ['service_order_id' => encodeId($serviceOrder->id)])}}">
                @csrf
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
                                   class="form-control zForm-control-alt"/>
                        </div>
                    @endif

                    <!-- Attachment -->
                    @if(getOnboardingField($formSetting, 'work_experience_attachment', 'show'))
                        <div class="col-lg-6">
                            <label for="add_work_experience_attachment" class="zForm-label-alt">
                                {{ __('Attachment (JPG, JPEG, PNG, PDF)') }}
                                @if(getOnboardingField($formSetting, 'work_experience_attachment', 'required'))
                                    <span>*</span>
                                @endif
                            </label>
                            <div class="file-upload-one">
                                <label for="add_work_experience_attachment">
                                    <p class="fileName fs-14 fw-500 lh-24 text-para-text">{{ __('Choose File to upload') }}</p>
                                    <p class="fs-15 fw-700 lh-28 text-white">{{ __('Browse File') }}</p>
                                </label>
                                <input type="file" name="work_experience_attachment"
                                       id="add_work_experience_attachment"
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
                                      class="form-control zForm-control-alt"></textarea>
                        </div>
                    @endif

                </div>
                <div class="pt-20 mt-20 bd-t-one bd-c-stroke">
                    <button type="submit" class="flipBtn sf-flipBtn-primary">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Extra Curricular Activity Modal -->
<div class="modal fade" id="extra-curriculum-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">
            <div
                class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
                <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Add Extra Curricular Activity') }}</h2>
                <div class="mClose">
                    <button type="button"
                            class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                            data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
            </div>
            <form action="{{ route(getOnboardingPrefix().'.onboarding.update', ['service_order_id' => encodeId($serviceOrder->id), 'section' => 'extra_curriculum_activity']) }}"
                  method="post"
                  enctype="multipart/form-data" class="ajax reset" data-handler="commonResponseRedirect"
                  data-redirect-url="{{ route(getOnboardingPrefix().'.onboarding', ['service_order_id' => encodeId($serviceOrder->id)]) }}">
                @csrf
                <div class="row rg-20">
                    <!-- Title -->
                    @if(getOnboardingField($formSetting, 'extra_curriculum_activity_title', 'show'))
                        <div class="col-lg-12">
                            <label for="extra_curriculum_activity_title" class="zForm-label-alt">
                                {{ __('Title') }}
                                @if(getOnboardingField($formSetting, 'extra_curriculum_activity_title', 'required'))
                                    <span>*</span>
                                @endif
                            </label>
                            <input type="text" name="extra_curriculum_activity_title"
                                   id="extra_curriculum_activity_title"
                                   placeholder="{{ __('Enter Title') }}"
                                   class="form-control zForm-control-alt"/>
                        </div>
                    @endif

                    <!-- Attachment -->
                    @if(getOnboardingField($formSetting, 'extra_curriculum_activity_attachment', 'show'))
                        <div class="col-lg-12">
                            <label for="add_extra_curriculum_activity_attachment" class="zForm-label-alt">
                                {{ __('Attachment (JPG, JPEG, PNG, PDF)') }}
                                @if(getOnboardingField($formSetting, 'extra_curriculum_activity_attachment', 'required'))
                                    <span>*</span>
                                @endif
                            </label>
                            <div class="file-upload-one">
                                <label for="add_extra_curriculum_activity_attachment">
                                    <p class="fileName fs-14 fw-500 lh-24 text-para-text">{{ __('Choose File to upload') }}</p>
                                    <p class="fs-15 fw-700 lh-28 text-white">{{ __('Browse File') }}</p>
                                </label>
                                <input type="file" name="extra_curriculum_activity_attachment"
                                       id="add_extra_curriculum_activity_attachment"
                                       class="fileUploadInput invisible position-absolute"/>
                            </div>
                        </div>
                    @endif

                    <!-- Description -->
                    @if(getOnboardingField($formSetting, 'extra_curriculum_activity_description', 'show'))
                        <div class="col-lg-12">
                            <label for="extra_curriculum_activity_description" class="zForm-label-alt">
                                {{ __('Description') }}
                                @if(getOnboardingField($formSetting, 'extra_curriculum_activity_description', 'required'))
                                    <span>*</span>
                                @endif
                            </label>
                            <textarea name="extra_curriculum_activity_description"
                                      id="extra_curriculum_activity_description"
                                      placeholder="{{ __('Enter Description') }}"
                                      class="form-control zForm-control-alt"></textarea>
                        </div>
                    @endif
                </div>
                <div class="pt-20 mt-20 bd-t-one bd-c-stroke">
                    <button type="submit" class="flipBtn sf-flipBtn-primary">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Language Proficiency Modal -->
<div class="modal fade" id="language-proficiency-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">
            <div
                class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
                <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Add Language Proficiency') }}</h2>
                <div class="mClose">
                    <button type="button"
                            class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                            data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
            </div>
            <form action="{{ route(getOnboardingPrefix().'.onboarding.update', ['service_order_id' => encodeId($serviceOrder->id), 'section' => 'language_proficiency']) }}"
                  method="post"
                  enctype="multipart/form-data" class="ajax reset" data-handler="commonResponseRedirect"
                  data-redirect-url="{{ route(getOnboardingPrefix().'.onboarding', ['service_order_id' => encodeId($serviceOrder->id)]) }}">
                @csrf
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
                            <select name="language_proficiency_test_id" id="language_proficiency_test_id"
                                    class="form-control sf-select-checkbox">
                                <option value="">{{ __('Select Proficiency Test') }}</option>
                                @foreach($proficiencyTests as $test)
                                    <option value="{{ $test->id }}">{{ $test->title }}</option>
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
                                   placeholder="{{ __('Enter Score') }}" class="form-control zForm-control-alt"/>
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
                            <input type="date" name="language_proficiency_test_date"
                                   id="language_proficiency_test_date"
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
                            <input type="date" name="language_proficiency_expired_date"
                                   id="language_proficiency_expired_date"
                                   class="form-control zForm-control-alt"/>
                        </div>
                    @endif

                    <!-- Attachment -->
                    @if(getOnboardingField($formSetting, 'language_proficiency_attachment', 'show'))
                        <div class="col-lg-12">
                            <label for="add_language_proficiency_attachment" class="zForm-label-alt">
                                {{ __('Attachment (JPG, JPEG, PNG, PDF)') }}
                                @if(getOnboardingField($formSetting, 'language_proficiency_attachment', 'required'))
                                    <span>*</span>
                                @endif
                            </label>
                            <div class="file-upload-one">
                                <label for="add_language_proficiency_attachment">
                                    <p class="fileName fs-14 fw-500 lh-24 text-para-text">{{ __('Choose File to upload') }}</p>
                                    <p class="fs-15 fw-700 lh-28 text-white">{{ __('Browse File') }}</p>
                                </label>
                                <input type="file" name="language_proficiency_attachment"
                                       id="add_language_proficiency_attachment"
                                       class="fileUploadInput invisible position-absolute"/>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="pt-20 mt-20 bd-t-one bd-c-stroke">
                    <button type="submit" class="flipBtn sf-flipBtn-primary">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
