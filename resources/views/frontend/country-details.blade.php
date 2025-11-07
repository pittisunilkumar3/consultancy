@extends('frontend.layouts.app')
@push('title')
    {{ __('Country') }}
@endpush
@section('content')
    <!-- Breadcrumb -->
    <section class="inner-breadcrumb-section">
        <div class="container">
            <div class="inner-breadcrumb-wrap" data-aos="fade-up" data-aos-duration="1000">
            <h4 class="inner-breadcrumb-title">{{__('Country Details')}}</h4>
            <ol class="breadcrumb inner-breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('frontend')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item"><a href="">{{__('Country Details')}}</a></li>
            </ol>
        </div>
        </div>
    </section>
    <!-- About destination -->
    <section class="section-gap-top">
        <div class="container">
            <div class="scholar-details-content destination-details-content" data-aos="fade-up" data-aos-duration="1000">
                <div class="textBlock-wrap">
                    <div class="text-content">
                        <h4 class="title">{{ $countryData->name }}</h4>
                        <div class="imgBlock">
                            <img src="{{ getFileUrl($countryData->banner_image) }}" class="w-100" alt=""/>
                        </div>
                        <div class="info">
                            {!! $countryData->details !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Top Univeristiy -->
    <section class="section-gap-top">
        <div class="container" data-aos="fade-up" data-aos-duration="1000">
            <div class="section-content-wrap partner-title-wrap">
                <h4 class="section-title text-center">{{__('Top Universities Of Australia')}}</h4>
                <p class="section-info text-center max-w-757 m-auto">{{__('Explore the top universities in Australia, renowned for their world-class education and research excellence.')}}</p>
                <div class="icon-wrap"><img src="{{ asset('assets/images/partner-separate-icon.svg') }}" alt="" /></div>
            </div>
            <!--  -->
            <div class="pb-30">
                <div class="row rg-24">
                    @foreach($universityData as $data)
                        <div class="col-lg-2">
                            <div class="partner-list">
                                <img src="{{ getFileUrl($data->logo) }}" alt="" />
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Core Benefits -->
    <section class="section-gap-top" data-background="{{asset('assets/images/why-choose-us.png')}}">
        <div class="container" data-aos="fade-up" data-aos-duration="1000">
            <!-- Core Benefits -->
            <div class="section-content-wrap max-w-550 m-auto">
                <h4 class="section-title text-center">{{__('Core Benefits')}}</h4>
                <p class="section-info text-center">{{__('Hear from our students who have experienced the life-changing journey of studying abroad with Studylifter’s expert guidance.')}}</p>
            </div>
            <div class="row rg-20">
                @foreach($countryData->core_benefits_title as $key => $title)
                    <div class="col-xl-3 col-md-4 col-sm-6">
                        <div class="choose-item">
                            <div class="icon">
                                <img src="{{ getFileUrl($countryData->core_benefits_icon[$key]) }}" alt="" />
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
                        <a href="{{ getFileUrl($countryData->gallery_image[0]) }}">
                            <img src="{{ getFileUrl($countryData->gallery_image[0]) }}" alt="" />
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row rg-24">
                        <div class="col-12">
                            <div class="row rg-24">
                                <div class="col-md-6">
                                    <div class="university-gallery-img max-h-204"><img src="{{ getFileUrl($countryData->gallery_image[1]) }}" alt="" /></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="university-gallery-img max-h-204"><img src="{{ getFileUrl($countryData->gallery_image[2]) }}" alt="" /></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="university-gallery-img max-h-297"><img src="{{ getFileUrl($countryData->gallery_image[3]) }}" alt="" /></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
