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
        
        .career-form-input:focus,
        .career-form-textarea:focus,
        .career-form-select:focus {
            outline: none;
            border-color: #14b8a6;
            box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.1);
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
        
        .career-form-radio-option:hover {
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
        
        .career-form-checkbox-option:hover {
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
    </style>
@endpush

@section('content')
    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center">
        <h4 class="fs-24 fw-600 lh-34 text-black-2">{{ __('Career Corner') }}</h4>
    </div>
    <div class="p-sm-30 p-15">
        @if(isset($formStructure) && $formStructure && isset($formData) && !empty($formData))
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
                                    @include('student.career-corner.partials.question', ['item' => $item, 'questions' => $questions ?? [], 'depth' => 0])
                                @endforeach
                            @endif
                        </div>
                    @elseif($element['type'] === 'item' && isset($element['item']))
                        <div class="career-form-section">
                            @include('student.career-corner.partials.question', ['item' => $element['item'], 'questions' => $questions ?? [], 'depth' => 0])
                        </div>
                    @endif
                @endforeach
                
                <div class="text-center mt-4">
                    <button type="submit" class="career-form-submit-btn">
                        <i class="fa-solid fa-paper-plane me-2"></i>{{ __('Submit Form') }}
                    </button>
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
                }
            });
            
            // Also handle on page load - check if any radio is already selected
            $('input[type="radio"][data-question-id]:checked').trigger('change');
            
            // Handle form submission
            $('#careerCornerForm').on('submit', function(e) {
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
                
                // TODO: Implement form submission to save student responses
                // For now, just show success message
                setTimeout(function() {
                    if (typeof toastr !== 'undefined') {
                        toastr.success('{{ __('Form submitted successfully!') }}');
                    } else {
                        alert('{{ __('Form submitted successfully!') }}');
                    }
                    $btn.prop('disabled', false).html(originalHtml);
                }, 1000);
            });
        });
    </script>
@endpush
