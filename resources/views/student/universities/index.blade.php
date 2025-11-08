@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center">
        <h4 class="fs-24 fw-600 lh-34 text-black-2">{{ __('Universities') }}</h4>
    </div>
    <div class="p-sm-30 p-15">
        <div class="bg-white bd-half bd-c-stroke br-8 p-30">
            <!-- Search Tabs Section -->
            <div class="hero-banner-filterWrap">
                <div class="zTab-wrap">
                    <ul class="nav nav-tabs zTab-reset zTab-one" id="universityTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="universities-tab" data-bs-toggle="tab"
                                    data-bs-target="#universities-tab-pane" type="button" role="tab"
                                    aria-controls="universities-tab-pane"
                                    aria-selected="true">{{__('Universities')}}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="courses-tab" data-bs-toggle="tab"
                                    data-bs-target="#courses-tab-pane" type="button" role="tab"
                                    aria-controls="courses-tab-pane" aria-selected="false">{{__('Subject')}}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="scholarships-tab" data-bs-toggle="tab"
                                    data-bs-target="#scholarships-tab-pane" type="button" role="tab"
                                    aria-controls="scholarships-tab-pane"
                                    aria-selected="false">{{__('Scholarships')}}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="events-tab" data-bs-toggle="tab"
                                    data-bs-target="#events-tab-pane" type="button" role="tab"
                                    aria-controls="events-tab-pane" aria-selected="false">{{__('Events')}}
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="tab-content mt-4" id="universityTabContent">
                    <!-- Universities Tab -->
                    <div class="tab-pane fade show active" id="universities-tab-pane" role="tabpanel"
                         aria-labelledby="universities-tab" tabindex="0">
                        <form class="universitySearchForm" method="GET" action="{{ route('student.universities.index') }}">
                            <div class="hero-banner-filterInut">
                                <div class="item">
                                    <label for="inputStudyDestination" class="zForm-label">
                                        {{ __('Study Destination') }} <span>*</span>
                                    </label>
                                    <input type="hidden" class="universityCountryRoute"
                                           value="{{ route('universities.byCountry') }}">
                                    <select class="country-select sf-select-checkbox" name="country[]">
                                        <option class="d-none" disabled
                                                selected>{{ __('Select Destination') }}</option>
                                        @foreach($countryData as $data)
                                            <option value="{{ $data->id }}" {{ in_array($data->id, request('country', [])) ? 'selected' : '' }}>{{ $data->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="error-message country-error text-danger d-none">
                                        {{ __('Please select a country.') }}
                                    </div>
                                </div>
                                <div class="item">
                                    <label for="inputSelectSubjects" class="zForm-label">
                                        {{ __('Select University') }}
                                    </label>
                                    <select name="university[]"
                                            class="university-select sf-select-checkbox"></select>
                                </div>
                                <button type="submit" class="searchButton flipBtn sf-flipBtn-brand-alt">
                                    <div class="d-none h-25 spinner-border w-25" role="status">
                                        <span class="visually-hidden"></span>
                                    </div>
                                    {{ __('Search') }}
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- Subject Tab -->
                    <div class="tab-pane fade" id="courses-tab-pane" role="tabpanel" aria-labelledby="courses-tab"
                         tabindex="0">
                        <form class="subjectSearchForm" method="GET" action="{{ route('student.universities.index') }}">
                            <input type="hidden" name="tab" value="subject">
                            <div class="hero-banner-filterInut hero-banner-filterInputThree">
                                <div class="item">
                                    <label for="inputStudyDestination" class="zForm-label">
                                        {{ __('Study Destination') }} <span>*</span>
                                    </label>
                                    <input type="hidden" class="subjectCountryRoute"
                                           value="{{ route('universities.byCountry') }}">
                                    <select class="subject-country-select sf-select-checkbox" name="country[]">
                                        <option class="d-none" disabled
                                                selected>{{__('Select Destination')}}</option>
                                        @foreach($countryData as $data)
                                            <option value="{{ $data->id }}" {{ in_array($data->id, request('country', [])) ? 'selected' : '' }}>{{ $data->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="error-message subject-country-error text-danger d-none">
                                        {{ __('Please select a country.') }}
                                    </div>
                                </div>
                                <div class="item">
                                    <label for="inputSelectSubjects" class="zForm-label">
                                        {{ __('Select University') }}
                                    </label>
                                    <select name="university[]"
                                            class="subject-university-select sf-select-checkbox">
                                        <option class="d-none" disabled selected value="">{{ __('Select University') }}</option>
                                    </select>
                                </div>
                                <div class="item">
                                    <label for="inputSubjectCategory" class="zForm-label">{{ __('Subject Category') }}</label>
                                    <select class="sf-select-checkbox" name="subject_category[]">
                                        <option class="d-none" selected
                                                disabled>{{__('Select Subject Category')}}</option>
                                        @foreach($subjectCategories as $category)
                                            <option value="{{ $category->id }}" {{ in_array($category->id, request('subject_category', [])) ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="item">
                                    <label for="inputStudyLevel" class="zForm-label">{{__('Study Level')}}</label>
                                    <select class="sf-select-checkbox" name="study_level[]">
                                        <option class="d-none" selected
                                                disabled>{{__('Select Study Level')}}</option>
                                        @foreach($studyLevels as $data)
                                            <option value="{{ $data->id }}" {{ in_array($data->id, request('study_level', [])) ? 'selected' : '' }}>{{ $data->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="searchButton flipBtn sf-flipBtn-brand-alt">
                                    <div class="d-none h-25 spinner-border w-25" role="status">
                                        <span class="visually-hidden"></span>
                                    </div>
                                    {{__('Search')}}
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- Scholarships Tab -->
                    <div class="tab-pane fade" id="scholarships-tab-pane" role="tabpanel"
                         aria-labelledby="scholarships-tab" tabindex="0">
                        <form class="scholarshipSearchForm" method="GET" action="{{ route('student.universities.index') }}">
                            <input type="hidden" name="tab" value="scholarship">
                            <div class="hero-banner-filterInut">
                                <div class="item">
                                    <label for="inputStudyDestination"
                                           class="zForm-label">{{__('Study Destination')}}
                                        <span>*</span>
                                    </label>
                                    <select class="scholarship-country-select sf-select-checkbox" name="country[]">
                                        <option class="d-none" disabled
                                                selected>{{__('Select Destination')}}</option>
                                        @foreach($countryData as $data)
                                            <option value="{{ $data->id }}" {{ in_array($data->id, request('country', [])) ? 'selected' : '' }}>{{ $data->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="error-message scholarship-country-error text-danger d-none">
                                        {{ __('Please select a country.') }}
                                    </div>
                                </div>
                                <div class="item">
                                    <label for="inputStudyLevel" class="zForm-label">{{__('Study Level')}}</label>
                                    <select class="sf-select-checkbox" name="study_level[]">
                                        <option class="d-none" selected
                                                disabled>{{__('Select Study Level')}}</option>
                                        @foreach($studyLevels as $data)
                                            <option value="{{ $data->id }}" {{ in_array($data->id, request('study_level', [])) ? 'selected' : '' }}>{{ $data->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="searchButton flipBtn sf-flipBtn-brand-alt">
                                    <div class="d-none h-25 spinner-border w-25" role="status">
                                        <span class="visually-hidden"></span>
                                    </div>
                                    {{__('Search')}}
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- Events Tab -->
                    <div class="tab-pane fade" id="events-tab-pane" role="tabpanel" aria-labelledby="events-tab"
                         tabindex="0">
                        <form class="eventSearchForm" method="GET" action="{{ route('student.universities.index') }}">
                            <input type="hidden" name="tab" value="event">
                            <div class="hero-banner-filterInut hero-banner-filterInputThree">
                                <div class="item">
                                    <label for="inputStudyDestination"
                                           class="zForm-label">{{__('Study Destination')}}
                                        <span>*</span></label>
                                    <input type="hidden" class="eventCountryRoute"
                                           value="{{ route('universities.byCountry') }}">
                                    <select class="event-country-select sf-select-checkbox" name="country[]">
                                        <option class="d-none" disabled
                                                selected>{{__('Select Destination')}}</option>
                                        @foreach($countryData as $data)
                                            <option value="{{ $data->id }}" {{ in_array($data->id, request('country', [])) ? 'selected' : '' }}>{{ $data->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="error-message event-country-error text-danger d-none">
                                        {{ __('Please select a country.') }}
                                    </div>
                                </div>
                                <div class="item">
                                    <label for="inputSelectSubjects" class="zForm-label">
                                        {{ __('Select University') }}
                                    </label>
                                    <select name="university[]"
                                            class="event-university-select sf-select-checkbox">
                                        <option class="d-none" disabled selected value="">{{ __('Select University') }}</option>
                                    </select>
                                </div>
                                <div class="item">
                                    <label for="inputStudyLevel" class="zForm-label">{{__('Study Level')}}</label>
                                    <select class="sf-select-checkbox" name="study_level[]">
                                        <option class="d-none" selected
                                                disabled>{{__('Select Study Level')}}</option>
                                        @foreach($studyLevels as $data)
                                            <option value="{{ $data->id }}" {{ in_array($data->id, request('study_level', [])) ? 'selected' : '' }}>{{ $data->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="searchButton flipBtn sf-flipBtn-brand-alt">
                                    <div class="d-none h-25 spinner-border w-25" role="status">
                                        <span class="visually-hidden"></span>
                                    </div>
                                    {{__('Search')}}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Search Results Section -->
            @if($showResults)
            <div class="mt-5" id="search-results-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fs-20 fw-600 mb-0">{{ __('Search Results') }}</h5>
                    @if($universityData->total() > 0)
                        <span class="text-muted">
                            {{ __('Showing') }} {{ $universityData->firstItem() ?? 0 }} - {{ $universityData->lastItem() ?? 0 }} 
                            {{ __('of') }} {{ $universityData->total() }} {{ __('universities') }}
                        </span>
                    @endif
                </div>

                @if($universityData->count() > 0)
                    <div class="row rg-15">
                        @foreach($universityData as $university)
                            <div class="col-xl-3 col-md-4 col-sm-6">
                                <div class="course-item-two">
                                    <a href="{{ route('universities.details', $university->slug) }}" class="img" target="_blank">
                                        <img src="{{getFileUrl($university->thumbnail_image)}}" 
                                             alt="{{$university->name}}" style="height: 180px; object-fit: cover; width: 100%;"/>
                                    </a>
                                    <div class="course-content">
                                        <div class="text-content">
                                            <a href="{{ route('universities.details', $university->slug) }}" 
                                               class="title" target="_blank">{{$university->name}}</a>
                                            @if($university->country)
                                                <p class="author">{{$university->country->name}}</p>
                                            @endif
                                        </div>
                                        <ul class="list zList-pb-6">
                                            @if($university->world_ranking)
                                                <li class="item">
                                                    <div class="icon d-flex">
                                                        <img src="{{asset('assets/images/icon/world-ranking.svg')}}" alt=""/>
                                                    </div>
                                                    <p class="text">{{__('World Ranking')}}: {{$university->world_ranking}}</p>
                                                </li>
                                            @endif
                                            @if($university->international_student)
                                                <li class="item">
                                                    <div class="icon d-flex">
                                                        <img src="{{asset('assets/images/icon/international-students.svg')}}" alt=""/>
                                                    </div>
                                                    <p class="text">{{__('International Students')}}: {{$university->international_student}}</p>
                                                </li>
                                            @endif
                                        </ul>
                                        <a href="{{route('universities.details', $university->slug)}}" 
                                           class="link" target="_blank">{{__('View Details')}}<i class="fa-solid fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $universityData->links('layouts.partial.common_pagination_with_count') }}
                    </div>
                @else
                    <div class="alert alert-info">
                        {{ __('No universities found matching your search criteria.') }}
                    </div>
                @endif
            </div>
            @else
            <div class="mt-5" id="search-results-section">
                <h5 class="fs-20 fw-600 mb-4">{{ __('Search Results') }}</h5>
                <div class="alert alert-info">
                    {{ __('Please select a country and click search to view results.') }}
                </div>
            </div>
            @endif

            <!-- Subject Search Results Section -->
            @if($showSubjectResults)
            <div class="mt-5" id="subject-search-results-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fs-20 fw-600 mb-0">{{ __('Subject Search Results') }}</h5>
                    @if($subjectData->total() > 0)
                        <span class="text-muted">
                            {{ __('Showing') }} {{ $subjectData->firstItem() ?? 0 }} - {{ $subjectData->lastItem() ?? 0 }} 
                            {{ __('of') }} {{ $subjectData->total() }} {{ __('subjects') }}
                        </span>
                    @endif
                </div>

                @if($subjectData->count() > 0)
                    <div class="row rg-15">
                        @foreach($subjectData as $subject)
                            <div class="col-xl-3 col-md-4 col-sm-6">
                                <div class="course-item-two">
                                    <a href="{{ route('subject.details', $subject->slug) }}" class="img" target="_blank">
                                        <img src="{{getFileUrl($subject->banner_image)}}" 
                                             alt="{{$subject->name}}" style="height: 180px; object-fit: cover; width: 100%;"/>
                                    </a>
                                    <div class="course-content">
                                        <div class="text-content">
                                            <a href="{{ route('subject.details', $subject->slug) }}" 
                                               class="title" target="_blank">{{$subject->name}}</a>
                                            @if($subject->universitiesName)
                                                <p class="author">{{$subject->universitiesName}}</p>
                                            @endif
                                        </div>
                                        <ul class="list zList-pb-6">
                                            @if($subject->countryName)
                                                <li class="item">
                                                    <div class="icon d-flex">
                                                        <i class="fa-solid fa-location-dot"></i>
                                                    </div>
                                                    <p class="text">{{__('Country')}}: {{$subject->countryName}}</p>
                                                </li>
                                            @endif
                                            @if($subject->studyLevels)
                                                <li class="item">
                                                    <div class="icon d-flex">
                                                        <i class="fa-solid fa-graduation-cap"></i>
                                                    </div>
                                                    <p class="text">{{__('Level')}}: {{$subject->studyLevels}}</p>
                                                </li>
                                            @endif
                                            @if($subject->subjectCategoriesName)
                                                <li class="item">
                                                    <div class="icon d-flex">
                                                        <i class="fa-solid fa-book"></i>
                                                    </div>
                                                    <p class="text">{{__('Category')}}: {{$subject->subjectCategoriesName}}</p>
                                                </li>
                                            @endif
                                            @if($subject->duration)
                                                <li class="item">
                                                    <div class="icon d-flex">
                                                        <i class="fa-solid fa-clock"></i>
                                                    </div>
                                                    <p class="text">{{__('Duration')}}: {{$subject->duration}}</p>
                                                </li>
                                            @endif
                                        </ul>
                                        <a href="{{route('subject.details', $subject->slug)}}" 
                                           class="link" target="_blank">{{__('View Details')}}<i class="fa-solid fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $subjectData->links('layouts.partial.common_pagination_with_count') }}
                    </div>
                @else
                    <div class="alert alert-info">
                        {{ __('No subjects found matching your search criteria.') }}
                    </div>
                @endif
            </div>
            @endif

            <!-- Scholarship Search Results Section -->
            @if($showScholarshipResults)
            <div class="mt-5" id="scholarship-search-results-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fs-20 fw-600 mb-0">{{ __('Scholarship Search Results') }}</h5>
                    @if($scholarshipData->total() > 0)
                        <span class="text-muted">
                            {{ __('Showing') }} {{ $scholarshipData->firstItem() ?? 0 }} - {{ $scholarshipData->lastItem() ?? 0 }} 
                            {{ __('of') }} {{ $scholarshipData->total() }} {{ __('scholarships') }}
                        </span>
                    @endif
                </div>

                @if($scholarshipData->count() > 0)
                    <div class="row rg-15">
                        @foreach($scholarshipData as $scholarship)
                            <div class="col-xl-3 col-md-4 col-sm-6">
                                <div class="course-item-two">
                                    <a href="{{ route('scholarship.details', $scholarship->slug) }}" class="img" target="_blank">
                                        <img src="{{getFileUrl($scholarship->banner_image)}}" 
                                             alt="{{$scholarship->title}}" style="height: 180px; object-fit: cover; width: 100%;"/>
                                    </a>
                                    <div class="course-content">
                                        <div class="text-content">
                                            <a href="{{ route('scholarship.details', $scholarship->slug) }}" 
                                               class="title" target="_blank">{{$scholarship->title}}</a>
                                            @if($scholarship->countryName)
                                                <p class="author">{{$scholarship->countryName}}</p>
                                            @endif
                                        </div>
                                        <ul class="list zList-pb-6">
                                            @if($scholarship->universitiesName)
                                                <li class="item">
                                                    <div class="icon d-flex">
                                                        <i class="fa-solid fa-building-columns"></i>
                                                    </div>
                                                    <p class="text">{{$scholarship->universitiesName}}</p>
                                                </li>
                                            @endif
                                            @if($scholarship->studyLevels)
                                                <li class="item">
                                                    <div class="icon d-flex">
                                                        <i class="fa-solid fa-graduation-cap"></i>
                                                    </div>
                                                    <p class="text">{{__('Level')}}: {{$scholarship->studyLevels}}</p>
                                                </li>
                                            @endif
                                            @if($scholarship->funding_type)
                                                <li class="item">
                                                    <div class="icon d-flex">
                                                        <i class="fa-solid fa-money-bill-wave"></i>
                                                    </div>
                                                    <p class="text">{{__('Funding')}}: {{ucfirst($scholarship->funding_type)}}</p>
                                                </li>
                                            @endif
                                            @if($scholarship->application_end_date)
                                                <li class="item">
                                                    <div class="icon d-flex">
                                                        <i class="fa-solid fa-calendar"></i>
                                                    </div>
                                                    <p class="text">{{__('Deadline')}}: {{date('M d, Y', strtotime($scholarship->application_end_date))}}</p>
                                                </li>
                                            @endif
                                        </ul>
                                        <a href="{{route('scholarship.details', $scholarship->slug)}}" 
                                           class="link" target="_blank">{{__('View Details')}}<i class="fa-solid fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $scholarshipData->links('layouts.partial.common_pagination_with_count') }}
                    </div>
                @else
                    <div class="alert alert-info">
                        {{ __('No scholarships found matching your search criteria.') }}
                    </div>
                @endif
            </div>
            @endif

            <!-- Event Search Results Section -->
            @if($showEventResults)
            <div class="mt-5" id="event-search-results-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fs-20 fw-600 mb-0">{{ __('Event Search Results') }}</h5>
                    @if($eventData->total() > 0)
                        <span class="text-muted">
                            {{ __('Showing') }} {{ $eventData->firstItem() ?? 0 }} - {{ $eventData->lastItem() ?? 0 }} 
                            {{ __('of') }} {{ $eventData->total() }} {{ __('events') }}
                        </span>
                    @endif
                </div>

                @if($eventData->count() > 0)
                    <div class="row rg-15">
                        @foreach($eventData as $event)
                            <div class="col-xl-3 col-md-4 col-sm-6">
                                <div class="course-item-two">
                                    <a href="{{ route('event.details', $event->slug) }}" class="img" target="_blank">
                                        <img src="{{getFileUrl($event->image)}}" 
                                             alt="{{$event->title}}" style="height: 180px; object-fit: cover; width: 100%;"/>
                                    </a>
                                    <div class="course-content">
                                        <div class="text-content">
                                            <a href="{{ route('event.details', $event->slug) }}" 
                                               class="title" target="_blank">{{$event->title}}</a>
                                            <p class="author">{{ucfirst($event->type)}}</p>
                                        </div>
                                        <ul class="list zList-pb-6">
                                            @if($event->date_time)
                                                <li class="item">
                                                    <div class="icon d-flex">
                                                        <i class="fa-solid fa-calendar-days"></i>
                                                    </div>
                                                    <p class="text">{{date('M d, Y h:i A', strtotime($event->date_time))}}</p>
                                                </li>
                                            @endif
                                            @if($event->location)
                                                <li class="item">
                                                    <div class="icon d-flex">
                                                        <i class="fa-solid fa-location-dot"></i>
                                                    </div>
                                                    <p class="text">{{$event->location}}</p>
                                                </li>
                                            @endif
                                            @if($event->price)
                                                <li class="item">
                                                    <div class="icon d-flex">
                                                        <i class="fa-solid fa-tag"></i>
                                                    </div>
                                                    <p class="text">{{__('Price')}}: {{$event->price}}</p>
                                                </li>
                                            @endif
                                        </ul>
                                        <a href="{{route('event.details', $event->slug)}}" 
                                           class="link" target="_blank">{{__('View Details')}}<i class="fa-solid fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $eventData->links('layouts.partial.common_pagination_with_count') }}
                    </div>
                @else
                    <div class="alert alert-info">
                        {{ __('No events found matching your search criteria.') }}
                    </div>
                @endif
            </div>
            @endif
        </div>
    </div>
@endsection
@push('script')
    <script>
        (function ($) {
            "use strict";

            $(document).ready(function() {
                
                // Initialize multiselect for country select with onChange handler
                $('.country-select').multiselect({
                    buttonClass: "form-select sf-select-checkbox-btn",
                    enableFiltering: false,
                    maxHeight: 322,
                    numberDisplayed: 1,
                    templates: {
                        button: '<button type="button" class="multiselect dropdown-toggle" data-bs-toggle="dropdown"><span class="multiselect-selected-text"></span></button>',
                    },
                    onChange: function(option, checked) {
                        // Get the selected country values
                        const countryId = $('.country-select').val();
                        const $form = $('.country-select').closest('.universitySearchForm');
                        const baseUrl = $form.find('.universityCountryRoute').val();
                        
                        $form.find('.country-error').addClass('d-none');
                        
                        if (countryId && countryId.length > 0) {
                            const url = `${baseUrl}/${countryId[0]}`;
                            
                            // Make AJAX call to get universities
                            $.ajax({
                                type: 'GET',
                                url: url,
                                dataType: 'json',
                                success: function(response) {
                                    if (response.status) {
                                        let universityOptions = '<option class="d-none" disabled selected value="">Select University</option>';
                                        response.data.forEach(function (university) {
                                            universityOptions += `<option value="${university.id}">${university.name}</option>`;
                                        });
                                        
                                        // Update university select
                                        const $universitySelect = $form.find('.university-select');
                                        $universitySelect.html(universityOptions);
                                        
                                        // Destroy and reinitialize multiselect
                                        if ($universitySelect.data('multiselect')) {
                                            $universitySelect.multiselect('destroy');
                                        }
                                        
                                        $universitySelect.multiselect({
                                            buttonClass: "form-select sf-select-checkbox-btn",
                                            enableFiltering: false,
                                            maxHeight: 322,
                                            templates: {
                                                button: '<button type="button" class="multiselect dropdown-toggle" data-bs-toggle="dropdown"><span class="multiselect-selected-text"></span></button>',
                                            },
                                        });
                                    } else {
                                        $form.find('.university-select').html('<option value="">No universities available</option>');
                                    }
                                },
                                error: function(error) {
                                    console.error('Error loading universities:', error);
                                    toastr.error('Failed to load universities');
                                }
                            });
                        } else {
                            $form.find('.university-select').html('<option value="">Select University</option>');
                        }
                    }
                });

                // Initialize multiselect for Subject tab country select with onChange handler
                $('.subject-country-select').multiselect({
                    buttonClass: "form-select sf-select-checkbox-btn",
                    enableFiltering: false,
                    maxHeight: 322,
                    numberDisplayed: 1,
                    includeSelectAllOption: false,
                    enableCaseInsensitiveFiltering: false,
                    templates: {
                        button: '<button type="button" class="multiselect dropdown-toggle" data-bs-toggle="dropdown"><span class="multiselect-selected-text"></span></button>',
                    },
                    onChange: function(option, checked) {
                        // Get the selected country values
                        const countryId = $('.subject-country-select').val();
                        const $form = $('.subject-country-select').closest('.subjectSearchForm');
                        const baseUrl = $form.find('.subjectCountryRoute').val();
                        
                        $form.find('.subject-country-error').addClass('d-none');
                        
                        if (countryId && countryId.length > 0) {
                            const url = `${baseUrl}/${countryId[0]}`;
                            
                            // Make AJAX call to get universities
                            $.ajax({
                                type: 'GET',
                                url: url,
                                dataType: 'json',
                                success: function(response) {
                                    if (response.status) {
                                        let universityOptions = '<option class="d-none" disabled selected value="">Select University</option>';
                                        response.data.forEach(function (university) {
                                            universityOptions += `<option value="${university.id}">${university.name}</option>`;
                                        });
                                        
                                        // Update university select
                                        const $universitySelect = $form.find('.subject-university-select');
                                        $universitySelect.html(universityOptions);
                                        
                                        // Destroy and reinitialize multiselect
                                        if ($universitySelect.data('multiselect')) {
                                            $universitySelect.multiselect('destroy');
                                        }
                                        
                                        $universitySelect.multiselect({
                                            buttonClass: "form-select sf-select-checkbox-btn",
                                            enableFiltering: false,
                                            maxHeight: 322,
                                            templates: {
                                                button: '<button type="button" class="multiselect dropdown-toggle" data-bs-toggle="dropdown"><span class="multiselect-selected-text"></span></button>',
                                            },
                                        });
                                    } else {
                                        $form.find('.subject-university-select').html('<option value="">No universities available</option>');
                                    }
                                },
                                error: function(error) {
                                    console.error('Error loading universities:', error);
                                    toastr.error('Failed to load universities');
                                }
                            });
                        } else {
                            $form.find('.subject-university-select').html('<option value="">Select University</option>');
                        }
                    }
                });

                // Initialize multiselect for Scholarship tab country select
                $('.scholarship-country-select').multiselect({
                    buttonClass: "form-select sf-select-checkbox-btn",
                    enableFiltering: false,
                    maxHeight: 322,
                    numberDisplayed: 1,
                    templates: {
                        button: '<button type="button" class="multiselect dropdown-toggle" data-bs-toggle="dropdown"><span class="multiselect-selected-text"></span></button>',
                    }
                });

                // Initialize multiselect for Event tab country select with onChange handler
                $('.event-country-select').multiselect({
                    buttonClass: "form-select sf-select-checkbox-btn",
                    enableFiltering: false,
                    maxHeight: 322,
                    numberDisplayed: 1,
                    templates: {
                        button: '<button type="button" class="multiselect dropdown-toggle" data-bs-toggle="dropdown"><span class="multiselect-selected-text"></span></button>',
                    },
                    onChange: function(option, checked) {
                        // Get the selected country values
                        const countryId = $('.event-country-select').val();
                        const $form = $('.event-country-select').closest('.eventSearchForm');
                        const baseUrl = $form.find('.eventCountryRoute').val();
                        
                        $form.find('.event-country-error').addClass('d-none');
                        
                        if (countryId && countryId.length > 0) {
                            const url = `${baseUrl}/${countryId[0]}`;
                            
                            // Make AJAX call to get universities
                            $.ajax({
                                type: 'GET',
                                url: url,
                                dataType: 'json',
                                success: function(response) {
                                    if (response.status) {
                                        let universityOptions = '<option class="d-none" disabled selected value="">Select University</option>';
                                        response.data.forEach(function (university) {
                                            universityOptions += `<option value="${university.id}">${university.name}</option>`;
                                        });
                                        
                                        // Update university select
                                        const $universitySelect = $form.find('.event-university-select');
                                        $universitySelect.html(universityOptions);
                                        
                                        // Destroy and reinitialize multiselect
                                        if ($universitySelect.data('multiselect')) {
                                            $universitySelect.multiselect('destroy');
                                        }
                                        
                                        $universitySelect.multiselect({
                                            buttonClass: "form-select sf-select-checkbox-btn",
                                            enableFiltering: false,
                                            maxHeight: 322,
                                            templates: {
                                                button: '<button type="button" class="multiselect dropdown-toggle" data-bs-toggle="dropdown"><span class="multiselect-selected-text"></span></button>',
                                            },
                                        });
                                    } else {
                                        $form.find('.event-university-select').html('<option value="">No universities available</option>');
                                    }
                                },
                                error: function(error) {
                                    console.error('Error loading universities:', error);
                                    toastr.error('Failed to load universities');
                                }
                            });
                        } else {
                            $form.find('.event-university-select').html('<option value="">Select University</option>');
                        }
                    }
                });

                // Initialize multiselect for study level dropdowns
                if ($('.sf-select-checkbox').length) {
                    $('.sf-select-checkbox').each(function() {
                        if (!$(this).hasClass('university-select') && 
                            !$(this).hasClass('country-select') && 
                            !$(this).hasClass('subject-university-select') && 
                            !$(this).hasClass('subject-country-select') &&
                            !$(this).hasClass('scholarship-country-select') &&
                            !$(this).hasClass('event-country-select') &&
                            !$(this).hasClass('event-university-select')) {
                            $(this).multiselect({
                                buttonClass: "form-select sf-select-checkbox-btn",
                                enableFiltering: false,
                                maxHeight: 322,
                                templates: {
                                    button: '<button type="button" class="multiselect dropdown-toggle" data-bs-toggle="dropdown"><span class="multiselect-selected-text"></span></button>',
                                },
                            });
                        }
                    });
                }

                // Form submission validation for Universities tab
                $('.universitySearchForm').on('submit', function(e) {
                    const countrySelected = $(this).find('.country-select').val();
                    $(this).find('.country-error').addClass('d-none');

                    if (!countrySelected || countrySelected.length === 0) {
                        e.preventDefault();
                        $(this).find('.country-error').removeClass('d-none');
                        return false;
                    }
                    
                    // Show loading spinner
                    $(this).find('.searchButton').prop('disabled', true);
                    $(this).find('.searchButton .spinner-border').removeClass('d-none');
                });

                // Form submission validation for Subject tab
                $('.subjectSearchForm').on('submit', function(e) {
                    const countrySelected = $(this).find('.subject-country-select').val();
                    $(this).find('.subject-country-error').addClass('d-none');

                    if (!countrySelected || countrySelected.length === 0) {
                        e.preventDefault();
                        $(this).find('.subject-country-error').removeClass('d-none');
                        return false;
                    }
                    
                    // Show loading spinner
                    $(this).find('.searchButton').prop('disabled', true);
                    $(this).find('.searchButton .spinner-border').removeClass('d-none');
                });

                // Form submission validation for Scholarship tab
                $('.scholarshipSearchForm').on('submit', function(e) {
                    const countrySelected = $(this).find('.scholarship-country-select').val();
                    $(this).find('.scholarship-country-error').addClass('d-none');

                    if (!countrySelected || countrySelected.length === 0) {
                        e.preventDefault();
                        $(this).find('.scholarship-country-error').removeClass('d-none');
                        return false;
                    }
                    
                    // Show loading spinner
                    $(this).find('.searchButton').prop('disabled', true);
                    $(this).find('.searchButton .spinner-border').removeClass('d-none');
                });

                // Form submission validation for Event tab
                $('.eventSearchForm').on('submit', function(e) {
                    const countrySelected = $(this).find('.event-country-select').val();
                    $(this).find('.event-country-error').addClass('d-none');

                    if (!countrySelected || countrySelected.length === 0) {
                        e.preventDefault();
                        $(this).find('.event-country-error').removeClass('d-none');
                        return false;
                    }
                    
                    // Show loading spinner
                    $(this).find('.searchButton').prop('disabled', true);
                    $(this).find('.searchButton .spinner-border').removeClass('d-none');
                });

                // If there are existing search results, scroll to them
                @if($showResults)
                    @if($universityData->count() > 0)
                        setTimeout(function() {
                            $('html, body').animate({
                                scrollTop: $("#search-results-section").offset().top - 100
                            }, 500);
                        }, 300);
                    @endif
                @endif

                // If there are existing subject search results, scroll to them and activate Subject tab
                @if($showSubjectResults)
                    // Activate Subject tab
                    $('#courses-tab').tab('show');
                    
                    @if($subjectData->count() > 0)
                        setTimeout(function() {
                            $('html, body').animate({
                                scrollTop: $("#subject-search-results-section").offset().top - 100
                            }, 500);
                        }, 300);
                    @endif
                @endif

                // If there are existing scholarship search results, scroll to them and activate Scholarship tab
                @if($showScholarshipResults)
                    // Activate Scholarship tab
                    $('#scholarships-tab').tab('show');
                    
                    @if($scholarshipData->count() > 0)
                        setTimeout(function() {
                            $('html, body').animate({
                                scrollTop: $("#scholarship-search-results-section").offset().top - 100
                            }, 500);
                        }, 300);
                    @endif
                @endif

                // If there are existing event search results, scroll to them and activate Event tab
                @if($showEventResults)
                    // Activate Event tab
                    $('#events-tab').tab('show');
                    
                    @if($eventData->count() > 0)
                        setTimeout(function() {
                            $('html, body').animate({
                                scrollTop: $("#event-search-results-section").offset().top - 100
                            }, 500);
                        }, 300);
                    @endif
                @endif

                // Check URL parameter to activate correct tab
                const urlParams = new URLSearchParams(window.location.search);
                const activeTab = urlParams.get('tab');
                if (activeTab === 'subject') {
                    $('#courses-tab').tab('show');
                } else if (activeTab === 'scholarship') {
                    $('#scholarships-tab').tab('show');
                } else if (activeTab === 'event') {
                    $('#events-tab').tab('show');
                }
            });

        })(jQuery);
    </script>
@endpush
