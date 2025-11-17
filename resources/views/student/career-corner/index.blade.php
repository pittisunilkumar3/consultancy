@extends('layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush

@push('style')
    <style>
        .career-form-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .career-form-section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.75rem !important;
            padding-bottom: 1rem;
            border-bottom: 3px solid #14b8a6;
            background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .career-form-section-description {
            color: #6b7280;
            margin-bottom: 1.5rem;
            font-size: 1rem;
            line-height: 1.6;
        }

        .career-form-question {
            margin-bottom: 1.5rem;
            padding: 1.25rem;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
        }

        .career-form-question-label {
            display: block;
            font-weight: 600;
            font-size: 1.05rem;
            color: #1f2937;
            margin-bottom: 0.75rem;
        }

        .career-form-question-label .required {
            color: #ef4444;
            margin-left: 0.25rem;
        }

        .career-form-question-help {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.5rem;
            margin-bottom: 0.75rem;
            font-style: italic;
        }

        .career-form-input,
        .career-form-textarea,
        .career-form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .career-form-input:focus:not(:readonly):not(:disabled),
        .career-form-textarea:focus:not(:readonly):not(:disabled),
        .career-form-select:focus:not(:disabled) {
            outline: none;
            border-color: #14b8a6;
            box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.1);
        }

        .career-form-input:hover:not(:readonly):not(:disabled),
        .career-form-textarea:hover:not(:readonly):not(:disabled),
        .career-form-select:hover:not(:disabled) {
            border-color: #d1d5db;
        }

        .career-form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .career-form-select {
            padding: 0.875rem 2.5rem 0.875rem 1rem;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 14 14'%3E%3Cpath fill='%23374151' d='M7 10L2 5h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.875rem center;
            background-size: 1.125rem;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            cursor: pointer;
        }

        .career-form-radio-group {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 0.5rem;
        }

        .career-form-radio-option {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .career-form-radio-option:hover:not(:has(input[type="radio"]:disabled)) {
            border-color: #14b8a6;
            background-color: #f0fdfa;
        }

        .career-form-radio-option input[type="radio"] {
            margin-right: 0.75rem;
            cursor: pointer;
        }

        .career-form-radio-option input[type="radio"]:checked + label {
            font-weight: 600;
            color: #0d9488;
        }

        .career-form-radio-option:has(input[type="radio"]:checked) {
            background: #f0fdfa;
            border-color: #14b8a6;
        }

        .career-form-checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .career-form-checkbox-option {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            background: #ffffff;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .career-form-checkbox-option:hover:not(:has(input[type="checkbox"]:disabled)) {
            border-color: #14b8a6;
            background-color: #f0fdfa;
        }

        .career-form-checkbox-option input[type="checkbox"] {
            margin-right: 0.75rem;
            cursor: pointer;
        }

        .career-form-checkbox-option input[type="checkbox"]:checked + label {
            font-weight: 600;
            color: #0d9488;
        }

        .career-form-checkbox-option:has(input[type="checkbox"]:checked) {
            background: #f0fdfa;
            border-color: #14b8a6;
        }

        .career-form-nested-questions {
            margin-top: 1.5rem;
            margin-left: 0;
            padding-left: 0;
            border-left: none;
            display: none !important;
        }

        .career-form-nested-questions.show {
            display: block !important;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .career-form-empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: #f9fafb;
            border-radius: 0.75rem;
            border: 2px dashed #d1d5db;
        }

        .career-form-empty-state i {
            font-size: 4rem;
            color: #9ca3af;
            margin-bottom: 1.5rem;
        }

        .career-form-empty-state h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.75rem;
        }

        .career-form-empty-state p {
            font-size: 1rem;
            color: #6b7280;
            margin-bottom: 1.5rem;
        }

        .career-form-submit-btn {
            padding: 0.875rem 2rem;
            background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(20, 184, 166, 0.2);
        }

        .career-form-submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(20, 184, 166, 0.3);
        }

        .career-form-submit-btn:active {
            transform: translateY(0);
        }


        /* Validation error styles */
        .career-form-input.is-invalid,
        .career-form-textarea.is-invalid,
        .career-form-select.is-invalid {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
        }

        .career-form-error-message {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            padding-left: 0.25rem;
            font-weight: 500;
        }

        .career-form-error-message:empty {
            display: none !important;
        }

        /* Disabled radio/checkbox styles (for edge cases in editable mode) */
        .career-form-radio-option input[type="radio"]:disabled + label,
        .career-form-checkbox-option input[type="checkbox"]:disabled + label {
            color: #6b7280;
            cursor: not-allowed !important;
        }

        .career-form-radio-option:has(input[type="radio"]:disabled),
        .career-form-checkbox-option:has(input[type="checkbox"]:disabled) {
            cursor: not-allowed !important;
            transition: none;
        }

        input[type="radio"]:disabled,
        input[type="checkbox"]:disabled {
            cursor: not-allowed !important;
        }

        /* Readonly Summary View - Clean display without form fields */
        .career-form-readonly-mode .career-form-question {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            align-items: flex-start;
            gap: 2rem;
            padding: 1.25rem;
            border-bottom: 1px solid #e5e7eb;
            background: #9be4e32b;
            margin-bottom: 2px;
        }

        .career-form-readonly-mode .career-form-question:last-child {
            border-bottom: none;
        }

        .career-form-readonly-mode .career-form-question-label {
            flex: 0 0 40%;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0;
            padding-right: 1rem;
        }

        .career-form-readonly-mode .career-form-question-answer {
            flex: 1;
            color: #1f2937;
            font-size: 1rem;
            line-height: 1.6;
            word-wrap: break-word;
        }

        /* Hide answer divs when not in readonly mode */
        .career-form-question-answer {
            display: none;
        }

        .career-form-readonly-mode .career-form-question-answer {
            display: block;
        }

        .career-form-readonly-mode .career-form-question-answer:empty::before {
            content: '—';
            color: #9ca3af;
            font-style: italic;
        }

        .career-form-readonly-mode .career-form-question-help {
            display: none;
        }

        /* Hide all form inputs in readonly mode */
        .career-form-readonly-mode .career-form-input,
        .career-form-readonly-mode .career-form-textarea,
        .career-form-readonly-mode .career-form-select,
        .career-form-readonly-mode .career-form-radio-group,
        .career-form-readonly-mode .career-form-checkbox-group,
        .career-form-readonly-mode input[type="radio"],
        .career-form-readonly-mode input[type="checkbox"],
        .career-form-readonly-mode input[type="file"] {
            display: none !important;
        }

        /* Show file display in readonly mode - only in answer section */
        .career-form-readonly-mode .career-form-question-answer .career-form-file-display {
            display: inline-flex !important;
            cursor: pointer;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
        }

        .career-form-readonly-mode .career-form-question-answer .career-form-file-display a {
            color: #14b8a6;
            text-decoration: none;
        }

        .career-form-readonly-mode .career-form-question-answer .career-form-file-display a:hover {
            text-decoration: underline;
        }

        /* Hide file displays that are direct children of question (old location) in readonly mode */
        .career-form-readonly-mode .career-form-question > .career-form-file-display {
            display: none !important;
        }

        /* Nested questions in readonly mode - break out of flex layout */
        .career-form-readonly-mode .career-form-nested-questions {
            flex-basis: 100%;
            width: 100%;
            min-width: 100%;
            margin-top: 1.5rem;
            padding-left: 0;
            padding-right: 0;
            border-top: 1px solid #e5e7eb;
            padding-top: 1.25rem;
            margin-left: 0;
            margin-right: 0;
            order: 10;
        }

        .career-form-readonly-mode .career-form-nested-questions .career-form-question {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            gap: 2rem;
            padding-left: 2rem;
            border-left: 3px solid #14b8a6;
            margin-top: 0.75rem;
            margin-bottom: 0.75rem;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            background: #f9fafb;
            border-radius: 0.375rem;
        }

        .career-form-readonly-mode .career-form-nested-questions .career-form-question:first-child {
            margin-top: 0;
        }

        .career-form-readonly-mode .career-form-nested-questions .career-form-question:last-child {
            margin-bottom: 0;
            border-bottom: none;
        }

        .career-form-readonly-mode .career-form-nested-questions .career-form-question-label {
            flex: 0 0 40%;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0;
            padding-right: 1rem;
        }

        .career-form-readonly-mode .career-form-nested-questions .career-form-question-answer {
            flex: 1;
            color: #1f2937;
            font-size: 1rem;
            line-height: 1.6;
            word-wrap: break-word;
        }

        /* Section styling in readonly mode */
        .career-form-readonly-mode .career-form-section {
            background: #ffffff;
            border: none;
            box-shadow: none;
            padding: 5px;
            margin-bottom: 2.5rem;
        }

        .career-form-readonly-mode .career-form-section-title {
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #14b8a6;
        }

        #cancelEditBtn {
            transition: all 0.2s ease;
        }

        #cancelEditBtn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(220, 38, 38, 0.2);
        }

        /* Step-by-step navigation styles */
        .career-form-step-mode .career-form-question {
            display: none;
        }

        .career-form-step-mode .career-form-question.step-active {
            display: block;
            animation: fadeIn 0.3s ease-out;
        }

        /* Ensure nested questions inside active nested containers are visible */
        .career-form-step-mode .career-form-nested-questions.step-active .career-form-question.step-active {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        /* Hide all sections by default in step mode */
        .career-form-step-mode .career-form-section {
            display: none;
            margin-bottom: 0;
        }

        /* Show section only when it contains active question */
        .career-form-step-mode .career-form-section.step-active {
            display: block;
        }

        .career-form-step-mode .career-form-section-title,
        .career-form-step-mode .career-form-section-description {
            display: none;
        }

        /* Show section title and description when section is active */
        .career-form-step-mode .career-form-section.step-active .career-form-section-title,
        .career-form-step-mode .career-form-section.step-active .career-form-section-description {
            display: block;
        }

        .career-form-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
            padding: 1.5rem;
            background: #f9fafb;
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
        }

        .career-form-navigation-buttons {
            display: flex;
            gap: 1rem;
        }

        .career-form-nav-btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .career-form-nav-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .career-form-nav-btn-prev {
            background: #ffffff;
            color: #374151;
            border: 2px solid #e5e7eb;
        }

        .career-form-nav-btn-prev:hover:not(:disabled) {
            background: #f3f4f6;
            border-color: #d1d5db;
        }

        .career-form-nav-btn-next {
            background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
            color: white;
            box-shadow: 0 2px 4px rgba(20, 184, 166, 0.2);
        }

        .career-form-nav-btn-next:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(20, 184, 166, 0.3);
        }

        .career-form-progress {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex: 1;
            justify-content: center;
        }

        .career-form-progress-text {
            font-size: 1rem;
            font-weight: 600;
            color: #374151;
        }

        .career-form-progress-bar-container {
            flex: 1;
            max-width: 300px;
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }

        .career-form-progress-bar {
            height: 100%;
            background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
            transition: width 0.3s ease;
            border-radius: 4px;
        }

        .career-form-step-mode .career-form-nested-questions {
            display: none !important;
        }

        /* Show nested container when it has step-active or show class */
        .career-form-step-mode .career-form-nested-questions.step-active,
        .career-form-step-mode .career-form-nested-questions.show {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        /* Show nested questions inside active nested container */
        .career-form-step-mode .career-form-nested-questions.step-active .career-form-question,
        .career-form-step-mode .career-form-nested-questions.show .career-form-question {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        /* Ensure nested questions with step-active class are visible */
        .career-form-step-mode .career-form-nested-questions.step-active .career-form-question.step-active {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        /* Also support show class for compatibility */
        .career-form-step-mode .career-form-nested-questions.step-active.show {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        .career-form-step-mode .career-form-nested-questions.step-active.show .career-form-question {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        .career-form-step-mode .career-form-nested-questions.step-active.show .career-form-question.step-active {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        @media (max-width: 768px) {
            .career-form-navigation {
                flex-direction: column;
                gap: 1rem;
            }

            .career-form-progress {
                width: 100%;
                flex-direction: column;
            }

            .career-form-progress-bar-container {
                max-width: 100%;
            }

            .career-form-navigation-buttons {
                width: 100%;
                justify-content: space-between;
            }

            .career-form-nav-btn {
                flex: 1;
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center">
        <h4 class="fs-24 fw-600 lh-34 text-black-2">{{ __('Career Corner') }}</h4>
    </div>
    <div class="p-sm-30 p-15">
        @if(isset($formStructure) && $formStructure && isset($formData) && !empty($formData))
            @if(isset($submission) && $submission)
                @if(isset($structureChanged) && $structureChanged)
                    <div class="alert alert-warning mb-4">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i>
                        <strong>{{ __('Form Structure Updated') }}</strong>
                        <p class="mb-0 mt-2">{{ __('The form has been updated since you last submitted. Click "Change Preferences" to review and update your answers with the latest form structure.') }}</p>
                    </div>
                @endif

                <div class="alert alert-success mb-4 d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fa-solid fa-check-circle me-2"></i>
                        <strong>{{ __('Form Submitted') }}</strong> - {{ __('Last updated on') }}: {{ $submission->updated_at->setTimezone(getOption('app_timezone', 'UTC'))->format('F d, Y h:i A') }}
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="changePreferencesBtn">
                            <i class="fa-solid fa-edit me-1"></i>{{ __('Change Preferences') }}
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm" id="cancelEditBtn" style="display: none;">
                            <i class="fa-solid fa-times me-1"></i>{{ __('Cancel') }}
                        </button>
                    </div>
                </div>

                {{-- Helper Messages for Missed Criteria --}}
                @if(isset($helperMessages) && !empty($helperMessages))
                <div class="alert alert-warning mb-4" id="helperMessagesSection">
                    <div class="d-flex align-items-start">
                        <i class="fa-solid fa-exclamation-triangle me-3 mt-1" style="font-size: 1.5rem;"></i>
                        <div class="flex-grow-1">
                            <h5 class="alert-heading mb-3">
                                <i class="fa-solid fa-info-circle me-2"></i>{{ __('Additional Criteria Information') }}
                            </h5>
                            <p class="mb-3">{{ __('The following criteria are used by universities for filtering, but you haven\'t provided information for them yet:') }}</p>
                            <ul class="list-unstyled mb-0">
                                @foreach($helperMessages as $helper)
                                <li class="mb-3 pb-3 border-bottom">
                                    <div class="d-flex align-items-start">
                                        <i class="fa-solid fa-circle-info me-2 mt-1 text-primary"></i>
                                        <div class="flex-grow-1">
                                            <strong class="d-block mb-1">{{ $helper['criteria_field_name'] }}</strong>
                                            <p class="mb-1 text-muted small">{{ $helper['message'] }}</p>
                                            @if(!empty($helper['related_questions']))
                                            <p class="mb-0 small">
                                                <em>{{ __('Related questions:') }}</em>
                                                @foreach($helper['related_questions'] as $index => $question)
                                                    {{ $question }}@if(!$loop->last), @endif
                                                @endforeach
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                            <p class="mb-0 mt-3 small text-muted">
                                <i class="fa-solid fa-lightbulb me-1"></i>
                                <strong>{{ __('Tip:') }}</strong> {{ __('You can click "Change Preferences" above to update your form and provide information for these criteria to get more accurate university recommendations.') }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Matching Universities Section --}}
                @if(isset($matchingUniversities) && $matchingUniversities->count() > 0)
                <div class="career-form-section mb-4" id="matchingUniversitiesSection">
                    <h3 class="career-form-section-title mb-3">
                        <i class="fa-solid fa-graduation-cap me-2"></i>{{ __('Recommended Universities Based on Your Profile') }}
                    </h3>
                    <p class="career-form-section-description mb-4">
                        {{ __('Based on your submitted information, here are universities that match your profile:') }}
                    </p>
                    <div class="row rg-15" id="matchingUniversitiesList">
                        @foreach($matchingUniversities as $university)
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
                    <div class="text-center mt-4">
                        <a href="{{ route('student.universities.index') }}" class="flipBtn sf-flipBtn-primary">
                            <i class="fa-solid fa-search me-2"></i>{{ __('View All Universities') }}
                        </a>
                    </div>
                </div>
                @elseif(isset($matchingUniversities) && $matchingUniversities->count() === 0 && isset($submission))
                <div class="alert alert-info mb-4" id="noMatchingUniversitiesAlert">
                    <i class="fa-solid fa-info-circle me-2"></i>
                    <strong>{{ __('No Matching Universities Found') }}</strong>
                    <p class="mb-0 mt-2">{{ __('We couldn\'t find any universities matching your current profile. Please try updating your preferences or contact our support team for assistance.') }}</p>
                    <a href="{{ route('student.universities.index') }}" class="btn btn-outline-primary btn-sm mt-3">
                        <i class="fa-solid fa-search me-1"></i>{{ __('Browse All Universities') }}
                    </a>
                </div>
                @endif
            @endif

            <form id="careerCornerForm" method="POST" action="{{ route('student.career-corner.submit') }}" novalidate class="{{ !isset($submission) || !$submission ? 'career-form-step-mode' : 'career-form-readonly-mode' }}">
                @csrf

                @foreach($formData as $element)
                    @if($element['type'] === 'section')
                        <div class="career-form-section" data-section-name="{{ !empty($element['name']) ? e($element['name']) : '' }}">
                            @if(!empty($element['name']))
                                <h3 class="career-form-section-title">{{ $element['name'] }}</h3>
                            @endif

                            @if(!empty($element['description']))
                                <p class="career-form-section-description">{{ $element['description'] }}</p>
                            @endif

                            @if(isset($element['items']) && !empty($element['items']))
                                @foreach($element['items'] as $item)
                                    @include('student.career-corner.partials.question', ['item' => $item, 'questions' => $questions ?? [], 'depth' => 0, 'submittedData' => $submittedData ?? null, 'isReadonly' => isset($submission) && $submission ? true : false])
                                @endforeach
                            @endif
                        </div>
                    @elseif($element['type'] === 'item' && isset($element['item']))
                        <div class="career-form-section" data-section-name="">
                            @include('student.career-corner.partials.question', ['item' => $element['item'], 'questions' => $questions ?? [], 'depth' => 0, 'submittedData' => $submittedData ?? null, 'isReadonly' => isset($submission) && $submission ? true : false])
                        </div>
                    @endif
                @endforeach

                {{-- Navigation buttons (only shown in step mode) --}}
                <div class="career-form-navigation" id="careerFormNavigation" style="{{ (isset($submission) && $submission) ? 'display: none;' : '' }}">
                    <div class="career-form-navigation-buttons">
                        <button type="button" class="career-form-nav-btn career-form-nav-btn-prev" id="careerFormPrevBtn" disabled>
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>{{ __('Previous') }}</span>
                        </button>
                    </div>
                    <div class="career-form-progress">
                        <span class="career-form-progress-text" id="careerFormProgressText">Question 1 of 1</span>
                        <div class="career-form-progress-bar-container">
                            <div class="career-form-progress-bar" id="careerFormProgressBar" style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="career-form-navigation-buttons">
                        <button type="button" class="career-form-nav-btn career-form-nav-btn-next" id="careerFormNextBtn">
                            <span>{{ __('Next') }}</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                        <button type="submit" class="career-form-nav-btn career-form-nav-btn-next" id="careerFormSubmitBtn" style="display: none;">
                            <i class="fa-solid fa-paper-plane me-2"></i>
                            <span>{{ __('Submit Form') }}</span>
                        </button>
                    </div>
                </div>
            </form>
        @else
            <div class="career-form-empty-state">
                <i class="fa-solid fa-file-circle-question"></i>
                <h3>{{ __('Form Not Available') }}</h3>
                <p>{{ __('The career assessment form is currently not available.') }}</p>
                <p class="text-muted">{{ __('Please contact the administrator for assistance or check back later.') }}</p>
            </div>
        @endif
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Add file change listeners to all file inputs on page load
            $(document).on('change', 'input[type="file"].career-form-input', function() {
                // File change handler - no logging needed
            });
            // Track if form is in readonly mode
            let isReadonly = {{ isset($submission) && $submission ? 'true' : 'false' }};

            // Step-by-step navigation variables
            let stepNavigation = {
                questions: [],
                currentIndex: 0,
                initialized: false
            };

            // Helper function to find nested containers for a question
            function findNestedContainers($question, questionId) {
                let $nestedContainers = $('#careerCornerForm').find('.career-form-nested-questions[data-parent-question="' + questionId + '"]');

                if ($nestedContainers.length === 0) {
                    const $section = $question.closest('.career-form-section');
                    $nestedContainers = $section.find('.career-form-nested-questions[data-parent-question="' + questionId + '"]');
                }

                if ($nestedContainers.length === 0) {
                    $nestedContainers = $question.siblings('.career-form-nested-questions[data-parent-question="' + questionId + '"]');
                }

                if ($nestedContainers.length === 0) {
                    $nestedContainers = $question.parent().find('.career-form-nested-questions[data-parent-question="' + questionId + '"]');
                }

                return $nestedContainers;
            }

            // Initialize step-by-step navigation (only for editable forms)
            function initializeStepNavigation() {
                if (isReadonly || $('#careerCornerForm').hasClass('career-form-step-mode') === false) {
                    return;
                }

                stepNavigation.questions = [];

                // Build flat list of all questions in order
                function collectQuestions($container) {
                    $container.find('.career-form-question').each(function() {
                        const $question = $(this);
                        const questionId = $question.data('question-id');
                        const depth = parseInt($question.data('depth') || 0);
                        const $nestedContainer = $question.closest('.career-form-nested-questions');

                        // Skip nested questions for now - they'll be added when parent is answered
                        if ($nestedContainer.length > 0) {
                            return;
                        }

                        // Only process top-level questions (depth 0)
                        if (depth === 0) {
                            stepNavigation.questions.push({
                                $element: $question,
                                questionId: questionId,
                                depth: depth
                            });
                        }
                    });
                }

                // Collect all top-level questions from all sections
                $('#careerCornerForm .career-form-section').each(function() {
                    collectQuestions($(this));
                });

                // Now, build a new array with nested questions inserted
                const finalQuestions = [];

                stepNavigation.questions.forEach(function(q) {
                    const $question = q.$element;
                    const questionId = q.questionId;
                    const questionType = $question.data('question-type');

                    // Add the parent question
                    finalQuestions.push(q);

                    // If it's a radio question, check for nested questions
                    // NOTE: We don't add nested questions to the navigation list
                    // They will be shown/hidden with the parent question
                    // User answers all nested questions at once, then clicks Next to go to next parent question
                    if (questionType === 'radio') {
                        const $checkedRadio = $question.find('input[type="radio"]:checked');
                        if ($checkedRadio.length > 0) {
                            // Nested questions will be shown/hidden dynamically when parent is displayed
                            // No need to add them to navigation list
                        }
                    }
                });

                stepNavigation.questions = finalQuestions;

                // Reset current index if needed
                if (stepNavigation.currentIndex >= stepNavigation.questions.length) {
                    stepNavigation.currentIndex = 0;
                }

                // Initialize first question
                if (stepNavigation.questions.length > 0) {
                    showQuestion(stepNavigation.currentIndex);
                    updateNavigationButtons();
                    updateProgress();
                }

                stepNavigation.initialized = true;
            }

            // Show specific question by index
            function showQuestion(index) {
                if (index < 0 || index >= stepNavigation.questions.length) {
                    return;
                }

                // Hide all questions, sections, and nested containers
                const currentQuestion = stepNavigation.questions[index];

                $('.career-form-question').removeClass('step-active').each(function() {
                    // Don't remove styles from the question we're about to show
                    if (currentQuestion && currentQuestion.$element && !$(this).is(currentQuestion.$element)) {
                        if (this.style) {
                            this.style.removeProperty('display');
                            this.style.removeProperty('visibility');
                            this.style.removeProperty('opacity');
                        }
                    }
                });

                // Hide all nested containers (they will be shown if parent radio is selected)
                $('.career-form-nested-questions').removeClass('step-active show').each(function() {
                    if (this.style) {
                        this.style.setProperty('display', 'none', 'important');
                    }
                });

                // Hide all sections explicitly
                $('.career-form-section').removeClass('step-active').each(function() {
                    if (this.style) {
                        this.style.setProperty('display', 'none', 'important');
                    }
                });

                $('.career-form-section-title').removeClass('step-active');
                $('.career-form-section-description').removeClass('step-active');

                // currentQuestion is already declared above
                const $question = currentQuestion.$element;
                const questionType = $question.data('question-type');

                // Get the section containing this question
                const $section = $question.closest('.career-form-section');

                // Show the section that contains the active question
                if ($section.length > 0) {
                    $section.addClass('step-active');
                    // Explicitly show the section with inline style
                    if ($section[0]) {
                        $section[0].style.setProperty('display', 'block', 'important');
                    }

                    // Hide all other sections
                    $('.career-form-section').not($section).each(function() {
                        if (this.style) {
                            this.style.setProperty('display', 'none', 'important');
                        }
                    });
                }

                // If this is a radio question, check if it has nested questions to show
                if (questionType === 'radio') {
                    const $checkedRadio = $question.find('input[type="radio"]:checked');
                    if ($checkedRadio.length > 0) {
                        const selectedValue = String($checkedRadio.val()).trim().toLowerCase();
                        const questionId = $question.data('question-id');

                        // Find and show nested question containers for selected option
                        const $nestedContainers = findNestedContainers($question, questionId);

                        // Show nested container for selected option
                        $nestedContainers.each(function() {
                            const $nestedContainer = $(this);
                            let containerOptionValue = $nestedContainer.data('option-value');
                            if (!containerOptionValue) {
                                containerOptionValue = $nestedContainer.attr('data-option-value');
                            }
                            containerOptionValue = String(containerOptionValue || '').trim().toLowerCase();
                            const selectedValueLower = selectedValue.toLowerCase();

                            if (containerOptionValue === selectedValueLower) {
                                // Show this nested container and all its questions
                                $nestedContainer.addClass('show step-active');
                                if ($nestedContainer[0]) {
                                    $nestedContainer[0].style.setProperty('display', 'block', 'important');
                                    $nestedContainer[0].style.setProperty('visibility', 'visible', 'important');
                                    $nestedContainer[0].style.setProperty('opacity', '1', 'important');
                                }

                                // Show all nested questions inside
                                $nestedContainer.find('.career-form-question').each(function() {
                                    const $nestedQ = $(this);
                                    $nestedQ.addClass('step-active');
                                    if ($nestedQ[0]) {
                                        $nestedQ[0].style.setProperty('display', 'block', 'important');
                                        $nestedQ[0].style.setProperty('visibility', 'visible', 'important');
                                        $nestedQ[0].style.setProperty('opacity', '1', 'important');
                                    }
                                });
                            } else {
                                // Hide nested containers that don't match
                                $nestedContainer.removeClass('show step-active');
                                if ($nestedContainer[0]) {
                                    $nestedContainer[0].style.setProperty('display', 'none', 'important');
                                }
                            }
                        });
                    }
                }

                // Show current question
                $question.addClass('step-active');

                // Show section title and description for all questions in the active section
                if ($section.length > 0) {
                    $section.find('.career-form-section-title').addClass('step-active');
                    $section.find('.career-form-section-description').addClass('step-active');
                }

                // Scroll to question
                setTimeout(function() {
                    const offset = $question.offset();
                    if (offset) {
                        $('html, body').animate({
                            scrollTop: offset.top - 100
                        }, 300);
                    }
                }, 100);

                stepNavigation.currentIndex = index;
            }

            // Update navigation buttons state
            function updateNavigationButtons() {
                const $prevBtn = $('#careerFormPrevBtn');
                const $nextBtn = $('#careerFormNextBtn');
                const $submitBtn = $('#careerFormSubmitBtn');

                // Previous button
                if (stepNavigation.currentIndex === 0) {
                    $prevBtn.prop('disabled', true);
                } else {
                    $prevBtn.prop('disabled', false);
                }

                // Next button / Submit button
                if (stepNavigation.currentIndex === stepNavigation.questions.length - 1) {
                    $nextBtn.hide();
                    $submitBtn.show();
                } else {
                    $nextBtn.show();
                    $submitBtn.hide();
                }
            }

            // Update progress indicator
            function updateProgress() {
                const total = stepNavigation.questions.length;
                const current = stepNavigation.currentIndex + 1;
                const percentage = total > 0 ? (current / total) * 100 : 0;

                $('#careerFormProgressText').text('Question ' + current + ' of ' + total);
                $('#careerFormProgressBar').css('width', percentage + '%');
            }

            // Handle nested questions visibility based on radio selections
            function updateNestedQuestionsVisibility() {
                // This will be called when radio buttons change
                // We need to rebuild the question list to include/exclude nested questions
                const oldIndex = stepNavigation.currentIndex;
                initializeStepNavigation();

                // Try to maintain position, but adjust if needed
                if (oldIndex < stepNavigation.questions.length) {
                    showQuestion(oldIndex);
                } else if (stepNavigation.questions.length > 0) {
                    showQuestion(stepNavigation.questions.length - 1);
                }
                updateNavigationButtons();
                updateProgress();
            }

            // Previous button handler
            $(document).on('click', '#careerFormPrevBtn', function() {
                if (stepNavigation.currentIndex > 0) {
                    showQuestion(stepNavigation.currentIndex - 1);
                    updateNavigationButtons();
                    updateProgress();
                }
            });

            // Next button handler
            $(document).on('click', '#careerFormNextBtn', function() {
                // Validate current question if required
                const currentQuestion = stepNavigation.questions[stepNavigation.currentIndex];
                if (currentQuestion) {
                    const $question = currentQuestion.$element;
                    const isRequired = $question.data('question-required') === '1' || $question.data('question-required') === 1 || $question.data('question-required') === true;
                    const questionType = $question.data('question-type');

                    if (isRequired) {
                        let isValid = false;
                        let $fieldToValidate = null;
                        let errorMessage = '{{ __('Please answer this question before proceeding') }}';

                        // Validate based on question type
                        if (questionType === 'radio') {
                            // Check if any radio option is selected
                            const $radioInputs = $question.find('input[type="radio"]');
                            if ($radioInputs.length > 0) {
                                const fieldName = $radioInputs.first().attr('name');
                                isValid = $question.find('input[type="radio"][name="' + fieldName + '"]:checked').length > 0;
                                if (!isValid) {
                                    $fieldToValidate = $radioInputs.first();
                                }
                            }
                        } else if (questionType === 'checkbox') {
                            // Check if at least one checkbox is selected
                            const $checkboxInputs = $question.find('input[type="checkbox"]');
                            if ($checkboxInputs.length > 0) {
                                const fieldName = $checkboxInputs.first().attr('name');
                                isValid = $question.find('input[type="checkbox"][name^="' + fieldName.replace('[]', '') + '"]:checked').length > 0;
                                if (!isValid) {
                                    $fieldToValidate = $checkboxInputs.first();
                                }
                            }
                        } else if (questionType === 'select') {
                            // Check if a select option is chosen (not the default empty option)
                            const $select = $question.find('select');
                            if ($select.length > 0) {
                                const value = $select.val();
                                isValid = value !== null && value !== '' && value !== undefined;
                                if (!isValid) {
                                    $fieldToValidate = $select;
                                }
                            }
                        } else if (questionType === 'file') {
                            // Check if a file is selected
                            const $fileInput = $question.find('input[type="file"]');
                            if ($fileInput.length > 0) {
                                const files = $fileInput[0].files;
                                isValid = files && files.length > 0;
                                if (!isValid) {
                                    $fieldToValidate = $fileInput;
                                }
                            }
                        } else {
                            // For text, textarea, number, email, etc.
                            const $input = $question.find('input, textarea').first();
                            if ($input.length > 0) {
                                const value = $input.val();
                                isValid = value !== null && value !== '' && value !== undefined && (typeof value !== 'string' || value.trim() !== '');
                                if (!isValid) {
                                    $fieldToValidate = $input;
                                }
                            }
                        }

                        if (!isValid) {
                            // Show error
                            if ($fieldToValidate) {
                                $fieldToValidate.addClass('is-invalid');
                                $fieldToValidate.focus();

                                // Remove existing error message
                                $question.find('.career-form-error-message').remove();

                                // Add error message
                                const $errorMsg = $('<div class="career-form-error-message">' + errorMessage + '</div>');
                                $fieldToValidate.after($errorMsg);
                            }

                            if (typeof toastr !== 'undefined') {
                                toastr.error(errorMessage);
                            }

                            // Scroll to question
                            setTimeout(function() {
                                const offset = $question.offset();
                                if (offset) {
                                    $('html, body').animate({
                                        scrollTop: offset.top - 100
                                    }, 300);
                                }
                            }, 100);

                            return;
                        } else {
                            // Clear any existing errors
                            $question.find('.is-invalid').removeClass('is-invalid');
                            $question.find('.career-form-error-message').remove();
                        }
                    }

                    // If this is a radio question, show/hide nested questions (but don't add them to navigation)
                    if (questionType === 'radio') {
                        const $checkedRadio = $question.find('input[type="radio"]:checked');
                        if ($checkedRadio.length > 0) {
                            const selectedValue = String($checkedRadio.val()).trim().toLowerCase();
                            const questionId = $question.data('question-id');

                            // Find and show nested question containers for selected option
                            let $nestedContainers = $('#careerCornerForm').find('.career-form-nested-questions[data-parent-question="' + questionId + '"]');

                            if ($nestedContainers.length === 0) {
                                const $sectionForNested = $question.closest('.career-form-section');
                                $nestedContainers = $sectionForNested.find('.career-form-nested-questions[data-parent-question="' + questionId + '"]');
                            }

                            if ($nestedContainers.length === 0) {
                                $nestedContainers = $question.siblings('.career-form-nested-questions[data-parent-question="' + questionId + '"]');
                            }

                            if ($nestedContainers.length === 0) {
                                $nestedContainers = $question.parent().find('.career-form-nested-questions[data-parent-question="' + questionId + '"]');
                            }

                            // Hide all nested containers first
                            $nestedContainers.removeClass('show step-active').each(function() {
                                if (this.style) {
                                    this.style.setProperty('display', 'none', 'important');
                                }
                            });

                            // Show nested container for selected option
                            $nestedContainers.each(function() {
                                const $nestedContainer = $(this);
                                let containerOptionValue = $nestedContainer.data('option-value');
                                if (!containerOptionValue) {
                                    containerOptionValue = $nestedContainer.attr('data-option-value');
                                }
                                containerOptionValue = String(containerOptionValue || '').trim().toLowerCase();
                                const selectedValueLower = selectedValue.toLowerCase();

                                if (containerOptionValue === selectedValueLower) {
                                    // Show this nested container and all its questions
                                    $nestedContainer.addClass('show step-active');
                                    if ($nestedContainer[0]) {
                                        $nestedContainer[0].style.setProperty('display', 'block', 'important');
                                        $nestedContainer[0].style.setProperty('visibility', 'visible', 'important');
                                        $nestedContainer[0].style.setProperty('opacity', '1', 'important');
                                    }

                                    // Show all nested questions inside
                                    $nestedContainer.find('.career-form-question').each(function() {
                                        const $nestedQ = $(this);
                                        $nestedQ.addClass('step-active');
                                        if ($nestedQ[0]) {
                                            $nestedQ[0].style.setProperty('display', 'block', 'important');
                                            $nestedQ[0].style.setProperty('visibility', 'visible', 'important');
                                            $nestedQ[0].style.setProperty('opacity', '1', 'important');
                                        }
                                    });
                                }
                            });
                        }
                    }

                    // Validate nested questions if they are visible
                    const $visibleNestedContainer = $question.siblings('.career-form-nested-questions.show, .career-form-nested-questions.step-active').first();
                    if ($visibleNestedContainer.length > 0) {
                        let nestedHasErrors = false;
                        $visibleNestedContainer.find('.career-form-question').each(function() {
                            const $nestedQ = $(this);
                            const nestedIsRequired = $nestedQ.data('question-required') === '1' || $nestedQ.data('question-required') === 1;
                            const nestedType = $nestedQ.data('question-type');

                            if (nestedIsRequired) {
                                let nestedIsValid = false;

                                if (nestedType === 'radio') {
                                    const $radioInputs = $nestedQ.find('input[type="radio"]');
                                    if ($radioInputs.length > 0) {
                                        const fieldName = $radioInputs.first().attr('name');
                                        nestedIsValid = $nestedQ.find('input[type="radio"][name="' + fieldName + '"]:checked').length > 0;
                                    }
                                } else if (nestedType === 'checkbox') {
                                    const $checkboxInputs = $nestedQ.find('input[type="checkbox"]');
                                    if ($checkboxInputs.length > 0) {
                                        const fieldName = $checkboxInputs.first().attr('name');
                                        nestedIsValid = $nestedQ.find('input[type="checkbox"][name^="' + fieldName.replace('[]', '') + '"]:checked').length > 0;
                                    }
                                } else if (nestedType === 'select') {
                                    const $select = $nestedQ.find('select');
                                    if ($select.length > 0) {
                                        const value = $select.val();
                                        nestedIsValid = value !== null && value !== '' && value !== undefined;
                                    }
                                } else if (nestedType === 'file') {
                                    const $fileInput = $nestedQ.find('input[type="file"]');
                                    if ($fileInput.length > 0) {
                                        const files = $fileInput[0].files;
                                        nestedIsValid = files && files.length > 0;
                                    }
                                } else {
                                    const $input = $nestedQ.find('input, textarea').first();
                                    if ($input.length > 0) {
                                        const value = $input.val();
                                        nestedIsValid = value !== null && value !== '' && value !== undefined && (typeof value !== 'string' || value.trim() !== '');
                                    }
                                }

                                if (!nestedIsValid) {
                                    nestedHasErrors = true;
                                    const $nestedField = $nestedQ.find('input, select, textarea').first();
                                    if ($nestedField.length > 0) {
                                        $nestedField.addClass('is-invalid');
                                        $nestedQ.find('.career-form-error-message').remove();
                                        const $errorMsg = $('<div class="career-form-error-message">' + errorMessage + '</div>');
                                        $nestedField.after($errorMsg);
                                    }
                                }
                            }
                        });

                        if (nestedHasErrors) {
                            if (typeof toastr !== 'undefined') {
                                toastr.error('{{ __('Please answer all required nested questions before proceeding') }}');
                            }
                            return;
                        }
                    }
                }

                // Move to next question if no nested questions to handle
                if (stepNavigation.currentIndex < stepNavigation.questions.length - 1) {
                    showQuestion(stepNavigation.currentIndex + 1);
                    updateNavigationButtons();
                    updateProgress();
                }
            });

            // Initialize step navigation if form is editable
            if (!isReadonly) {
                initializeStepNavigation();
            }

            // Function to make form editable
            function makeFormEditable() {
                isReadonly = false;

                // Remove readonly mode and enable step mode
                $('#careerCornerForm').removeClass('career-form-readonly-mode').addClass('career-form-step-mode');
                $('#careerFormNavigation').show();

                // Explicitly show all form fields that might be hidden
                $('#careerCornerForm .career-form-input, #careerCornerForm .career-form-textarea, #careerCornerForm .career-form-select, #careerCornerForm .career-form-radio-group, #careerCornerForm .career-form-checkbox-group').each(function() {
                    const $field = $(this);
                    // Remove any inline display:none styles
                    if ($field.css('display') === 'none' || $field.attr('style') && $field.attr('style').includes('display: none')) {
                        $field.css('display', '').removeAttr('style');
                    }
                });

                // Hide answer divs
                $('.career-form-question-answer').hide();

                // Clear any existing error messages and validation states
                $('.career-form-error-message').remove();
                $('.career-form-input, .career-form-textarea, .career-form-select').removeClass('is-invalid');
                $('#careerCornerForm input, #careerCornerForm textarea, #careerCornerForm select').each(function() {
                    if (this.setCustomValidity) {
                        this.setCustomValidity('');
                    }
                });

                // Remove readonly/disabled attributes from all fields
                $('#careerCornerForm input[readonly]').removeAttr('readonly').removeAttr('style');
                $('#careerCornerForm textarea[readonly]').removeAttr('readonly').removeAttr('style');
                $('#careerCornerForm select[disabled]').prop('disabled', false).removeAttr('style');
                $('#careerCornerForm input[disabled]').prop('disabled', false);

                // Handle file fields - hide readonly display and show file input
                $('.career-form-file-display').each(function() {
                    const $fileDisplay = $(this);
                    const $question = $fileDisplay.closest('.career-form-question');
                    const questionId = $question.data('question-id');
                    const fieldName = 'career_q_' + questionId;

                    // Find file input (should exist but might be hidden in a div)
                    let $fileInput = $question.find('input[type="file"][name="' + fieldName + '"]');

                    // If file input is inside a hidden div, show the div first
                    if ($fileInput.length > 0) {
                        const $parentDiv = $fileInput.closest('div[style*="display: none"]');
                        if ($parentDiv.length > 0) {
                            $parentDiv.css('display', 'block').removeAttr('style');
                        }
                    }

                    if ($fileInput.length === 0) {
                        // Create file input if it doesn't exist
                        const isRequired = $question.data('question-required') === '1' || $question.data('question-required') === 1;
                        $fileInput = $('<input>', {
                            type: 'file',
                            class: 'career-form-input',
                            name: fieldName,
                            accept: '*/*'
                        });
                        if (isRequired) {
                            $fileInput.attr('required', 'required');
                        }
                        $fileDisplay.after($fileInput);
                    }

                    // Hide readonly display and show file input
                    $fileDisplay.hide();
                    $fileInput.css('display', 'block').removeAttr('style').show();
                });

                // Also find and show any file inputs that might be hidden in divs (including those not found above)
                $('#careerCornerForm').find('input[type="file"]').each(function() {
                    const $fileInput = $(this);
                    const $hiddenParent = $fileInput.closest('div[style*="display: none"], div[style*="display:none"]');

                    // Show parent div if it's hidden (but not if it's the answer div)
                    if ($hiddenParent.length > 0 && !$hiddenParent.hasClass('career-form-question-answer') && !$hiddenParent.closest('.career-form-question-answer').length) {
                        $hiddenParent.css('display', 'block').removeAttr('style');
                    }

                    // Show the file input itself
                    if ($fileInput.css('display') === 'none' || $fileInput.is(':hidden')) {
                        $fileInput.css('display', 'block').removeAttr('style').show();
                    }
                });

                // STEP 1: Remove required from ALL hidden nested questions
                $('.career-form-nested-questions').each(function() {
                    const $container = $(this);
                    const parentQuestionId = $container.data('parent-question');
                    const containerOptionValue = $container.data('option-value');

                    let isVisible = false;
                    if (parentQuestionId && containerOptionValue) {
                        const $checkedRadio = $(`input[type="radio"][data-question-id="${parentQuestionId}"]:checked`);
                        if ($checkedRadio.length > 0) {
                            const selectedValue = String($checkedRadio.val()).trim().toLowerCase();
                            const containerValue = String(containerOptionValue).trim().toLowerCase();
                            isVisible = (selectedValue === containerValue);
                        }
                    }

                    if (!isVisible || !$container.hasClass('show') || $container.css('display') === 'none') {
                        $container.find('input, select, textarea').each(function() {
                            $(this).removeAttr('required');
                            $(this).prop('required', false);
                            if (this.removeAttribute) this.removeAttribute('required');
                        });
                    }
                });

                // STEP 2: For ALL visible questions, set required based on database value
                $('.career-form-question').each(function() {
                    const $question = $(this);
                    const questionRequired = $question.data('question-required');
                    const isRequired = questionRequired === '1' || questionRequired === 1 || questionRequired === true;

                    // Check if question is visible
                    const $nestedContainer = $question.closest('.career-form-nested-questions');
                    let isQuestionVisible = true;

                    if ($nestedContainer.length > 0) {
                        const parentQuestionId = $nestedContainer.data('parent-question');
                        const containerOptionValue = $nestedContainer.data('option-value');

                        if (parentQuestionId && containerOptionValue) {
                            const $checkedRadio = $(`input[type="radio"][data-question-id="${parentQuestionId}"]:checked`);
                            if ($checkedRadio.length === 0) {
                                isQuestionVisible = false;
                            } else {
                                const selectedValue = String($checkedRadio.val()).trim().toLowerCase();
                                const containerValue = String(containerOptionValue).trim().toLowerCase();
                                isQuestionVisible = (selectedValue === containerValue);
                            }
                        }

                        if (!$nestedContainer.hasClass('show') || $nestedContainer.css('display') === 'none') {
                            isQuestionVisible = false;
                        }
                    }

                    // Set required attribute based on database value
                    $question.find('input, select, textarea').each(function() {
                        const $field = $(this);

                        if (!isQuestionVisible || $field.is(':hidden') || $field.css('display') === 'none') {
                            // Hidden field - remove required
                            $field.removeAttr('required');
                            $field.prop('required', false);
                            if ($field[0] && $field[0].removeAttribute) {
                                $field[0].removeAttribute('required');
                            }
                        } else if (isRequired) {
                            // Visible and required in database - add required
                            $field.attr('required', 'required');
                        } else {
                            // Visible but optional in database - remove required
                            $field.removeAttr('required');
                            $field.prop('required', false);
                            if ($field[0] && $field[0].removeAttribute) {
                                $field[0].removeAttribute('required');
                            }
                            // For radio buttons, clear validation
                            if ($field[0] && $field[0].type === 'radio') {
                                const fieldName = $field.attr('name');
                                $(`input[type="radio"][name="${fieldName}"]`).each(function() {
                                    if (this.setCustomValidity) this.setCustomValidity('');
                                });
                            }
                        }
                    });
                });

                // Hide matching universities section and helper messages when editing
                $('#matchingUniversitiesSection').slideUp(300);
                $('#noMatchingUniversitiesAlert').slideUp(300);
                $('#helperMessagesSection').slideUp(300);

                // Hide change preferences button and show cancel button
                $('#changePreferencesBtn').hide();
                $('#cancelEditBtn').show();

                // Show submit button if we're on the last question
                const $submitBtn = $('#careerFormSubmitBtn');
                if (stepNavigation.questions.length > 0 && stepNavigation.currentIndex === stepNavigation.questions.length - 1) {
                    $submitBtn.show();
                }

                // Initialize interactive features
                initializeFormInteractivity();

                // Initialize step navigation
                initializeStepNavigation();

                // Ensure form submission handler is attached
                attachFormSubmitHandler();

                // Re-attach error clearing handlers for editable fields
                $(document).off('input change', '.career-form-input, .career-form-textarea, .career-form-select');
                $(document).on('input change', '.career-form-input, .career-form-textarea, .career-form-select', function() {
                    const $field = $(this);
                    if ($field.hasClass('is-invalid')) {
                        $field.removeClass('is-invalid');
                        $field.closest('.career-form-question').find('.career-form-error-message').remove();
                        if ($field[0] && $field[0].setCustomValidity) {
                            $field[0].setCustomValidity('');
                        }
                    }
                });

                // Trigger change on all checked radio buttons to show/hide nested questions correctly
                $('input[type="radio"][data-question-id]:checked').trigger('change');

                // Final check after radio changes
                setTimeout(function() {
                    updateRequiredAttributes();
                }, 100);
            }

            // Function to revert form back to readonly mode
            function revertToReadonlyMode() {
                isReadonly = true;

                // Show matching universities section and helper messages again when canceling edit
                $('#matchingUniversitiesSection').slideDown(300);
                $('#noMatchingUniversitiesAlert').slideDown(300);
                $('#helperMessagesSection').slideDown(300);

                // Store current form values before reverting
                const formValues = {};
                $('#careerCornerForm input, #careerCornerForm textarea, #careerCornerForm select').each(function() {
                    const $field = $(this);
                    const name = $field.attr('name');
                    if (name) {
                        if ($field.is(':checkbox') || $field.is(':radio')) {
                            if ($field.is(':checked')) {
                                if (!formValues[name]) {
                                    formValues[name] = [];
                                }
                                formValues[name].push($field.val());
                            }
                        } else {
                            formValues[name] = $field.val();
                        }
                    }
                });

                // Reload the page to restore original submitted values
                // This ensures we get the exact original data back
                window.location.reload();
            }

            // Function to attach form submission handler
            function attachFormSubmitHandler() {
                // Remove existing handler to prevent duplicates
                $('#careerCornerForm').off('submit');

                // Handle form submission
                $('#careerCornerForm').on('submit', function(e) {
                    e.preventDefault();

                    // STEP 1: Remove required from ALL hidden nested questions (when parent is not selected)
                    $('.career-form-nested-questions').each(function() {
                        const $container = $(this);
                        const parentQuestionId = $container.data('parent-question');
                        const containerOptionValue = $container.data('option-value');

                        let isVisible = false;
                        if (parentQuestionId && containerOptionValue) {
                            const $checkedRadio = $(`input[type="radio"][data-question-id="${parentQuestionId}"]:checked`);
                            if ($checkedRadio.length > 0) {
                                const selectedValue = String($checkedRadio.val()).trim().toLowerCase();
                                const containerValue = String(containerOptionValue).trim().toLowerCase();
                                isVisible = (selectedValue === containerValue);
                            }
                        }

                        if (!isVisible || !$container.hasClass('show') || $container.css('display') === 'none') {
                            // Hidden nested question - remove required from all fields
                            $container.find('input, select, textarea').each(function() {
                                $(this).removeAttr('required');
                                $(this).prop('required', false);
                                if (this.removeAttribute) this.removeAttribute('required');
                            });
                        }
                    });

                    // STEP 2: For ALL visible questions, set required based on database value
                    $('.career-form-question').each(function() {
                        const $question = $(this);
                        const questionRequired = $question.data('question-required');
                        const isRequired = questionRequired === '1' || questionRequired === 1 || questionRequired === true;

                        // Check if question is visible (not in hidden nested container)
                        const $nestedContainer = $question.closest('.career-form-nested-questions');
                        let isQuestionVisible = true;

                        if ($nestedContainer.length > 0) {
                            const parentQuestionId = $nestedContainer.data('parent-question');
                            const containerOptionValue = $nestedContainer.data('option-value');

                            if (parentQuestionId && containerOptionValue) {
                                const $checkedRadio = $(`input[type="radio"][data-question-id="${parentQuestionId}"]:checked`);
                                if ($checkedRadio.length === 0) {
                                    isQuestionVisible = false;
                                } else {
                                    const selectedValue = String($checkedRadio.val()).trim().toLowerCase();
                                    const containerValue = String(containerOptionValue).trim().toLowerCase();
                                    isQuestionVisible = (selectedValue === containerValue);
                                }
                            }

                            if (!$nestedContainer.hasClass('show') || $nestedContainer.css('display') === 'none') {
                                isQuestionVisible = false;
                            }
                        }

                        // Set required attribute based on database value
                        $question.find('input, select, textarea').each(function() {
                            const $field = $(this);

                            if (!isQuestionVisible || $field.is(':hidden') || $field.css('display') === 'none') {
                                // Hidden field - remove required
                                $field.removeAttr('required');
                                $field.prop('required', false);
                                if ($field[0] && $field[0].removeAttribute) {
                                    $field[0].removeAttribute('required');
                                }
                            } else if (isRequired) {
                                // Visible and required in database - add required
                                $field.attr('required', 'required');
                            } else {
                                // Visible but optional in database - remove required
                                $field.removeAttr('required');
                                $field.prop('required', false);
                                if ($field[0] && $field[0].removeAttribute) {
                                    $field[0].removeAttribute('required');
                                }
                                // For radio buttons, also clear validation for all in group
                                if ($field[0] && $field[0].type === 'radio') {
                                    const fieldName = $field.attr('name');
                                    $(`input[type="radio"][name="${fieldName}"]`).each(function() {
                                        if (this.setCustomValidity) this.setCustomValidity('');
                                    });
                                }
                            }
                        });
                    });

                    // Helper function to show error message
                    function showFieldError($field, message) {
                            const $question = $field.closest('.career-form-question');
                            if ($question.length) {
                            // Remove existing error message
                            $question.find('.career-form-error-message').remove();

                            // Add error class to field - this makes it red
                            $field.addClass('is-invalid');

                            // Determine where to place error message
                            let $targetElement = $field;
                            const $checkboxGroup = $field.closest('.career-form-checkbox-group');
                            const $radioGroup = $field.closest('.career-form-radio-group');

                            if ($checkboxGroup.length) {
                                $targetElement = $checkboxGroup;
                            } else if ($radioGroup.length) {
                                $targetElement = $radioGroup;
                            }

                            // Create error message div with explicit styling
                            const $errorMsg = $('<div class="career-form-error-message">' + message + '</div>');
                            $targetElement.after($errorMsg);

                            // Force visibility
                            setTimeout(function() {
                                $errorMsg.css({
                                    'display': 'block',
                                    'visibility': 'visible',
                                    'opacity': '1'
                                });
                            }, 10);
                        }
                    }

                    // Helper function to clear error message
                    function clearFieldError($field) {
                        $field.removeClass('is-invalid');
                        $field.closest('.career-form-question').find('.career-form-error-message').remove();
                    }

                    // Clear all previous errors
                    $('.career-form-question').find('.career-form-error-message').remove();
                    $('.career-form-input, .career-form-textarea, .career-form-select').removeClass('is-invalid');

                    // STEP 3: Validate email type fields
                    let validationErrors = [];
                    $('.career-form-question').each(function() {
                        const $question = $(this);
                        const questionType = $question.data('question-type');
                                const questionRequired = $question.data('question-required');
                                const isRequired = questionRequired === '1' || questionRequired === 1 || questionRequired === true;

                        // Skip hidden questions
                        const $nestedContainer = $question.closest('.career-form-nested-questions');
                        let isQuestionVisible = true;
                        if ($nestedContainer.length > 0) {
                            const parentQuestionId = $nestedContainer.data('parent-question');
                            const containerOptionValue = $nestedContainer.data('option-value');
                            if (parentQuestionId && containerOptionValue) {
                                const $checkedRadio = $(`input[type="radio"][data-question-id="${parentQuestionId}"]:checked`);
                                if ($checkedRadio.length === 0) {
                                    isQuestionVisible = false;
                                } else {
                                    const selectedValue = String($checkedRadio.val()).trim().toLowerCase();
                                    const containerValue = String(containerOptionValue).trim().toLowerCase();
                                    isQuestionVisible = (selectedValue === containerValue);
                                }
                            }
                            if (!$nestedContainer.hasClass('show') || $nestedContainer.css('display') === 'none') {
                                isQuestionVisible = false;
                            }
                        }

                        if (!isQuestionVisible) {
                            return; // Skip hidden questions
                        }

                        // Get the input field
                        const $field = $question.find('input, select, textarea').first();
                        if ($field.length === 0 || $field.is(':hidden') || $field.css('display') === 'none') {
                            return;
                        }

                        const fieldValue = $field.val();
                        const fieldName = $field.attr('name');

                        // Skip if field is disabled/readonly (not in editable mode)
                        if ($field.is(':disabled') || $field.is('[readonly]')) {
                            return;
                        }

                        // Validate email type
                        if (questionType === 'email' && fieldValue) {
                            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                            if (!emailRegex.test(fieldValue.trim())) {
                                const errorMsg = '{{ __('Please enter a valid email address') }}';
                                if ($field[0] && $field[0].setCustomValidity) {
                                    $field[0].setCustomValidity(errorMsg);
                                }
                                showFieldError($field, errorMsg);
                                validationErrors.push({
                                    field: $field[0],
                                    message: errorMsg,
                                    $field: $field
                                });
                            } else {
                                // Clear any previous error
                                    if ($field[0] && $field[0].setCustomValidity) {
                                        $field[0].setCustomValidity('');
                                }
                                clearFieldError($field);
                            }
                        }

                    });

                    // STEP 4: Validate form with HTML5 validation (but don't show browser tooltips due to novalidate)
                    // Check required fields manually
                    let hasRequiredErrors = false;
                    let firstRequiredError = null;

                    $(this).find('input[required], select[required], textarea[required]').each(function() {
                        const $field = $(this);
                        const $question = $field.closest('.career-form-question');

                        // Skip hidden fields
                        if ($field.is(':hidden') || $field.css('display') === 'none') {
                            return;
                        }

                        // Check if field is in hidden nested container
                        const $nestedContainer = $question.closest('.career-form-nested-questions');
                        if ($nestedContainer.length > 0) {
                            if (!$nestedContainer.hasClass('show') || $nestedContainer.css('display') === 'none') {
                                return;
                            }
                        }

                        const value = $field.val();
                        const isEmpty = !value || (typeof value === 'string' && value.trim() === '');

                        if (isEmpty) {
                            hasRequiredErrors = true;
                            if (!firstRequiredError) {
                                firstRequiredError = {
                                    field: $field,
                                    message: '{{ __('This field is required') }}'
                                };
                                }
                            }
                        });

                    if (hasRequiredErrors && firstRequiredError) {
                        showFieldError(firstRequiredError.field, firstRequiredError.message);

                        const offset = firstRequiredError.field.offset();
                        if (offset) {
                            $('html, body').animate({
                                scrollTop: offset.top - 100
                            }, 500);
                        }

                        firstRequiredError.field[0].focus();

                        if (typeof toastr !== 'undefined') {
                            toastr.error(firstRequiredError.message);
                        }
                            return;
                        }

                    // Check if there are any email validation errors
                    if (validationErrors.length > 0) {
                        const firstError = validationErrors[0];
                        if (firstError.$field && firstError.$field.length > 0) {
                            // Scroll to the error field first
                            const $errorField = firstError.$field;
                            const offset = $errorField.offset();
                            if (offset) {
                                $('html, body').animate({
                                    scrollTop: offset.top - 100
                                }, 500);
                            }

                            // Focus the field
                            $errorField[0].focus();

                            // Ensure error message is visible (it should already be shown by showFieldError)
                            // But show it again to make sure
                            if (firstError.message) {
                                showFieldError($errorField, firstError.message);
                            }

                            // Show toastr message if available
                            if (typeof toastr !== 'undefined' && firstError.message) {
                                toastr.error(firstError.message);
                            }
                        } else if (firstError.field) {
                            // Fallback if $field is not available
                            const $fallbackField = $(firstError.field);
                            const offset = $fallbackField.offset();
                            if (offset) {
                                $('html, body').animate({
                                    scrollTop: offset.top - 100
                                }, 500);
                            }
                            firstError.field.focus();

                            // Show error message if not already shown
                            if (firstError.message && !$fallbackField.closest('.career-form-question').find('.career-form-error-message').length) {
                                showFieldError($fallbackField, firstError.message);
                            }

                            // Show toastr message if available
                            if (typeof toastr !== 'undefined' && firstError.message) {
                                toastr.error(firstError.message);
                            }
                        }
                        return;
                    }

                    // Show loading state
                    const $btn = $(this).find('button[type="submit"]');
                    const originalHtml = $btn.html();
                    $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i>{{ __('Submitting...') }}');

                    // Collect form data, excluding hidden nested questions
                    const formData = new FormData();
                    const formDataObj = {};

                    // First, collect all file inputs separately (including hidden ones)
                    // This ensures all files are captured regardless of visibility
                    $(this).find('input[type="file"]').each(function() {
                        const $fileField = $(this);
                        const fileName = $fileField.attr('name');

                        if (!fileName || fileName === '_token') {
                            return;
                        }

                        const fileInput = $fileField[0];
                        const hasFile = fileInput && fileInput.files && fileInput.files.length > 0;

                        // Check if file input has a file selected (even if hidden)
                        if (hasFile) {
                            const file = fileInput.files[0];
                            // Append actual file to FormData with the correct field name
                            formData.append(fileName, file, file.name);
                        }
                    });

                    // Collect all other form fields, excluding hidden nested questions
                    $(this).find('input, select, textarea').each(function() {
                        const $field = $(this);
                        const name = $field.attr('name');

                        if (!name || name === '_token') {
                            return;
                        }

                        // Skip file inputs (already handled above)
                        if ($field.attr('type') === 'file') {
                            return;
                        }

                        // Skip disabled/readonly fields that are not in readonly mode (they're hidden)
                        if ($field.is(':disabled') && !isReadonly) {
                            return;
                        }

                        // For nested questions, check if parent option is selected
                        // If parent option matches, include the field even if container appears hidden in step mode
                        const $nestedContainer = $field.closest('.career-form-nested-questions');
                        if ($nestedContainer.length > 0) {
                            const parentQuestionId = $nestedContainer.data('parent-question');
                            let containerOptionValue = $nestedContainer.data('option-value');

                            // Try attribute if data doesn't work
                            if (!containerOptionValue) {
                                containerOptionValue = $nestedContainer.attr('data-option-value');
                            }

                            if (parentQuestionId && containerOptionValue) {
                                // Find the checked radio for this parent question
                                const $checkedRadio = $(`input[type="radio"][data-question-id="${parentQuestionId}"]:checked`);
                                if ($checkedRadio.length > 0) {
                                    const selectedValue = String($checkedRadio.val()).trim().toLowerCase();
                                    const containerValue = String(containerOptionValue || '').trim().toLowerCase();

                                    // If parent option doesn't match, skip this nested field
                                    if (selectedValue !== containerValue) {
                                        return;
                                    }
                                } else {
                                    // Parent not selected, skip nested field
                                    return;
                                }
                            } else {
                                // Can't determine parent, skip to be safe
                                return;
                            }
                        }

                        // Collect field value
                        if ($field.is(':checkbox')) {
                            if ($field.is(':checked')) {
                                if (!formDataObj[name]) {
                                    formDataObj[name] = [];
                                }
                                formDataObj[name].push($field.val());
                            }
                        } else if ($field.is(':radio')) {
                            if ($field.is(':checked')) {
                                formDataObj[name] = $field.val();
                            }
                        } else {
                            const value = $field.val();
                            // Include value if it's not empty (empty strings mean not answered)
                            if (value !== null && value !== '' && value !== undefined) {
                                formDataObj[name] = value;
                            }
                        }
                    });

                    // Convert object to FormData (excluding files which are already added)
                    Object.keys(formDataObj).forEach(key => {
                        const value = formDataObj[key];
                        if (Array.isArray(value)) {
                            value.forEach(v => formData.append(key + '[]', v));
                        } else {
                            formData.append(key, value);
                        }
                    });

                    // Add CSRF token
                    formData.append('_token', $('input[name="_token"]').val() || '{{ csrf_token() }}');

                    // Submit via AJAX
                    $.ajax({
                        url: '{{ route("student.career-corner.submit") }}',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val() || '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status) {
                                if (typeof toastr !== 'undefined') {
                                    toastr.success(response.message || '{{ __('Form submitted successfully!') }}');
                                } else {
                                    alert(response.message || '{{ __('Form submitted successfully!') }}');
                                }

                                // Refresh matching universities after submission
                                refreshMatchingUniversities();

                                // Reload page to show submitted form in readonly mode
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1500);
                            } else {
                                if (typeof toastr !== 'undefined') {
                                    toastr.error(response.message || '{{ __('Error submitting form') }}');
                                } else {
                                    alert(response.message || '{{ __('Error submitting form') }}');
                                }
                            }
                            $btn.prop('disabled', false).html(originalHtml);
                        },
                        error: function(xhr) {
                            let errorMessage = '{{ __('Error submitting form. Please try again.') }}';

                            if (xhr.responseJSON) {
                                if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                                }

                                // Handle validation errors (422 status)
                                if (xhr.status === 422 && xhr.responseJSON.errors && typeof xhr.responseJSON.errors === 'object') {
                                    const errors = xhr.responseJSON.errors;
                                    let errorMessages = [];

                                    // Show first error and set custom validity on fields
                                    Object.keys(errors).forEach(function(fieldName) {
                                        const $field = $(`[name="${fieldName}"]`);
                                        if ($field.length > 0) {
                                            const errorMsg = errors[fieldName];
                                            if ($field[0] && $field[0].setCustomValidity) {
                                                $field[0].setCustomValidity(errorMsg);
                                            }

                                            // Show error message below field
                                            showFieldError($field, errorMsg);

                                            errorMessages.push(errorMsg);

                                            // Scroll to first error field
                                            if (errorMessages.length === 1) {
                                                const offset = $field.offset();
                                                if (offset) {
                                                    $('html, body').animate({
                                                        scrollTop: offset.top - 100
                                                    }, 500);
                                                }
                                                $field[0].focus();
                                            }
                                        }
                                    });

                                    if (errorMessages.length > 0) {
                                        errorMessage = errorMessages[0];
                                    }
                            } else if (xhr.status === 422) {
                                errorMessage = '{{ __('Validation error. Please check your inputs.') }}';
                                }
                            } else if (xhr.status === 401) {
                                errorMessage = '{{ __('Please login to submit the form') }}';
                            } else if (xhr.status === 405) {
                                errorMessage = '{{ __('Method not allowed. Please refresh the page and try again.') }}';
                            }

                            if (typeof toastr !== 'undefined') {
                                toastr.error(errorMessage);
                            } else {
                                alert(errorMessage);
                            }
                            $btn.prop('disabled', false).html(originalHtml);
                        }
                    });
                });
            }

            // Change Preferences button handler
            $('#changePreferencesBtn').on('click', function() {
                Swal.fire({
                    title: '{{ __('Edit Form') }}',
                    text: '{{ __('Are you sure you want to edit your submitted form?') }}',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '{{ __('Yes, Edit It') }}',
                    cancelButtonText: '{{ __('Cancel') }}'
                }).then((result) => {
                    if (result.isConfirmed || result.value) {
                        makeFormEditable();
                    }
                });
            });

            // Cancel Edit button handler
            $('#cancelEditBtn').on('click', function() {
                Swal.fire({
                    title: '{{ __('Cancel Editing') }}',
                    text: '{{ __('Are you sure you want to cancel editing? Any unsaved changes will be lost.') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '{{ __('Yes, Cancel') }}',
                    cancelButtonText: '{{ __('No, Continue Editing') }}'
                }).then((result) => {
                    if (result.isConfirmed || result.value) {
                        revertToReadonlyMode();
                    }
                });
            });

            // Function to initialize form interactivity
            function initializeFormInteractivity() {
                // Initialize radio button change handlers
                initializeRadioHandlers();
            }

            // Function to initialize radio button handlers
            function initializeRadioHandlers() {
                // Remove existing handlers to prevent duplicates
                $(document).off('change', 'input[type="radio"][data-question-id]');

                // Handle radio button changes to show/hide nested questions
                $(document).on('change', 'input[type="radio"][data-question-id]', function() {
                    const $radio = $(this);
                    const questionId = $radio.data('question-id');
                    const optionValue = $radio.val();

                    if (!questionId || !optionValue) {
                        return;
                    }

                    // Hide all nested questions for this parent question
                    const $allNested = $(`.career-form-nested-questions[data-parent-question="${questionId}"]`);

                    // Clear values in nested questions that are being hidden
                    $allNested.each(function() {
                        const $nestedContainer = $(this);
                        const containerOptionValue = $nestedContainer.data('option-value') || $nestedContainer.attr('data-option-value');
                        const selectedValue = String(optionValue).trim().toLowerCase();
                        const containerValue = String(containerOptionValue).trim().toLowerCase();

                        // If this nested container doesn't match the selected option, clear its values
                        if (containerValue !== selectedValue) {
                            // Clear all input values in this nested container
                            $nestedContainer.find('input[type="text"], input[type="number"], input[type="email"], textarea').val('');
                            $nestedContainer.find('select').val('').prop('selectedIndex', 0);
                            $nestedContainer.find('input[type="radio"]').prop('checked', false);
                            $nestedContainer.find('input[type="checkbox"]').prop('checked', false);
                        }
                    });

                    $allNested.removeClass('show').css('display', 'none');

                    // Remove required from hidden nested questions
                    $allNested.find('input[required], select[required], textarea[required]').removeAttr('required');

                    // Update required attributes for visible questions
                    updateRequiredAttributes();

                    // Show nested questions for selected option (case-insensitive match)
                    const $nestedContainer = $allNested.filter(function() {
                        const containerOptionValue = $(this).data('option-value') || $(this).attr('data-option-value');
                        if (!containerOptionValue) {
                            return false;
                        }
                        // Trim and compare case-insensitively
                        const selectedValue = String(optionValue).trim().toLowerCase();
                        const containerValue = String(containerOptionValue).trim().toLowerCase();
                        return containerValue === selectedValue;
                    });

                    if ($nestedContainer.length > 0) {
                        $nestedContainer.css('display', 'block').addClass('show');

                        // Restore required attributes for visible nested questions
                        $nestedContainer.find('.career-form-question').each(function() {
                            const $question = $(this);
                            // Get required status from data attribute (from question table)
                            const questionRequired = $question.data('question-required');
                            const isRequired = questionRequired === '1' || questionRequired === 1 || questionRequired === true;

                            $question.find('input, select, textarea').each(function() {
                            const $field = $(this);

                                // Double-check field is visible
                                if ($field.is(':hidden') || $field.css('display') === 'none') {
                                    $field.removeAttr('required');
                                    return;
                                }

                                // ONLY use the database value (isRequired from data-question-required)
                                // This is the source of truth - optional questions should never be required
                                if (isRequired) {
                                $field.attr('required', 'required');
                                } else {
                                    $field.removeAttr('required');
                            }
                            });
                        });
                    }

                    // Update required attributes after showing/hiding nested questions
                    updateRequiredAttributes();

                    // If step navigation is active, rebuild question list to include/exclude nested questions
                    if (stepNavigation.initialized && !isReadonly && $('#careerCornerForm').hasClass('career-form-step-mode')) {
                        const currentQuestionId = questionId;
                        const currentIndex = stepNavigation.currentIndex;

                        // Rebuild question list
                        setTimeout(function() {
                            updateNestedQuestionsVisibility();

                            // Try to find the current question in the new list and maintain position
                            let newIndex = currentIndex;
                            for (let i = 0; i < stepNavigation.questions.length; i++) {
                                if (stepNavigation.questions[i].questionId == currentQuestionId) {
                                    newIndex = i;
                                    break;
                                }
                            }

                            // If we're past the end, go to the last question
                            if (newIndex >= stepNavigation.questions.length) {
                                newIndex = Math.max(0, stepNavigation.questions.length - 1);
                            }

                            showQuestion(newIndex);
                            updateNavigationButtons();
                            updateProgress();
                        }, 100);
                    }
                });
            }

            // Function to update required attributes based on visibility
            function updateRequiredAttributes() {
                // Only update if form is not readonly
                if (isReadonly) {
                    return;
                }

                // STEP 1: Remove required from ALL hidden nested questions
                $('.career-form-nested-questions').each(function() {
                    const $container = $(this);
                    const parentQuestionId = $container.data('parent-question');
                    const containerOptionValue = $container.data('option-value');

                    let isVisible = false;
                    if (parentQuestionId && containerOptionValue) {
                        const $checkedRadio = $(`input[type="radio"][data-question-id="${parentQuestionId}"]:checked`);
                        if ($checkedRadio.length > 0) {
                            const selectedValue = String($checkedRadio.val()).trim().toLowerCase();
                            const containerValue = String(containerOptionValue).trim().toLowerCase();
                            isVisible = (selectedValue === containerValue);
                        }
                    }

                    if (!isVisible || !$container.hasClass('show') || $container.css('display') === 'none') {
                        $container.find('input, select, textarea').each(function() {
                            $(this).removeAttr('required');
                            $(this).prop('required', false);
                            if (this.removeAttribute) this.removeAttribute('required');
                        });
                    }
                });

                // STEP 2: For ALL visible questions, set required based on database value
                $('.career-form-question').each(function() {
                    const $question = $(this);
                    const questionRequired = $question.data('question-required');
                    const isRequired = questionRequired === '1' || questionRequired === 1 || questionRequired === true;

                    // Check if question is visible
                    const $nestedContainer = $question.closest('.career-form-nested-questions');
                    let isQuestionVisible = true;

                    if ($nestedContainer.length > 0) {
                        const parentQuestionId = $nestedContainer.data('parent-question');
                        const containerOptionValue = $nestedContainer.data('option-value');

                        if (parentQuestionId && containerOptionValue) {
                            const $checkedRadio = $(`input[type="radio"][data-question-id="${parentQuestionId}"]:checked`);
                            if ($checkedRadio.length === 0) {
                                isQuestionVisible = false;
                            } else {
                                const selectedValue = String($checkedRadio.val()).trim().toLowerCase();
                                const containerValue = String(containerOptionValue).trim().toLowerCase();
                                isQuestionVisible = (selectedValue === containerValue);
                            }
                        }

                        if (!$nestedContainer.hasClass('show') || $nestedContainer.css('display') === 'none') {
                            isQuestionVisible = false;
                        }
                    }

                    // Set required attribute based on database value
                    $question.find('input, select, textarea').each(function() {
                        const $field = $(this);

                        if (!isQuestionVisible || $field.is(':hidden') || $field.css('display') === 'none') {
                            // Hidden field - remove required
                            $field.removeAttr('required');
                            $field.prop('required', false);
                            if ($field[0] && $field[0].removeAttribute) {
                                $field[0].removeAttribute('required');
                            }
                        } else if (isRequired) {
                            // Visible and required in database - add required
                            $field.attr('required', 'required');
                        } else {
                            // Visible but optional in database - remove required
                            $field.removeAttr('required');
                            $field.prop('required', false);
                            if ($field[0] && $field[0].removeAttribute) {
                                $field[0].removeAttribute('required');
                            }
                            // For radio buttons, clear validation
                            if ($field[0] && $field[0].type === 'radio') {
                                const fieldName = $field.attr('name');
                                $(`input[type="radio"][name="${fieldName}"]`).each(function() {
                                    if (this.setCustomValidity) this.setCustomValidity('');
                                });
                            }
                        }
                    });
                });
            }

            // Store original required status on page load for ALL questions (not just nested)
            // This ensures we can restore required attributes when making form editable
            // Use the data-question-required attribute which comes from the database
            $('.career-form-question').each(function() {
                const $question = $(this);
                // Get required status from data attribute (comes from question table)
                const questionRequired = $question.data('question-required');
                const isRequired = questionRequired === '1' || questionRequired === 1 || questionRequired === true;

                // Store required status for all fields in this question
                $question.find('input, select, textarea').each(function() {
                    const $field = $(this);
                    // Store the actual required status from the database
                    $field.data('original-required', isRequired);
                });
            });

            if (isReadonly) {
                // Form is readonly, just show all nested questions that should be visible
                // They are already shown by the server-side logic
                return;
            }

            // Initialize form interactivity for editable form
            initializeFormInteractivity();

            // Attach form submission handler
            attachFormSubmitHandler();

            // Clear error messages when user starts typing/selecting
            // This works for both first-time and editable modes
            $(document).off('input change', '.career-form-input, .career-form-textarea, .career-form-select, input[type="radio"], input[type="checkbox"], input[type="file"]');
            $(document).on('input change', '.career-form-input, .career-form-textarea, .career-form-select, input[type="radio"], input[type="checkbox"], input[type="file"]', function() {
                const $field = $(this);
                const $question = $field.closest('.career-form-question');

                // Clear error for this field
                if ($field.hasClass('is-invalid')) {
                    $field.removeClass('is-invalid');
                    if ($field[0] && $field[0].setCustomValidity) {
                        $field[0].setCustomValidity('');
                    }
                }

                // For radio buttons, clear error for all radios in the group
                if ($field.is(':radio')) {
                    const fieldName = $field.attr('name');
                    $question.find('input[type="radio"][name="' + fieldName + '"]').removeClass('is-invalid');
                }

                // For checkboxes, clear error for all checkboxes in the group
                if ($field.is(':checkbox')) {
                    const fieldName = $field.attr('name');
                    $question.find('input[type="checkbox"][name^="' + fieldName.replace('[]', '') + '"]').removeClass('is-invalid');
                }

                // Remove error message from question
                $question.find('.career-form-error-message').remove();
            });

            // Also handle on page load - check if any radio is already selected
            $('input[type="radio"][data-question-id]:checked').trigger('change');

            // Function to refresh matching universities via AJAX (optional - page reloads anyway)
            function refreshMatchingUniversities() {
                // This function is called after form submission
                // Since we reload the page, universities will be refreshed automatically
                // But we can use this for future AJAX updates if needed
                $.ajax({
                    url: '{{ route("student.career-corner.matching-universities") }}',
                    type: 'GET',
                    success: function(response) {
                        if (response.status && response.data) {
                            // Universities will be updated on page reload
                            // This is just a placeholder for future AJAX updates
                        }
                    }
                });
            }
        });
    </script>
@endpush
