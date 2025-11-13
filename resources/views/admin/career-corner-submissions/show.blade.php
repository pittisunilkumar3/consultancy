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
            margin-bottom: 0.75rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid #14b8a6;
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

        .career-form-input,
        .career-form-textarea,
        .career-form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            font-size: 1rem;
            background-color: #f3f4f6;
            cursor: not-allowed;
        }

        .career-form-radio-group,
        .career-form-checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 0.5rem;
        }

        .career-form-radio-option,
        .career-form-checkbox-option {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            background: #f3f4f6;
            cursor: not-allowed;
        }

        .career-form-file-display {
            padding: 0.875rem 1rem;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            color: #6b7280;
        }

        .career-form-nested-questions {
            margin-top: 1.5rem;
            margin-left: 0;
            padding-left: 0;
            border-left: none;
        }

        .career-form-nested-questions:not(.show) {
            display: none !important;
        }

        .career-form-nested-questions.show {
            display: block !important;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .submission-info {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .submission-info-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e5e7eb;
            gap: 0.75rem;
        }

        .submission-info-item:last-child {
            border-bottom: none;
        }

        .submission-info-label {
            font-weight: 600;
            color: #374151;
            min-width: 140px;
            flex-shrink: 0;
        }

        .submission-info-value {
            color: #6b7280;
            flex: 1;
        }
    </style>
@endpush

@section('content')
    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center g-10">
            <div class="dataTables_paginate paging_simple_numbers">
                @if(isset($previousSubmission) && $previousSubmission)
                    <a href="{{ route('admin.career-corner-submissions.show', $previousSubmission->id) }}"
                       class="paginate_button previous"
                       title="{{ __('Previous Submission') }}">
                        <i class="fa-solid fa-angles-left"></i>
                    </a>
                @else
                    <a href="#" class="paginate_button previous disabled" aria-disabled="true">
                        <i class="fa-solid fa-angles-left"></i>
                    </a>
                @endif

                @if(isset($nextSubmission) && $nextSubmission)
                    <a href="{{ route('admin.career-corner-submissions.show', $nextSubmission->id) }}"
                       class="paginate_button next"
                       title="{{ __('Next Submission') }}">
                        <i class="fa-solid fa-angles-right"></i>
                    </a>
                @else
                    <a href="#" class="paginate_button next disabled" aria-disabled="true">
                        <i class="fa-solid fa-angles-right"></i>
                    </a>
                @endif
            </div>

            <h4 class="fs-18 fw-700 lh-24 text-title-text mb-0 ms-3">{{ $pageTitle }}</h4>
        </div>

        <a href="{{ route('admin.career-corner-submissions.index') }}" class="flipBtn sf-flipBtn-secondary flex-shrink-0">
            <i class="fa-solid fa-arrow-left me-1"></i>{{ __('Back to List') }}
        </a>
    </div>

    <div class="p-sm-30 p-15">
        @if(isset($structureChanged) && $structureChanged)
            <div class="alert alert-warning mb-4">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                <strong>{{ __('Form Structure Changed') }}</strong>
                <p class="mb-0 mt-2">{{ __('The form structure has been modified since this submission was made. The data displayed below reflects the form structure at the time of submission.') }}</p>
            </div>
        @endif

        @if(isset($submission) && $submission)
            <div class="submission-info">
                <h5 class="mb-3">{{ __('Submission Information') }}</h5>
                <div class="submission-info-item">
                    <span class="submission-info-label">{{ __('Student Name') }}:</span>
                    <span class="submission-info-value">{{ $submission->user ? $submission->user->name : __('N/A') }}</span>
                </div>
                <div class="submission-info-item">
                    <span class="submission-info-label">{{ __('Email') }}:</span>
                    <span class="submission-info-value">{{ $submission->user ? $submission->user->email : __('N/A') }}</span>
                </div>
                <div class="submission-info-item">
                    <span class="submission-info-label">{{ __('Form Name') }}:</span>
                    <span class="submission-info-value">{{ $submission->formStructure ? $submission->formStructure->name : __('N/A') }}</span>
                </div>
                <div class="submission-info-item">
                    <span class="submission-info-label">{{ __('Status') }}:</span>
                    <span class="submission-info-value">
                        @if($submission->status == STATUS_ACTIVE)
                            <span class="badge bg-success">{{ __('Active') }}</span>
                        @else
                            <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                        @endif
                    </span>
                </div>
                <div class="submission-info-item">
                    <span class="submission-info-label">{{ __('Submitted On') }}:</span>
                    <span class="submission-info-value">{{ $submission->created_at ? $submission->created_at->setTimezone(getOption('app_timezone', 'UTC'))->format('F d, Y h:i A') : __('N/A') }}</span>
                </div>
                <div class="submission-info-item">
                    <span class="submission-info-label">{{ __('Last Updated') }}:</span>
                    <span class="submission-info-value">{{ $submission->updated_at ? $submission->updated_at->setTimezone(getOption('app_timezone', 'UTC'))->format('F d, Y h:i A') : __('N/A') }}</span>
                </div>
            </div>
        @endif

        @if(isset($formData) && !empty($formData))
            @foreach($formData as $element)
                @if($element['type'] === 'section')
                    <div class="career-form-section">
                        @if(!empty($element['name']))
                            <h3 class="career-form-section-title">{{ $element['name'] }}</h3>
                        @endif

                        @if(!empty($element['description']))
                            <p class="career-form-section-description">{{ $element['description'] }}</p>
                        @endif

                        @if(isset($element['items']) && !empty($element['items']))
                            @foreach($element['items'] as $item)
                                @include('student.career-corner.partials.question', ['item' => $item, 'questions' => $questions ?? [], 'depth' => 0, 'submittedData' => $submittedData ?? null, 'isReadonly' => true])
                            @endforeach
                        @endif
                    </div>
                @elseif($element['type'] === 'item' && isset($element['item']))
                    <div class="career-form-section">
                        @include('student.career-corner.partials.question', ['item' => $element['item'], 'questions' => $questions ?? [], 'depth' => 0, 'submittedData' => $submittedData ?? null, 'isReadonly' => true])
                    </div>
                @endif
            @endforeach
        @else
            <div class="alert alert-info">
                <i class="fa-solid fa-info-circle me-2"></i>
                {{ __('No form data available for this submission.') }}
            </div>
        @endif
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Ensure nested questions are shown correctly in readonly mode
            // This handles cases where the inline style might not be enough
            function showNestedQuestions() {
                $('.career-form-nested-questions').each(function(index) {
                    const $nestedContainer = $(this);
                    const parentQuestionId = $nestedContainer.data('parent-question');
                    const containerOptionValue = $nestedContainer.data('option-value');

                    if (parentQuestionId && containerOptionValue) {
                        let shouldShow = false;

                        // Method 1: Check if inline style says to show
                        const inlineStyle = $nestedContainer.attr('style') || '';
                        if (inlineStyle.includes('display: block')) {
                            shouldShow = true;
                        }

                        // Method 2: Check if the parent radio button is checked
                        const $parentRadio = $(`input[type="radio"][data-question-id="${parentQuestionId}"]:checked`);
                        if ($parentRadio.length > 0) {
                            $parentRadio.each(function() {
                                const selectedValue = String($(this).val()).trim();
                                const containerValue = String(containerOptionValue).trim();

                                // Case-insensitive comparison
                                if (selectedValue.toLowerCase() === containerValue.toLowerCase()) {
                                    shouldShow = true;
                                    return false; // break
                                }
                            });
                        }

                        // Method 3: Check all radio buttons with the parent question ID (even if not :checked selector works)
                        if (!shouldShow) {
                            const $allRadios = $(`input[type="radio"][data-question-id="${parentQuestionId}"]`);
                            $allRadios.each(function() {
                                const isChecked = $(this).is(':checked') || $(this).prop('checked');
                                if (isChecked) {
                                    const selectedValue = String($(this).val()).trim();
                                    const containerValue = String(containerOptionValue).trim();
                                    if (selectedValue.toLowerCase() === containerValue.toLowerCase()) {
                                        shouldShow = true;
                                        return false; // break
                                    }
                                }
                            });
                        }

                        // Apply the display
                        if (shouldShow) {
                            // Force show with multiple methods to ensure it works
                            $nestedContainer
                                .addClass('show')
                                .css({'display': 'block !important'})
                                .attr('style', 'display: block !important;')
                                .show(); // jQuery show() as backup

                            // Force remove any conflicting styles
                            $nestedContainer.removeAttr('hidden');
                        } else {
                            // Only hide if we're sure it shouldn't be shown
                            if (!$nestedContainer.hasClass('show') && inlineStyle.includes('display: none')) {
                                $nestedContainer.css({'display': 'none'}).removeClass('show').attr('style', 'display: none !important;');
                            }
                        }
                    }
                });
            }

            // Run immediately
            showNestedQuestions();

            // Also run after a short delay to ensure DOM is fully ready
            setTimeout(showNestedQuestions, 100);
        });
    </script>
@endpush

