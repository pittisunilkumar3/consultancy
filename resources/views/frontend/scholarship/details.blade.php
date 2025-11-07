@extends('frontend.layouts.app')
@push('title')
    {{ __('Home') }}
@endpush
@section('content')
    <!-- Breadcrumb -->
    <section class="inner-breadcrumb-section">
        <div class="container">
            <div class="inner-breadcrumb-wrap" data-aos="fade-up" data-aos-duration="1000">
            <h4 class="inner-breadcrumb-title">{{__('Scholarship')}}</h4>
            <ol class="breadcrumb inner-breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('frontend')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('scholarship.list')}}">{{__('Scholarship List')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('scholarship.details',$scholarshipDetails->slug)}}">{{__('Scholarship Details')}}</a></li>
            </ol>
        </div>
        </div>
    </section>

    <!-- About -->
    <section class="section-gap-top">
        <div class="container">
            <div class="row rg-20">
                <div class="col-lg-8">
                    <div class="scholar-details-content" data-aos="fade-up" data-aos-duration="1000">
                        <div class="textBlock-wrap">
                            <div class="text-content">
                                <div class="imgBlock">
                                    <img src="{{ getFileUrl($scholarshipDetails->banner_image) }}" class="w-100" alt=""/>
                                </div>
                                <h4 class="title">{{ $scholarshipDetails->title }}</h4>
                                <div class="info">
                                    {!! $scholarshipDetails->details !!}
                                </div>
                            </div>
                        </div>
                        <div class="mt-40">
                            <a href="{{route('consultations.list')}}" class="sf-btn-icon-primary">
                                {{__('Book a Consultation')}}
                                <span class="icon">
                                    <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                                    <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-duration="1000">
                    <div class="mb-10">
                        <div class="meet-consultant" data-background="{{ getFileUrl(getOption('cta_sidebar_image')) }}">
                            <h4 class="title">{{ getOption('cta_sidebar_title') }}</h4>
                            <a href="{{route('consultations.list')}}" class="sf-btn-icon-primary">
                                {{__('Book a Consultation')}}
                                <span class="icon">
                        <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                        <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                    </span>
                            </a>
                        </div>
                    </div>
                    <div class="subject-details-sidebar bd-ra-20 bg-secondary-bg p-sm-25 p-15">
                        <h4 class="title">{{__('Scholarship Details')}}</h4>
                        <ul class="zList-pb-13">
                            <li class="item">
                                <div class="left w-100">
                                    <p class="fs-16 fw-400 lh-28 text-para-text d-flex g-5 flex-grow-1 justify-content-between"><span class="flex-shrink-0">{{__('Location')}} : </span> {{ $scholarshipDetails->countryName }}</p>
                                </div>
                            </li>
                            <li class="item">
                                <div class="left w-100">
                                    <p class="fs-16 fw-400 lh-28 text-para-text d-flex g-5 flex-grow-1 justify-content-between"><span class="flex-shrink-0">{{__('Institution')}} : </span> {{ $scholarshipDetails->universityName }}</p>
                                </div>
                            </li>
                            <li class="item">
                                <div class="left w-100">
                                    <p class="fs-16 fw-400 lh-28 text-para-text d-flex g-5 flex-grow-1 justify-content-between"><span class="flex-shrink-0">{{__('Qualification')}} : </span> {{ $scholarshipDetails->studyLevelName }}</p>
                                </div>
                            </li>
                            <li class="item">
                                <div class="left w-100">
                                    <p class="fs-16 fw-400 lh-28 text-para-text d-flex g-5 flex-grow-1 justify-content-between"><span class="flex-shrink-0">{{__('Available Awards Number')}} : </span> {{ $scholarshipDetails->available_award_number }}</p>
                                </div>
                            </li>
                            <li class="item">
                                <div class="left w-100">
                                    @if($scholarshipDetails->funding_type == SCHOLARSHIP_FUNDING_TYPE_PARTIAL)
                                        <p class="fs-16 fw-400 lh-28 text-para-text d-flex g-5 flex-grow-1 justify-content-between"><span class="flex-shrink-0">{{__('Value of Award')}} : </span> {{ __('Partial') }}</p>
                                    @elseif($scholarshipDetails->funding_type == SCHOLARSHIP_FUNDING_TYPE_FULL_FUNDED)
                                        <p class="fs-16 fw-400 lh-28 text-para-text d-flex g-5 flex-grow-1 justify-content-between"><span class="flex-shrink-0">{{__('Value of Award')}} : </span> {{ __('Full Funded') }}</p>
                                    @endif
                                </div>
                            </li>
                            <li class="item">
                                <div class="left w-100">
                                    <p class="fs-16 fw-400 lh-28 text-para-text d-flex g-5 flex-grow-1 justify-content-between"><span class="flex-shrink-0">{{__('Application Start Date')}} : </span> {{ \Carbon\Carbon::parse($scholarshipDetails->application_start_date)->format('Y-m-d') }}</p>
                                </div>
                            </li>
                            <li class="item">
                                <div class="left w-100">
                                    <p class="fs-16 fw-400 lh-28 text-para-text d-flex g-5 flex-grow-1 justify-content-between"><span class="flex-shrink-0">{{__('Application End Date')}} : </span> {{ \Carbon\Carbon::parse($scholarshipDetails->application_end_date)->format('Y-m-d') }}</p>
                                </div>
                            </li>
                            <li class="item">
                                <div class="left w-100">
                                    <p class="fs-16 fw-400 lh-28 text-para-text d-flex g-5 flex-grow-1 justify-content-between"><span class="flex-shrink-0">{{__('Offers Received From')}} : </span> {{ \Carbon\Carbon::parse($scholarshipDetails->offers_received_from_date)->format('Y-m-d') }}</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
