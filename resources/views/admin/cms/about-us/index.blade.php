@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="p-sm-30 p-15">
        <div class="">
            <div class="row rg-20">
                <div class="col-xl-12">
                    <div class="align-items-center bd-b-one bd-c-one bd-c-stroke bd-c-stroke-2 d-flex justify-content-between mb-30 pb-15 pb-sm-30">
                        <h4 class="fs-18 fw-700 lh-24 text-title-text">{{$pageTitle}}</h4>
                    </div>
                    <form class="ajax" action="{{ route('admin.cms-settings.about-us.store') }}"
                          method="POST" enctype="multipart/form-data" data-handler="settingCommonHandler">
                        @csrf
                        <input type="hidden" value="{{$aboutUsData->id ?? ''}}" name="id">
                        <div class="bg-white p-sm-25 mb-20 p-15 bd-one bd-c-stroke bd-ra-8">
                            <div class="bd-c-stroke-2 d-flex justify-content-between align-items-center bd-b-one bd-c-stroke-2 pb-10">
                                <h4 class="fs-18 fw-700 lh-24 text-title-text">{{ __('About Us Leading Page') }}</h4>
                            </div>
                            <div class="row rg-20">
                                <div class="col-xxl-12 col-lg-12 pt-10">
                                    <label for="aboutUsTitle" class="zForm-label-alt">{{ __('About Us Title') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="title" id="aboutUsTitle" placeholder="{{__('About Us Title')}}" value="{{ $aboutUsData->title ?? '' }}"
                                           class="form-control zForm-control-alt">
                                </div>
                            </div>
                            <div class="row pt-10 rg-20">
                                <div class="col-xl-3 col-md-6">
                                    <div class="primary-form-group mt-3">
                                        <div class="primary-form-group-wrap zImage-upload-details mw-100">
                                            <div class="zImage-inside text-center">
                                                <div class="d-flex justify-content-center pb-12">
                                                    <img src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt="" />
                                                </div>
                                                <p class="fs-15 fw-500 lh-16 text-1b1c17">
                                                    {{ __('Drag & drop files here') }}
                                                </p>
                                            </div>
                                            <label for="bannerImage" class="zForm-label-alt">
                                                {{ __('Banner Image 1') }}
                                                <span class="text-mime-type">{{ __('(jpeg, png, jpg, svg, webp)') }}</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="upload-img-box text-center">
                                                <img src="{{getFileUrl($aboutUsData->banner_image[0] ?? '')}}" id="bannerImage" />
                                                <input type="file" name="banner_image[]" class="banner_image" id="bannerImage" accept="image/*"
                                                       onchange="previewFile(this)" />
                                            </div>
                                            <span
                                                class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 690 px / 540 px")}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="primary-form-group mt-3">
                                        <div class="primary-form-group-wrap zImage-upload-details mw-100">
                                            <div class="zImage-inside text-center">
                                                <div class="d-flex justify-content-center pb-12">
                                                    <img src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt="" />
                                                </div>
                                                <p class="fs-15 fw-500 lh-16 text-1b1c17">
                                                    {{ __('Drag & drop files here') }}
                                                </p>
                                            </div>
                                            <label for="bannerImage" class="zForm-label-alt">
                                                {{ __('Banner Image 2') }}
                                                <span class="text-mime-type">{{ __('(jpeg, png, jpg, svg, webp)') }}</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="upload-img-box text-center">
                                                <img src="{{getFileUrl($aboutUsData->banner_image[1] ?? '')}}" id="bannerImage" />
                                                <input type="file" name="banner_image[]" class="banner_image" id="bannerImage" accept="image/*"
                                                       onchange="previewFile(this)" />
                                            </div>
                                            <span
                                                class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 690 px / 540 px")}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="primary-form-group mt-3">
                                        <div class="primary-form-group-wrap zImage-upload-details mw-100">
                                            <div class="zImage-inside text-center">
                                                <div class="d-flex justify-content-center pb-12">
                                                    <img src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt="" />
                                                </div>
                                                <p class="fs-15 fw-500 lh-16 text-1b1c17">
                                                    {{ __('Drag & drop files here') }}
                                                </p>
                                            </div>
                                            <label for="bannerImage" class="zForm-label-alt">
                                                {{ __('Banner Image 3') }}
                                                <span class="text-mime-type">{{ __('(jpeg, png, jpg, svg, webp)') }}</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="upload-img-box text-center">
                                                <img src="{{getFileUrl($aboutUsData->banner_image[2] ?? '')}}" id="bannerImage" />
                                                <input type="file" name="banner_image[]" class="banner_image" id="bannerImage" accept="image/*"
                                                       onchange="previewFile(this)" />
                                            </div>
                                            <span
                                                class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 690 px / 540 px")}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="primary-form-group mt-3">
                                        <div class="primary-form-group-wrap zImage-upload-details mw-100">
                                            <div class="zImage-inside text-center">
                                                <div class="d-flex justify-content-center pb-12">
                                                    <img src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt="" />
                                                </div>
                                                <p class="fs-15 fw-500 lh-16 text-1b1c17">
                                                    {{ __('Drag & drop files here') }}
                                                </p>
                                            </div>
                                            <label for="bannerImage" class="zForm-label-alt">
                                                {{ __('Banner Image 4') }}
                                                <span class="text-mime-type">{{ __('(jpeg, png, jpg, svg, webp)') }}</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="upload-img-box text-center">
                                                <img src="{{getFileUrl($aboutUsData->banner_image[3] ?? '')}}" id="bannerImage" />
                                                <input type="file" name="banner_image[]" class="banner_image" id="bannerImage" accept="image/*"
                                                       onchange="previewFile(this)" />
                                            </div>
                                            <span
                                                class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 690 px / 540 px")}}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xxl-12 col-lg-12 pt-4">
                                    <label for="aboutUsDetails" class="zForm-label-alt">{{ __('About Us Details') }} <span
                                            class="text-danger">*</span></label>
                                    <textarea name="details" id="aboutUsDetails" class="form-control zForm-control-alt" cols="10" rows="5">{!! $aboutUsData->details ?? '' !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="p-sm-25 p-15 bd-one bd-c-stroke mb-20 bd-ra-10 bg-white">
                            <div id="about-us-point-block">
                                <div
                                    class="bd-c-stroke-2 justify-content-between align-items-center text-end d-flex justify-content-between bd-b-one pb-20">
                                    <h4 class="fs-18 fw-700 lh-24 text-title-text">{{__('About Us Details Point')}}</h4>
                                    <button type="button" class="flipBtn sf-flipBtn-primary flex-shrink-0" id="addAboutUsPoint">
                                        + {{__('Add More')}}
                                    </button>
                                </div>
                                @foreach($aboutUsData->about_us_point ?? [[]] as $index => $ourHistory)
                                    <div class="about-us-item row rg-20 bd-b-one bd-c-one bd-c-stroke-2 pb-15 mb-15 pt-20">
                                        <div class="col-md-12">
                                            <div class="image-block d-flex g-10 justify-content-between">
                                                <div class="col-md-10">
                                                    <label for="about_us_point_{{$index}}" class="zForm-label-alt">{{__('About Us Details Point')}}</label>
                                                    <input type="text" name="about_us_point[]" id="about_us_point_{{$index}}"
                                                           placeholder="{{__('Type about us details point')}}"
                                                           value="{{$ourHistory['point'] ?? ''}}"
                                                           class="form-control about_us_point zForm-control-alt">
                                                </div>
                                            @if($index > 0)
                                                <!-- Add the remove button for items other than the first one -->
                                                    <button type="button"
                                                            class="removeAboutUsPoint top-0 end-0 bg-transparent border-0 p-0 m-2 rounded-circle d-flex justify-content-center align-items-center">
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
                        <div class="p-sm-25 p-15 bd-one bd-c-stroke mb-20 bd-ra-10 bg-white">
                            <div id="our-history-block">
                                <div
                                    class="bd-c-stroke-2 justify-content-between align-items-center text-end d-flex justify-content-between bd-b-one pb-20">
                                    <h4 class="fs-18 fw-700 lh-24 text-title-text">{{__('Our History')}}</h4>
                                    <button type="button" class="flipBtn sf-flipBtn-primary flex-shrink-0" id="addOurHistory">
                                        + {{__('Add More')}}
                                    </button>
                                </div>
                                @foreach($aboutUsData->our_history ?? [[]] as $index => $ourHistory)
                                    <input type="hidden" class="old_our_history_image" name="old_our_history_image[{{$index}}]" value="{{ $ourHistory['image'] ?? '' }}">
                                    <div class="our-history-item row rg-20 bd-b-one bd-c-one bd-c-stroke-2 pb-15 mb-15 pt-20">
                                        <div class="col-xl-2 col-lg-6">
                                            <label for="our_history_year_{{$index}}" class="zForm-label-alt">{{__('Our History Year')}}</label>
                                            <input type="text" name="our_history_year[]" id="our_history_year_{{$index}}"
                                                   placeholder="{{__('Type our history year')}}"
                                                   value="{{$ourHistory['year'] ?? ''}}"
                                                   class="form-control our_history_year zForm-control-alt">
                                        </div>
                                        <div class="col-xl-2 col-lg-6">
                                            <label for="our_history_title_{{$index}}" class="zForm-label-alt">{{__('Our History Title')}}</label>
                                            <input type="text" name="our_history_title[]" id="our_history_title_{{$index}}"
                                                   placeholder="{{__('Type Our History Title')}}"
                                                   value="{{$ourHistory['title'] ?? ''}}"
                                                   class="form-control our_history_title zForm-control-alt">
                                        </div>
                                        <div class="col-xl-4 col-lg-6">
                                            <label for="our_history_description_{{$index}}" class="zForm-label-alt">{{__('Our History Description')}}</label>
                                            <input type="text" name="our_history_description[]" id="our_history_description_{{$index}}"
                                                   placeholder="{{__('Type our history description')}}"
                                                   value="{{$ourHistory['description'] ?? ''}}"
                                                   class="form-control our_history_description zForm-control-alt">
                                        </div>
                                        <div class="col-xl-2 col-lg-6">
                                            <div class="image-block d-flex g-10 justify-content-between">
                                                <div class="w-100">
                                                    <label for="our_history_image_{{$index}}" class="zForm-label-alt">{{__('Our History Image')}}
                                                        <span class="text-danger">*</span>
                                                        @if($ourHistory['image'] ?? '')
                                                            <small class="preview-image-div">
                                                                <a href="{{getFileUrl($ourHistory['image'])}}"
                                                                   target="_blank"><i class="fa-solid fa-eye"></i></a>
                                                            </small>
                                                        @endif
                                                    </label>
                                                    <div class="file-upload-one d-flex flex-column g-10 w-100">
                                                        <label for="our_history_image_{{$index}}">
                                                            <p class="fileName fs-12 fw-500 lh-16 text-para-text">{{__('Choose Image to upload')}}</p>
                                                            <p class="fs-12 fw-500 lh-16 text-white">{{__('Browse File')}}</p>
                                                        </label>
                                                        <span
                                                            class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 434 px / 413 px")}}</span>
                                                        <div class="max-w-150 flex-shrink-0">
                                                            <input type="file" name="our_history_image[]" id="our_history_image_{{$index}}"
                                                                   accept="image/*"
                                                                   class="fileUploadInput our_history_image position-absolute invisible"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            @if($index > 0)
                                                <!-- Add the remove button for items other than the first one -->
                                                    <button type="button"
                                                            class="removeOurHistory top-0 end-0 bg-transparent border-0 p-0 m-2 rounded-circle d-flex justify-content-center align-items-center">
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
                        <div class="bg-white p-sm-25 mb-20 p-15 bd-one bd-c-stroke bd-ra-8">
                            <div class="bd-c-stroke-2 d-flex justify-content-between align-items-center bd-b-one bd-c-stroke-2 pb-10">
                                <h4 class="fs-18 fw-700 lh-24 text-title-text">{{ __('About Us Our Mission') }}</h4>
                            </div>
                            <div class="row rg-20">
                                <div class="col-xxl-12 col-lg-12 pt-10">
                                    <label for="ourMissionTitle" class="zForm-label-alt">{{ __('Our Mission Title') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="our_mission_title" id="ourMissionTitle" placeholder="{{__('Our Mission Title')}}" value="{{$aboutUsData->our_mission_title ?? ''}}"
                                           class="form-control zForm-control-alt">
                                </div>
                            </div>
                            <div class="row pt-10 rg-20">
                                <div class="col-xxl-6">
                                    <div class="primary-form-group">
                                        <div class="primary-form-group-wrap zImage-upload-details mw-100">
                                            <div class="zImage-inside text-center">
                                                <div class="d-flex justify-content-center pb-12">
                                                    <img src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt="" />
                                                </div>
                                                <p class="fs-15 fw-500 lh-16 text-1b1c17">
                                                    {{ __('Drag & drop files here') }}
                                                </p>
                                            </div>
                                            <label for="ourMissionImage" class="zForm-label-alt">
                                                {{ __('Our Mission Image') }}
                                                <span class="text-mime-type">{{ __('(jpeg, png, jpg, svg, webp)') }}</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="upload-img-box text-center">
                                                <img src="{{getFileUrl($aboutUsData->our_mission_image ?? '')}}" id="ourMissionImage" />
                                                <input type="file" name="our_mission_image" id="ourMissionImage" accept="image/*"
                                                       onchange="previewFile(this)" />
                                            </div>
                                            <span
                                                class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 580 px / 420 px")}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-6">
                                    <label for="ourMissionDetails" class="zForm-label-alt">{{ __('Our Mission Details') }} <span
                                            class="text-danger">*</span></label>
                                    <textarea name="our_mission_details" id="ourMissionDetails" class="form-control zForm-control-alt summernoteOne">{!! $aboutUsData->our_mission_details ?? '' !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white p-sm-25 mb-20 p-15 bd-one bd-c-stroke bd-ra-8">
                            <div class="bd-c-stroke-2 d-flex justify-content-between align-items-center bd-b-one bd-c-stroke-2 pb-10">
                                <h4 class="fs-18 fw-700 lh-24 text-title-text">{{ __('About Us Our Vision') }}</h4>
                            </div>
                            <div class="row rg-20">
                                <div class="col-xxl-12 col-lg-12 pt-10">
                                    <label for="aboutUsOurVisionTitle" class="zForm-label-alt">{{ __('Our Vision Title') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="our_vision_title" id="aboutUsOurVisionTitle" placeholder="{{__('Our Vision Title')}}" value="{{$aboutUsData->our_vision_title ?? ''}}"
                                           class="form-control zForm-control-alt">
                                </div>
                            </div>
                            <div class="row pt-10">
                                <div class="col-xxl-6">
                                    <div class="primary-form-group">
                                        <div class="primary-form-group-wrap zImage-upload-details mw-100">
                                            <div class="zImage-inside text-center">
                                                <div class="d-flex justify-content-center pb-12">
                                                    <img src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt="" />
                                                </div>
                                                <p class="fs-15 fw-500 lh-16 text-1b1c17">
                                                    {{ __('Drag & drop files here') }}
                                                </p>
                                            </div>
                                            <label for="bannerImage" class="zForm-label-alt">
                                                {{ __('Our Vision Image') }}
                                                <span class="text-mime-type">{{ __('(jpeg, png, jpg, svg, webp)') }}</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="upload-img-box text-center">
                                                <img src="{{getFileUrl($aboutUsData->our_vision_image ?? '')}}" id="aboutUsOurVisionImage" />
                                                <input type="file" name="our_vision_image" id="aboutUsOurVisionImage" accept="image/*"
                                                       onchange="previewFile(this)" />
                                            </div>
                                            <span
                                                class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 580 px / 420 px")}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-6">
                                    <label for="aboutUsOurVisionDetails" class="zForm-label-alt">{{ __('Our Vision Details') }} <span
                                            class="text-danger">*</span></label>
                                    <textarea name="our_vision_details" id="aboutUsOurVisionDetails" class="form-control zForm-control-alt summernoteOne">{!! $aboutUsData->our_vision_details ?? '' !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white p-sm-25 mb-20 p-15 bd-one bd-c-stroke bd-ra-8">
                            <div class="bd-c-stroke-2 d-flex justify-content-between align-items-center bd-b-one bd-c-stroke-2 pb-10">
                                <h4 class="fs-18 fw-700 lh-24 text-title-text">{{ __('About Us Our Goal') }}</h4>
                            </div>
                            <div class="row rg-20">
                                <div class="col-xxl-12 col-lg-12 pt-10">
                                    <label for="aboutUsOurGoalTitle" class="zForm-label-alt">{{ __('Our Goal Title') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="our_goal_title" id="aboutUsOurGoalTitle" placeholder="{{__('Our Goal Title')}}" value="{{ $aboutUsData->our_goal_title ?? '' }}"
                                           class="form-control zForm-control-alt">
                                </div>
                            </div>
                            <div class="row pt-10 rg-20">
                                <div class="col-xxl-6">
                                    <div class="primary-form-group">
                                        <div class="primary-form-group-wrap zImage-upload-details mw-100">
                                            <div class="zImage-inside text-center">
                                                <div class="d-flex justify-content-center pb-12">
                                                    <img src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt="" />
                                                </div>
                                                <p class="fs-15 fw-500 lh-16 text-1b1c17">
                                                    {{ __('Drag & drop files here') }}
                                                </p>
                                            </div>
                                            <label for="aboutUsOurGoalImage" class="zForm-label-alt">
                                                {{ __('Our Goal Image') }}
                                                <span class="text-mime-type">{{ __('(jpeg, png, jpg, svg, webp)') }}</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="upload-img-box text-center">
                                                <img src="{{ getFileUrl($aboutUsData->our_goal_image ?? '') }}" id="aboutUsOurGoalImage" />
                                                <input type="file" name="our_goal_image" id="aboutUsOurGoalImage" accept="image/*"
                                                       onchange="previewFile(this)" />
                                            </div>
                                            <span
                                                class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 580 px / 420 px")}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-6">
                                    <label for="aboutUsOurGoalDetails" class="zForm-label-alt">{{ __('Our Goal Details') }} <span
                                            class="text-danger">*</span></label>
                                    <textarea name="our_goal_details" id="aboutUsOurGoalDetails" class="form-control zForm-control-alt summernoteOne">{!! $aboutUsData->our_goal_details ?? '' !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="p-sm-25 p-15 bd-one bd-c-stroke mb-20 bd-ra-10 bg-white">
                            <div id="awards-block">
                                <div
                                    class="bd-c-stroke-2 justify-content-between align-items-center text-end d-flex justify-content-between pb-10">
                                    <h4 class="fs-18 fw-700 lh-24 text-title-text">{{__('Awards')}}</h4>
                                    <button type="button" class="flipBtn sf-flipBtn-primary flex-shrink-0" id="addAwards">
                                        + {{__('Add More')}}
                                    </button>
                                </div>
                                @foreach($aboutUsData->awards ?? [[]] as $index => $award)
                                    <input type="hidden" class="old_awards_image" name="old_awards_image[{{$index}}]" value="{{ $award['image'] ?? '' }}">
                                    <div class="awards-item row rg-20 bd-b-one bd-c-one bd-c-stroke-2 pb-15 mb-15">
                                        <div class="col-md-6">
                                            <label for="awards_title_{{$index}}" class="zForm-label-alt">{{__('Awards Title')}}</label>
                                            <input type="text" name="awards_title[]" id="awards_title_{{$index}}"
                                                   placeholder="{{__('Type Awards Title')}}"
                                                   value="{{$award['name'] ?? ''}}"
                                                   class="form-control awards_title zForm-control-alt">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="image-block d-flex g-10 justify-content-between">
                                                <div class="w-100">
                                                    <label for="awards_image_{{$index}}" class="zForm-label-alt">{{__('Awards Image')}}
                                                        <span class="text-danger">*</span>
                                                        @if($award['image'] ?? '')
                                                            <small class="preview-image-div">
                                                                <a href="{{getFileUrl($award['image'])}}"
                                                                   target="_blank"><i class="fa-solid fa-eye"></i></a>
                                                            </small>
                                                        @endif
                                                        {{__('(jpeg,png,jpg,svg,webp)')}}
                                                    </label>
                                                    <div class="file-upload-one d-flex flex-column g-10 w-100">
                                                        <label for="awards_image_{{$index}}">
                                                            <p class="fileName fs-12 fw-500 lh-16 text-para-text">{{__('Choose Image to upload')}}</p>
                                                            <p class="fs-12 fw-500 lh-16 text-white">{{__('Browse File')}}</p>
                                                        </label>
                                                        <span
                                                            class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 600 px / 400 px")}}</span>
                                                        <div class="max-w-150 flex-shrink-0">
                                                            <input type="file" name="awards_image[]" id="awards_image_{{$index}}"
                                                                   accept="image/*"
                                                                   class="fileUploadInput awards_image position-absolute invisible"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            @if($index > 0)
                                                <!-- Add the remove button for items other than the first one -->
                                                    <button type="button"
                                                            class="removeAwards top-0 end-0 bg-transparent border-0 p-0 m-2 rounded-circle d-flex justify-content-center align-items-center">
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
                        <div class="bd-c-stroke-2 justify-content-between align-items-center text-end pt-15">
                            <button type="submit" class="flipBtn sf-flipBtn-primary flex-shrink-0">{{__('Submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{asset('admin/custom/js/cms/about-us.js')}}"></script>
@endpush
