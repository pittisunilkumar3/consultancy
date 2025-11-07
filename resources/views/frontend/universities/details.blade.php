@extends('frontend.layouts.app')
@push('title')
    {{ __('Home') }}
@endpush
@section('content')
    <!-- Breadcrumb -->
    <section class="inner-breadcrumb-section">
        <div class="container">
            <div class="inner-breadcrumb-wrap" data-aos="fade-up" data-aos-duration="1000">
            <h4 class="inner-breadcrumb-title">{{__('University')}}</h4>
            <ol class="breadcrumb inner-breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('frontend')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('universities.list')}}">{{__('University List')}}</a></li>
                <li class="breadcrumb-item"><a
                        href="{{route('universities.details',$universityData->slug)}}">{{__('University Details')}}</a>
                </li>
            </ol>
        </div>
        </div>
    </section>

    <!-- About -->
    <section class="section-gap bg-white">
        <div class="container">
            <div class="row rg-20">
                <div class="col-lg-8">
                    <div class="scholar-details-content" data-aos="fade-up" data-aos-duration="1000">
                        <div class="textBlock-wrap">
                            <div class="text-content">
                                <div class="imgBlock">
                                    <img src="{{ getFileUrl($universityData->thumbnail_image) }}" class="w-100" alt=""/>
                                </div>
                                <h4 class="title">{{ $universityData->name }}</h4>
                                <div class="info">
                                    {!! $universityData->details !!}
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
                        <h4 class="title">{{__('University Details')}}</h4>
                        <ul class="zList-pb-13">
                            <li class="item">
                                <div class="left w-100">
                                    <div class="icon"><img src="{{ asset('assets/images/icon/ranking.svg') }}" alt="" /></div>
                                    <p class="fs-16 fw-400 lh-28 text-para-text d-flex flex-grow-1 justify-content-between g-5"><span class="flex-shrink-0"> {{__('World Ranking')}} :</span> {{ $universityData->world_ranking }}</p>
                                </div>
                            </li>
                            <li class="item">
                                <div class="left w-100">
                                    <div class="icon"><img src="{{ asset('assets/images/icon/students2.svg') }}" alt="" /></div>
                                    <p class="fs-16 fw-400 lh-28 text-para-text d-flex flex-grow-1 justify-content-between g-5"><span class="flex-shrink-0">{{__('International Students')}} : </span> {{ $universityData->international_student }}</p>
                                </div>
                            </li>
                            <li class="item">
                                <div class="left w-100">
                                    <div class="icon"><img src="{{ asset('assets/images/icon/country2.svg') }}" alt="" /></div>
                                    <p class="fs-16 fw-400 lh-28 text-para-text d-flex flex-grow-1 justify-content-between g-5"><span class="flex-shrink-0">{{__('Country')}} : </span> {{ $universityData->countryName }}</p>
                                </div>
                            </li>
                            <li class="item">
                                <div class="left w-100">
                                    <div class="icon"><img src="{{ asset('assets/images/icon/price.svg') }}" alt="" /></div>
                                    <p class="fs-16 fw-400 lh-28 text-para-text d-flex flex-grow-1 justify-content-between g-5"><span class="flex-shrink-0">{{__('Average Cost')}} : </span> {{ showPrice($universityData->avg_cost) }}</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Subjects -->
    <section class="section-gap bg-secondary-bg">
        <div class="container" data-aos="fade-up" data-aos-duration="1000">
            <div class="section-content-wrap max-w-550 m-auto">
                <h4 class="section-title text-center pb-0">{{__('Explore All Subjects')}}</h4>
            </div>
            <!--  -->
            <div class="swiper threeSlider">
                <div class="swiper-wrapper">
                    @if($subjectData->isNotEmpty())
                        @foreach($subjectData as $data)
                            <div class="swiper-slide">
                                <div class="course-item-two">
                                    <a href="{{ route('subject.details',$data->subjectSlug) }}" class="img">
                                        <img src="{{getFileUrl($data->banner_image)}}" alt=""/>
                                    </a>
                                    <div class="course-content">
                                        <div class="text-content">
                                            <a href="{{ route('subject.details',$data->subjectSlug) }}"
                                               class="title">{{ $data->subjectName }}</a>
                                            <p class="author">{{ $data->subjectCategoryName }}</p>
                                        </div>
                                        <ul class="list zList-pb-6">
                                            <li class="item">
                                                <div class="icon d-flex"><img
                                                        src="{{asset('assets/images/icon/qualification.svg')}}"
                                                        alt=""/></div>
                                                <p class="text">{{ $data->studyLevelName }}</p>
                                            </li>
                                            <li class="item">
                                                <div class="icon d-flex"><img
                                                        src="{{asset('assets/images/icon/country.png')}}" alt=""/>
                                                </div>
                                                <p class="text">{{ $data->countryName }}</p>
                                            </li>
                                            <li class="item">
                                                <div class="icon d-flex"><img
                                                        src="{{asset('assets/images/icon/next-course.svg')}}"
                                                        alt=""/></div>
                                                <p class="text">{{__('Intake Date')}}
                                                    : {{ \Carbon\Carbon::parse($data->subjectIntakeTime)->format('Y-m-d')}}</p>
                                            </li>
                                        </ul>
                                        <a href="{{ route('subject.details',$data->subjectSlug) }}"
                                           class="link">{{__('More Details')}} <i
                                                class="fa-solid fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="bd-c-stroke bd-one bd-ra-10 bg-white p-15 p-sm-25 text-center w-100">
                            <p>{{__('No subjects found for this university.')}}</p>
                        </div>
                    @endif
                </div>
                <div class="sliderControls">
                    <div class="swiper-button-prev">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14"
                             fill="none">
                            <path
                                d="M8.82617 1.87305L4.01367 6.68555L3.71289 7L4.01367 7.31445L8.82617 12.127L9.45508 11.498L4.95703 7L9.45508 2.50195L8.82617 1.87305Z"
                                fill="#999999"/>
                        </svg>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14"
                             fill="none">
                            <path
                                d="M6.17383 1.87305L5.54492 2.50195L10.043 7L5.54492 11.498L6.17383 12.127L10.9863 7.31445L11.2871 7L10.9863 6.68555L6.17383 1.87305Z"
                                fill="#999999"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Benefits -->
    <section class="section-gap-top">
        <div class="container" data-aos="fade-up" data-aos-duration="1000">
            <div class="section-content-wrap max-w-550 m-auto">
                <h4 class="section-title text-center">{{__('Core Benefits')}}</h4>
                <p class="section-info text-center">{{__('Hear from our students who have experienced the life-changing journey of studying abroad with Studylifter’s expert guidance.')}}</p>
            </div>
            <div class="row rg-20">
                @foreach($universityData->core_benefits_title as $key => $title)
                    <div class="col-xl-3 col-md-4 col-sm-6">
                        <div class="choose-item-alt">
                            <div class="icon">
                                <img src="{{ getFileUrl($universityData->core_benefits_icon[$key]) }}" alt=""/>
                            </div>
                            <p class="info pt-17">{{ $title }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Gallery -->
    <section class="section-gap-top">
        <div class="container" data-aos="fade-up" data-aos-duration="1000">
            <div class="section-content-wrap max-w-550 m-auto">
                <h4 class="section-title text-center">{{__('Gallery')}}</h4>
                <p class="section-info text-center">{{__('Explore our gallery to discover the vibrant campus life and academic excellence at the University of Melbourne.')}}</p>
            </div>
            <!--  -->
            <div class="row rg-24">
                <div class="col-md-6">
                    <div class="university-gallery-img max-h-525 sf-popup-gallery">
                        <a href="{{ getFileUrl($universityData->gallery_image[0]) }}">
                            <img src="{{ getFileUrl($universityData->gallery_image[0]) }}" alt=""/>
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row rg-24">
                        <div class="col-12">
                            <div class="row rg-24">
                                <div class="col-md-6">
                                    <div class="university-gallery-img max-h-204 sf-popup-gallery">
                                        <a href="{{ getFileUrl($universityData->gallery_image[1]) }}">
                                            <img src="{{ getFileUrl($universityData->gallery_image[1]) }}" alt=""/>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="university-gallery-img max-h-204 sf-popup-gallery">
                                        <a href="{{ getFileUrl($universityData->gallery_image[2]) }}">
                                            <img src="{{ getFileUrl($universityData->gallery_image[2]) }}" alt=""/>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="university-gallery-img max-h-297 sf-popup-gallery">
                                <a href="{{ getFileUrl($universityData->gallery_image[3]) }}">
                                    <img src="{{ getFileUrl($universityData->gallery_image[3]) }}" alt=""/>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="section-gap section-gap-margin-top bg-secondary-bg">
        <div class="container" data-aos="fade-up" data-aos-duration="1000">
            <div class="section-content-wrap max-w-606 m-auto">
                <h4 class="section-title text-center">{{__('Frequently')}}<br> {{__('Asked Question')}}</h4>
            </div>
            <div class="accordion zAccordion-reset zAccordion-two" id="accordionExample">
                @foreach($faqData as $index => $data)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $index }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $index }}" aria-controls="collapse{{ $index }}"
                                    aria-expanded="false">
                                {{ $data->question }}
                            </button>
                        </h2>
                        <div id="collapse{{ $index }}" class="accordion-collapse collapse"
                             aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ $data->answer }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
