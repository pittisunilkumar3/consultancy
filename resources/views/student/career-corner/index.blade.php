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
        
        .career-form-input:readonly,
        .career-form-textarea:readonly,
        .career-form-select:disabled,
        .career-form-input:disabled,
        input[type="text"]:readonly,
        input[type="number"]:readonly,
        input[type="email"]:readonly,
        input[type="tel"]:readonly,
        input[type="date"]:readonly,
        input[type="file"]:disabled,
        textarea:readonly,
        select:disabled,
        input.career-form-input[readonly],
        textarea.career-form-textarea[readonly],
        select.career-form-select[disabled] {
            background-color: #f3f4f6;
            cursor: not-allowed !important;
            opacity: 0.8;
            transition: none;
        }
        
        /* Force cursor not-allowed for all text, textarea, and select fields when readonly/disabled */
        .career-form-question input[readonly],
        .career-form-question textarea[readonly],
        .career-form-question select[disabled],
        .career-form-question input:readonly,
        .career-form-question textarea:readonly,
        .career-form-question select:disabled {
            cursor: not-allowed !important;
        }
        
        .career-form-input:readonly:hover,
        .career-form-textarea:readonly:hover,
        .career-form-select:disabled:hover,
        .career-form-input:disabled:hover {
            background-color: #f3f4f6;
            border-color: #e5e7eb;
            transform: none;
            box-shadow: none;
        }
        
        .career-form-input:readonly:focus,
        .career-form-textarea:readonly:focus,
        .career-form-select:disabled:focus,
        .career-form-input:readonly:active,
        .career-form-textarea:readonly:active,
        .career-form-select:disabled:active {
            outline: none;
            border-color: #e5e7eb;
            box-shadow: none;
            background-color: #f3f4f6;
        }
        
        .career-form-radio-option input[type="radio"]:disabled + label,
        .career-form-checkbox-option input[type="checkbox"]:disabled + label,
        input[type="radio"]:disabled + label,
        input[type="checkbox"]:disabled + label {
            color: #6b7280;
            cursor: not-allowed !important;
        }
        
        .career-form-radio-option:has(input[type="radio"]:disabled),
        .career-form-checkbox-option:has(input[type="checkbox"]:disabled),
        .career-form-radio-group:has(input[type="radio"]:disabled),
        .career-form-checkbox-group:has(input[type="checkbox"]:disabled) {
            cursor: not-allowed !important;
            transition: none;
        }
        
        input[type="radio"]:disabled,
        input[type="checkbox"]:disabled {
            cursor: not-allowed !important;
        }
        
        /* All labels for disabled/readonly fields */
        label:has(+ input:readonly),
        label:has(+ input:disabled),
        label:has(+ textarea:readonly),
        label:has(+ select:disabled),
        .career-form-question:has(input:readonly) label,
        .career-form-question:has(input:disabled) label,
        .career-form-question:has(textarea:readonly) label,
        .career-form-question:has(select:disabled) label {
            cursor: not-allowed !important;
        }
        
        /* Parent containers for all disabled/readonly fields */
        .career-form-question:has(input:readonly),
        .career-form-question:has(input:disabled),
        .career-form-question:has(textarea:readonly),
        .career-form-question:has(select:disabled) {
            cursor: not-allowed !important;
        }
        
        .career-form-radio-option:has(input[type="radio"]:disabled):hover,
        .career-form-checkbox-option:has(input[type="checkbox"]:disabled):hover,
        .career-form-radio-option:has(input[type="radio"]:disabled):active,
        .career-form-checkbox-option:has(input[type="checkbox"]:disabled):active {
            background: #ffffff;
            border-color: #e5e7eb;
            transform: none;
            box-shadow: none;
            cursor: not-allowed !important;
        }
        
        .career-form-file-display {
            padding: 0.875rem 1rem;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            color: #6b7280;
            cursor: not-allowed !important;
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
                <div class="alert alert-success mb-4">
                    <i class="fa-solid fa-check-circle me-2"></i>
                    <strong>{{ __('Form Submitted') }}</strong> - {{ __('Submitted on') }}: {{ $submission->created_at->format('F d, Y h:i A') }}
                </div>
            @endif
            
            <form id="careerCornerForm" method="POST">
                @csrf
                
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
                                    @include('student.career-corner.partials.question', ['item' => $item, 'questions' => $questions ?? [], 'depth' => 0, 'submittedData' => $submittedData ?? null])
                                @endforeach
                            @endif
                        </div>
                    @elseif($element['type'] === 'item' && isset($element['item']))
                        <div class="career-form-section">
                            @include('student.career-corner.partials.question', ['item' => $element['item'], 'questions' => $questions ?? [], 'depth' => 0, 'submittedData' => $submittedData ?? null])
                        </div>
                    @endif
                @endforeach
                
                @if(!isset($submission) || !$submission)
                    <div class="text-center mt-4">
                        <button type="submit" class="career-form-submit-btn">
                            <i class="fa-solid fa-paper-plane me-2"></i>{{ __('Submit Form') }}
                        </button>
                    </div>
                @endif
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
            // If form is readonly (submitted), don't initialize interactive features
            const isReadonly = {{ isset($submission) && $submission ? 'true' : 'false' }};
            
            if (isReadonly) {
                // Form is readonly, just show all nested questions that should be visible
                // They are already shown by the server-side logic
                return;
            }
            
            // Function to update required attributes based on visibility
            function updateRequiredAttributes() {
                // Remove required from all hidden nested questions
                $('.career-form-nested-questions:not(.show)').find('input[required], select[required], textarea[required]').each(function() {
                    $(this).removeAttr('required');
                });
                
                // Add required back to visible nested questions that should be required
                $('.career-form-nested-questions.show').find('input, select, textarea').each(function() {
                    const $field = $(this);
                    // Check if the original question was required by checking data attribute or parent
                    const $question = $field.closest('.career-form-question');
                    const questionId = $question.data('question-id');
                    if (questionId) {
                        // Check if this field should be required based on the question's required status
                        // We'll check the data-required attribute or check the original question
                        const originalRequired = $field.data('original-required');
                        if (originalRequired === true || originalRequired === 'true') {
                            $field.attr('required', 'required');
                        }
                    }
                });
            }
            
            // Store original required status on page load
            $('.career-form-nested-questions input[required], .career-form-nested-questions select[required], .career-form-nested-questions textarea[required]').each(function() {
                $(this).data('original-required', true);
            });
            
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
                $allNested.removeClass('show').css('display', 'none');
                
                // Remove required from hidden nested questions
                $allNested.find('input[required], select[required], textarea[required]').removeAttr('required');
                
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
                    $nestedContainer.find('input, select, textarea').each(function() {
                        const $field = $(this);
                        if ($field.data('original-required') === true || $field.data('original-required') === 'true') {
                            $field.attr('required', 'required');
                        }
                    });
                }
            });
            
            // Also handle on page load - check if any radio is already selected
            $('input[type="radio"][data-question-id]:checked').trigger('change');
            
            // Handle form submission
            $('#careerCornerForm').on('submit', function(e) {
                // Remove required from all hidden nested questions before validation
                $('.career-form-nested-questions:not(.show)').find('input[required], select[required], textarea[required]').removeAttr('required');
                
                e.preventDefault();
                
                // Validate form
                if (!this.checkValidity()) {
                    this.reportValidity();
                    return;
                }
                
                // Show loading state
                const $btn = $(this).find('button[type="submit"]');
                const originalHtml = $btn.html();
                $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i>{{ __('Submitting...') }}');
                
                // Collect all form data
                const formData = new FormData(this);
                
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
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.status === 422) {
                            errorMessage = '{{ __('Validation error. Please check your inputs.') }}';
                        } else if (xhr.status === 401) {
                            errorMessage = '{{ __('Please login to submit the form') }}';
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
        });
    </script>
@endpush
