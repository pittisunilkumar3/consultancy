@extends('frontend.layouts.app')
@push('title')
    {{ __('Home') }}
@endpush
@section('content')
    <!-- Breadcrumb -->
    <section class="inner-breadcrumb-section">
        <div class="container">
            <div class="inner-breadcrumb-wrap" data-aos="fade-up" data-aos-duration="1000">
            <h4 class="inner-breadcrumb-title">{{__('Events')}}</h4>
            <ol class="breadcrumb inner-breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('frontend')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('event.list')}}">{{__('Event List')}}</a></li>
                <li class="breadcrumb-item"><a href="">{{__('Event Details')}}</a></li>
            </ol>
        </div>
        </div>
    </section>

    <div class="section-gap">
        <div class="container">
            <div class="row rg-20">
                <div class="col-lg-8">
                    <div class="event-details-content p-0" data-aos="fade-up" data-aos-duration="1000">
                        <div class="imgBlock">
                            <img src="{{ getFileUrl($eventData->image) }}" class="w-100" alt=""/>
                        </div>
                        <h4 class="title">{{ $eventData->title }}</h4>
                        <div class="info">
                        {!! $eventData->description !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="subject-details-sidebar bd-ra-20 bg-secondary p-sm-25 p-15" data-aos="fade-up" data-aos-duration="1000">
                        <h4 class="title">{{__('Event Details')}}</h4>
                        <ul class="zList-pb-13">
                            <li class="item">
                                <div class="left w-100">
                                    <div class="icon">
                                        <img src="{{ asset('assets/images/icon/location-2.png') }}" alt=""/>
                                    </div>
                                    <p class="fs-16 fw-400 lh-28 text-para-text d-flex flex-grow-1 g-5">{{ $eventData->location }}</p>
                                </div>
                            </li>
                            @if(count($eventData->studyLevelsName))
                                <li class="item">
                                    <div class="left w-100">
                                        <div class="icon">
                                            <img src="{{ asset('assets/images/icon/cap-2.svg') }}" alt=""/>
                                        </div>
                                        <p class="fs-16 fw-400 lh-28 text-para-text d-flex flex-grow-1 flex-wrap g-5">
                                            <span class="flex-shrink-0">{{ __('Study Level') }} : </span>
                                            @foreach($eventData->studyLevelsName as $level)
                                                <span>{{ $level }}</span>@if(!$loop->last),@endif
                                            @endforeach
                                        </p>
                                    </div>
                                </li>
                            @endif
                            @if(count($eventData->countryName))
                                <li class="item">
                                    <div class="left w-100">
                                        <div class="icon">
                                            <img src="{{ asset('assets/images/icon/globe.svg') }}" alt=""/>
                                        </div>
                                        <p class="fs-16 fw-400 lh-28 text-para-text d-flex flex-grow-1 flex-wrap g-5">
                                            <span class="flex-shrink-0">{{ __('Study Destination') }} : </span>
                                            @foreach($eventData->countryName as $level)
                                                <span>{{ $level }}</span>@if(!$loop->last),@endif
                                            @endforeach
                                        </p>
                                    </div>
                                </li>
                            @endif
                            @if(count($eventData->universityName))
                                <li class="item">
                                    <div class="left w-100">
                                        <div class="icon">
                                            <img src="{{ asset('assets/images/icon/ranking.svg') }}" alt=""/>
                                        </div>
                                        <p class="fs-16 fw-400 lh-28 text-para-text d-flex flex-grow-1 flex-wrap g-5">
                                            <span class="flex-shrink-0">{{ __('University') }} : </span>
                                            @foreach($eventData->universityName as $level)
                                                <span>{{ $level }}</span>@if(!$loop->last),@endif
                                            @endforeach
                                        </p>
                                    </div>
                                </li>
                            @endif
                            <li class="item">
                                <div class="left w-100">
                                    <div class="icon"><img src="{{asset('assets/images/icon/time.svg')}}"
                                                                  alt=""/></div>
                                    <p class="fs-16 fw-400 lh-28 text-para-text d-flex flex-grow-1 g-5">
                                        <span class="flex-shrink-0">{{__('Date-Time')}} : </span>
                                        {{ \Carbon\Carbon::parse($eventData->date_time)->format('M jS Y , g:i A') }}</p>
                                </div>
                            </li>
                            <li class="item">
                                <div class="left w-100">
                                    <div class="icon"><img
                                                src="{{ asset('assets/images/icon/event-22.svg') }}" alt=""/>
                                    </div>
                                    @if($eventData->type == EVENT_TYPE_PHYSICAL)
                                        <p class="fs-16 fw-400 lh-28 text-para-text d-flex flex-grow-1 g-5">
                                            <span class="flex-shrink-0">{{__('Event Type')}} : </span>
                                            {{__('Physical')}}</p>
                                    @elseif($eventData->type == EVENT_TYPE_VIRTUAL)
                                        <p class="fs-16 fw-400 lh-28 text-para-text d-flex flex-grow-1 g-5">
                                            <span class="flex-shrink-0">{{__('Event Type')}} : </span> {{__('Virtual')}}</p>
                                    @endif
                                </div>
                            </li>
                            <li class="item">
                                <div class="left w-100">
                                    <div class="icon"><img
                                                src="{{ asset('assets/images/icon/price.svg') }}" alt=""/>
                                    </div>
                                    <p class="fs-16 fw-400 lh-28 text-para-text d-flex flex-grow-1 g-5">
                                        <span class="flex-shrink-0">{{__('Price')}}: </span>
                                        {{ showPrice($eventData->price) }}</p>
                                </div>
                            </li>
                        </ul>
                       @if(!auth()->check() || auth()->user()->role == USER_ROLE_STUDENT)
                            <div class="pt-20">
                                <a href="{{route('student.checkout', ['type' => 'event', 'slug' => $eventData->slug])}}" class="sf-btn-icon-primary w-100">
                                    {{__('Book Ticket')}}
                                    <span class="icon">
                                        <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                                        <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                                    </span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(count($moreEvent))
        <!-- Related -->
        <section class="section-gap bg-secondary-bg">
            <div class="container" data-aos="fade-up" data-aos-duration="1000">
                <div class="section-content-wrap max-w-550 m-auto">
                    <h4 class="section-title text-center">{{__('More Events')}}</h4>
                </div>
                <!--  -->
                <div class="swiper threeSlider">
                    <div class="swiper-wrapper">
                        @foreach($moreEvent as $data)
                            <div class="swiper-slide">
                                <div class="course-item-three">
                                    <a href="{{route('event.details',$data->slug)}}" class="img">
                                        <img src="{{ getFileUrl($data->image) }}" alt=""/>
                                        <p class="dateTime">{{ \Carbon\Carbon::parse($data->date_time)->format('M jS Y , g:i A') }}</p>
                                    </a>
                                    <div class="course-content">
                                        <ul class="category-list">
                                            @if($data->type == EVENT_TYPE_PHYSICAL)
                                                <li><p class="item">{{__('Physical')}}</p></li>
                                            @elseif($data->type == EVENT_TYPE_VIRTUAL)
                                                <li><p class="item">{{__('Virtual')}}</p></li>
                                            @endif
                                        </ul>
                                        <div class="text-content">
                                            <h4 class="title">{{ $data->title }}</h4>
                                            <div class="location">
                                                <div class="icon d-flex"><img
                                                            src="{{ asset('assets/images/icon/location.svg') }}"
                                                            alt=""/></div>
                                                <p class="text">{{ $data->location }}</p>
                                            </div>
                                        </div>
                                        <a href="{{route('event.details',$data->slug)}}"
                                           class="link">{{__('Book a Ticket')}} <i
                                                    class="fa-solid fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="sliderControls">
                        <div class="swiper-button-prev">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none"><path d="M8.82617 1.87305L4.01367 6.68555L3.71289 7L4.01367 7.31445L8.82617 12.127L9.45508 11.498L4.95703 7L9.45508 2.50195L8.82617 1.87305Z" fill="#999999"/></svg>
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-next">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none"><path d="M6.17383 1.87305L5.54492 2.50195L10.043 7L5.54492 11.498L6.17383 12.127L10.9863 7.31445L11.2871 7L10.9863 6.68555L6.17383 1.87305Z" fill="#999999"/></svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

@endsection
