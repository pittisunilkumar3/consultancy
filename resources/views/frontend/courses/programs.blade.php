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
                <li class="breadcrumb-item"><a>{{__('Courses')}}</a></li>
                <li class="breadcrumb-item active"><a>{{$pageTitle}}</a></li>
            </ol>
        </div>
        </div>
    </section>

    <!-- Program About -->
    <section class="section-gap">
        <div class="container" data-aos="fade-up" data-aos-duration="1000">
            <div class="row align-items-center rg-24">
                <div class="col-lg-6">
                    <div class="program-content-2">
                        <h4 class="title">{{$program->top_section['title']}}</h4>
                        <div class="text-title-text">
                        {!! $program->top_section['details'] !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="program-img-2">
                        <div class="img-bg"></div>
                        <img src="{{getFileUrl($program->top_section['image'])}}" alt=""/>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if(count($courses))
        <!-- All Course -->
        <section class="section-gap bg-secondary-bg" data-background="{{asset('assets/images/Ielts-Courses.png')}}">
            <div class="container" data-aos="fade-up" data-aos-duration="1000">
                <div class="section-content-wrap max-w-550 m-auto">
                    <h4 class="section-title text-center pb-0">{{$program->title}} {{__('Courses')}}</h4>
                </div>
                <!--  -->
                <div class="row rg-24">
                    @foreach($courses as $course)
                        <div class="col-lg-4 col-md-6">
                            <div class="course-item-two">
                                <a href="{{route('courses.single', $course->slug)}}" class="img">
                                    <img src="{{getFileUrl($course->thumbnail)}}" alt="{{$course->title}}"/>
                                </a>
                                <div class="course-content">
                                    <div class="text-content">
                                        <a href="{{route('courses.single', $course->slug)}}" class="title">{{$course->title}}</a>
                                        @if(isset($course->instructors) && is_array($course->instructors))
                                            <p class="author">
                                                {{ implode(' & ', array_column($course->instructors, 'name')) }}
                                            </p>
                                        @endif
                                    </div>
                                    <ul class="list zList-pb-6">
                                        <li class="item">
                                            <div class="icon d-flex"><img
                                                    src="{{asset('assets/images/icon/course-fee.svg')}}" alt=""/></div>
                                            <p class="text">{{__('Course Fee')}} : {{showPrice($course->price)}}</p>
                                        </li>
                                        <li class="item">
                                            <div class="icon d-flex"><img
                                                    src="{{asset('assets/images/icon/clock.svg')}}" alt=""/></div>
                                            <p class="text">{{__('Duration')}} : {{$course->duration}} {{__('Days')}}</p>
                                        </li>
                                        <li class="item">
                                            <div class="d-flex">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 23 24" fill="none">
                                                    <path d="M7.1875 5.23926H15.3333C16.1272 5.23926 16.7708 5.88285 16.7708 6.67676V8.11426" stroke="#636370" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M14.375 12.9059H8.625" stroke="#636370" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M11.5 16.7393H8.625" stroke="#636370" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M17.7263 2.41667L6.04481 2.41669C5.56856 2.41669 5.08144 2.48657 4.69646 2.76916C3.47582 3.66517 2.53513 5.66351 4.43478 7.46766C4.96816 7.97423 5.71371 8.15787 6.44659 8.15787H17.5216C18.2821 8.15787 19.6458 8.26671 19.6458 10.5887V17.732C19.6458 19.859 17.9307 21.5833 15.815 21.5833H7.16107C5.04928 21.5833 3.51339 20.0908 3.39207 17.8183L3.35997 5.45111" stroke="#636370" stroke-width="1.5" stroke-linecap="round"></path>
                                                </svg>
                                            </div>
                                            <p class="text">{{__('Module')}} : {{$course->lessons_count}}</p>
                                        </li>
                                    </ul>
                                    <a href="{{route('courses.single', $course->slug)}}"
                                       class="link">{{__('View More')}} <i class="fa-solid fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <!-- Step -->
    <section class="section-gap program-step-section" data-background="{{asset('assets/images/step-shape.svg')}}">
        <div class="container" data-aos="fade-up" data-aos-duration="1000">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="program-img">
                        <div class="img-bg"></div>
                        <img src="{{getFileUrl($program->step_section['image'])}}" alt=""/>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="program-content text-white">
                        <h4 class="title">{{$program->step_section['title']}}</h4>
                        <div class="text text-white">
                        {!! $program->step_section['details'] !!}
                        </div>
                    </div>
                </div>
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
                                {{ __($data->question) }}
                            </button>
                        </h2>
                        <div id="collapse{{ $index }}" class="accordion-collapse collapse"
                             aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ __($data->answer) }}</p>
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
                <p class="section-info text-center">{{__('Real stories from students who turned their study abroad dreams into reality with Studylifter.')}}</p>
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
