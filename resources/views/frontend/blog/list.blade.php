@extends('frontend.layouts.app')
@push('title')
    {{ __('Home') }}
@endpush
@section('content')
    <!-- Breadcrumb -->
    <section class="inner-breadcrumb-section">
        <div class="container">
            <div class="inner-breadcrumb-wrap" data-aos="fade-up" data-aos-duration="1000">
            <h4 class="inner-breadcrumb-title">{{__('Blog')}}</h4>
            <ol class="breadcrumb inner-breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('frontend')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('blog.list')}}">{{__('Blog List')}}</a></li>
            </ol>
        </div>
        </div>
    </section>

    <!-- Blog -->
    <section class="section-gap bg-brand-primary blogList-inner-section" data-background="{{asset('assets/images/articles-overlay-bg.svg')}}">
        <div class="container">
            <div class="row rg-24 justify-content-between align-items-center pb-24" data-aos="fade-up" data-aos-duration="1000">
                <div class="col-lg-5">
                    <div class="max-w-473">
                        <h4 class="section-title text-white mb-24">{{__('Insights, Inspiration, and Innovation.')}}</h4>
                        <a href="{{route('register')}}" class="sf-btn-icon-primary">
                            {{__('Register Now')}}
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
                                        <h4 class="name">{{ $blogData[0]->firstName }} {{ $blogData[0]->lastName }}</h4>
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
            <div class="row rg-24" data-aos="fade-up" data-aos-duration="1000">
                @foreach($blogData->skip(1)->take(3) as $data)
                    <div class="col-xl-4 col-md-6">
                        <div class="blog-item-one">
                            <div class="topContent">
                                <div class="author">
                                    <img src="{{getFileUrl($data->userImage)}}" alt="">
                                    <div class="nameDegi">
                                        <h4 class="name">{{ $data->firstName }} {{ $data->lastName }}</h4>
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
    <!-- More Blog -->
    @if(count($blogMoreArticlesData))
        <section class="section-gap">
        <div class="container" data-aos="fade-up" data-aos-duration="1000">
            <div class="section-content-wrap max-w-550 m-auto">
                <h4 class="section-title text-center">{{__('More Articles')}}</h4>
            </div>
            <!--  -->
            <div class="row rg-24">
                @foreach($blogMoreArticlesData as $data)
                    <div class="col-xl-4 col-md-6">
                        <div class="blog-item-one">
                            <div class="topContent">
                                <div class="author">
                                    <img src="{{getFileUrl($data->userImage)}}" alt="">
                                    <div class="nameDegi">
                                        <h4 class="name">{{ $data->firstName }} {{ $data->lastName }}</h4>
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

@endsection
