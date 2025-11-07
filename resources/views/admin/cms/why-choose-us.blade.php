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
                            <div class="row bd-b-one bd-c-one bd-c-stroke pb-20 bd-c-stroke-2 mb-30 pb-sm-30">
                                <div class="rg-20 col-md-3">
                                    <div class="col-xxl-12 col-lg-12">
                                        <label class="zForm-label-alt">{{ __('Partnering Universities') }} </label>
                                        <input type="text" name="why_choose_us_partnering_universities" value="{{ getOption('why_choose_us_partnering_universities') }}" placeholder="{{__('Number Of Partnering Universities ')}}"
                                               class="form-control zForm-control-alt">
                                    </div>
                                </div>
                                <div class="rg-20 col-md-3">
                                    <div class="col-xxl-12 col-lg-12">
                                        <label class="zForm-label-alt">{{ __('Countries') }} </label>
                                        <input type="text" name="why_choose_us_countries" value="{{ getOption('why_choose_us_countries') }}" placeholder="{{__('Number Of Countries')}}"
                                               class="form-control zForm-control-alt">
                                    </div>
                                </div>
                                <div class="rg-20 col-md-3">
                                    <div class="col-xxl-12 col-lg-12">
                                        <label class="zForm-label-alt">{{ __('Global Students') }} </label>
                                        <input type="text" name="why_choose_us_global_students" value="{{ getOption('why_choose_us_global_students') }}" placeholder="{{__('Number Of Global Students')}}"
                                               class="form-control zForm-control-alt">
                                    </div>
                                </div>
                                <div class="rg-20 col-md-3">
                                    <div class="col-xxl-12 col-lg-12">
                                        <label class="zForm-label-alt">{{ __('Courses') }} </label>
                                        <input type="text" name="why_choose_us_courses" value="{{ getOption('why_choose_us_courses') }}" placeholder="{{__('Number Of Courses')}}"
                                               class="form-control zForm-control-alt">
                                    </div>
                                </div>
                                <div class="rg-20 col-md-3 pt-10">
                                    <div class="col-xxl-12 col-lg-12">
                                        <label class="zForm-label-alt">{{ __('Student Visa Approval Rate (%) ')  }} </label>
                                        <input type="text" name="student_visa_approval_rate" value="{{ getOption('student_visa_approval_rate') }}" placeholder="{{__('Number Of Student Visa Approval')}}"
                                               class="form-control zForm-control-alt">
                                    </div>
                                </div>
                            </div>
                            <div class="bd-c-stroke-2 justify-content-between align-items-center text-end">
                                <button type="submit" class="flipBtn sf-flipBtn-primary flex-shrink-0">{{__('Submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
