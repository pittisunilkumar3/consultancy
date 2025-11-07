@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@push('style')
    <!-- Plyr CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/plyr.css')}}"/>
@endpush
@section('content')
    <div class="p-sm-30 p-15">
        <!-- Course Content -->
                <h4 class="fs-24 fw-700 lh-32 text-title-text pb-20">{{ $course?->title }}</h4>
        <div class="row rg-20">
            <div class="col-xll-3 col-lg-5 col-md-6">
                <div class="accordion zAccordion-reset zAccordion-one" id="accordionModule">
                    @foreach($course->lessons as $lesson)
                        <div class="item-wrap lesson-item">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button
                                        class="accordion-button {{ $lesson->id == $currentResource->lecture->lesson->id ? '' : 'collapsed' }}"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse-lesson-{{$lesson->id}}"
                                        aria-expanded="{{ $lesson->id == $currentResource->lecture->lesson->id ? 'true' : 'false' }}"
                                        aria-controls="collapse-lesson-{{$lesson->id}}">
                                        {{ $lesson->title }}
                                    </button>
                                </h2>
                                <div id="collapse-lesson-{{$lesson->id}}"
                                     class="accordion-collapse collapse {{ $lesson->id == $currentResource->lecture->lesson->id ? 'show' : '' }}"
                                     data-bs-parent="#accordionModule">
                                    <div class="accordion-body">
                                        <p class="fs-12 fw-500 lh-18 text-para-text pb-20">
                                            {!! $lesson->description !!}
                                        </p>
                                        <ul class="course-modules-list lecture-list">
                                            @foreach($lesson->lectures as $lecture)
                                                <li class="item">
                                                    <div class="content-wrap">
                                                        <p class="fs-12 fw-500 lh-24 text-para-text pt-11 lecture-number">{{addLeadingZero($loop->iteration)}}</p>
                                                        <div class="moduleDetails-content-alt">
                                                            <form class="lecture-form ajax" method="POST"
                                                                  action="{{route('admin.courses.lectures.store', ['course_id' => $course->id, 'lesson_id' => $lesson->id])}}"
                                                                  data-handler="settingCommonHandler">
                                                                @csrf
                                                                <div class="d-flex align-items-center g-10 titleWrap">
                                                                    <input type="hidden" name="id"
                                                                           value="{{$lecture->id}}">
                                                                    <p class="px-20 w-100">{{$lecture->title}}</p>
                                                                    <button
                                                                        class="{{ $lecture->id == $currentResource->lecture->id ? '' : 'collapsed' }}"
                                                                        type="button"
                                                                        data-bs-toggle="collapse"
                                                                        data-bs-target="#collapse-lecture-{{$lecture->id}}"
                                                                        aria-expanded="{{ $lecture->id == $currentResource->lecture->id ? 'true' : 'false' }}"
                                                                        aria-controls="collapse-lecture-{{$lecture->id}}">
                                                                        <img
                                                                            src="{{asset('assets/images/icon/accordion-icon-1-alt.svg')}}"
                                                                            alt="" class="defaultIcon"/>
                                                                    </button>
                                                                </div>
                                                            </form>

                                                            <div
                                                                class="collapse {{ $lecture->id == $currentResource->lecture->id ? 'show' : '' }}"
                                                                id="collapse-lecture-{{$lecture->id}}">
                                                                @if(count($lecture->resources))
                                                                    <div class="card card-body">
                                                                        <ul class="zList-pb-5">
                                                                            @foreach($lecture->resources as $resource)
                                                                                <li class="d-flex justify-content-between align-items-center g-10">
                                                                                    <div class="d-flex g-7">
                                                                                        <div class="d-flex">
                                                                                            <!-- Determine icon based on resource status -->
                                                                                            @if(in_array($resource->id, $finishedResourceIds))
                                                                                                <img
                                                                                                    src="{{ asset('assets/images/icon/video-complete.svg') }}"
                                                                                                    alt="{{__('Finished')}}"/>
                                                                                            @elseif($resource->id == $currentResourceId)
                                                                                                <img
                                                                                                    src="{{ asset('assets/images/icon/video-processing.svg') }}"
                                                                                                    alt="{{__('Current')}}"/>
                                                                                            @else
                                                                                                <img
                                                                                                    src="{{ asset('assets/images/icon/video-incomplete.svg') }}"
                                                                                                    alt="{{__('Unfinished')}}"/>
                                                                                            @endif
                                                                                        </div>
                                                                                        <a href="{{ route('student.my_courses.view', ['enrollment_id' => encodeId($course->enrollment_id), 'lecture_id' => encodeId($lecture->id), 'resource_id' => encodeId($resource->id)]) }}"
                                                                                           title="{{ __('Play') }}">
                                                                                            <p class="fs-15 fw-500 lh-24 text-para-text">{{ $resource->title }}</p>
                                                                                        </a>
                                                                                    </div>
                                                                                    <div
                                                                                        class="d-flex align-items-center g-5">
                                                                                        <p class="fs-15 fw-500 lh-24 text-para-text">
                                                                                            @if($resource->duration)
                                                                                                {{ getResourceDuration($resource->duration) }}
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
                                                                    <div class="card card-body text-center">
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
                        </div>
                    @endforeach
                </div>
                @if(!count($course->lessons))
                    <div
                        class="align-items-center bd-ra-5 bg-white course-details-info d-flex flex-column g-10 h-100 justify-content-center p-16">
                        <span class="text-center">{{__('No module found')}}</span>
                    </div>
                @endif
            </div>

            <div class="col-xll-9 col-lg-7 col-md-6">
                @if($currentResource)
                    <div>
                        @if($currentResource->resource_type == RESOURCE_TYPE_LOCAL)
                            <!-- Local Video Player -->
                            <video class="w-100" id="video-player" controls>
                                <source src="{{ getFileUrl($currentResource->resource) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @elseif($currentResource->resource_type == RESOURCE_TYPE_PDF)
                            <!-- PDF Viewer -->
                            <div class="p-md-30 p-sm-25 p-15 bd-one bd-c-stroke bd-ra-5 bg-white">
                                <iframe src="{{ getFileUrl($currentResource->resource) }}" class="w-100" height="600"
                                        style="border: none;"></iframe>
                            </div>
                        @elseif($currentResource->resource_type == RESOURCE_TYPE_IMAGE)
                            <!-- Image Viewer -->
                            <img src="{{ getFileUrl($currentResource->resource) }}"
                                 alt="{{ $currentResource->title }}" class="w-100">
                        @elseif($currentResource->resource_type == RESOURCE_TYPE_AUDIO)
                            <!-- Audio Player -->
                            <div class="p-md-30 p-sm-25 p-15 bd-one bd-c-stroke bd-ra-5 bg-white">
                                <audio class="w-100" id="audio-player" controls>
                                    <source src="{{ getFileUrl($currentResource->resource) }}" type="audio/mp3">
                                    Your browser does not support the audio tag.
                                </audio>
                            </div>
                        @elseif($currentResource->resource_type == RESOURCE_TYPE_YOUTUBE_ID)
                            <!-- YouTube Player -->
                            <div class="video-player-area">
                                <div class="youtube-player">
                                    <div id="youtube-player-video" class="youtube-video" data-video-id="{{ $currentResource->resource }}"></div>

                                    <button class="youtube-video-overBtn"><i class="fa fa-play"></i></button>

                                    <div class="youtube-player-controls">
                                        <button class="play-button">
                                            <i class="fa fa-play"></i>
                                        </button>
                                        <progress class="progress-bar" min="0" max="100" value="0"></progress>
                                        <span class="progress-text"></span>

                                        <button class="sound-button">
                                            <i class="fa fa-volume-up"></i>
                                        </button>
                                        <progress class="sound-bar" min="0" max="100" value="0"></progress>
                                        <button class="fullscreen-button">
                                            <i class="fa fa-expand"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @elseif($currentResource->resource_type == RESOURCE_TYPE_SLIDE)
                            <!-- Slide Content (e.g., HTML or embedded iframe) -->
                            <div class="p-md-30 p-sm-25 p-15 bd-one bd-c-stroke bd-ra-5 bg-white">
                                {!! $currentResource->resource !!}
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{asset('assets/js/plyr.js')}}"></script>
    <script src="{{asset('common/js/youtube-player.js')}}"></script>
    <script src="{{asset('common/js/player.js')}}"></script>
@endpush
