@extends('frontend.layouts.app')
@push('title')
    {{ __('Home') }}
@endpush
@section('content')
    <!-- Breadcrumb -->
    <section class="inner-breadcrumb-section">
        <div class="container">
            <div class="inner-breadcrumb-wrap" data-aos="fade-up" data-aos-duration="1000">
            <h4 class="inner-breadcrumb-title">{{__('About Us')}}</h4>
            <ol class="breadcrumb inner-breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('frontend')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('about-us.details')}}">{{__('About Us Details')}}</a></li>
            </ol>
        </div>
        </div>
    </section>

    <!-- About Us -->
    <section class="section-gap-top overflow-hidden bg-white">
        <div class="container">
            <div class="section-content-wrap" data-aos="fade-up" data-aos-duration="1000">
                <div class="row rg-20 justify-content-between align-items-center">
                    <div class="col-lg-6">
                        <h4 class="section-title">{{$aboutUs->title ?? ''}}</h4>
                    </div>
                    <div class="col-lg-5">
                        <p class="section-info">
                            {{$aboutUs->details ?? ''}}
                        </p>
                    </div>
                </div>
            </div>
            <!-- Images -->
            <div class="row rg-24" data-aos="fade-up" data-aos-duration="1000">
                <div class="col-sm-6">
                    <div class="aboutUs-img"><img src="{{getFileUrl($aboutUs->banner_image[0] ?? '')}}" alt="" /></div>
                </div>
                <div class="col-sm-6">
                    <div class="row rg-24">
                        <div class="col-12">
                            <div class="aboutUs-img"><img src="{{getFileUrl($aboutUs->banner_image[1] ?? '')}}" alt="" /></div>
                        </div>
                        <div class="col-sm-6">
                            <div class="aboutUs-img"><img src="{{getFileUrl($aboutUs->banner_image[2] ?? '')}}" alt="" /></div>
                        </div>
                        <div class="col-sm-6">
                            <div class="aboutUs-img"><img src="{{getFileUrl($aboutUs->banner_image[3] ?? '')}}" alt="" /></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- History -->
    <section class="bg-white overflow-hidden section-gap-top">
        <div class="container">
            <div class="section-content-wrap max-w-550 m-auto" data-aos="fade-up" data-aos-duration="1000">
                <h4 class="section-title text-center">{{__('Our History')}}</h4>
                <p class="section-info text-center">{{__('Our history at StudyJet is defined by our commitment to empowering
                    students in their journey toward global education since our founding in 2016.')}}</p>
            </div>
        </div>
        <div class="history-item-wrap" data-aos="fade-up" data-aos-duration="1000">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    @foreach($aboutUs->our_history ?? [] as $history)
                        <div class="col-xxl-4 col-md-6">
                            <div class="history-item">
                                <div class="img"><img src="{{ getFileUrl($history['image']) }}" alt=""></div>
                                <div class="icon"></div>
                                <p class="year">{{$history['year']}}</p>
                                <div class="text-content">
                                    <h4 class="title">{{$history['title']}}</h4>
                                    <p class="info">{{$history['description']}}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!--  -->
    <section class="section-gap bg-white">
        <div class="container">
            <div class="section-content-wrap max-w-550 m-auto" data-aos="fade-up" data-aos-duration="1000">
                <h4 class="section-title text-center">{{__('Number Says it All')}}</h4>
                <p class="section-info text-center">{{__('Numbers speak volumes at StudyJet, showcasing our impact and success in guiding thousands of students toward their global education goals.')}}</p>
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

    <!-- Mission - Vision - Goal -->
    <section class="section-gap bg-secondary-bg" data-background="{{asset('assets/images/why-choose-us.png')}}">
        <div class="container" data-aos="fade-up" data-aos-duration="1000">
            <div class="section-content-wrap max-w-627 m-auto">
                <h4 class="section-title text-center max-w-488 m-auto">{{__('We Know Exactly How to Help You for Succeed.')}}</h4>
                <p class="section-info text-center">{{__('We know exactly how to help you succeed by providing tailored support and resources for your educational journey.')}}</p>
            </div>
            <ul class="nav nav-tabs zTab-reset zTab-two" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="ourMission-tab" data-bs-toggle="tab" data-bs-target="#ourMission-tab-pane" type="button" role="tab" aria-controls="ourMission-tab-pane" aria-selected="true">{{__('Our Mission')}}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="ourVision-tab" data-bs-toggle="tab" data-bs-target="#ourVision-tab-pane" type="button" role="tab" aria-controls="ourVision-tab-pane" aria-selected="false">{{__('Our Vision')}}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="ourGoal-tab" data-bs-toggle="tab" data-bs-target="#ourGoal-tab-pane" type="button" role="tab" aria-controls="ourGoal-tab-pane" aria-selected="false">{{__('Our Goal')}}</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="ourMission-tab-pane" role="tabpanel" aria-labelledby="ourMission-tab" tabindex="0">
                    <div class="mvg-content-wrap">
                        <div class="row rg-20 align-items-center">
                            <div class="col-lg-6">
                                <div class="mvg-content-img">
                                    <img src="{{ getFileUrl($aboutUs->our_mission_image ?? '') }}" alt="" />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mvg-content-text">
                                    <h4 class="title">{{ $aboutUs->our_mission_title ?? '' }}</h4>
                                    <p class="info">{!! $aboutUs->our_mission_details ?? '' !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="ourVision-tab-pane" role="tabpanel" aria-labelledby="ourVision-tab" tabindex="0">
                    <div class="mvg-content-wrap">
                        <div class="row rg-20 align-items-center">
                            <div class="col-lg-6">
                                <div class="mvg-content-img">
                                    <img src="{{ getFileUrl($aboutUs->our_vision_image ?? '') }}" alt="" />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mvg-content-text">
                                    <h4 class="title">{{ $aboutUs->our_vision_title ?? '' }}</h4>
                                    <p class="info">{!! $aboutUs->our_vision_details ?? '' !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="ourGoal-tab-pane" role="tabpanel" aria-labelledby="ourGoal-tab" tabindex="0">
                    <div class="mvg-content-wrap">
                        <div class="row rg-20 align-items-center">
                            <div class="col-lg-6">
                                <div class="mvg-content-img">
                                    <img src="{{ getFileUrl($aboutUs->our_goal_image ?? '') }}" alt="" />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mvg-content-text">
                                    <h4 class="title">{{ $aboutUs->our_goal_title ?? '' }}</h4>
                                    <p class="info">{!! $aboutUs->our_goal_details ?? '' !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Awards & Achievements -->
    <section class="section-gap bg-white">
        <div class="container" data-aos="fade-up" data-aos-duration="1000">
            <div class="section-content-wrap max-w-550 m-auto">
                <h4 class="section-title text-center">{{__('Awards & Achievements')}}</h4>
                <p class="section-info text-center">{{__('Our awards and achievements highlight our commitment to excellence in guiding students toward their international education dreams.')}}</p>
            </div>
            <div class="row rg-24">
                @if(!empty($aboutUs->awards))
                    @foreach($aboutUs->awards as $award)
                        @if(!empty($award['name']) && !empty($award['image']))
                            <div class="col-md-6">
                                <div class="award-item-one">
                                    <div class="img">
                                        <img src="{{ getFileUrl($award['image']) }}" alt="" />
                                    </div>
                                    <h4 class="title">{{ $award['name'] }}</h4>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>

        </div>
    </section>

    <!-- FAQ -->
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

    <!-- Testimonials -->
    <section class="section-gap-top overflow-hidden">
        <div class="container" data-aos="fade-up" data-aos-duration="1000">
            <div class="section-content-wrap max-w-550 m-auto">
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
@endsection
