@extends('frontend.layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@push('style')
    <!-- Plyr CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/plyr.css')}}"/>
@endpush
@section('content')
    <section class="course-single-content-wrap">
        <div class="course-single-overlay bg-red" data-background="{{asset('assets/images/inner-breadcrumb.svg')}}"></div>
        <div class="container">
            <div class="row rg-24">
                <div class="col-lg-8">
                    <div class="course-single-content" data-aos="fade-up" data-aos-duration="1000">
                        <!--  -->
                        <div class="text-content">
                            <h4 class="title">{{$course->title}}</h4>
                            <p class="info">{{$course->subtitle}}</p>
                            @if(isset($course->instructors) && is_array($course->instructors))
                                <p class="author">
                                    {{ implode(' & ', array_column($course->instructors, 'name')) }}
                                </p>
                            @endif
                        </div>
                        <div class="course-single-link justify-content-lg-between">
                            <a href="#courseInstructor" class="link">{{__('Instructor')}}</a>
                            <a href="#courseDetails" class="link">{{__('Details')}}</a>
                            <a href="#courseCurriculum" class="link">{{__('Curriculum')}}</a>
                            <a href="#courseWhatYouLearn" class="link">{{__('What You Will Learn?')}}</a>
                            <a href="#courseFAQ" class="link">{{__('FAQ')}}</a>
                        </div>
                        <div class="course-single-content-details">
                            <!-- Course Instructors -->
                            <div class="itemWrap" id="courseInstructor">
                                <div class="item bg-white">
                                    <h4 class="title">{{__('Course Instructors')}}</h4>
                                    <div class="row rg-24">
                                        @foreach($course->instructors as $instructor)
                                            <div class="col-md-6">
                                                <div class="courseInstructor">
                                                    <div class="img">
                                                        <img src="{{getFileUrl($instructor['photo'])}}"
                                                             alt="{{$instructor['name']}}"/>
                                                    </div>
                                                    <div class="content">
                                                        <h4 class="name">{{$instructor['name']}}</h4>
                                                        <p class="info">{{$instructor['professional_title']}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Course Details -->
                            <div class="itemWrap" id="courseDetails">
                                <div class="item bg-white">
                                    <h4 class="title">{{ __('Details') }}</h4>
                                    <div class="program-content-2">
                                        <p class="info">
                                            {!! $course->description !!}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- course curriculum -->
                            <div class="itemWrap" id="courseCurriculum">
                                <div class="item bg-secondary-bg border-0">
                                    <h4 class="title">{{__('Course Curriculum')}}</h4>
                                    @if(count($course->lessons))
                                        <div class="accordion zAccordion-reset zAccordion-five zAccordion-curriculum"
                                             id="accordionCourseCurriculum">
                                            @foreach($course->lessons as $lesson)
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#collapse-lesson-{{$lesson->id}}"
                                                                aria-controls="collapse-lesson-{{$lesson->id}}"
                                                                aria-expanded="false">{{$lesson->title}}
                                                        </button>
                                                    </h2>
                                                    <div id="collapse-lesson-{{$lesson->id}}"
                                                         class="accordion-collapse collapse"
                                                         data-bs-parent="#accordionCourseCurriculum">
                                                        <div class="accordion-body">
                                                            <p class="">
                                                                {!! $lesson->description !!}
                                                            </p>
                                                            <ul class="course-modules-list lecture-list courseSingle-list">
                                                                @foreach($lesson->lectures as $lecture)
                                                                    <li class="item">
                                                                        <div class="content-wrap">
                                                                            <div class="moduleDetails-content-alt">
                                                                                <div
                                                                                    class="d-flex align-items-center g-10 titleWrap">
                                                                                    <p>{{$lecture->title}}</p>
                                                                                    <button
                                                                                        class="{{$loop->first ? '' : 'collapsed'}}"
                                                                                        type="button"
                                                                                        data-bs-toggle="collapse"
                                                                                        data-bs-target="#collapse-lecture-{{$lecture->id}}"
                                                                                        aria-expanded="{{$loop->first ? 'true' : ''}}"
                                                                                        aria-controls="collapse-lecture-{{$lecture->id}}">
                                                                                        <img
                                                                                            src="{{asset('assets/images/icon/accordion-icon-1-alt.svg')}}"
                                                                                            alt=""
                                                                                            class="defaultIcon"/>
                                                                                    </button>
                                                                                </div>
                                                                                <div
                                                                                    class="collapse {{$loop->first ? 'show' : ''}}"
                                                                                    id="collapse-lecture-{{$lecture->id}}">
                                                                                    @if(count($lecture->resources))
                                                                                        <div class="card card-body">
                                                                                            <ul class="zList-pb-5">
                                                                                                @foreach($lecture->resources as $resource)
                                                                                                    <li class="d-flex justify-content-between align-items-center g-10">
                                                                                                        <div
                                                                                                            class="d-flex g-7">
                                                                                                            <div
                                                                                                                class="d-flex">
                                                                                                                <img
                                                                                                                    src="{{asset('assets/images/icon/'. getResourceIcon($resource->resource_type))}}"
                                                                                                                    alt=""/>
                                                                                                            </div>
                                                                                                            <p class="fs-15 fw-500 lh-24 text-title-text">{{$resource->title}}</p>
                                                                                                        </div>
                                                                                                        <div
                                                                                                            class="d-flex align-items-center g-5">
                                                                                                            <p class="fs-15 fw-500 lh-24 text-para-text">
                                                                                                                @if($resource->duration)
                                                                                                                    {{getResourceDuration($resource->duration)}}
                                                                                                                @else
                                                                                                                    --:--
                                                                                                                @endif
                                                                                                            </p>
                                                                                                        </div>
                                                                                                    </li>
                                                                                                @endforeach
                                                                                            </ul>
                                                                                        </div>
                                                                                    @else
                                                                                        <div
                                                                                            class="card card-body text-center">
                                                                                            {{__('No resource')}}
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div>
                                            {{__('No Curriculum yet')}}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- What learn -->
                            <div class="itemWrap" id="courseWhatYouLearn">
                                <div class="item bg-white">
                                    <h4 class="title">{{ __('What You Will Learn') }}</h4>
                                    <div class="row rg-15">
                                        @if (!empty($course->description_point))
                                            @php
                                                // Split description points into two halves
                                                $half = ceil(count($course->description_point) / 2);
                                                $firstColumn = array_slice($course->description_point, 0, $half);
                                                $secondColumn = array_slice($course->description_point, $half);
                                            @endphp

                                                <!-- First Column -->
                                            <div class="col-md-6">
                                                <ul class="zList-pb-15">
                                                    @foreach($firstColumn as $point)
                                                        <li>
                                                            <div class="courseLearnItem">
                                                                <div class="icon d-flex">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                        <path d="M5 14.5C5 14.5 6.5 14.5 8.5 18C8.5 18 14.0588 8.83333 19 7" stroke="#FB5421" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    </svg>
                                                                </div>
                                                                <h4 class="text">
                                                                    <span>{{ $point['title'] }}:</span> {{ $point['text'] }}
                                                                </h4>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>

                                            <!-- Second Column -->
                                            <div class="col-md-6">
                                                <ul class="zList-pb-15">
                                                    @foreach($secondColumn as $point)
                                                        <li>
                                                            <div class="courseLearnItem">
                                                                <div class="icon d-flex">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                        <path d="M5 14.5C5 14.5 6.5 14.5 8.5 18C8.5 18 14.0588 8.83333 19 7" stroke="#FB5421" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    </svg>
                                                                </div>
                                                                <h4 class="text">
                                                                    <span>{{ $point['title'] }}:</span> {{ $point['text'] }}
                                                                </h4>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>

                                        @else
                                            <div class="col-12">
                                                <p>{{ __('No learning points available.') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- FAQ -->
                            <div class="itemWrap" id="courseFAQ">
                                <div class="item bg-secondary-bg border-0">
                                    <h4 class="title">{{__('FAQs')}}</h4>
                                    @if (!empty($course->faqs))
                                        <div class="accordion zAccordion-reset zAccordion-two" id="accordionExample1">
                                            @foreach($course->faqs as $index => $faq)
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#collapseFaq{{$index}}"
                                                                aria-controls="collapseFaq{{$index}}"
                                                                aria-expanded="false">
                                                            {{ __($faq['question']) }}
                                                        </button>
                                                    </h2>
                                                    <div id="collapseFaq{{$index}}" class="accordion-collapse collapse"
                                                         data-bs-parent="#accordionExample1">
                                                        <div class="accordion-body">
                                                            <p class="">{{ __($faq['answer']) }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div>
                                            {{__('No FAQ yet')}}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="course-single-sidebar" data-aos="fade-up" data-aos-duration="1000">
                        <div class="item">
                            <div class="img">
                                <img src="{{getFileUrl($course->thumbnail)}}" alt="{{$course->title}}"/>
                                <a href="#" class="modalBtn"
                                   onclick="getEditModal('{{ route('courses.video',$course->slug) }}', '#course-video-view-modal')"><i
                                        class="fa-solid fa-play"></i></a>
                            </div>
                            <p class="price">
                                @if($course->price != 0)
                                    {{showPrice($course->price)}}
                                @else
                                    {{__('Free')}}
                                @endif
                            </p>
                            @if(!auth()->check() || auth()->user()->role == USER_ROLE_STUDENT)
                                <a href="{{route('student.checkout', ['type' => 'course', 'slug' => $course->slug])}}" class="sf-btn-icon-primary w-100">
                                    {{__('Buy Course')}}
                                    <span class="icon">
                                        <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                                        <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                                    </span>
                                </a>
                            @else
                                <a class="sf-btn-icon-primary w-100">
                                    {{__('Buy Course')}}
                                    <span class="icon">
                                        <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                                        <img src="{{ asset('assets/images/icon/icon-angle-right.svg') }}" alt="">
                                    </span>
                                </a>
                            @endif
                        </div>
                        <div class="courseFeatures">
                            <h4 class="title">{{__('What is in this course?')}}</h4>
                            <ul class="zList-pb-13">
                                <li class="item">
                                    <div class="left">
                                        <div class="icon">
                                            <img src="{{asset('assets/images/icon/time.svg')}}" alt=""/>
                                        </div>
                                        <p class="fs-16 fw-400 lh-28 text-para-text d-flex g-5 text-break">
                                            <span class="text-title-text flex-shrink-0">{{__('Duration')}} :</span>
                                            {{$course->duration}} {{__('Days')}}
                                        </p>
                                    </div>
                                </li>
                                <li class="item">
                                    <div class="left">
                                        <div class="icon">
                                            <img src="{{asset('assets/images/icon/teacher.svg')}}" alt=""/>
                                        </div>
                                        <p class="fs-16 fw-400 lh-28 text-para-text d-flex g-5 text-break">
                                            <span class="text-title-text flex-shrink-0">{{__('Class Start Date')}} :</span>
                                            {{$course->start_date->format('Y-m-d')}}
                                        </p>
                                    </div>
                                </li>
                                @foreach($course->course_benefits as $benefit)
                                    <li class="item">
                                        <div class="left">
                                            <div class="icon">
                                                <img src="{{asset('assets/images/icon/book-2.svg')}}" alt=""/>
                                            </div>
                                            <p class="fs-16 fw-400 lh-28 text-para-text d-flex g-5 text-break">
                                                <span class="text-title-text flex-shrink-0"> {{$benefit['name']}} :</span>
                                                {{$benefit['value']}}
                                            </p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Resource View Modal section start -->
    <div class="modal fade" id="course-video-view-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4">

            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{asset('assets/js/plyr.js')}}"></script>
    <script src="{{asset('common/js/youtube-player.js')}}"></script>
    <script src="{{asset('common/js/player.js')}}"></script>
@endpush
