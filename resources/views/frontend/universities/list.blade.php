@extends('frontend.layouts.app')
@push('title')
    {{ __('Home') }}
@endpush
@section('content')
    <!-- Breadcrumb -->
    <section class="inner-breadcrumb-section">
        <div class="container">
            <div class="inner-breadcrumb-wrap" data-aos="fade-up" data-aos-duration="1000">
            <h4 class="inner-breadcrumb-title">{{__('University')}}</h4>
            <ol class="breadcrumb inner-breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('frontend')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('universities.list')}}">{{__('University List')}}</a></li>
            </ol>
        </div>
        </div>
    </section>
    <div class="section-gap-top">
        <div class="container" data-aos="fade-up" data-aos-duration="1000">
            <div class="row rg-24 align-items-center pb-md-40 pb-20">
                <div class="col-lg-8">
                    <div class="align-items-center course-search-suggestion">
                        <h4 class="title"> {{__('Showing')}}</h4>
                        <span class="fs-18">
                            {{ ($universityData->currentPage() - 1) * $universityData->perPage() + 1 }}
                            -
                            {{ min($universityData->currentPage() * $universityData->perPage(), $universityData->total()) }}
                            {{__('of')}}
                            {{ $universityData->total() }} {{__('universities')}}
                        </span>
                    </div>
                </div>
                <div class="col-lg-4">
                    <!-- Form for Sorting -->
                    <form id="sortForm" method="GET" action="{{ route('universities.list') }}">
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
                                        <option
                                            value="ranking-asc" {{ request('sort_by') == 'ranking-asc' ? 'selected' : '' }}>{{__('Ranking High to Low')}}</option>
                                        <option
                                            value="ranking-desc" {{ request('sort_by') == 'ranking-desc' ? 'selected' : '' }}>{{__('Ranking Low to High')}}</option>
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
                        <form id="filterForm" method="GET" action="{{ route('universities.list') }}">
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
                                 id="accordionCountry">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseStudyLevel"
                                                aria-expanded="true"
                                                aria-controls="collapseStudyLevel">{{__('Country')}}</button>
                                    </h2>
                                    <div id="collapseStudyLevel" class="accordion-collapse collapse show"
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
                        </form> <!-- End of Country Filter Form -->
                    </div>
                </div>

                <div class="col-lg-8 col-md-6">
                    <div class="row rg-24" data-aos="fade-up" data-aos-duration="1000">
                        @forelse($universityData as $university)
                            <div class="col-lg-6">
                                <div class="course-item-two">
                                    <a href="{{ route('universities.details', $university->slug) }}" class="img">
                                        <img src="{{getFileUrl($university->thumbnail_image)}}"
                                             alt="{{$university->name}}"/>
                                    </a>
                                    <div class="course-content">
                                        <div class="text-content">
                                            <a href="{{ route('universities.details', $university->slug) }}"
                                               class="title">{{$university->name}}</a>
                                            <p class="author">{{$university->countryName}}</p>
                                        </div>
                                        <ul class="list zList-pb-6">
                                            <li class="item">
                                                <div class="icon d-flex">
                                                    <img src="{{asset('assets/images/icon/world-ranking.svg')}}"
                                                         alt=""/>
                                                </div>
                                                <p class="text">{{__('World Ranking')}}
                                                    : {{$university->world_ranking}}</p>
                                            </li>
                                            <li class="item">
                                                <div class="icon d-flex">
                                                    <img
                                                        src="{{asset('assets/images/icon/international-students.svg')}}"
                                                        alt=""/>
                                                </div>
                                                <p class="text">{{__('International Students')}}
                                                    : {{$university->international_student}}</p>
                                            </li>
                                        </ul>
                                        <a href="{{route('universities.details', $university->slug)}}"
                                           class="link">{{__('View Details & Subjects ')}}<i
                                                class="fa-solid fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-md-12">
                                <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
                                    <p>{{__('No universities found')}}</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    {{$universityData->links()}}

                </div>
            </div>
        </div>
    </div>
@endsection
