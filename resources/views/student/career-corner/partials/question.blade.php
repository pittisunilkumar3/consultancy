@php
    // Handle both snapshot questions (arrays) and current questions (objects)
    $questionData = $questions[$item['question_id']] ?? null;

    // Debug: Log if question is missing
    if (!$questionData && isset($item['question_id'])) {
        \Log::warning('Career Corner: Question not found in questions array', [
            'question_id' => $item['question_id'],
            'available_question_ids' => array_keys(is_array($questions) ? $questions : $questions->toArray()),
            'item' => $item
        ]);
    }

    if (!$questionData) {
        return;
    }

    // Normalize question data - handle both array (snapshot) and object (current) formats
    if (is_array($questionData)) {
        $questionId = 'career_q_' . $questionData['id'];
        $questionText = $questionData['question'] ?? '';
        $questionType = $questionData['type'] ?? 'text';
        $questionOptions = $questionData['options'] ?? [];
        $questionRequired = $questionData['required'] ?? false;
        $questionHelpText = $questionData['help_text'] ?? null;
    } else {
        $questionId = 'career_q_' . $questionData->id;
        $questionText = $questionData->question ?? '';
        $questionType = $questionData->type ?? 'text';
        $questionOptions = $questionData->options ?? [];
        $questionRequired = $questionData->required ?? false;
        $questionHelpText = $questionData->help_text ?? null;
    }

    $required = $questionRequired ? '<span class="required">*</span>' : '';
    $helpText = $questionHelpText ? '<div class="career-form-question-help">' . e($questionHelpText) . '</div>' : '';

    // Check if form is readonly (submitted or explicitly set)
    $isReadonly = isset($isReadonly) ? $isReadonly : (isset($submittedData) && $submittedData !== null && !empty($submittedData));

    // Get field value from submitted data
    $fieldValue = null;
    if ($isReadonly && isset($submittedData) && is_array($submittedData) && !empty($submittedData)) {
        // Check for the field value using the question ID as key
        $fieldValue = $submittedData[$questionId] ?? null;

        // For checkbox fields, ensure fieldValue is an array
        // Laravel normalizes checkbox arrays (removes brackets from key)
        if ($questionType === 'checkbox' && $fieldValue !== null) {
            $fieldValue = is_array($fieldValue) ? $fieldValue : [$fieldValue];
        }
    }

    // Debug data attributes
    $debugInfo = [
        'questionId' => $questionId,
        'questionText' => $questionText,
        'fieldValue' => $fieldValue,
        'isReadonly' => $isReadonly,
        'hasSubmittedData' => isset($submittedData) && is_array($submittedData) && !empty($submittedData),
        'submittedDataKeys' => isset($submittedData) && is_array($submittedData) ? array_keys($submittedData) : []
    ];
@endphp

<div class="career-form-question"
     data-question-id="{{ is_array($questionData) ? $questionData['id'] : $questionData->id }}"
     data-depth="{{ $depth }}"
     data-question-key="{{ $questionId }}"
     data-field-value="{{ is_array($fieldValue) ? json_encode($fieldValue) : ($fieldValue ?? '') }}"
     data-is-readonly="{{ $isReadonly ? '1' : '0' }}"
     data-question-required="{{ $questionRequired ? '1' : '0' }}"
     data-debug-info="{{ json_encode($debugInfo) }}">
    <label class="career-form-question-label">
        {{ e($questionText) }}{!! $required !!}
    </label>
    {!! $helpText !!}

    @if($questionType === 'radio' && !empty($questionOptions))
        <div class="career-form-radio-group" data-question-id="{{ is_array($questionData) ? $questionData['id'] : $questionData->id }}">
            @foreach($questionOptions as $index => $option)
                @php
                    $optionValue = is_array($option) ? ($option['value'] ?? $option['label'] ?? '') : $option;
                    $optionLabel = is_array($option) ? ($option['label'] ?? $option['value'] ?? '') : $option;
                    $optionId = $questionId . '_opt_' . $index;
                @endphp
                <div class="career-form-radio-option">
                    @php
                        // Compare values case-insensitively and trim whitespace
                        $isChecked = false;
                        if ($isReadonly && $fieldValue !== null) {
                            $fieldValueTrimmed = trim((string)$fieldValue);
                            $optionValueTrimmed = trim((string)$optionValue);
                            // Check both exact match and case-insensitive match
                            $isChecked = ($fieldValueTrimmed === $optionValueTrimmed) ||
                                        (strtolower($fieldValueTrimmed) === strtolower($optionValueTrimmed));
                        }
                    @endphp
                    <input type="radio"
                           id="{{ $optionId }}"
                           name="{{ $questionId }}"
                           value="{{ e($optionValue) }}"
                           data-question-id="{{ is_array($questionData) ? $questionData['id'] : $questionData->id }}"
                           data-option-value="{{ e($optionValue) }}"
                           {{ $isChecked ? 'checked' : '' }}
                           {{ $isReadonly ? 'disabled' : '' }}
                           {{ (!$isReadonly && $questionRequired) ? 'required' : '' }}>
                    <label for="{{ $optionId }}" style="cursor: {{ $isReadonly ? 'default' : 'pointer' }};">{{ e($optionLabel) }}</label>
                </div>
            @endforeach
        </div>

        {{-- Render nested questions for each option --}}
        @if(isset($item['children']) && (is_array($item['children']) || is_object($item['children'])))
            @foreach($item['children'] as $optionValue => $childData)
                @if(isset($childData['items']) && !empty($childData['items']))
                    @php
                        // Ensure optionValue is a string and trimmed for matching
                        $optionValueStr = trim((string)$optionValue);
                        // Check if this nested question should be visible (if readonly and option was selected)
                        // Use case-insensitive comparison to handle "No" vs "no" etc.
                        $shouldShow = false;
                        $debugMatch = [
                            'parentQuestionId' => $questionId,
                            'optionValue' => $optionValueStr,
                            'hasSubmittedData' => isset($submittedData),
                            'submittedValue' => null,
                            'matchResult' => false
                        ];

                        if ($isReadonly && isset($submittedData[$questionId])) {
                            $submittedValue = trim((string)$submittedData[$questionId]);
                            $debugMatch['submittedValue'] = $submittedValue;

                            // Case-insensitive comparison
                            $exactMatch = ($submittedValue === $optionValueStr);
                            $caseInsensitiveMatch = (strtolower($submittedValue) === strtolower($optionValueStr));
                            $shouldShow = $exactMatch || $caseInsensitiveMatch;
                            $debugMatch['matchResult'] = $shouldShow;
                            $debugMatch['exactMatch'] = $exactMatch;
                            $debugMatch['caseInsensitiveMatch'] = $caseInsensitiveMatch;
                        }
                    @endphp
                    <div class="career-form-nested-questions"
                         data-parent-question="{{ is_array($questionData) ? $questionData['id'] : $questionData->id }}"
                         data-option-value="{{ e($optionValueStr) }}"
                         data-debug-match="{{ json_encode($debugMatch) }}"
                         style="display: {{ $shouldShow ? 'block' : 'none' }} !important;"
                         {{ $shouldShow ? 'class="show"' : '' }}>
                        @foreach($childData['items'] as $childItem)
                            @include('student.career-corner.partials.question', ['item' => $childItem, 'questions' => $questions, 'depth' => $depth + 1, 'submittedData' => $submittedData ?? null, 'isReadonly' => $isReadonly])
                        @endforeach
                    </div>
                @endif
            @endforeach
        @endif

    @elseif($questionType === 'select' && !empty($questionOptions))
        <select class="career-form-select" name="{{ $questionId }}" {{ $isReadonly ? 'disabled style="cursor: not-allowed !important;"' : '' }} {{ (!$isReadonly && $questionRequired) ? 'required' : '' }}>
            <option value="">{{ __('-- Select an option --') }}</option>
            @foreach($questionOptions as $option)
                @php
                    $optionValue = is_array($option) ? ($option['value'] ?? $option['label'] ?? '') : $option;
                    $optionLabel = is_array($option) ? ($option['label'] ?? $option['value'] ?? '') : $option;
                    // Compare values case-insensitively and trim whitespace
                    $isSelected = false;
                    if ($isReadonly && $fieldValue !== null) {
                        $fieldValueTrimmed = trim((string)$fieldValue);
                        $optionValueTrimmed = trim((string)$optionValue);
                        $isSelected = ($fieldValueTrimmed === $optionValueTrimmed) ||
                                     (strtolower($fieldValueTrimmed) === strtolower($optionValueTrimmed));
                    }
                @endphp
                <option value="{{ e($optionValue) }}" {{ $isSelected ? 'selected' : '' }}>{{ e($optionLabel) }}</option>
            @endforeach
        </select>

    @elseif($questionType === 'textarea')
        <textarea class="career-form-textarea"
                  name="{{ $questionId }}"
                  rows="4"
                  {{ $isReadonly ? 'readonly style="cursor: not-allowed !important;"' : '' }}
                  {{ (!$isReadonly && $questionRequired) ? 'required' : '' }}
                  placeholder="{{ __('Enter your answer') }}">{{ $isReadonly ? e($fieldValue) : '' }}</textarea>

    @elseif($questionType === 'number')
        <input type="number"
               class="career-form-input"
               name="{{ $questionId }}"
               value="{{ $isReadonly ? e($fieldValue) : '' }}"
               {{ $isReadonly ? 'readonly style="cursor: not-allowed !important;"' : '' }}
               {{ (!$isReadonly && $questionRequired) ? 'required' : '' }}
               placeholder="{{ __('Enter a number') }}">

    @elseif($questionType === 'file')
        @if($isReadonly && $fieldValue)
            <div class="career-form-file-display">
                <i class="fa-solid fa-file me-2"></i>{{ __('File uploaded: ') }}{{ basename($fieldValue) }}
            </div>
        @else
            <input type="file"
                   class="career-form-input"
                   name="{{ $questionId }}"
                   {{ (!$isReadonly && $questionRequired) ? 'required' : '' }}
                   accept="*/*">
        @endif

    @elseif($questionType === 'checkbox' && !empty($questionOptions))
        <div class="career-form-checkbox-group">
            @foreach($questionOptions as $index => $option)
                @php
                    $optionValue = is_array($option) ? ($option['value'] ?? $option['label'] ?? '') : $option;
                    $optionLabel = is_array($option) ? ($option['label'] ?? $option['value'] ?? '') : $option;
                    $optionId = $questionId . '_opt_' . $index;
                    $isChecked = $isReadonly && is_array($fieldValue) && in_array($optionValue, $fieldValue);
                @endphp
                <div class="career-form-checkbox-option">
                    <input type="checkbox"
                           id="{{ $optionId }}"
                           name="{{ $questionId }}[]"
                           value="{{ e($optionValue) }}"
                           {{ $isChecked ? 'checked' : '' }}
                           {{ $isReadonly ? 'disabled' : '' }}
                           {{ (!$isReadonly && $questionRequired) ? 'required' : '' }}>
                    <label for="{{ $optionId }}" style="cursor: {{ $isReadonly ? 'default' : 'pointer' }};">{{ e($optionLabel) }}</label>
                </div>
            @endforeach
        </div>

    @else
        <input type="text"
               class="career-form-input"
               name="{{ $questionId }}"
               value="{{ $isReadonly ? e($fieldValue) : '' }}"
               {{ $isReadonly ? 'readonly style="cursor: not-allowed !important;"' : '' }}
               {{ (!$isReadonly && $questionRequired) ? 'required' : '' }}
               placeholder="{{ __('Enter your answer') }}">
    @endif
</div>

