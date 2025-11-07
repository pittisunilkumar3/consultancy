@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="p-sm-30 p-15">
        <div class="row rg-20">
            <div class="">
                <div
                    class="align-items-center bd-b-one bd-c-one bd-c-stroke bd-c-stroke-2 d-flex justify-content-between mb-30 pb-15 pb-sm-30">
                    <h4 class="fs-18 fw-700 lh-24 text-title-text">{{$pageTitle}}</h4>
                    <a href="{{route('admin.cms-settings.countries.index')}}" class="flipBtn sf-flipBtn-secondary flex-shrink-0">{{__('Back')}}</a>
                </div>
                <form class="ajax reset" action="{{ route('admin.cms-settings.countries.store') }}" method="post"
                      data-handler="commonResponseRedirect" data-redirect-url="{{route('admin.cms-settings.countries.index')}}">
                    @csrf
                    <div class="p-sm-25 mb-20 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
                        <div class="row rg-20">
                            <div class="col-md-6">
                                <label for="symbol" class="zForm-label-alt">{{ __('Name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" placeholder="{{ __('Type Name') }}"
                                       class="form-control zForm-control-alt">
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="zForm-label-alt">{{ __('Status') }}
                                    <span class="text-danger">*</span></label>
                                <select class="sf-select-without-search" id="eventType" name="status">
                                    <option value="{{STATUS_ACTIVE}}">{{ __('Active') }}</option>
                                    <option value="{{STATUS_DEACTIVATE}}">{{ __('Deactivate') }}</option>
                                </select>
                            </div>
                            <div class="">
                                <label for="details" class="zForm-label-alt">{{ __('Details') }} <span
                                        class="text-danger">*</span></label>
                                <textarea class="summernoteOne" name="details" id="details"
                                          placeholder="{{__('Details')}}"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="p-sm-25 mb-20 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
                        <div class="core-benefits-block">
                            <div
                                class="bd-c-stroke-2 justify-content-between align-items-center pt-15 d-flex justify-content-between rg-20 flex-wrap">
                                <h4 class="fs-18 fw-700 lh-24">{{__('Core Benefits Section')}}</h4>
                                <button type="button" class="flipBtn sf-flipBtn-primary flex-shrink-0 addCountryCoreBenefitsBtn">
                                    {{ __('+ Add More') }}
                                </button>
                            </div>
                            <div class="countryCoreBenefitsItems row rg-20 bd-b-one bd-c-one bd-c-stroke-2 pb-15">
                                <div class="col-md-6 pt-14">
                                    <label for="symbol" class="zForm-label-alt">{{ __('Core Benefits Title') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="core_benefits_title[]" id="core_benefits_title-0"
                                           placeholder="{{ __('Type Core Benefits Title') }}"
                                           class="form-control zForm-control-alt core_benefits_title">
                                </div>
                                <div class="col-md-6">
                                    <div class="primary-form-group  d-flex justify-content-between">
                                        <div class="primary-form-group-wrap zImage-upload-details mw-100 mt-12">
                                            <label for="coreBenefitsIcon-0"
                                                   class="zForm-label-alt">{{ __('Core Benefits Icon') }} <span
                                                    class="text-mime-type">{{__('(jpeg,png,jpg,svg,webp)')}}</span> <span
                                                    class="text-danger">*</span></label>
                                            <div class="d-flex align-items-center g-10">
                                                <div class="file-upload-one d-flex flex-column g-10 w-100">
                                                    <label for="coreBenefitsIcon-0">
                                                        <p class="fileName fs-12 fw-500 lh-16 text-para-text">{{__("Choose Image to upload")}}</p>
                                                        <p class="fs-12 fw-500 lh-16 text-white">{{__("Browse File")}}</p>
                                                    </label>
                                                    <span
                                                        class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 56 px / 56 px")}}</span>
                                                    <div class="max-w-150 flex-shrink-0">
                                                        <input type="file" name="core_benefits_icon[]"
                                                               id="coreBenefitsIcon-0" accept="image/*"
                                                               class="fileUploadInput position-absolute invisible core_benefits_icon"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-sm-25 mb-20 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
                        <div
                            class="bd-c-stroke-2 justify-content-between align-items-center pt-15 d-flex justify-content-between rg-20 flex-wrap">
                            <h4 class="fs-18 fw-700 lh-24">{{__('Image Gallery Section')}}<span
                                    class="text-mime-type zForm-label-alt">{{__(' (jpeg,png,jpg,svg,webp)')}}</span></h4>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-6">
                                <div class="primary-form-group">
                                    <div class="primary-form-group-wrap zImage-upload-details mw-100 mt-12">
                                        <div class="zImage-inside">
                                            <div class="d-flex pb-12"><img
                                                    src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt=""/>
                                            </div>
                                            <p class="fs-15 fw-500 lh-16 text-1b1c17">{{ __('Drag & drop files here') }}
                                            </p>
                                        </div>
                                        <label for="galleryImage1" class="zForm-label-alt">{{ __('Gallery Image 1') }} <span
                                                class="text-danger">*</span></label>
                                        <div class="upload-img-box">
                                            <img src=""/>
                                            <input type="file" name="gallery_image[]" class="gallery_image"
                                                   id="galleryImage1" accept="image/*"
                                                   onchange="previewFile(this)"/>
                                        </div>
                                        <span class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 650 px / 300 px")}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="primary-form-group">
                                    <div class="primary-form-group-wrap zImage-upload-details mw-100 mt-12">
                                        <div class="zImage-inside">
                                            <div class="d-flex pb-12"><img
                                                    src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt=""/>
                                            </div>
                                            <p class="fs-15 fw-500 lh-16 text-1b1c17">{{ __('Drag & drop files here') }}
                                            </p>
                                        </div>
                                        <label for="galleryImage2" class="zForm-label-alt">{{ __('Gallery Image 2') }} <span
                                                class="text-danger">*</span></label>
                                        <div class="upload-img-box">
                                            <img src=""/>
                                            <input type="file" name="gallery_image[]" class="gallery_image"
                                                   id="galleryImage2" accept="image/*"
                                                   onchange="previewFile(this)"/>
                                        </div>
                                        <span class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 650 px / 300 px")}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="primary-form-group">
                                    <div class="primary-form-group-wrap zImage-upload-details mw-100 mt-12">
                                        <div class="zImage-inside">
                                            <div class="d-flex pb-12"><img
                                                    src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt=""/>
                                            </div>
                                            <p class="fs-15 fw-500 lh-16 text-1b1c17">{{ __('Drag & drop files here') }}
                                            </p>
                                        </div>
                                        <label for="galleryImage3" class="zForm-label-alt">{{ __('Gallery Image 3') }} <span
                                                class="text-danger">*</span></label>
                                        <div class="upload-img-box">
                                            <img src=""/>
                                            <input type="file" name="gallery_image[]" class="gallery_image"
                                                   id="galleryImage3" accept="image/*"
                                                   onchange="previewFile(this)"/>
                                        </div>
                                        <span class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 650 px / 300 px")}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="primary-form-group">
                                    <div class="primary-form-group-wrap zImage-upload-details mw-100 mt-12">
                                        <div class="zImage-inside">
                                            <div class="d-flex pb-12"><img
                                                    src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt=""/>
                                            </div>
                                            <p class="fs-15 fw-500 lh-16 text-1b1c17">{{ __('Drag & drop files here') }}
                                            </p>
                                        </div>
                                        <label for="galleryImage4" class="zForm-label-alt">{{ __('Gallery Image 4') }} <span
                                                class="text-danger">*</span></label>
                                        <div class="upload-img-box">
                                            <img src=""/>
                                            <input type="file" name="gallery_image[]" class="gallery_image"
                                                   id="galleryImage4" accept="image/*"
                                                   onchange="previewFile(this)"/>
                                        </div>
                                        <span class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 650 px / 300 px")}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
                        <div
                            class="bd-c-stroke-2 justify-content-between align-items-center text-end pt-15 d-flex justify-content-between">
                            <h4 class="fs-18 fw-700 lh-24">{{__('Banner Image Section')}}</h4>
                        </div>
                        <div class="col-12">
                            <div class="primary-form-group">
                                <div class="primary-form-group-wrap zImage-upload-details mw-100 mt-12">
                                    <div class="zImage-inside">
                                        <div class="d-flex pb-12"><img
                                                src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt=""/>
                                        </div>
                                        <p class="fs-15 fw-500 lh-16 text-1b1c17">{{ __('Drag & drop files here') }}
                                        </p>
                                    </div>
                                    <label for="zImageUpload" class="zForm-label-alt">{{ __('Banner Image') }} <span
                                            class="text-mime-type">{{__('(jpeg,png,jpg,svg,webp)')}}</span> <span
                                            class="text-danger">*</span></label>
                                    <div class="upload-img-box">
                                        <img src=""/>
                                        <input type="file" name="banner_image" id="banner_image" accept="image/*"
                                               onchange="previewFile(this)"/>
                                    </div>
                                    <span class="fs-12 fw-400 lh-24 text-main-color pt-3">{{__("Recommended: 830 px / 550 px")}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bd-c-stroke-2 justify-content-between align-items-center text-end pt-15">
                        <button type="submit" class="flipBtn sf-flipBtn-primary flex-shrink-0">
                            <span>{{__('Submit')}}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Page content area end -->
@endsection
@push('script')
    <script src="{{asset('admin/custom/js/cms/countries.js')}}"></script>
@endpush
