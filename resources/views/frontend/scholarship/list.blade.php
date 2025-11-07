@extends('frontend.layouts.app')
@push('title')
    {{ __('Home') }}
@endpush
@section('content')
    <!-- Breadcrumb -->
    <section class="inner-breadcrumb-section">
        <div class="container">
            <div class="inner-breadcrumb-wrap" data-aos="fade-up" data-aos-duration="1000">
            <h4 class="inner-breadcrumb-title">{{__('Scholarship')}}</h4>
            <ol class="breadcrumb inner-breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('frontend')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('scholarship.list')}}">{{__('Scholarship List')}}</a></li>
            </ol>
        </div>
        </div>
    </section>
    <div class="section-gap-top">
        <div class="container">
            <div class="row rg-24 align-items-center pb-md-40 pb-20" data-aos="fade-up" data-aos-duration="1000">
                <div class="col-lg-8">
                    <div class="align-items-center course-search-suggestion">
                        <h4 class="title"> {{__('Showing')}}</h4>
                        <span class="fs-18">
                            {{ ($scholarshipData->currentPage() - 1) * $scholarshipData->perPage() + 1 }}
                            -
                            {{ min($scholarshipData->currentPage() * $scholarshipData->perPage(), $scholarshipData->total()) }}
                            {{__('of')}}
                            {{ $scholarshipData->total() }} {{__('scholarships')}}
                        </span>
                    </div>
                </div>
                <div class="col-lg-4">
                    <!-- Form for Sorting -->
                    <form id="sortForm" method="GET" action="{{ route('scholarship.list') }}">
                        <!-- Persist filter values as hidden inputs -->
                        <input type="hidden" name="search_key" value="{{ request('search_key') }}">
                        @foreach(request('country', []) as $country)
                            <input type="hidden" name="country[]" value="{{ $country }}">
                        @endforeach

                        <div class="d-flex justify-content-lg-end">
                            <div class="course-search-sort">
                                <div class="d-flex align-items-center g-10">
                                    <div class="icon d-flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21"
                                             viewBox="0 0 21 21" fill="none">
                                            <path d="M9.66699 7.16675L16.3337 7.1668" stroke="#636370"
                                                  stroke-width="2" stroke-linecap="round"
                                                  stroke-linejoin="round"/>
                                            <path d="M9.66699 10.5H13.8337" stroke="#636370"
                                                  stroke-width="2" stroke-linecap="round"
                                                  stroke-linejoin="round"/>
                                            <path d="M9.66699 13.8333H12.167" stroke="#636370"
                                                  stroke-width="2" stroke-linecap="round"
                                                  stroke-linejoin="round"/>
                                            <path d="M9.66699 3.83325H18.0003" stroke="#636370"
                                                  stroke-width="2" stroke-linecap="round"
                                                  stroke-linejoin="round"/>
                                            <path
                                                d="M5.08333 18V3M5.08333 18C4.49981 18 3.40961 16.3381 3 15.9167M5.08333 18C5.66686 18 6.75706 16.3381 7.16667 15.9167"
                                                stroke="#636370" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <h4 class="title">{{__('Sort By')}} :</h4>
                                    <select name="sort_by" class="sf-select-checkbox"
                                            onchange="document.getElementById('sortForm').submit();">
                                        <option value="">{{__('Default')}}</option>
                                        <option
                                            value="name-asc" {{ request('sort_by') == 'name-asc' ? 'selected' : '' }}>{{__('Name A to Z')}}</option>
                                        <option
                                            value="name-desc" {{ request('sort_by') == 'name-desc' ? 'selected' : '' }}>{{__('Name Z to A')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form> <!-- End of Sorting Form -->
                </div>
            </div>
            <div class="row rg-24">
                <div class="col-lg-4 col-md-6">
                    <div class="course-search-sidebar" data-aos="fade-up" data-aos-duration="1000">
                        <!-- Form for Filtering by Country -->
                        <form id="filterForm" method="GET" action="{{ route('scholarship.list') }}">
                            <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                            <div class="pb-25">
                                <div class="search-one">
                                    <input type="text" id="search-key" name="search_key"
                                           value="{{ request('search_key') }}"
                                           placeholder="{{__('Search here...')}}"/>
                                    <button class="icon">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M8.71401 15.7857C12.6194 15.7857 15.7854 12.6197 15.7854 8.71428C15.7854 4.80884 12.6194 1.64285 8.71401 1.64285C4.80856 1.64285 1.64258 4.80884 1.64258 8.71428C1.64258 12.6197 4.80856 15.7857 8.71401 15.7857Z"
                                                stroke="#707070" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                            <path d="M18.3574 18.3571L13.8574 13.8571" stroke="#707070"
                                                  stroke-width="1.35902" stroke-linecap="round"
                                                  stroke-linejoin="round"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="accordion zAccordion-reset zAccordion-three pb-md-40 pb-20"
                                 id="accordionStudyLevel">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseStydyLevel"
                                                aria-expanded="true"
                                                aria-controls="collapseStydyLevel">{{__('Study Level')}}</button>
                                    </h2>
                                    <div id="collapseStydyLevel" class="accordion-collapse collapse show"
                                         data-bs-parent="#accordionStudyLevel">
                                        <div class="accordion-body">
                                            <ul class="zList-pb-8">
                                                @foreach($studyLevel as $data)
                                                    <li>
                                                        <div class="zForm-wrap-checkbox">
                                                            <input type="checkbox" name="study_level[]"
                                                                   value="{{$data->id}}"
                                                                   class="form-check-input bd-c-stroke-2 bg-transparent"
                                                                   id="study_level-{{$data->id}}"
                                                                   {{ in_array($data->id, request('study_level', [])) ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit();"/>
                                                            <label
                                                                for="study_level-{{$data->id}}">{{$data->name}}</label>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion zAccordion-reset zAccordion-three pb-md-40 pb-20"
                                 id="accordionCountry">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseCountry"
                                                aria-expanded="true"
                                                aria-controls="collapseCountry">{{__('Country')}}</button>
                                    </h2>
                                    <div id="collapseCountry" class="accordion-collapse collapse show"
                                         data-bs-parent="#accordionCountry">
                                        <div class="accordion-body">
                                            <ul class="zList-pb-8">
                                                @foreach($countries as $country)
                                                    <li>
                                                        <div class="zForm-wrap-checkbox">
                                                            <input type="checkbox" name="country[]"
                                                                   value="{{$country->id}}"
                                                                   class="form-check-input bd-c-stroke-2 bg-transparent"
                                                                   id="country-{{$country->id}}"
                                                                   {{ in_array($country->id, request('country', [])) ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit();"/>
                                                            <label
                                                                for="country-{{$country->id}}">{{$country->name}}</label>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion zAccordion-reset zAccordion-three pb-md-40 pb-20"
                                 id="accordionUniversity">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseUniversity"
                                                aria-expanded="true"
                                                aria-controls="collapseUniversity">{{__('University')}}</button>
                                    </h2>
                                    <div id="collapseUniversity" class="accordion-collapse collapse"
                                         data-bs-parent="#accordionUniversity">
                                        <div class="accordion-body">
                                            <ul class="zList-pb-8">
                                                @foreach($universities as $university)
                                                    <li>
                                                        <div class="zForm-wrap-checkbox">
                                                            <input type="checkbox" name="university[]"
                                                                   value="{{$university->id}}"
                                                                   class="form-check-input bd-c-stroke-2 bg-transparent"
                                                                   id="university-{{$university->id}}"
                                                                   {{ in_array($university->id, request('university', [])) ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit();"/>
                                                            <label
                                                                for="university-{{$university->id}}">{{$university->name}}</label>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion zAccordion-reset zAccordion-three"
                                 id="accordionSubject">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseSubjects"
                                                aria-expanded="true"
                                                aria-controls="collapseSubjects">{{__('Subject')}}</button>
                                    </h2>
                                    <div id="collapseSubjects" class="accordion-collapse collapse"
                                         data-bs-parent="#accordionSubject">
                                        <div class="accordion-body">
                                            <ul class="zList-pb-8">
                                                @foreach($subjects as $subject)
                                                    <li>
                                                        <div class="zForm-wrap-checkbox">
                                                            <input type="checkbox" name="subject[]"
                                                                   value="{{$subject->id}}"
                                                                   class="form-check-input bd-c-stroke-2 bg-transparent"
                                                                   id="subject-{{$subject->id}}"
                                                                   {{ in_array($subject->id, request('subject', [])) ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit();"/>
                                                            <label
                                                                for="subject-{{$subject->id}}">{{$subject->name}}</label>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form> <!-- End of Country Filter Form -->
                    </div>
                </div>

                <div class="col-lg-8 col-md-6">
                    <div class="row rg-24" data-aos="fade-up" data-aos-duration="1000">
                        @forelse($scholarshipData as $scholarship)
                            <div class="col-lg-6">
                                <div class="course-item-two">
                                    <a href="{{route('scholarship.details', $scholarship->slug)}}" class="img">
                                        <img src="{{getFileUrl($scholarship->banner_image)}}"
                                             alt="{{$scholarship->name}}"/>
                                    </a>
                                    <div class="course-content">
                                        <div class="text-content">
                                            <a href="{{ route('scholarship.details', $scholarship->slug) }}"
                                               class="title">{{$scholarship->title}}</a>
                                            <p class="author">{{$scholarship->universitiesName}}</p>
                                        </div>
                                        <ul class="list zList-pb-6">
                                            <li class="item">
                                                <div class="icon d-flex">
                                                    <img src="{{asset('assets/images/icon/country.png')}}"
                                                         alt=""/>
                                                </div>
                                                <p class="text">{{$scholarship->countryName}}</p>
                                            </li>
                                            <li class="item">
                                                <div class="icon d-flex">
                                                    <img
                                                        src="{{asset('assets/images/icon/qualification.svg')}}"
                                                        alt=""/>
                                                </div>
                                                <p class="text">{{$scholarship->studyLevels}}</p>
                                            </li>
                                            <li class="item">
                                                <div class="icon d-flex">
                                                    <img
                                                        src="{{asset('assets/images/icon/program-score.svg')}}"
                                                        alt=""/>
                                                </div>
                                                @if($scholarship->funding_type == SCHOLARSHIP_FUNDING_TYPE_PARTIAL)
                                                    <p class="text">{{__('Funding Type')}} : {{__('Partial')}}</p>
                                                @elseif($scholarship->funding_type == SCHOLARSHIP_FUNDING_TYPE_FULL_FUNDED)
                                                    <p class="text">{{__('Funding Type')}} : {{__('Full Funded')}}</p>
                                                @endif
                                            </li>
                                        </ul>
                                        <a href="{{route('scholarship.details', $scholarship->slug)}}"
                                           class="link">{{__('View Details & Subjects ')}}<i
                                                class="fa-solid fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-md-12">
                                <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
                                    <p>{{__('No scholarship found')}}</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    {{$scholarshipData->links()}}

                </div>
            </div>
        </div>
    </div>
@endsection
