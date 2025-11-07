@extends('frontend.layouts.app')
@push('title')
    {{ __('Home') }}
@endpush
@section('content')
    <!-- Breadcrumb -->
    <section class="inner-breadcrumb-section">
        <div class="container">
            <div class="inner-breadcrumb-wrap" data-aos="fade-up" data-aos-duration="1000">
            <h4 class="inner-breadcrumb-title">{{__('Service Details')}}</h4>
            <ol class="breadcrumb inner-breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('frontend')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item"><a href="#">{{__('Service Details')}}</a></li>
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
                                    <img src="{{ getFileUrl($serviceData->image) }}" class="w-100" alt=""/>
                                </div>
                                <h4 class="title">{{ $serviceData->title }}</h4>
                                <div class="info">
                                    {!! $serviceData->description !!}
                                </div>
                            </div>
                        </div>
                        @if(!auth()->check() || auth()->user()->role == USER_ROLE_STUDENT)
                        <div class="mt-40">
                            <a href="{{route('student.checkout',['type' => 'service' , 'slug' =>  $serviceData->slug])}}" class="sf-btn-icon-primary">
                                {{__('Get the Service')}}
                                <span class="icon">
                                    <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                                    <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                                </span>
                            </a>
                        </div>
                        @endif
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
                        <h4 class="title">{{__('Service Details')}}</h4>
                        <ul class="zList-pb-13">
                            <li class="item">
                                <div class="left w-100">
                                    <div class="icon"><img src="{{ asset('assets/images/icon/location-2.png') }}" alt="" /></div>
                                    <p class="fs-16 fw-400 lh-28 text-para-text d-flex g-5 flex-grow-1 text-break">
                                        <span class="text-break">{{__('Title')}} : </span> {{ $serviceData->title }}
                                    </p>
                                </div>
                            </li>
                            <li class="item">
                                <div class="left w-100">
                                    <div class="icon"><img src="{{ asset('assets/images/icon/price.svg') }}" alt="" /></div>
                                    <p class="fs-16 fw-400 lh-28 text-para-text d-flex g-5 flex-grow-1 text-break">
                                        <span class="text-break">{{__('Price')}} : </span> {{ showPrice($serviceData->price) }}
                                    </p>
                                </div>
                            </li>
                            @foreach($serviceData->feature as $data)
                                <li class="item">
                                    <div class="left w-100">
                                        <div class="icon"><img src="{{ asset('assets/images/icon/teacher.svg') }}" alt="" /></div>
                                        <p class="fs-16 fw-400 lh-28 text-para-text d-flex g-5 flex-grow-1 text-break">
                                            <span class="text-break">{{$data['name']}} : </span>
                                            {{$data['value']}}
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
