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
    $isReadonly = isset($isReadonly) ? $isReadonly : (isset($submittedData) && $submittedData !== null);
    $fieldValue = $isReadonly ? ($submittedData[$questionId] ?? null) : null;

    // For checkbox, get array of values
    if ($isReadonly && $questionType === 'checkbox' && isset($submittedData[$questionId])) {
        $fieldValue = is_array($submittedData[$questionId]) ? $submittedData[$questionId] : [$submittedData[$questionId]];
    }
@endphp

<div class="career-form-question" data-question-id="{{ is_array($questionData) ? $questionData['id'] : $questionData->id }}" data-depth="{{ $depth }}">
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
                    <input type="radio"
                           id="{{ $optionId }}"
                           name="{{ $questionId }}"
                           value="{{ e($optionValue) }}"
                           data-question-id="{{ is_array($questionData) ? $questionData['id'] : $questionData->id }}"
                           data-option-value="{{ e($optionValue) }}"
                           {{ ($isReadonly && $fieldValue == $optionValue) ? 'checked' : '' }}
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
                        $shouldShow = false;
                        if ($isReadonly && isset($submittedData[$questionId]) && trim((string)$submittedData[$questionId]) == $optionValueStr) {
                            $shouldShow = true;
                        }
                    @endphp
                    <div class="career-form-nested-questions"
                         data-parent-question="{{ is_array($questionData) ? $questionData['id'] : $questionData->id }}"
                         data-option-value="{{ e($optionValueStr) }}"
                         style="display: {{ $shouldShow ? 'block' : 'none' }} !important;"
                         {{ $shouldShow ? 'class="show"' : '' }}>
                        @foreach($childData['items'] as $childItem)
                            @include('student.career-corner.partials.question', ['item' => $childItem, 'questions' => $questions, 'depth' => $depth + 1, 'submittedData' => $submittedData ?? null])
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
                    $isSelected = $isReadonly && $fieldValue == $optionValue;
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

