@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="p-sm-30 p-15">
        <div class="">
            <div class="row rg-20">
                <div class="col-xl-3">
                    <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                        @include('admin.cms.partials.cms-sidebar')
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="align-items-center bd-b-one bd-c-one bd-c-stroke bd-c-stroke-2 d-flex justify-content-between mb-30 pb-15 pb-sm-30">
                        <h4 class="fs-18 fw-700 lh-24 text-title-text">{{$pageTitle}}</h4>
                    </div>
                    <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                        <form class="ajax" action="{{ route('admin.setting.application-settings.update') }}"
                              method="POST" enctype="multipart/form-data" data-handler="settingCommonHandler">
                            @csrf
                            <div class="align-items-center bd-b-one bd-c-one bd-c-stroke bd-c-stroke-2 d-flex justify-content-between mb-30 pb-15 pb-sm-30">
                                <h4 class="fs-18 fw-700 lh-24 text-title-text">{{__('Cta Sidebar Section')}}</h4>
                            </div>
                            <div class="row">
                                <div class="col-xxl-6 col-lg-6">
                                    <label for="ctaSidebarTitle" class="zForm-label-alt">{{ __('Cta Sidebar Title') }} <span
                                            class="text-danger">*</span></label>
                                    <input id="ctaSidebarTitle" type="text" name="cta_sidebar_title" value="{{ getOption('cta_sidebar_title') }}" placeholder="{{__('Type Cta Sidebar Title')}}"
                                           class="form-control zForm-control-alt">
                                </div>
                                <div class="col-xl-6 col-md-6 col-sm-6">
                                    <div class="primary-form-group">
                                        <div class="primary-form-group-wrap zImage-upload-details">
                                            <div class="zImage-inside">
                                                <div class="d-flex pb-12">
                                                    <img src="{{ asset('assets/images/icon/upload-img-1.svg') }}"
                                                         alt="" />
                                                </div>
                                                <p class="fs-15 fw-500 lh-16 text-1b1c17">
                                                    {{ __('Drag & drop file shere') }}
                                                </p>
                                            </div>
                                            <label for="ctaSidebarImage" class="zForm-label-alt">{{ __('Cta Sidebar Image') }} <span
                                                    class="text-danger">*</span></label>
                                            <div class="upload-img-box">
                                                @if (getOption('cta_sidebar_image'))
                                                    <img src="{{ getSettingImage('cta_sidebar_image') }}" />
                                                @else
                                                    <img src="" />
                                                @endif
                                                <input type="file" name="cta_sidebar_image" id="ctaSidebarImage"
                                                       accept="image/*,video/*" onchange="previewFile(this)" />
                                            </div>
                                        </div>
                                    </div>
                                    @if ($errors->has('cta_sidebar_image'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                {{ $errors->first('cta_sidebar_image') }}</span>
                                    @endif
                                    <p>
                                        <span class="text-black">
                                            <span class="text-black">{{ __('Recommend Size') }}:</span>
                                            260 x 235
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="align-items-center bd-b-one bd-c-one bd-c-stroke bd-c-stroke-2 d-flex justify-content-between mb-30 pb-15 pb-sm-30 pt-30">
                                <h4 class="fs-18 fw-700 lh-24 text-title-text">{{__('Cta Footer Section')}}</h4>
                            </div>
                            <div class="row rg-20">
                                <div class="col-xxl-12 col-lg-12">
                                    <label for="ctaFooterTitle" class="zForm-label-alt">{{ __('Cta Footer Title') }} <span
                                            class="text-danger">*</span></label>
                                    <input id="ctaFooterTitle" type="text" name="cta_footer_title" value="{{ getOption('cta_footer_title') }}" placeholder="{{__('Type Cta Footer Title')}}"
                                           class="form-control zForm-control-alt">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xxl-6 col-lg-6 pt-15">
                                    <label for="ctaFooterDes" class="zForm-label-alt">{{ __('Cta Footer Description') }} <span
                                            class="text-danger">*</span></label>
                                    <textarea name="cta_footer_description" id="ctaFooterDes" placeholder="{{__('Type Cta Footer Description')}}" class="form-control zForm-control-alt" cols="10" rows="5">{{ getOption('cta_footer_description') }}</textarea>
                                </div>
                                <div class="col-xl-6 col-md-6 col-sm-6 pt-15">
                                    <div class="primary-form-group">
                                        <div class="primary-form-group-wrap zImage-upload-details">
                                            <div class="zImage-inside">
                                                <div class="d-flex pb-12">
                                                    <img src="{{ asset('assets/images/icon/upload-img-1.svg') }}"
                                                         alt="" />
                                                </div>
                                                <p class="fs-15 fw-500 lh-16 text-1b1c17">
                                                    {{ __('Drag & drop file shere') }}
                                                </p>
                                            </div>
                                            <label for="ctaFooterImage" class="zForm-label-alt">{{ __('Cta Footer Image') }} <span
                                                    class="text-danger">*</span></label>
                                            <div class="upload-img-box">
                                                @if (getOption('cta_footer_image'))
                                                    <img src="{{ getSettingImage('cta_footer_image') }}" />
                                                @else
                                                    <img src="" />
                                                @endif
                                                <input type="file" name="cta_footer_image" id="ctaFooterImage"
                                                       accept="image/*,video/*" onchange="previewFile(this)" />
                                            </div>
                                        </div>
                                    </div>
                                    @if ($errors->has('cta_footer_image'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                {{ $errors->first('cta_footer_image') }}</span>
                                    @endif
                                    <p>
                                        <span class="text-black">
                                            <span class="text-black">{{ __('Recommend Size') }}:</span>
                                            505 x 515
                                        </span>
                                    </p>
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
    </div>
@endsection
