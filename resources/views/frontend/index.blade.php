@extends('frontend.layouts.app')
@push('title')
    {{ __('Home') }}
@endpush
@section('content')
    <!-- Hero Banner -->
    @if(getOption('hero_banner_status') == STATUS_ACTIVE)
        <section class="hero-banner-section">
            <div class="container">
                <div class="hero-banner-content" data-background="{{ getFileUrl(getOption('banner_image')) }}" data-aos="fade-up" data-aos-duration="1000">
                    <h4 class="title">{{ getOption('banner_title') }}</h4>
                    <p class="info">{{ getOption('banner_description') }}</p>
                    <a href="{{route('register')}}" class="sf-btn-icon-primary position-relative z-index-1">
                        {{__('Register Now')}}
                        <span class="icon">
                            <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                            <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                        </span>
                    </a>
                </div>
                <!-- Filter -->
                <div class="hero-banner-filterWrap">
                    <div class="zTab-wrap">
                        <ul class="nav nav-tabs zTab-reset zTab-one" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="universities-tab" data-bs-toggle="tab"
                                        data-bs-target="#universities-tab-pane" type="button" role="tab"
                                        aria-controls="universities-tab-pane"
                                        aria-selected="true">{{__('Universities')}}
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="courses-tab" data-bs-toggle="tab"
                                        data-bs-target="#courses-tab-pane" type="button" role="tab"
                                        aria-controls="courses-tab-pane" aria-selected="false">{{__('Subject')}}
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="scholarships-tab" data-bs-toggle="tab"
                                        data-bs-target="#scholarships-tab-pane" type="button" role="tab"
                                        aria-controls="scholarships-tab-pane"
                                        aria-selected="false">{{__('Scholarships')}}
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="events-tab" data-bs-toggle="tab"
                                        data-bs-target="#events-tab-pane" type="button" role="tab"
                                        aria-controls="events-tab-pane" aria-selected="false">{{__('Events')}}
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="universities-tab-pane" role="tabpanel"
                             aria-labelledby="universities-tab" tabindex="0">
                            <form class="searchForm" method="GET" action="{{ auth()->check() ? route('student.universities.index') : route('login') }}">
                                <div class="hero-banner-filterInut">
                                    <div class="item">
                                        <label for="inputStudyDestination" class="zForm-label">
                                            {{ __('Study Destination') }} <span>*</span>
                                        </label>
                                        <input type="hidden" class="universityCountryRoute"
                                               value="{{ route('universities.byCountry') }}">
                                        <select class="country-select sf-select-checkbox" name="country[]">
                                            <option class="d-none" disabled
                                                    selected>{{ __('Select Destination') }}</option>
                                            @foreach($countryData as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                        <!-- Validation error message for country -->
                                        <div class="error-message country-error text-danger d-none">
                                            {{ __('Please select a country.') }}
                                        </div>
                                    </div>
                                    <div class="item">
                                        <label for="inputSelectSubjects" class="zForm-label">
                                            {{ __('Select University') }}
                                        </label>
                                        <select name="university[]"
                                                class="university-select sf-select-checkbox"></select>
                                    </div>
                                    <button type="submit" class="searchButton flipBtn sf-flipBtn-brand-alt">
                                        <div class="d-none h-25 spinner-border w-25" role="status">
                                            <span class="visually-hidden"></span>
                                        </div>
                                        {{ __('Search') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="courses-tab-pane" role="tabpanel" aria-labelledby="courses-tab"
                             tabindex="0">
                            <form class="searchForm" method="GET" action="{{ auth()->check() ? route('student.universities.index', ['tab' => 'subject']) : route('login') }}">
                                <div class="hero-banner-filterInut hero-banner-filterInputThree">
                                    <div class="item">
                                        <label for="inputStudyDestination" class="zForm-label">
                                            {{ __('Study Destination') }} <span>*</span>
                                        </label>
                                        <input type="hidden" class="universityCountryRoute"
                                               value="{{ url('universities') }}">
                                        <select class="country-select sf-select-checkbox" name="country[]">
                                            <option class="d-none" disabled
                                                    selected>{{__('Select Destination')}}</option>
                                            @foreach($countryData as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                        <!-- Validation error message for country -->
                                        <div class="error-message country-error text-danger d-none">
                                            {{ __('Please select a country.') }}
                                        </div>
                                    </div>
                                    <div class="item">
                                        <label for="inputSelectSubjects" class="zForm-label">
                                            {{ __('Select University') }}
                                        </label>
                                        <select name="university[]"
                                                class="university-select sf-select-checkbox"></select>
                                    </div>
                                    <div class="item">
                                        <label for="inputStudyLevel" class="zForm-label">{{__('Study Level')}}</label>
                                        <select class="sf-select-checkbox" name="study_level[]">
                                            <option class="d-none" selected
                                                    disabled>{{__('Selected Study Level')}}</option>
                                            @foreach($studyLevels as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="searchButton flipBtn sf-flipBtn-brand-alt">
                                        <div class="d-none h-25 spinner-border w-25" role="status">
                                            <span class="visually-hidden"></span>
                                        </div>
                                        {{__('Search')}}
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="scholarships-tab-pane" role="tabpanel"
                             aria-labelledby="scholarships-tab" tabindex="0">
                            <form class="searchForm" method="GET" action="{{ auth()->check() ? route('student.universities.index', ['tab' => 'scholarship']) : route('login') }}">
                                <div class="hero-banner-filterInut">
                                    <div class="item">
                                        <label for="inputStudyDestination"
                                               class="zForm-label">{{__('Study Destination')}}
                                            <span>*</span>
                                        </label>
                                        <select class="country-select sf-select-checkbox" name="country[]">
                                            <option class="d-none" disabled
                                                    selected>{{__('Select Destination')}}</option>
                                            @foreach($countryData as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                        <!-- Validation error message for country -->
                                        <div class="error-message country-error text-danger d-none">
                                            {{ __('Please select a country.') }}
                                        </div>
                                    </div>
                                    <div class="item">
                                        <label for="inputStudyLevel" class="zForm-label">{{__('Study Level')}}</label>
                                        <select class="sf-select-checkbox" name="study_level[]">
                                            <option class="d-none" selected
                                                    disabled>{{__('Select Study Level')}}</option>
                                            @foreach($studyLevels as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="searchButton flipBtn sf-flipBtn-brand-alt">
                                        <div class="d-none h-25 spinner-border w-25" role="status">
                                            <span class="visually-hidden"></span>
                                        </div>
                                        {{__('Search')}}
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="events-tab-pane" role="tabpanel" aria-labelledby="events-tab"
                             tabindex="0">
                            <form class="searchForm" method="GET" action="{{ auth()->check() ? route('student.universities.index', ['tab' => 'event']) : route('login') }}">
                                <div class="hero-banner-filterInut hero-banner-filterInputThree">
                                    <div class="item">
                                        <label for="inputStudyDestination"
                                               class="zForm-label">{{__('Study Destination')}}
                                            <span>*</span></label>
                                        <input type="hidden" class="universityCountryRoute"
                                               value="{{ url('universities') }}">
                                        <select class="country-select sf-select-checkbox" name="country[]">
                                            <option class="d-none" disabled
                                                    selected>{{__('Select Destination')}}</option>
                                            @foreach($countryData as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                        <!-- Validation error message for country -->
                                        <div class="error-message country-error text-danger d-none">
                                            {{ __('Please select a country.') }}
                                        </div>
                                    </div>
                                    <div class="item">
                                        <label for="inputSelectSubjects" class="zForm-label">
                                            {{ __('Select University') }}
                                        </label>
                                        <select name="university[]"
                                                class="university-select sf-select-checkbox"></select>
                                    </div>
                                    <div class="item">
                                        <label for="inputStudyLevel" class="zForm-label">{{__('Study Level')}}</label>
                                        <select class="sf-select-checkbox" name="study_level[]">
                                            <option class="d-none" selected
                                                    disabled>{{__('Select Study Level')}}</option>
                                            @foreach($studyLevels as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="searchButton flipBtn sf-flipBtn-brand-alt">
                                        <div class="d-none h-25 spinner-border w-25" role="status">
                                            <span class="visually-hidden"></span>
                                        </div>
                                        {{__('Search')}}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!-- Discover -->
    @if(getOption('service_status') == STATUS_ACTIVE)
        <section class="position-relative section-gap bg-secondary-bg z-index-1" id="serviceSection">
            <div class="container">
                <div class="row rg-24" data-aos="fade-up" data-aos-duration="1000">
                    <div class="col-lg-4">
                        <div class="service-content-left">
                            <h4 class="section-title">{{__('Our Services')}}</h4>
                            <p class="section-info">{{__('StudyJet helps you explore top study abroad destinations, offering diverse programs and unforgettable cultural experiences.')}}</p>
                            <div class="img"><img src="{{getFileUrl(getOption('our_service_image'))}}" alt=""></div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="row rg-24">
                            @foreach($serviceData as $data)
                                <div class="col-sm-6">
                                    <div class="service-item-one">
                                        <div class="icon"><img src="{{ getFileUrl( $data->icon ) }}" alt=""/></div>
                                        <a href="{{ route('service.details',$data->slug) }}"
                                           class="title">{{ $data->title }}</a>
                                        <p class="info">{!! getSubText($data->description ,85)  !!}</p>
                                        <a href="{{route('service.details',$data->slug)}}" class="link">{{__('Read More')}} <i
                                                class="fa-solid fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- About Us -->
    @if(getOption('about_us_status') == STATUS_ACTIVE)
        @if($aboutUs)
            <section class="section-gap home-about-section bg-brand-primary" data-background="{{asset('assets/images/about-bg-shape.svg')}}">
                <div class="container">
                    <div class="home-about-content" data-aos="fade-up" data-aos-duration="1000">
                        <h4 class="section-title text-white">{{ $aboutUs->title }}</h4>
                        <p class="info">{{ $aboutUs->details }}</p>
                        <div class="about-list">
                            @foreach($aboutUs->about_us_point ?? [] as $point)
                                <div class="item"><div class="icon"></div><p class="text">{{$point['point']}}</p></div>
                            @endforeach
                        </div>
                        <a href="{{route('consultations.list')}}" class="sf-btn-icon-primary">
                            {{__('Book a Consultation')}}
                            <span class="icon">
                                <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                                <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                            </span>
                        </a>
                    </div>
                    <div class="home-about-content-img" data-aos="fade-up" data-aos-duration="1000">
                        <div class="imgWrap"><img src="{{ getFileUrl( $aboutUs->banner_image[0] ?? '') }}" alt=""></div>
                        <div class="textWrap">
                            <h4 class="title">{{ getOption('why_choose_us_global_students') ?? 0 }}+</h4>
                            <p class="info">{{__('Students successfully placed in universities worldwide')}}</p>
                        </div>
                        <div class="textWrap">
                            <h4 class="title">{{ getOption('student_visa_approval_rate') ?? 0 }}%</h4>
                            <p class="info">{{__('Student visa approval rate')}}</p>
                        </div>
                        <div class="imgWrap"><img src="{{ getFileUrl($aboutUs->banner_image[1] ?? '') }}" alt=""></div>
                    </div>
                </div>
            </section>
        @endif
    @endif

    <!-- How We Work -->
    @if(getOption('how_we_work_status') == STATUS_ACTIVE)
        <section class="howWork-section section-gap-top" id="howWeWork">
            <div class="container">
                <div class="section-content-wrap max-w-550 m-auto" data-aos="fade-up" data-aos-duration="1000">
                    <h4 class="section-title text-center">{{__('How We Work')}}</h4>
                    <p class="section-info text-center max-w-550 m-auto">{{__('We work collaboratively with students to provide tailored solutions for their study abroad needs.')}}</p>
                </div>
                <!--  -->
                <div class="howWork-wrap" data-aos="fade-up" data-aos-duration="1000">
                    <div class="howWork-stepWrap">
                        <div class="row justify-content-center align-items-start flex-lg-nowrap rg-20">
                            @foreach($titles as $index => $title)
                                <div class="col-lg-auto col-md-4 col-sm-6">
                                    <div class="howWork-item">
                                        <div class="noWrap">
                                            <p class="no">0{{ $index + 1 }}</p>
                                            <div class="bar"></div>
                                        </div>
                                        <div class="text-content">
                                            <h4 class="title">{{ $title }}</h4>
                                            <p class="info">{{ $descriptions[$index] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="howWork-videoWrap sf-popup-gallery" data-aos="fade-up" data-aos-duration="1000">
                    <img src="{{getFileUrl(getOption('how_we_work_image'))}}" alt=""/>
                    <a href="{{getFileUrl(getOption('how_we_work_video'))}}" class="video"><i
                            class="fa-solid fa-play"></i></a>
                </div>
            </div>
        </section>
    @endif

    <!-- Discover -->
    @if(getOption('country_status') == STATUS_ACTIVE)
        <section class="section-gap bg-secondary-bg">
            <div class="container" data-aos="fade-up" data-aos-duration="1000">
                <div class="section-content-wrap max-w-550 m-auto">
                    <h4 class="section-title text-center max-w-396 m-auto">{{__('Discover Your Ideal Study Destination')}}</h4>
                    <p class="section-info text-center max-w-550 m-auto">{{__('We work collaboratively with students to provide tailored solutions for their study abroad needs.')}}</p>
                </div>
                <!--  -->
                <div class="swiper discoverSlider">
                    <div class="swiper-wrapper">
                        @foreach($countryData as $data)
                            <div class="swiper-slide">
                                <div class="discover-item-one">
                                    <a href="{{ route('country.details',$data->slug) }}" class="img">
                                        <img src="{{ getFileUrl($data->banner_image) }}" alt=""/>
                                    </a>
                                    <div class="content">
                                        <h4 class="title">{{$data->name}}</h4>
                                        <p class="info">{{ getSubText($data->details , 70) }} </p>
                                        <a href="{{ route('country.details',$data->slug) }}"
                                           class="link">{{__('Read More')}} <i
                                                class="fa-solid fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
    @endif

    <!-- Consultation Form -->
    @if(getOption('booking_consultation_from') == STATUS_ACTIVE)
        <section class="ld-booking-section section-gap bg-white section-overlay-wrap" id="freeConsultation" data-background="{{ getFileUrl(getOption('free_consultant_image')) }}">
            <div class="container" data-aos="fade-up" data-aos-duration="1000">
                <div class="row rg-24">
                    <div class="col-xl-7 col-lg-8">
                        <div class="landingBook-form landingBook-form-left">
                            <h4 class="section-title pb-md-40 pb-20">{{__('Book Your Free Consultation.')}}</h4>
                            <form action="{{route('free_consultation')}}" method="POST" class="ajax reset"
                                  data-handler="commonResponseWithPageLoad">
                                @csrf
                                <div class="row rg-24">
                                    <div class="col-md-6">
                                        <label for="inputFirstName" class="zForm-label">{{__('First Name')}}
                                            <span>*</span></label>
                                        <input type="text" value="{{auth()->user()->first_name ?? ''}}"
                                               class="form-control zForm-control" name="first_name"
                                               id="inputFirstName"
                                               placeholder="{{__('Enter First Name')}}"/>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="inputLastName" class="zForm-label">{{__('Last Name')}}
                                            <span>*</span></label>
                                        <input type="text" value="{{auth()->user()->last_name ?? ''}}"
                                               class="form-control zForm-control" name="last_name"
                                               id="inputLastName"
                                               placeholder="{{__('Enter Last Name')}}"/>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="inputMobileNumber" class="zForm-label">{{__('Mobile Number')}}
                                            <span>*</span></label>
                                        <input type="text" value="{{auth()->user()->mobile ?? ''}}"
                                               class="form-control zForm-control" name="mobile"
                                               id="inputMobileNumber"
                                               placeholder="{{__('Enter Mobile Number')}}"/>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="inputEmailAddress" class="zForm-label">{{__('Email Address')}}
                                            <span>*</span></label>
                                        <input type="email" value="{{auth()->user()->email ?? ''}}"
                                               class="form-control zForm-control" name="email"
                                               id="inputEmailAddress"
                                               placeholder="{{__('Enter Email Address')}}"/>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="inputPreferredStudyDestination"
                                               class="zForm-label">{{__('Preferred Study Destination')}}
                                            <span>*</span></label>
                                        <select class="sf-select-checkbox" multiple name="country_ids[]"
                                                id="inputPreferredStudyDestination">
                                            @foreach($countryData as $country)
                                                <option value="{{$country->id}}">{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="inputChooseMethodOfCounseling"
                                               class="zForm-label">{{__('Choose Method of Counseling')}}
                                            <span>*</span></label>
                                        <select class="sf-select-checkbox" id="inputChooseMethodOfCounseling"
                                                name="consultation_type">
                                            @foreach(getConsultationType() as $optionIndex => $consultationType)
                                                <option value="{{$optionIndex}}">{{$consultationType}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="inputEducationFundType"
                                               class="zForm-label">{{__('Education Fund Type')}}
                                            <span>*</span></label>
                                        <select class="sf-select-checkbox" name="study_level_id"
                                                id="inputEducationFundType">
                                            @foreach(getFundType() as $optionIndex => $fundType)
                                                <option value="{{$optionIndex}}">{{$fundType}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="inputSelectStudyLevel"
                                               class="zForm-label">{{__('Select Study Level')}}
                                            <span>*</span></label>
                                        <select class="sf-select-checkbox" id="inputSelectStudyLevel" name="fund_type">
                                            @foreach($studyLevels as $studyLevel)
                                                <option value="{{$studyLevel->id}}">{{$studyLevel->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="sf-btn-icon-primary">
                                            {{__('Book a Consultation')}}
                                            <span class="icon">
                                                <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                                                <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Upcoming Events -->
    @if(getOption('upcoming_event_status') == STATUS_ACTIVE)
        <section class="section-gap bg-secondary-bg">
            <div class="container" data-aos="fade-up" data-aos-duration="1000">
                <div class="section-content-wrap max-w-561 m-auto">
                    <h4 class="section-title text-center">{{__('Events & Your Path to Global Education')}}</h4>
                    <p class="section-info text-center max-w-550 m-auto">{{__('Explore events that guide you on your study abroad journey with expert support.')}}</p>
                </div>
                <!--  -->
                <div class="swiper upcomingSlider" data-aos="fade-up" data-aos-duration="1000">
                    <div class="swiper-wrapper">
                        @foreach($eventData as $data)
                            <div class="swiper-slide">
                                <div class="event-item-one">
                                    <a href="{{ route('event.details',$data->slug) }}" class="img">
                                        <img src="{{getFileUrl($data->image)}}" alt=""/>
                                        <p class="dateTime">
                                            {{ \Carbon\Carbon::parse($data->date_time)->format('M jS Y , g:i A') }}
                                        </p>
                                    </a>
                                    <div class="content">
                                        <p class="category">{{$data->type === EVENT_TYPE_PHYSICAL ? 'Physical' : 'Virtual'}}</p>
                                        <a href="{{route('event.details',$data->slug)}}"
                                           class="title">{{$data->title}}</a>
                                        @if($data->type === EVENT_TYPE_PHYSICAL)
                                            <div class="location">
                                                <div class="icon d-flex">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                         viewBox="0 0 20 20" fill="none">
                                                        <path
                                                            d="M17.5 8.33325C17.5 14.1666 10 19.1666 10 19.1666C10 19.1666 2.5 14.1666 2.5 8.33325C2.5 6.34413 3.29018 4.43647 4.6967 3.02995C6.10322 1.62343 8.01088 0.833252 10 0.833252C11.9891 0.833252 13.8968 1.62343 15.3033 3.02995C16.7098 4.43647 17.5 6.34413 17.5 8.33325Z"
                                                            stroke="#636370" stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round"/>
                                                        <path
                                                            d="M10 10.8333C11.3807 10.8333 12.5 9.71396 12.5 8.33325C12.5 6.95254 11.3807 5.83325 10 5.83325C8.61929 5.83325 7.5 6.95254 7.5 8.33325C7.5 9.71396 8.61929 10.8333 10 10.8333Z"
                                                            stroke="#636370" stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round"/>
                                                    </svg>
                                                </div>
                                                <p class="name">{{$data->location}}</p>
                                            </div>
                                        @else
                                            <div class="location">
                                                <p class="name">{{__('Virtual link will be provided')}}</p>
                                            </div>
                                        @endif
                                        <a href="{{ route('event.details',$data->slug) }}"
                                           class="link">{{__('Book a Ticket')}} <i
                                                class="fa-solid fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
    @endif

    <!-- Partners -->
    @if(getOption('top_university_status') == STATUS_ACTIVE)
        <section class="section-gap-top">
            <div class="container">
                <div class="section-content-wrap partner-title-wrap" data-aos="fade-up" data-aos-duration="1500">
                    <h4 class="section-title text-center">{{__('Our Esteemed University Partners')}}</h4>
                    <p class="section-info text-center max-w-757 m-auto">{{__('We collaborate with prestigious universities worldwide to provide you with exceptional study opportunities.')}}</p>
                    <div class="icon-wrap"><img src="{{ asset('assets/images/partner-separate-icon.svg') }}" alt=""/>
                    </div>
                </div>
                <!--  -->
                <div class="swiper autoImageslider" data-aos="fade-up" data-aos-duration="1000">
                    <div class="swiper-wrapper">
                        @foreach($universityData as $data)
                            <div class="swiper-slide">
                                <div class="partner-list">
                                    <img src="{{ getFileUrl($data->logo) }}" alt=""/>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Why Choose Us -->
    @if(getOption('why_choose_us_status') == STATUS_ACTIVE)
        <section class="section-gap-top">
            <div class="container">
                <div class="section-content-wrap max-w-550 m-auto" data-aos="fade-up" data-aos-duration="1000">
                    <h4 class="section-title text-center">{{__('Why Choose Us')}}</h4>
                    <p class="section-info text-center">{{__('We work collaboratively with students to provide tailored solutions for their study abroad needs.')}}</p>
                </div>
                <div class="choose-items-wrap" data-aos="fade-up" data-aos-duration="1000">
                    <div class="row rg-20">
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <div class="choose-item">
                                <div class="icon"><img src="{{ asset('assets/images/icon/choose-icon-1.svg') }}"
                                                       alt=""/></div>
                                <h4 class="title"> {{ getOption('why_choose_us_partnering_universities') ?? 0}} </h4>
                                <p class="info">{{__('Partnering Universities')}}</p>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <div class="choose-item">
                                <div class="icon"><img src="{{ asset('assets/images/icon/choose-icon-2.svg') }}"
                                                       alt=""/></div>
                                <h4 class="title"> {{ getOption('why_choose_us_countries') ?? 0 }} </h4>
                                <p class="info">{{__('Countries')}}</p>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <div class="choose-item">
                                <div class="icon"><img src="{{ asset('assets/images/icon/choose-icon-3.svg') }}"
                                                       alt=""/></div>
                                <h4 class="title"> {{ getOption('why_choose_us_global_students') ?? 0 }} </h4>
                                <p class="info">{{__('Global Students')}}</p>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <div class="choose-item">
                                <div class="icon"><img src="{{ asset('assets/images/icon/choose-icon-4.svg') }}"
                                                       alt=""/></div>
                                <h4 class="title"> {{ getOption('why_choose_us_courses') ?? 0 }} </h4>
                                <p class="info">{{__('Courses')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- News -->
    @if(getOption('blog_article_status') == STATUS_ACTIVE)
        <section class="section-gap section-gap-margin-top bg-brand-primary" data-background="{{asset('assets/images/articles-overlay-bg.svg')}}">
            <div class="container" data-aos="fade-up" data-aos-duration="1000">
                <div class="row rg-24 justify-content-between align-items-center pb-24">
                    <div class="col-lg-5">
                        <div class="max-w-473">
                            <h4 class="section-title text-white mb-24">{{__('Insights, Inspiration, and Innovation.')}}</h4>
                            <a href="{{ route('blog.list') }}" class="sf-btn-icon-primary">
                                {{__('More Articles')}}
                                <span class="icon">
                                    <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                                    <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                                </span>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        @if($blogData->isNotEmpty())
                            <div class="blog-item-one">
                                <div class="topContent">
                                    <div class="author">
                                        <img src="{{getFileUrl($blogData[0]->userImage)}}" alt="">
                                        <div class="nameDegi">
                                            <h4 class="name">{{ $blogData[0]->userName }}</h4>
                                            <p class="degi">{{ $blogData[0]->userDesignation }}</p>
                                        </div>
                                    </div>
                                    <div class="date"><p>{{ \Carbon\Carbon::parse($blogData[0]->publish_date)->format('M jS Y') }}</p></div>
                                </div>
                                <a href="#" class="img">
                                    <img src="{{ getFileUrl($blogData[0]->banner_image) }}" alt=""/>
                                </a>
                                <div class="content">
                                    <a href="{{ route('blog.details',$blogData[0]->slug) }}"
                                       class="title">{{ $blogData[0]->title }}</a>
                                    <a href="{{ route('blog.details',$blogData[0]->slug) }}"
                                       class="link">{{__('Read More')}} <i
                                            class="fa-solid fa-arrow-right"></i></a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row rg-24">
                    @foreach($blogData->skip(1)->take(3) as $data)
                        <div class="col-xl-4 col-md-6">
                            <div class="blog-item-one">
                                <div class="topContent">
                                    <div class="author">
                                        <img src="{{getFileUrl($data->userImage)}}" alt="">
                                        <div class="nameDegi">
                                            <h4 class="name">{{ $data->userName }}</h4>
                                            <p class="degi">{{ $data->userDesignation }}</p>
                                        </div>
                                    </div>
                                    <div class="date"><p>{{ \Carbon\Carbon::parse($data->publish_date)->format('M jS Y') }}</p></div>
                                </div>
                                <a href="#" class="img">
                                    <img src="{{ getFileUrl($data->banner_image) }}" alt=""/>
                                </a>
                                <div class="content">
                                    <a href="{{ route('blog.details',$data->slug) }}"
                                       class="title">{{ $data->title }}</a>
                                    <a href="{{ route('blog.details',$data->slug) }}"
                                       class="link">{{__('Read More')}} <i
                                            class="fa-solid fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- FAQ -->
    @if(getOption('faq_status') == STATUS_ACTIVE)
        <section class="section-gap bg-secondary-bg">
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
    @endif

    <!-- Testimonials -->
    @if(getOption('testimonial_status') == STATUS_ACTIVE)
        <section class="section-gap-top overflow-hidden">
            <div class="container">
                <div class="section-content-wrap max-w-550 m-auto" data-aos="fade-up" data-aos-duration="1000">
                    <h4 class="section-title text-center">{{__('What Our Student Says')}}</h4>
                    <p class="section-info text-center">{{__('Real stories from students who turned their study abroad dreams into reality with StudyJet.')}}</p>
                </div>
                <div class="swiper ladingTestimonials-slider">
                    <div class="swiper-wrapper">
                        @foreach ($testimonialData as $data)
                            <div class="swiper-slide">
                                <div class="testimonial-item-one">
                                    <p class="info">{{ $data->description }}</p>
                                    <div class="content">
                                        <div class="text-content">
                                            <h4 class="name">— {{ $data->name }}</h4>
                                            <p class="date">{{ formatDate($data->review_date, 'F j, Y') }}</p>
                                        </div>
                                        <div class="img">
                                            <img src="{{ getFileUrl($data->image) }}" alt=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </section>
    @endif
@endsection
@push('script')
    <script src="{{ asset('frontend/js/frontend.js') }}"></script>
@endpush
