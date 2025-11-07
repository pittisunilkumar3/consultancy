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
                        <form class="searchForm" method="GET" action="{{ route('universities.list') }}" target="_blank">
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
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
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
                        <form class="searchForm" method="GET" action="{{ route('subject.list') }}" target="_blank">
                            <div class="hero-banner-filterInut hero-banner-filterInputThree">
                                <div class="item">
                                    <label for="inputStudyDestination" class="zForm-label">
                                        {{ __('Study Destination') }} <span>*</span>
                                    </label>
                                    <input type="hidden" class="universityCountryRoute"
                                           value="{{ url('universities') }}">
                                    <select class="country-select sf-select-checkbox" name="country[]">
                                        <option class="d-none" disabled
                                                selected>{{__('Select Destination')}}</option>
                                        @foreach($countryData as $data)
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
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
                                <div class="item">
                                    <label for="inputStudyLevel" class="zForm-label">{{__('Study Level')}}</label>
                                    <select class="sf-select-checkbox" name="study_level[]">
                                        <option class="d-none" selected
                                                disabled>{{__('Selected Study Level')}}</option>
                                        @foreach($studyLevels as $data)
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
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
                        <form class="searchForm" method="GET" action="{{ route('scholarship.list') }}" target="_blank">
                            <div class="hero-banner-filterInut">
                                <div class="item">
                                    <label for="inputStudyDestination"
                                           class="zForm-label">{{__('Study Destination')}}
                                        <span>*</span>
                                    </label>
                                    <select class="country-select sf-select-checkbox" name="country[]">
                                        <option class="d-none" disabled
                                                selected>{{__('Select Destination')}}</option>
                                        @foreach($countryData as $data)
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="error-message country-error text-danger d-none">
                                        {{ __('Please select a country.') }}
                                    </div>
                                </div>
                                <div class="item">
                                    <label for="inputStudyLevel" class="zForm-label">{{__('Study Level')}}</label>
                                    <select class="sf-select-checkbox" name="study_level[]">
                                        <option class="d-none" selected
                                                disabled>{{__('Select Study Level')}}</option>
                                        @foreach($studyLevels as $data)
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
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
                        <form class="searchForm" method="GET" action="{{ route('event.list') }}" target="_blank">
                            <div class="hero-banner-filterInut hero-banner-filterInputThree">
                                <div class="item">
                                    <label for="inputStudyDestination"
                                           class="zForm-label">{{__('Study Destination')}}
                                        <span>*</span></label>
                                    <input type="hidden" class="universityCountryRoute"
                                           value="{{ url('universities') }}">
                                    <select class="country-select sf-select-checkbox" name="country[]">
                                        <option class="d-none" disabled
                                                selected>{{__('Select Destination')}}</option>
                                        @foreach($countryData as $data)
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
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
                                <div class="item">
                                    <label for="inputStudyLevel" class="zForm-label">{{__('Study Level')}}</label>
                                    <select class="sf-select-checkbox" name="study_level[]">
                                        <option class="d-none" selected
                                                disabled>{{__('Select Study Level')}}</option>
                                        @foreach($studyLevels as $data)
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
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
            <div class="mt-5" id="search-results-section">
                <h5 class="fs-20 fw-600 mb-4">{{ __('Search Results') }}</h5>
                <div class="alert alert-info">
                    {{ __('Please select a country and search to view results. Results will open in a new tab.') }}
                </div>
            </div>
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
                        const $form = $('.country-select').closest('.searchForm');
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

                // Initialize multiselect for study level dropdowns
                if ($('.sf-select-checkbox').length) {
                    $('.sf-select-checkbox').each(function() {
                        if (!$(this).hasClass('university-select') && !$(this).hasClass('country-select')) {
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

                // Form submission validation
                $('.searchForm').on('submit', function(e) {
                    const countrySelected = $(this).find('.country-select').val();
                    $(this).find('.country-error').addClass('d-none');

                    if (!countrySelected || countrySelected.length === 0) {
                        e.preventDefault();
                        $(this).find('.country-error').removeClass('d-none');
                    } else {
                        $(this).find('.searchButton').prop('disabled', true);
                        $(this).find('.searchButton .spinner-border').removeClass('d-none');
                    }
                });
            });

        })(jQuery);
    </script>
@endpush
