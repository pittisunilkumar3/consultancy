@extends('frontend.layouts.app')
@push('title')
    {{$pageTitle}}
@endpush
@section('content')

    <!-- Breadcrumb -->
    <section class="inner-breadcrumb-section">
        <div class="container">
            <div class="inner-breadcrumb-wrap" data-aos="fade-up" data-aos-duration="1000">
                <h4 class="inner-breadcrumb-title">{{$pageTitle}}</h4>
                <ol class="breadcrumb inner-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('frontend')}}">{{__('Home')}}</a></li>
                    <li class="breadcrumb-item"><a href="">{{$pageTitle}}</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Consultant -->
    <section class="section-gap-top" data-background="{{asset('assets/images/why-choose-us.png')}}">
        <div class="container">
            <div class="row rg-20" data-aos="fade-up" data-aos-duration="1000">
                @foreach($consultants as $consultant)
                <div class="col-lg-4 col-sm-6">
                    <div class="consultant-item-one">
                        <div class="info-content position-relative z-index-1">
                            <div class="img"><img class="w-100" src="{{getFileUrl($consultant->image)}}" alt="{{$consultant->name}}"/></div>
                            <h4 class="title">{{$consultant->name}}</h4>
                            <p class="info">{{$consultant->professional_title}}</p>
                            <p class="price">{{showPrice($consultant->fee)}}/{{__('Hour')}}</p>
                        </div>
                        <div class="linkWrap position-relative z-index-1">
                            <a href="{{route('consultations.details', encodeId($consultant->id))}}" class="sf-btn-icon-primary">
                                {{__('Book a Consultation')}}
                                <span class="icon">
                                    <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                                    <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            {{$consultants->links()}}
        </div>
    </section>

@endsection
