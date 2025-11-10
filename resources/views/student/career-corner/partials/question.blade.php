@php
    $question = $questions[$item['question_id']] ?? null;
    if (!$question) {
        return;
    }
    
    $questionId = 'career_q_' . $question->id;
    $required = $question->required ? '<span class="required">*</span>' : '';
    $helpText = $question->help_text ? '<div class="career-form-question-help">' . e($question->help_text) . '</div>' : '';
@endphp

<div class="career-form-question" data-question-id="{{ $question->id }}" data-depth="{{ $depth }}">
    <label class="career-form-question-label">
        {{ e($question->question) }}{!! $required !!}
    </label>
    {!! $helpText !!}
    
    @if($question->type === 'radio' && !empty($question->options))
        <div class="career-form-radio-group" data-question-id="{{ $question->id }}">
            @foreach($question->options as $index => $option)
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
                           data-question-id="{{ $question->id }}"
                           data-option-value="{{ e($optionValue) }}"
                           {{ $question->required ? 'required' : '' }}>
                    <label for="{{ $optionId }}" style="cursor: pointer;">{{ e($optionLabel) }}</label>
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
                    @endphp
                    <div class="career-form-nested-questions" 
                         data-parent-question="{{ $question->id }}" 
                         data-option-value="{{ e($optionValueStr) }}"
                         style="display: none !important;">
                        @foreach($childData['items'] as $childItem)
                            @include('student.career-corner.partials.question', ['item' => $childItem, 'questions' => $questions, 'depth' => $depth + 1])
                        @endforeach
                    </div>
                @endif
            @endforeach
        @endif
        
    @elseif($question->type === 'select' && !empty($question->options))
        <select class="career-form-select" name="{{ $questionId }}" {{ $question->required ? 'required' : '' }}>
            <option value="">{{ __('-- Select an option --') }}</option>
            @foreach($question->options as $option)
                @php
                    $optionValue = is_array($option) ? ($option['value'] ?? $option['label'] ?? '') : $option;
                    $optionLabel = is_array($option) ? ($option['label'] ?? $option['value'] ?? '') : $option;
                @endphp
                <option value="{{ e($optionValue) }}">{{ e($optionLabel) }}</option>
            @endforeach
        </select>
        
    @elseif($question->type === 'textarea')
        <textarea class="career-form-textarea" 
                  name="{{ $questionId }}" 
                  rows="4" 
                  {{ $question->required ? 'required' : '' }} 
                  placeholder="{{ __('Enter your answer') }}"></textarea>
        
    @elseif($question->type === 'number')
        <input type="number" 
               class="career-form-input" 
               name="{{ $questionId }}" 
               {{ $question->required ? 'required' : '' }} 
               placeholder="{{ __('Enter a number') }}">
        
    @elseif($question->type === 'file')
        <input type="file" 
               class="career-form-input" 
               name="{{ $questionId }}" 
               {{ $question->required ? 'required' : '' }}
               accept="*/*">
        
    @elseif($question->type === 'checkbox' && !empty($question->options))
        <div class="career-form-checkbox-group">
            @foreach($question->options as $index => $option)
                @php
                    $optionValue = is_array($option) ? ($option['value'] ?? $option['label'] ?? '') : $option;
                    $optionLabel = is_array($option) ? ($option['label'] ?? $option['value'] ?? '') : $option;
                    $optionId = $questionId . '_opt_' . $index;
                @endphp
                <div class="career-form-checkbox-option">
                    <input type="checkbox" 
                           id="{{ $optionId }}" 
                           name="{{ $questionId }}[]" 
                           value="{{ e($optionValue) }}"
                           {{ $question->required ? 'required' : '' }}>
                    <label for="{{ $optionId }}" style="cursor: pointer;">{{ e($optionLabel) }}</label>
                </div>
            @endforeach
        </div>
        
    @else
        <input type="text" 
               class="career-form-input" 
               name="{{ $questionId }}" 
               {{ $question->required ? 'required' : '' }} 
               placeholder="{{ __('Enter your answer') }}">
    @endif
</div>

