<div class="p-sm-25 p-15 mb-20 bd-one bd-c-stroke bd-ra-10 bg-white">
    <div class="row rg-20">
        <div class="col-md-4">
            <label for="title" class="zForm-label-alt">{{ __('Title') }} <span class="text-danger">*</span></label>
            <input type="text" name="title" id="title" placeholder="{{ __('Type Title') }}"
                   value="{{ $course->title ?? '' }}" class="form-control zForm-control-alt">
        </div>
        <div class="col-md-4">
            <label for="program_id" class="zForm-label-alt">{{ __('Program') }} <span
                    class="text-danger">*</span></label>
            <select class="sf-select-two" name="program_id" id="program_id">
                @foreach($programs as $program)
                    <option
                        value="{{$program->id}}" {{ (isset($course) && $course->program_id == $program->id) ? 'selected' : '' }}>{{$program->title}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label for="duration" class="zForm-label-alt">{{ __('Duration (Days)') }} <span class="text-danger">*</span></label>
            <input type="number" name="duration" id="duration" placeholder="{{ __('Type Duration') }}"
                   value="{{ $course->duration ?? '' }}" class="form-control zForm-control-alt">
        </div>
        <div class="col-md-4">
            <label for="start_date" class="zForm-label-alt">{{ __('Start Date') }}</label>
            <input type="date" name="start_date" id="start_date" placeholder="{{ __('Start Date') }}"
                   value="{{ isset($course->start_date) ? $course->start_date?->format('Y-m-d') : '' }}" class="form-control zForm-control-alt">
        </div>
        <div class="col-md-4">
            <label for="price" class="zForm-label-alt">{{ __('Price') }}<span class="text-danger">*</span></label>
            <input type="number" min=1 name="price" id="price" placeholder="{{ __('Price') }}" min="0"
                   value="{{ $course->price ?? 0 }}" class="form-control zForm-control-alt">
        </div>
        <div class="col-md-4">
            <label for="status" class="zForm-label-alt">{{ __('Status') }} <span class="text-danger">*</span></label>
            <select class="sf-select-without-search" id="status" name="status">
                <option
                    value="{{ STATUS_ACTIVE }}" {{ (isset($course) && $course->status == STATUS_ACTIVE) ? 'selected' : '' }}>{{ __('Active') }}</option>
                <option
                    value="{{ STATUS_DEACTIVATE }}" {{ (isset($course) && $course->status == STATUS_DEACTIVATE) ? 'selected' : '' }}>{{ __('Inactive') }}</option>
            </select>
        </div>
    </div>
</div>

<h4 class="mb-15 fs-18 fw-700 lh-24 text-title-text">{{__('Course Details')}}</h4>
<div class="p-sm-25 p-15 bd-one bd-c-stroke mb-20 bd-ra-10 bg-white">
    <div class="row rg-20">
        <div class="col-md-6">
            <label for="subtitle" class="zForm-label-alt">{{ __('Subtitle') }}</label>
            <textarea class="form-control zForm-control-alt" name="subtitle" id="subtitle"
                      placeholder="{{ __('Sub Title') }}">{{ $course->subtitle ?? '' }}</textarea>
        </div>
        <div class="col-md-6">
            <div class="primary-form-group mt-3">
                <div class="primary-form-group-wrap zImage-upload-details mw-100">
                    <div class="zImage-inside text-center">
                        <div class="d-flex justify-content-center pb-12">
                            <img src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt=""/>
                        </div>
                        <p class="fs-15 fw-500 lh-16 text-1b1c17">
                            {{ __('Drag & drop files here') }}
                        </p>
                    </div>
                    <label for="thumbnailImage" class="zForm-label-alt">
                        {{ __('Thumbnail Image') }}
                        <span class="text-mime-type">{{ __('(jpeg,png,jpg,svg,webp)') }}</span>
                        <span class="text-danger">*</span>
                    </label>
                    <div class="upload-img-box text-center">
                        <img src="{{ (isset($course) && !is_null($course->thumbnail)) ? getFileUrl($course->thumbnail) : '' }}" id="thumbnailImage"/>
                        <input type="file" name="thumbnail" id="thumbnailImage"
                               accept="image/*"
                               onchange="previewFile(this)"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <label for="intro_video_type" class="zForm-label-alt">{{ __('Intro Video Type') }}</label>
            <select class="sf-select-without-search" id="intro_video_type" name="intro_video_type">
                <option
                    value="{{ RESOURCE_TYPE_LOCAL }}" {{ ($course->intro_video_type ?? '') == RESOURCE_TYPE_LOCAL ? 'selected' : '' }}>{{ __('Local File') }}</option>
                <option
                    value="{{ RESOURCE_TYPE_YOUTUBE_ID }}" {{ ($course->intro_video_type ?? '') == RESOURCE_TYPE_YOUTUBE_ID ? 'selected' : '' }}>{{ __('YouTube ID') }}</option>
            </select>
        </div>
        <div class="col-md-6 {{ isset($course) && ($course->intro_video_type ?? '') != RESOURCE_TYPE_LOCAL ? 'd-none' : '' }}" id="video-type-local">
            <label for="intro_file" class="zForm-label-alt">{{ __('File') }}
                <span class="text-danger">*</span>
                @if(isset($course->intro_video) && $course->intro_video_type == RESOURCE_TYPE_LOCAL)
                    <small class="preview-image-div">
                        <a href="{{ getFileUrl($course->intro_video) }}" target="_blank"><i class="fa-solid fa-eye"></i></a>
                    </small>
                @endif
            </label>

            <div class="file-upload-one d-flex flex-column g-10 w-100">
                <label for="intro_file">
                    <p class="fileName fs-12 fw-500 lh-16 text-para-text">{{__('Choose Video to upload')}}</p>
                    <p class="fs-12 fw-500 lh-16 text-white">{{__('Browse File')}}</p>
                </label>
                <span class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__('Recommended: (mp4, avi, mkv, webm)')}}</span>
                <div class="max-w-150 flex-shrink-0">
                    <input type="file" name="intro_file" id="intro_file" accept="video/*" class="fileUploadInput position-absolute invisible"/>
                </div>
            </div>
        </div>

        <div class="col-md-6 {{($course->intro_video_type ?? '') == RESOURCE_TYPE_YOUTUBE_ID ? '' : 'd-none'}}"
             id="video-type-youtube-id">
            <label for="youtube_id" class="zForm-label-alt">{{ __('YouTube ID') }}</label>
            <input type="text" name="youtube_id" id="youtube_id" placeholder="{{ __('Type YouTube Video ID') }}"
                   value="{{ $course->intro_video ?? '' }}" class="form-control zForm-control-alt">
        </div>
    </div>
</div>

<h4 class="mb-15 fs-18 fw-700 lh-24 text-title-text">{{__('Description')}}</h4>
<div class="p-sm-25 p-15 bd-one bd-c-stroke mb-20 bd-ra-10 bg-white">
    <div class="pb-20 rg-20 row">
        <div class="col-md-12">
            <label for="description" class="zForm-label-alt">{{ __('Description') }}</label>
            <textarea class="form-control zForm-control-alt" name="description" id="description"
                      placeholder="{{__('Description')}}">{{ $course->description ?? '' }}</textarea>
        </div>
    </div>
    <div id="learn-point-block" class="bd-c-stroke-2 bd-one p-20 rounded mb-20">
        <div class="d-flex justify-content-between g-10 flex-wrap pb-10">
            <h5 class="mb-15 fw-700 lh-24 text-title-text">{{__('What You Will Learn Point?')}}</h5>
            <button type="button" class="flex-shrink-0 align-self-start px-11 py-1 flipBtn sf-flipBtn-primary" id="addLearnPoint">
                + Add More
            </button>
        </div>
        @foreach($course->description_point ?? [[]] as $index => $point)
            <div class="learn-point-item row rg-20 bd-b-one bd-c-one bd-c-stroke-2 pb-15 mb-15">
                <div class="col-md-6">
                    <label for="learn_point_title_{{$index}}" class="zForm-label-alt">{{__('Point Title')}} <span
                            class="text-danger">*</span></label>
                    <input type="text" name="learn_point_title[]" id="learn_point_title_{{$index}}"
                           placeholder="{{__('Type Point Title')}}" value="{{ $point['title'] ?? '' }}"
                           class="form-control learn_point_title zForm-control-alt">
                </div>
                <div class="align-items-end text-block col-md-6 d-flex g-10 justify-content-between">
                    <div class="w-100">
                        <label for="learn_point_text_{{$index}}" class="zForm-label-alt">{{__('Point Text')}} <span
                                class="text-danger">*</span></label>
                        <input type="text" name="learn_point_text[]" id="learn_point_text_{{$index}}"
                               placeholder="{{__('Type Point Text')}}" value="{{ $point['text'] ?? '' }}"
                               class="form-control learn_point_text zForm-control-alt">
                    </div>
                    @if($index > 0)
                        <!-- Add the remove button for items other than the first one -->
                        <button type="button"
                                class="removeLearnPoint top-0 end-0 bg-transparent border-0 p-0 m-2 rounded-circle d-flex justify-content-center align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                 fill="none">
                                <path
                                    d="M16.25 4.58334L15.7336 12.9376C15.6016 15.072 15.5357 16.1393 15.0007 16.9066C14.7361 17.2859 14.3956 17.6061 14.0006 17.8467C13.2017 18.3333 12.1325 18.3333 9.99392 18.3333C7.8526 18.3333 6.78192 18.3333 5.98254 17.8458C5.58733 17.6048 5.24667 17.284 4.98223 16.904C4.4474 16.1355 4.38287 15.0668 4.25384 12.9293L3.75 4.58334"
                                    stroke="#EF4444" stroke-width="1.5" stroke-linecap="round"/>
                                <path
                                    d="M2.5 4.58333H17.5M13.3797 4.58333L12.8109 3.40977C12.433 2.63021 12.244 2.24043 11.9181 1.99734C11.8458 1.94341 11.7693 1.89545 11.6892 1.85391C11.3283 1.66666 10.8951 1.66666 10.0287 1.66666C9.14067 1.66666 8.69667 1.66666 8.32973 1.86176C8.24842 1.90501 8.17082 1.95491 8.09774 2.01097C7.76803 2.26391 7.58386 2.66796 7.21551 3.47605L6.71077 4.58333"
                                    stroke="#EF4444" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M7.91669 13.75V8.75" stroke="#EF4444" stroke-width="1.5"
                                      stroke-linecap="round"/>
                                <path d="M12.0833 13.75V8.75" stroke="#EF4444" stroke-width="1.5"
                                      stroke-linecap="round"/>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div id="benefit-block" class="bd-c-stroke-2 bd-one p-20 rounded">
        <div class="d-flex justify-content-between g-10 flex-wrap pb-10">
            <h5 class="mb-15 fw-700 lh-24 text-title-text">{{__('Benefits')}}</h5>
            <button type="button" class="flex-shrink-0 align-self-start px-11 py-1 flipBtn sf-flipBtn-primary" id="addBenefit">
                + Add More
            </button>
        </div>
        @foreach($course->course_benefits ?? [[]] as $index => $benefit)
            <div class="benefit-item row rg-20 bd-b-one bd-c-one bd-c-stroke-2 pb-15 mb-15">
                <div class="col-md-6">
                    <label for="benefit_name_{{$index}}" class="zForm-label-alt">{{__('Name')}} <span
                            class="text-danger">*</span></label>
                    <input type="text" name="benefit_name[]" id="benefit_name_{{$index}}"
                           placeholder="{{__('Type Name')}}" value="{{ $benefit['name'] ?? '' }}"
                           class="form-control benefit_name zForm-control-alt">
                </div>
                <div class="col-md-6 d-flex align-items-end value-block g-10 justify-content-between">
                    <div class="w-100">
                        <label for="benefit_value_{{$index}}" class="zForm-label-alt">{{__('Value')}} <span
                                class="text-danger">*</span></label>
                        <input type="text" name="benefit_value[]" id="benefit_value_{{$index}}"
                               placeholder="{{__('Type Value')}}" value="{{ $benefit['value'] ?? '' }}"
                               class="form-control benefit_value zForm-control-alt">
                    </div>
                    @if($index > 0)
                        <!-- Add the remove button for items other than the first one -->
                        <button type="button"
                                class="removeBenefit top-0 end-0 bg-transparent border-0 p-0 m-2 rounded-circle d-flex justify-content-center align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                 fill="none">
                                <path
                                    d="M16.25 4.58334L15.7336 12.9376C15.6016 15.072 15.5357 16.1393 15.0007 16.9066C14.7361 17.2859 14.3956 17.6061 14.0006 17.8467C13.2017 18.3333 12.1325 18.3333 9.99392 18.3333C7.8526 18.3333 6.78192 18.3333 5.98254 17.8458C5.58733 17.6048 5.24667 17.284 4.98223 16.904C4.4474 16.1355 4.38287 15.0668 4.25384 12.9293L3.75 4.58334"
                                    stroke="#EF4444" stroke-width="1.5" stroke-linecap="round"/>
                                <path
                                    d="M2.5 4.58333H17.5M13.3797 4.58333L12.8109 3.40977C12.433 2.63021 12.244 2.24043 11.9181 1.99734C11.8458 1.94341 11.7693 1.89545 11.6892 1.85391C11.3283 1.66666 10.8951 1.66666 10.0287 1.66666C9.14067 1.66666 8.69667 1.66666 8.32973 1.86176C8.24842 1.90501 8.17082 1.95491 8.09774 2.01097C7.76803 2.26391 7.58386 2.66796 7.21551 3.47605L6.71077 4.58333"
                                    stroke="#EF4444" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M7.91669 13.75V8.75" stroke="#EF4444" stroke-width="1.5"
                                      stroke-linecap="round"/>
                                <path d="M12.0833 13.75V8.75" stroke="#EF4444" stroke-width="1.5"
                                      stroke-linecap="round"/>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
<h4 class="mb-15 fs-18 fw-700 lh-24 text-title-text">{{__('Instructors')}}</h4>
<div class="p-sm-25 p-15 bd-one bd-c-stroke mb-20 bd-ra-10 bg-white">
    <div id="instructor-block">
        <div class="bd-c-stroke-2 d-flex justify-content-end">
            <button type="button" class="flex-shrink-0 px-11 py-1 flipBtn sf-flipBtn-primary" id="addInstructor">
                + {{__('Add More')}}
            </button>
        </div>
        @foreach($course->instructors ?? [[]] as $index => $instructor)
            <input type="hidden" class="old_instructor_photo" name="old_instructor_photo[{{$index}}]" value="{{ $instructor['photo'] ?? '' }}">
            <div class="instructor-item row rg-20 bd-b-one bd-c-one bd-c-stroke-2 pb-15 mb-15">
                <div class="col-md-4">
                    <label for="instructor_name_{{$index}}" class="zForm-label-alt">{{__('Name')}}</label>
                    <input type="text" name="instructor_name[]" id="instructor_name_{{$index}}"
                           placeholder="{{__('Type Name')}}"
                           value="{{$instructor['name'] ?? ''}}"
                           class="form-control instructor_name zForm-control-alt">
                </div>
                <div class="col-md-4">
                    <label for="instructor_professional_title_{{$index}}"
                           class="zForm-label-alt">{{__('Professional Title')}}</label>
                    <input type="text" name="instructor_professional_title[]"
                           id="instructor_professional_title_{{$index}}"
                           placeholder="{{__('Professional Title')}}"
                           value="{{$instructor['professional_title'] ?? ''}}"
                           class="form-control instructor_professional_title zForm-control-alt">
                </div>
                <div class="col-md-4">
                    <div class="photo-block d-flex g-10 justify-content-between">
                        <div class="w-100">
                            <label for="instructor_photo_{{$index}}" class="zForm-label-alt">{{__('Photo')}}
                                <span class="text-danger">*</span>
                                @if($instructor['photo'] ?? '')
                                    <small class="preview-image-div">
                                        <a href="{{getFileUrl($instructor['photo'])}}"
                                           target="_blank"><i class="fa-solid fa-eye"></i></a>
                                    </small>
                                @endif
                            </label>
                            <div class="file-upload-one d-flex flex-column g-10 w-100">
                                <label for="instructor_photo_{{$index}}">
                                    <p class="fileName fs-12 fw-500 lh-16 text-para-text">{{__('Choose Image to upload')}}</p>
                                    <p class="fs-12 fw-500 lh-16 text-white">{{__('Browse File')}}</p>
                                </label>
                                <span
                                    class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__('Recommended: (jpeg,png,jpg,svg,webp)')}}</span>
                                <div class="max-w-150 flex-shrink-0">
                                    <input type="file" name="instructor_photo[]" id="instructor_photo_{{$index}}"
                                           accept="image/*"
                                           class="fileUploadInput instructor_photo position-absolute invisible"/>
                                </div>
                            </div>
                        </div>
                        @if($index > 0)
                            <!-- Add the remove button for items other than the first one -->
                            <button type="button"
                                    class="removeInstructor top-0 end-0 bg-transparent border-0 p-0 m-2 rounded-circle d-flex justify-content-center align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                     fill="none">
                                    <path
                                        d="M16.25 4.58334L15.7336 12.9376C15.6016 15.072 15.5357 16.1393 15.0007 16.9066C14.7361 17.2859 14.3956 17.6061 14.0006 17.8467C13.2017 18.3333 12.1325 18.3333 9.99392 18.3333C7.8526 18.3333 6.78192 18.3333 5.98254 17.8458C5.58733 17.6048 5.24667 17.284 4.98223 16.904C4.4474 16.1355 4.38287 15.0668 4.25384 12.9293L3.75 4.58334"
                                        stroke="#EF4444" stroke-width="1.5" stroke-linecap="round"/>
                                    <path
                                        d="M2.5 4.58333H17.5M13.3797 4.58333L12.8109 3.40977C12.433 2.63021 12.244 2.24043 11.9181 1.99734C11.8458 1.94341 11.7693 1.89545 11.6892 1.85391C11.3283 1.66666 10.8951 1.66666 10.0287 1.66666C9.14067 1.66666 8.69667 1.66666 8.32973 1.86176C8.24842 1.90501 8.17082 1.95491 8.09774 2.01097C7.76803 2.26391 7.58386 2.66796 7.21551 3.47605L6.71077 4.58333"
                                        stroke="#EF4444" stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M7.91669 13.75V8.75" stroke="#EF4444" stroke-width="1.5"
                                          stroke-linecap="round"/>
                                    <path d="M12.0833 13.75V8.75" stroke="#EF4444" stroke-width="1.5"
                                          stroke-linecap="round"/>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<h4 class="mb-15 fs-18 fw-700 lh-24 text-title-text">{{__('Course Faqs')}}</h4>
<div class="p-sm-25 p-15 bd-one bd-c-stroke mb-20 bd-ra-10 bg-white">
    <div id="faq-block">
        <div class="bd-c-stroke-2 d-flex justify-content-end">
            <button type="button" class="flex-shrink-0 px-11 py-1 flipBtn sf-flipBtn-primary" id="addFaq">
                + {{__('Add More')}}
            </button>
        </div>
        @foreach($course->faqs ?? [[]] as $index => $faq)
            <div class="faq-item row rg-20 bd-b-one bd-c-one bd-c-stroke-2 pb-15 mb-15">
                <div class="col-md-6">
                    <label for="faq_question_{{$index}}" class="zForm-label-alt">{{__('Question')}} <span
                            class="text-danger">*</span></label>
                    <input type="text" name="faq_question[]" id="faq_question_{{$index}}"
                           placeholder="{{__('Type Question')}}"
                           value="{{ $faq['question'] ?? '' }}"
                           class="form-control faq_question zForm-control-alt">
                </div>
                <div class="align-items-end answer-block col-md-6 d-flex g-10 justify-content-between position-relative">
                    <div class="w-100">
                        <label for="faq_answer_{{$index}}" class="zForm-label-alt">{{__('Answer')}} <span
                                class="text-danger">*</span></label>
                        <input type="text" name="faq_answer[]" id="faq_answer_{{$index}}"
                               placeholder="{{__('Type Answer')}}"
                               value="{{ $faq['answer'] ?? '' }}"
                               class="form-control faq_answer zForm-control-alt">
                    </div>
                    @if($index > 0)
                        <!-- Add the remove button for items other than the first one -->
                        <button type="button"
                                class="removeFaq top-0 end-0 bg-transparent border-0 p-0 m-2 rounded-circle d-flex justify-content-center align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                 fill="none">
                                <path
                                    d="M16.25 4.58334L15.7336 12.9376C15.6016 15.072 15.5357 16.1393 15.0007 16.9066C14.7361 17.2859 14.3956 17.6061 14.0006 17.8467C13.2017 18.3333 12.1325 18.3333 9.99392 18.3333C7.8526 18.3333 6.78192 18.3333 5.98254 17.8458C5.58733 17.6048 5.24667 17.284 4.98223 16.904C4.4474 16.1355 4.38287 15.0668 4.25384 12.9293L3.75 4.58334"
                                    stroke="#EF4444" stroke-width="1.5" stroke-linecap="round"/>
                                <path
                                    d="M2.5 4.58333H17.5M13.3797 4.58333L12.8109 3.40977C12.433 2.63021 12.244 2.24043 11.9181 1.99734C11.8458 1.94341 11.7693 1.89545 11.6892 1.85391C11.3283 1.66666 10.8951 1.66666 10.0287 1.66666C9.14067 1.66666 8.69667 1.66666 8.32973 1.86176C8.24842 1.90501 8.17082 1.95491 8.09774 2.01097C7.76803 2.26391 7.58386 2.66796 7.21551 3.47605L6.71077 4.58333"
                                    stroke="#EF4444" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M7.91669 13.75V8.75" stroke="#EF4444" stroke-width="1.5"
                                      stroke-linecap="round"/>
                                <path d="M12.0833 13.75V8.75" stroke="#EF4444" stroke-width="1.5"
                                      stroke-linecap="round"/>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
