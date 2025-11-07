@extends('frontend.layouts.app')
@push('title')
    {{ __('Home') }}
@endpush
@section('content')
    <!-- Breadcrumb -->
    <section class="inner-breadcrumb-section">
        <div class="container">
            <div class="inner-breadcrumb-wrap" data-aos="fade-up" data-aos-duration="1000">
                <h4 class="inner-breadcrumb-title">{{ $policyPageTitle }}</h4>
                <ol class="breadcrumb inner-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('frontend')}}">{{__('Home')}}</a></li>
                </ol>
            </div>
        </div>
    </section>
    <!-- About destination -->
    <section class="section-gap bg-white">
        <div class="container">
            <div class="scholar-details-content destination-details-content" data-aos="fade-up" data-aos-duration="1000">
                <div class="textBlock-wrap">
                    <div class="text-content">
                        <h4 class="title">{{ $policyPageTitle }}</h4>
                        <div class="info">
                            {!! $policyPageDetails !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
