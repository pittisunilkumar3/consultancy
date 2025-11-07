<div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
    <h2 class="fs-18 fw-600 lh-22 text-title-text">{{ __('Update Extra Curricular Activity') }}</h2>
    <div class="mClose">
        <button type="button" class="bd-one bd-c-stroke rounded-circle w-25 h-25 bg-transparent text-para-text"
                data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
</div>

<form action="{{ route(getOnboardingPrefix().'.onboarding.update', ['section' => 'extra_curriculum_activity', 'id' => $activityData->id, 'service_order_id' => encodeId($serviceOrder->id)]) }}"
      method="post" enctype="multipart/form-data" class="ajax reset" data-handler="commonResponseRedirect"
      data-redirect-url="{{ route(getOnboardingPrefix().'.onboarding', ['service_order_id' => encodeId($serviceOrder->id)]) }}">
    @csrf
    <input type="hidden" name="id" value="{{ $activityData->id }}">

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
                <input type="text" name="extra_curriculum_activity_title" id="extra_curriculum_activity_title"
                       placeholder="{{ __('Enter Title') }}"
                       value="{{ old('extra_curriculum_activity_title', $activityData->title) }}"
                       class="form-control zForm-control-alt"/>
            </div>
        @endif

        <!-- Attachment -->
        @if(getOnboardingField($formSetting, 'extra_curriculum_activity_attachment', 'show'))
            <div class="col-lg-12">
                <label for="extra_curriculum_activity_attachment" class="zForm-label-alt">
                    {{ __('Attachment (JPG, JPEG, PNG, PDF)') }}
                    @if(getOnboardingField($formSetting, 'extra_curriculum_activity_attachment', 'required'))
                        <span>*</span>
                    @endif
                    @if($activityData->attachment)
                        <small class="preview-image-div">
                            <a href="{{ getFileUrl($activityData->attachment) }}" target="_blank">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </small>
                    @endif
                </label>
                <div class="file-upload-one">
                    <label for="extra_curriculum_activity_attachment">
                        <p class="fileName fs-14 fw-500 lh-24 text-para-text">{{ __('Choose File to upload') }}</p>
                        <p class="fs-15 fw-700 lh-28 text-white">{{ __('Browse File') }}</p>
                    </label>
                    <input type="file" name="extra_curriculum_activity_attachment" id="extra_curriculum_activity_attachment"
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
                <textarea name="extra_curriculum_activity_description" id="extra_curriculum_activity_description"
                          placeholder="{{ __('Enter Description') }}"
                          class="form-control zForm-control-alt">{{ old('extra_curriculum_activity_description', $activityData->description) }}</textarea>
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
