@extends('frontend.layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <!-- Breadcrumb -->
    <section class="inner-breadcrumb-section">
        <div class="container">
            <div class="inner-breadcrumb-wrap" data-aos="fade-up" data-aos-duration="1000">
            <h4 class="inner-breadcrumb-title">{{$pageTitle}}</h4>
            <ol class="breadcrumb inner-breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('frontend')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('consultations.list')}}">{{__('Consultation')}}</a></li>
                <li class="breadcrumb-item"><a href="#">{{$pageTitle}}</a></li>
            </ol>
        </div>
        </div>
    </section>

    <!-- Consultant Details -->
    <section class="section-gap-top">
        <div class="container">
            <div class="row rg-24">
                <div class="col-lg-5">
                    <div class="consultant-details-left" data-background="{{asset('assets/images/why-choose-us.png')}}" data-aos="fade-up" data-aos-duration="1000">
                        <div class="img">
                            <img class="w-100" src="{{getFileUrl($consultant->image)}}" alt="{{$consultant->name}}"/>
                        </div>
                        <div class="text-content">
                            <h4 class="title">{{$consultant->name}}</h4>
                            <p class="info">{{$consultant->professional_title}}</p>
                            <p class="info">{{__('Total Consultation Completed')}} : {{$consultant->completed_consultation}}</p>
                            <p class="info">{{$consultant->experience}}</p>
                            <p class="price">{{showPrice($consultant->fee)}}/{{__('Hour')}}</p>
                        </div>
                        <div class="availableDay">
                            <h4 class="title">{{ __('Available Day') }}</h4>
                            <ul class="dayList">
                                @foreach(dayShort() as $dayIndex => $dayName)
                                    @if(!in_array($dayIndex, $consultant->day_off ?? []))
                                        <li><a href="#" class="item">{{ $dayName }}</a></li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="consultant-details-right" data-aos="fade-up" data-aos-duration="1000">
                        <div class="text-content">
                            <h4 class="title">{{__('About')}} {{$consultant->name}}</h4>
                            <div class="info">
                                {!! $consultant->about_me !!}
                            </div>
                        </div>
                        <a href="{{route('consultations.booking', encodeId($consultant->id))}}" class="sf-btn-icon-primary">
                            {{__('Book Consultation')}}
                            <span class="icon">
                        <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                        <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                    </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if(count($reviews))
    <!-- Feedback -->
    <section class="section-gap-top overflow-hidden">
        <div class="container" data-aos="fade-up" data-aos-duration="1000">
            <div class="section-content-wrap max-w-550 m-auto">
                <h4 class="section-title text-center">{{__('Students Feedback')}}</h4>
            </div>
            <div class="swiper ladingTestimonials-slider">
                <div class="swiper-wrapper">
                    @foreach ($reviews as $data)
                        <div class="swiper-slide">
                            <div class="testimonial-item-one">
                                <p class="info">{{ $data->comment }}</p>
                                <div class="content">
                                    <div class="text-content">
                                        <h4 class="name">— {{ $data->reviewer->name }}</h4>
                                        <p class="date">{{ formatDate($data->created_at, 'F j, Y') }}</p>
                                    </div>
                                    <div class="img">
                                        <img src="{{ getFileUrl($data->reviewer->image) }}" alt="{{ $data->reviewer->image }}"/>
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
