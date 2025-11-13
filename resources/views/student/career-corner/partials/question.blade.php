@php
    // Handle both snapshot questions (arrays) and current questions (objects)
    $questionData = $questions[$item['question_id']] ?? null;

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
        $questionPlaceholder = $questionData['placeholder'] ?? null;
        $questionStep = $questionData['step'] ?? null;
    } else {
        $questionId = 'career_q_' . $questionData->id;
        $questionText = $questionData->question ?? '';
        $questionType = $questionData->type ?? 'text';
        $questionOptions = $questionData->options ?? [];
        $questionRequired = $questionData->required ?? false;
        $questionHelpText = $questionData->help_text ?? null;
        $questionPlaceholder = $questionData->placeholder ?? null;
        $questionStep = $questionData->step ?? null;
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

@endphp

<div class="career-form-question"
     data-question-id="{{ is_array($questionData) ? $questionData['id'] : $questionData->id }}"
     data-depth="{{ $depth }}"
     data-question-key="{{ $questionId }}"
     data-field-value="{{ is_array($fieldValue) ? json_encode($fieldValue) : ($fieldValue ?? '') }}"
     data-is-readonly="{{ $isReadonly ? '1' : '0' }}"
     data-question-required="{{ $questionRequired ? '1' : '0' }}"
     data-question-type="{{ $questionType }}">
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

                        if ($isReadonly && isset($submittedData) && is_array($submittedData) && !empty($submittedData)) {
                            // Check if the submitted data has the parent question's answer
                            // Try multiple key formats to be safe
                            $submittedValue = null;

                            // First try with career_q_ prefix
                            if (isset($submittedData[$questionId])) {
                                $submittedValue = $submittedData[$questionId];
                            }

                            // If not found, try with just the question ID (for backward compatibility)
                            if ($submittedValue === null) {
                                $questionIdOnly = is_array($questionData) ? $questionData['id'] : $questionData->id;
                                if (isset($submittedData[$questionIdOnly])) {
                                    $submittedValue = $submittedData[$questionIdOnly];
                                }
                            }

                            // Also try with 'career_q_' prefix but numeric ID
                            if ($submittedValue === null) {
                                $questionIdOnly = is_array($questionData) ? $questionData['id'] : $questionData->id;
                                $altKey = 'career_q_' . $questionIdOnly;
                                if (isset($submittedData[$altKey])) {
                                    $submittedValue = $submittedData[$altKey];
                                }
                            }

                            if ($submittedValue !== null && $submittedValue !== '') {
                                $submittedValueStr = trim((string)$submittedValue);
                                $optionValueStrTrimmed = trim((string)$optionValueStr);

                                // Case-insensitive comparison
                                $exactMatch = ($submittedValueStr === $optionValueStrTrimmed);
                                $caseInsensitiveMatch = (strtolower($submittedValueStr) === strtolower($optionValueStrTrimmed));

                                // Also check if optionValue is an array with value/label structure
                                if (!$exactMatch && !$caseInsensitiveMatch && is_array($optionValue)) {
                                    $optionValueFromArray = trim((string)($optionValue['value'] ?? $optionValue['label'] ?? ''));
                                    if ($optionValueFromArray) {
                                        $exactMatch = ($submittedValueStr === $optionValueFromArray);
                                        $caseInsensitiveMatch = (strtolower($submittedValueStr) === strtolower($optionValueFromArray));
                                    }
                                }

                                $shouldShow = $exactMatch || $caseInsensitiveMatch;
                            }
                        }
                    @endphp
                    <div class="career-form-nested-questions{{ $shouldShow ? ' show' : '' }}"
                         data-parent-question="{{ is_array($questionData) ? $questionData['id'] : $questionData->id }}"
                         data-option-value="{{ e($optionValueStr) }}"
                         style="display: {{ $shouldShow ? 'block' : 'none' }} !important;">
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
                  placeholder="{{ $questionPlaceholder ? e($questionPlaceholder) : __('Enter your answer') }}">{{ $isReadonly ? e($fieldValue) : '' }}</textarea>

    @elseif($questionType === 'number')
        <input type="number"
               class="career-form-input"
               name="{{ $questionId }}"
               value="{{ $isReadonly ? e($fieldValue) : '' }}"
               {{ $isReadonly ? 'readonly style="cursor: not-allowed !important;"' : '' }}
               {{ (!$isReadonly && $questionRequired) ? 'required' : '' }}
               @if($questionStep) step="{{ e($questionStep) }}" @endif
               placeholder="{{ $questionPlaceholder ? e($questionPlaceholder) : __('Enter a number') }}">

    @elseif($questionType === 'email')
        <input type="email"
               class="career-form-input"
               name="{{ $questionId }}"
               value="{{ $isReadonly ? e($fieldValue) : '' }}"
               {{ $isReadonly ? 'readonly style="cursor: not-allowed !important;"' : '' }}
               {{ (!$isReadonly && $questionRequired) ? 'required' : '' }}
               placeholder="{{ $questionPlaceholder ? e($questionPlaceholder) : __('Enter your email address') }}">

    @elseif($questionType === 'file')
        @if($isReadonly)
            @if($fieldValue)
                <div class="career-form-file-display">
                    @php
                        // Get file URL from storage path
                        // Use asset() for better localhost compatibility
                        // The file path stored in DB is like: uploads/career-corner/filename.pdf
                        // So we need: storage/uploads/career-corner/filename.pdf
                        $fileUrl = asset('storage/' . $fieldValue);
                        $fileName = basename($fieldValue);
                    @endphp
                    <div class="d-inline-flex align-items-center cg-2">
                        <i class="fa-solid fa-file me-2"></i>
                        <span>{{ __('File uploaded: ') }}{{ $fileName }}</span>
                        <a href="{{ $fileUrl }}" target="_blank" class="ms-2 text-primary text-decoration-none" title="{{ __('Download file') }}">
                            <i class="fa-solid fa-download"></i>
                        </a>
                    </div>
                </div>
                {{-- Always include file input for editing, but hide it in readonly mode --}}
                <input type="file"
                       class="career-form-input"
                       name="{{ $questionId }}"
                       style="display: none;"
                       {{ (!$isReadonly && $questionRequired) ? 'required' : '' }}
                       accept="*/*">
            @else
                {{-- No file uploaded - show message in readonly mode --}}
                <div class="career-form-file-display text-muted">
                    <i class="fa-solid fa-file me-2"></i>
                    <span>{{ __('No file uploaded') }}</span>
                </div>
                {{-- Always include file input for editing, but hide it in readonly mode --}}
                <input type="file"
                       class="career-form-input"
                       name="{{ $questionId }}"
                       style="display: none;"
                       {{ (!$isReadonly && $questionRequired) ? 'required' : '' }}
                       accept="*/*">
            @endif
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
               placeholder="{{ $questionPlaceholder ? e($questionPlaceholder) : __('Enter your answer') }}">
    @endif
</div>

