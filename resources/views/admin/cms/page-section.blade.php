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
                                <h4 class="fs-18 fw-700 lh-24 text-title-text">{{__('Terms & Services Page')}}</h4>
                            </div>
                            <div>
                                <label class="zForm-label-alt">{{ __('Terms & services page title') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="terms_services_page_title" value="{{ getOption('terms_services_page_title') }}" placeholder="{{__('Type terms & services page title')}}"
                                       class="form-control zForm-control-alt">
                            </div>
                            <div class="pt-15">
                                <label class="zForm-label-alt">{{ __('Terms & services page details') }} <span
                                        class="text-danger">*</span></label>
                                <textarea class="summernoteOne" name="terms_services_page_details" placeholder="{{__('Type terms & services page details')}}">{!! getOption('terms_services_page_details') !!}</textarea>
                            </div>
                            <div class="align-items-center bd-b-one bd-c-one bd-c-stroke bd-c-stroke-2 d-flex justify-content-between mb-30 pb-15 pb-sm-30 pt-30">
                                <h4 class="fs-18 fw-700 lh-24 text-title-text">{{__('Refund Policy Page')}}</h4>
                            </div>
                            <div class="">
                                <label class="zForm-label-alt">{{ __('Type refund policy page Title') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="refund_policy_page_title" value="{{ getOption('refund_policy_page_title') }}" placeholder="{{__('Type refund policy page Title')}}"
                                       class="form-control zForm-control-alt">
                            </div>
                            <div class="pt-15">
                                <div class="">
                                    <label class="zForm-label-alt">{{ __('Refund Policy Page Details') }} <span
                                            class="text-danger">*</span></label>
                                    <textarea class="summernoteOne" name="refund_policy_page_details" placeholder="{{__('Type refund policy page details')}}" >{!! getOption('refund_policy_page_details') !!}</textarea>
                                </div>
                            </div>
                            <div class="align-items-center bd-b-one bd-c-one bd-c-stroke bd-c-stroke-2 d-flex justify-content-between mb-30 pb-15 pb-sm-30 pt-30">
                                <h4 class="fs-18 fw-700 lh-24 text-title-text">{{__('Privacy Policy Page')}}</h4>
                            </div>
                            <div class="">
                                <label class="zForm-label-alt">{{ __('Privacy policy page title') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="privacy_policy_page_title" value="{{ getOption('privacy_policy_page_title') }}" placeholder="{{__('Type privacy policy page title')}}"
                                       class="form-control zForm-control-alt">
                            </div>
                            <div class="pt-15">
                                <div class="">
                                    <label class="zForm-label-alt">{{ __('Privacy policy page details') }} <span
                                            class="text-danger">*</span></label>
                                    <textarea class="summernoteOne" name="privacy_policy_page_details" placeholder="{{__('Type privacy policy page details')}}">{!! getOption('privacy_policy_page_details') !!}</textarea>
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
