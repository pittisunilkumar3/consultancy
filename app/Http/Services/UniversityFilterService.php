<?php

namespace App\Http\Services;

use App\Models\CareerCornerSubmission;
use App\Models\Question;
use App\Models\QuestionCriteriaMapping;
use App\Models\University;
use App\Models\UniversityCriteriaField;
use App\Models\UniversityCriteriaValue;
use App\Models\Country;

class UniversityFilterService
{
    /**
     * Filter universities based on a student's career corner submission
     *
     * @param CareerCornerSubmission $submission
     * @return array ['universities' => Collection, 'helperMessages' => array]
     */
    public function filterBySubmission(CareerCornerSubmission $submission)
    {
        $formData = $submission->form_data ?? [];

        if (empty($formData)) {
            return [
                'universities' => collect([]),
                'helperMessages' => []
            ];
        }

        // Start with all active universities
        $query = University::where('status', STATUS_ACTIVE);

        // Get all question IDs from form data
        $questionIds = [];
        foreach ($formData as $key => $value) {
            // Extract question ID from keys like "career_q_5"
            if (preg_match('/career_q_(\d+)/', $key, $matches)) {
                $questionIds[] = (int)$matches[1];
            }
        }

        if (empty($questionIds)) {
            return [
                'universities' => collect([]),
                'helperMessages' => []
            ];
        }

        // Get ALL question-criteria mappings (not just for answered questions)
        // This helps us identify which criteria fields exist but weren't answered
        // Also load dependsOn relationship for dependency checking
        $allMappings = QuestionCriteriaMapping::with(['criteriaField.dependsOn', 'question'])
            ->get();

        // Get mappings for answered questions only
        $mappings = $allMappings->whereIn('question_id', $questionIds);

        // Track helper messages for unanswered criteria
        $helperMessages = [];

        // Get all unique criteria fields that have mappings
        $allCriteriaFieldsWithMappings = $allMappings->pluck('criteriaField')->unique('id')->filter();

        // Check which criteria fields are mapped but not answered
        foreach ($allCriteriaFieldsWithMappings as $criteriaField) {
            // Check if this criteria field depends on another criteria field
            $parentCriteriaMatches = true; // Default to true if no dependency

            if ($criteriaField->depends_on_criteria_field_id) {
                // This criteria field depends on a parent criteria field
                // Find the parent criteria field and check if it's answered with the correct value
                $parentCriteriaField = $allCriteriaFieldsWithMappings->firstWhere('id', $criteriaField->depends_on_criteria_field_id);

                if ($parentCriteriaField) {
                    // Get all questions mapped to the parent criteria field
                    $parentMappedQuestions = $allMappings->where('criteria_field_id', $parentCriteriaField->id);

                    // Check if parent criteria is answered with the correct value
                    $parentHasMatchingAnswer = false;

                    foreach ($parentMappedQuestions as $parentMapping) {
                        $parentFormKey = 'career_q_' . $parentMapping->question_id;

                        if (isset($formData[$parentFormKey]) && $formData[$parentFormKey] !== null && $formData[$parentFormKey] !== '') {
                            $parentAnswer = $formData[$parentFormKey];

                            // Handle array answers (e.g., checkboxes)
                            if (is_array($parentAnswer)) {
                                $parentAnswer = implode(',', $parentAnswer);
                            }

                            // Convert to string for comparison
                            $parentAnswer = (string)$parentAnswer;

                            // Check if parent answer matches the required value
                            if ($criteriaField->shouldBeShown($parentAnswer)) {
                                $parentHasMatchingAnswer = true;
                                break;
                            }
                        }
                    }

                    // Only show helper message if parent criteria matches
                    $parentCriteriaMatches = $parentHasMatchingAnswer;
                } else {
                    // Parent criteria field not found, don't show helper message
                    $parentCriteriaMatches = false;
                }
            }

            // Only proceed to check for answers if parent criteria matches (or no dependency)
            if (!$parentCriteriaMatches) {
                continue; // Skip this criteria field - parent doesn't match
            }

            // Get all questions mapped to this criteria field
            $mappedQuestions = $allMappings->where('criteria_field_id', $criteriaField->id);

            // Check if any of these questions were answered
            $hasAnswer = false;
            $mappedQuestionIds = [];

            foreach ($mappedQuestions as $mapping) {
                $mappedQuestionIds[] = $mapping->question_id;
                $formKey = 'career_q_' . $mapping->question_id;

                if (isset($formData[$formKey]) && $formData[$formKey] !== null && $formData[$formKey] !== '') {
                    $answer = $formData[$formKey];

                    // Handle different answer types
                    if (is_array($answer)) {
                        // For checkboxes, check if array is not empty
                        $hasAnswer = !empty($answer);
                    } elseif (is_string($answer)) {
                        // For text inputs, check if string is not empty after trimming
                        $hasAnswer = trim($answer) !== '';
                    } else {
                        // For other types (numbers, etc.), just check if not null/empty
                        $hasAnswer = true;
                    }

                    if ($hasAnswer) {
                        break;
                    }
                }
            }

            // If no answer found for this criteria field, add helper message
            // (Only if parent criteria matches, which we already checked above)
            if (!$hasAnswer) {
                $questionTexts = [];
                foreach ($mappedQuestions as $mapping) {
                    if ($mapping->question) {
                        $questionTexts[] = $mapping->question->question;
                    }
                }

                $helperMessages[] = [
                    'criteria_field_id' => $criteriaField->id,
                    'criteria_field_name' => $criteriaField->name,
                    'criteria_field_slug' => $criteriaField->slug,
                    'message' => "This is a criteria to enter. You have not attempted: {$criteriaField->name}",
                    'related_questions' => $questionTexts,
                    'related_question_ids' => $mappedQuestionIds
                ];
            }
        }

        if ($mappings->isEmpty()) {
            // Return empty universities but with helper messages
            return [
                'universities' => collect([]),
                'helperMessages' => $helperMessages
            ];
        }

        // Group mappings by criteria field - only for questions that have answers
        $criteriaFilters = [];
        $filtersApplied = 0;

        foreach ($mappings as $mapping) {
            $criteriaField = $mapping->criteriaField;
            $questionId = $mapping->question_id;
            $formKey = 'career_q_' . $questionId;

            // Skip if no answer for this question
            if (!isset($formData[$formKey])) {
                continue;
            }

            $studentAnswer = $formData[$formKey];

            if ($studentAnswer === null || $studentAnswer === '') {
                continue;
            }

            // Skip empty answers
            if (is_string($studentAnswer) && trim($studentAnswer) === '') {
                continue;
            }

            if (!isset($criteriaFilters[$criteriaField->id])) {
                $criteriaFilters[$criteriaField->id] = [
                    'field' => $criteriaField,
                    'answers' => []
                ];
            }

            $criteriaFilters[$criteriaField->id]['answers'][] = $studentAnswer;
        }

        // If no criteria filters have answers, return empty (don't show all universities)
        if (empty($criteriaFilters)) {
            return [
                'universities' => collect([]),
                'helperMessages' => $helperMessages
            ];
        }

        // Apply filters for each criteria field that has an answer
        foreach ($criteriaFilters as $criteriaFieldId => $filterData) {
            $criteriaField = $filterData['field'];
            $studentAnswers = $filterData['answers'];

            // Check if this criteria field depends on another field
            // If it does, validate the parent condition before applying the filter
            if ($criteriaField->depends_on_criteria_field_id) {
                $parentConditionMet = $this->checkParentCondition(
                    $criteriaField,
                    $formData,
                    $questionIds,
                    $allMappings
                );

                if (!$parentConditionMet) {
                    // Parent condition not met, skip this filter
                    continue;
                }
            }

            // For JSON type, we need to handle arrays properly
            // If multiple questions map to same criteria, merge all answers
            // For checkbox questions, the answer is already an array
            if ($criteriaField->type === 'json') {
                // Collect all values from all answers (handles both arrays and single values)
                $allValues = [];
                foreach ($studentAnswers as $answer) {
                    if (is_array($answer)) {
                        $allValues = array_merge($allValues, $answer);
                    } else {
                        $allValues[] = $answer;
                    }
                }
                // Remove duplicates and empty values
                $allValues = array_unique(array_filter($allValues));

                if (!empty($allValues)) {
                    // Get current university IDs before applying filter
                    $beforeCount = $query->count();
                    // Pass as array for JSON filtering
                    $query = $this->applyCriteriaFilter($query, $criteriaField, $allValues);
                    $afterCount = $query->count();
                    $filtersApplied++;
                }
            } else {
                // For non-JSON types, use the first answer
                $studentValue = is_array($studentAnswers) ? $studentAnswers[0] : $studentAnswers;

                // Check if value is valid before applying filter
                if ($studentValue !== null && $studentValue !== '') {
                    // Get current university IDs before applying filter
                    $beforeCount = $query->count();
                    $query = $this->applyCriteriaFilter($query, $criteriaField, $studentValue);
                    $afterCount = $query->count();
                    $filtersApplied++;
                }
            }
        }

        // Apply country filter if country questions were answered
        $countryIds = $this->extractCountryIdsFromAnswers($formData, $questionIds);
        if (!empty($countryIds)) {
            $query->whereIn('country_id', $countryIds);
            $filtersApplied++;
        }

        // Only return results if at least one filter was successfully applied
        // This prevents showing all universities when mapped questions are not answered
        if ($filtersApplied === 0) {
            return [
                'universities' => collect([]),
                'helperMessages' => $helperMessages
            ];
        }

        $results = $query->with('country')->get();

        return [
            'universities' => $results,
            'helperMessages' => $helperMessages
        ];
    }

    /**
     * Apply a single criteria filter to the query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param UniversityCriteriaField $criteriaField
     * @param mixed $studentValue
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyCriteriaFilter($query, UniversityCriteriaField $criteriaField, $studentValue)
    {
        if ($studentValue === null || $studentValue === '') {
            return $query; // Skip if no student value
        }

        switch ($criteriaField->type) {
            case 'boolean':
                return $this->filterBoolean($query, $criteriaField, $studentValue);

            case 'number':
            case 'decimal':
                return $this->filterNumeric($query, $criteriaField, $studentValue);

            case 'text':
                return $this->filterText($query, $criteriaField, $studentValue);

            case 'json':
                return $this->filterJson($query, $criteriaField, $studentValue);

            default:
                return $query;
        }
    }

    /**
     * Filter by boolean criteria
     * If student answered "Yes"/"true"/"1", find universities where value = "1"
     */
    protected function filterBoolean($query, UniversityCriteriaField $criteriaField, $studentValue)
    {
        // Normalize student answer to boolean
        $studentBool = $this->normalizeBoolean($studentValue);

        if ($studentBool === null) {
            return $query; // Invalid answer, skip
        }

        $targetValue = $studentBool ? '1' : '0';

        // Find universities where this criteria value matches student's answer
        $universityIds = UniversityCriteriaValue::where('criteria_field_id', $criteriaField->id)
            ->where('value', $targetValue)
            ->pluck('university_id')
            ->toArray();

        if (empty($universityIds)) {
            return $query->whereRaw('1 = 0'); // Force empty result
        }

        return $query->whereIn('id', $universityIds);
    }

    /**
     * Filter by numeric criteria (number or decimal)
     * Handles both minimum requirements and maximum constraints
     */
    protected function filterNumeric($query, UniversityCriteriaField $criteriaField, $studentValue)
    {
        $studentNumeric = $this->normalizeNumeric($studentValue);

        if ($studentNumeric === null) {
            return $query; // Invalid answer, skip
        }

        // Determine if this is a maximum constraint or minimum requirement
        // Check slug/name for keywords indicating maximum constraint
        $isMaximumConstraint = $this->isMaximumConstraint($criteriaField);

        // Get all universities with this criteria
        $criteriaValues = UniversityCriteriaValue::where('criteria_field_id', $criteriaField->id)
            ->get();

        $matchingUniversityIds = [];

        foreach ($criteriaValues as $criteriaValue) {
            $universityValue = $this->normalizeNumeric($criteriaValue->value);

            if ($universityValue === null) {
                continue; // Skip invalid values
            }

            if ($isMaximumConstraint) {
                // For maximum constraints (like maximum backlogs):
                // Student's value should be <= university's maximum
                // Example: Student has 2 backlogs <= University max 3 backlogs ✅
                if ($studentNumeric <= $universityValue) {
                    $matchingUniversityIds[] = $criteriaValue->university_id;
                }
            } else {
                // For minimum requirements (like minimum GPA, minimum IELTS):
                // Student's value should be >= university's requirement
                // Example: Student GPA 3.8 >= University minimum 3.5 ✅
                if ($studentNumeric >= $universityValue) {
                    $matchingUniversityIds[] = $criteriaValue->university_id;
                }
            }
        }

        if (empty($matchingUniversityIds)) {
            return $query->whereRaw('1 = 0'); // Force empty result
        }

        return $query->whereIn('id', $matchingUniversityIds);
    }

    /**
     * Check if a criteria field represents a maximum constraint
     * (e.g., maximum backlogs) vs minimum requirement (e.g., minimum GPA)
     */
    protected function isMaximumConstraint(UniversityCriteriaField $criteriaField): bool
    {
        $slug = strtolower($criteriaField->slug);
        $name = strtolower($criteriaField->name);

        // Keywords that indicate maximum constraint
        $maximumKeywords = ['max', 'maximum', 'backlog', 'backlogs', 'limit', 'upto', 'up_to', 'at_most', 'atmost'];

        // Check if slug or name contains maximum keywords
        foreach ($maximumKeywords as $keyword) {
            if (strpos($slug, $keyword) !== false || strpos($name, $keyword) !== false) {
                return true;
            }
        }

        // Default to minimum requirement (for GPA, IELTS scores, etc.)
        return false;
    }

    /**
     * Filter by text criteria
     * Match exact or contains
     */
    protected function filterText($query, UniversityCriteriaField $criteriaField, $studentValue)
    {
        $studentText = trim((string)$studentValue);

        if (empty($studentText)) {
            return $query; // Skip empty
        }

        // Find universities where this criteria value matches or contains student's answer
        $universityIds = UniversityCriteriaValue::where('criteria_field_id', $criteriaField->id)
            ->where(function($q) use ($studentText) {
                $q->where('value', 'LIKE', "%{$studentText}%")
                  ->orWhere('value', '=', $studentText);
            })
            ->pluck('university_id')
            ->toArray();

        if (empty($universityIds)) {
            return $query->whereRaw('1 = 0'); // Force empty result
        }

        return $query->whereIn('id', $universityIds);
    }

    /**
     * Filter by JSON criteria
     * For arrays/objects stored as JSON
     */
    protected function filterJson($query, UniversityCriteriaField $criteriaField, $studentValue)
    {
        // Regular JSON field (simple arrays like ["UG", "PG"])
        // Student value can be:
        // - An array (from checkbox or merged from multiple questions): ["UG", "PG"]
        // - A string (from radio/select): "UG"
        // - A JSON string: '["UG", "PG"]'

        // Normalize to array
        $studentData = [];

        if (is_array($studentValue)) {
            // Already an array - use directly
            $studentData = array_filter(array_map('trim', $studentValue));
        } elseif (is_string($studentValue)) {
            // Try to decode as JSON first
            $decoded = json_decode($studentValue, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                // Valid JSON array
                $studentData = array_filter(array_map('trim', $decoded));
            } else {
                // Not JSON, treat as single value
                $studentData = [trim($studentValue)];
            }
        } else {
            // Other types - convert to string and wrap in array
            $studentData = [trim((string)$studentValue)];
        }

        // Remove empty values
        $studentData = array_filter($studentData);

        if (empty($studentData)) {
            return $query->whereRaw('1 = 0'); // No valid student values
        }

        // Find universities where JSON contains any of the student's values
        $universityIds = [];
        $criteriaValues = UniversityCriteriaValue::where('criteria_field_id', $criteriaField->id)->get();

        foreach ($criteriaValues as $criteriaValue) {
            $universityData = json_decode($criteriaValue->value, true);

            if (!is_array($universityData)) {
                continue;
            }

            // Normalize university data (trim strings, remove empty)
            $universityData = array_filter(array_map(function($item) {
                return is_string($item) ? trim($item) : $item;
            }, $universityData));

            // Check if any student value is in university's array
            // Use case-insensitive comparison for better matching
            $hasMatch = false;
            foreach ($studentData as $studentItem) {
                foreach ($universityData as $universityItem) {
                    // Case-insensitive comparison
                    if (strcasecmp(trim((string)$studentItem), trim((string)$universityItem)) === 0) {
                        $hasMatch = true;
                        break 2; // Break both loops
                    }
                }
            }

            if ($hasMatch) {
                $universityIds[] = $criteriaValue->university_id;
            }
        }

        if (empty($universityIds)) {
            return $query->whereRaw('1 = 0'); // Force empty result
        }

        return $query->whereIn('id', $universityIds);
    }

    /**
     * Check if parent condition is met for a dependent criteria field
     *
     * @param UniversityCriteriaField $criteriaField The dependent criteria field
     * @param array $formData Student form submission data
     * @param array $questionIds Array of answered question IDs
     * @param \Illuminate\Support\Collection $allMappings All question-criteria mappings
     * @return bool True if parent condition is met, false otherwise
     */
    protected function checkParentCondition(
        UniversityCriteriaField $criteriaField,
        array $formData,
        array $questionIds,
        $allMappings
    ) {
        $parentFieldId = $criteriaField->depends_on_criteria_field_id;
        $requiredParentValue = $criteriaField->depends_on_value;

        if (!$parentFieldId || $requiredParentValue === null) {
            return true; // No dependency or no specific value required
        }

        // Load parent field with relationship
        $parentField = $criteriaField->dependsOn;
        if (!$parentField) {
            return false;
        }

        // Get all questions mapped to the parent criteria field
        $parentMappings = $allMappings->where('criteria_field_id', $parentFieldId);

        if ($parentMappings->isEmpty()) {
            // No questions mapped to parent field - condition cannot be met
            return false;
        }

        // Get parent field's answer from student form data
        $parentAnswers = [];
        foreach ($parentMappings as $parentMapping) {
            $parentQuestionId = $parentMapping->question_id;
            $parentFormKey = 'career_q_' . $parentQuestionId;

            if (isset($formData[$parentFormKey]) && $formData[$parentFormKey] !== null && $formData[$parentFormKey] !== '') {
                $parentAnswer = $formData[$parentFormKey];

                // Skip empty answers
                if (is_string($parentAnswer) && trim($parentAnswer) === '') {
                    continue;
                }

                $parentAnswers[] = $parentAnswer;
            }
        }

        if (empty($parentAnswers)) {
            // No answer for parent field - condition not met
            return false;
        }

        // Check if parent is JSON checkbox field (has options)
        if ($parentField->type === 'json' && !empty($parentField->options) && is_array($parentField->options)) {
            // Parent is JSON checkbox - check if required value is in selected options
            $selectedOptions = [];
            foreach ($parentAnswers as $answer) {
                if (is_array($answer)) {
                    $selectedOptions = array_merge($selectedOptions, $answer);
                } else {
                    // Try to decode as JSON
                    $decoded = json_decode($answer, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $selectedOptions = array_merge($selectedOptions, $decoded);
                    } else {
                        $selectedOptions[] = $answer;
                    }
                }
            }

            // Normalize selected options (trim, remove empty)
            $selectedOptions = array_map('trim', array_filter($selectedOptions));
            $normalizedRequiredValue = trim((string)$requiredParentValue);

            // Check if required value is in selected options (case-insensitive)
            $conditionMet = false;
            foreach ($selectedOptions as $option) {
                if (strcasecmp(trim((string)$option), $normalizedRequiredValue) === 0) {
                    $conditionMet = true;
                    break;
                }
            }

            return $conditionMet;
        } else {
            // Parent is single value field (boolean, text, number, select)
            // Use first answer (or merge if multiple)
            $parentValue = is_array($parentAnswers) ? $parentAnswers[0] : $parentAnswers;

            // Normalize values for comparison
            $normalizedParentValue = trim((string)$parentValue);
            $normalizedRequiredValue = trim((string)$requiredParentValue);

            // For boolean fields, normalize to "1" or "0"
            if ($parentField->type === 'boolean') {
                $parentBool = $this->normalizeBoolean($parentValue);
                if ($parentBool === null) {
                    return false;
                }
                $normalizedParentValue = $parentBool ? '1' : '0';
            }

            $conditionMet = (strcasecmp($normalizedParentValue, $normalizedRequiredValue) === 0);

            return $conditionMet;
        }
    }

    /**
     * Normalize a value to boolean
     * Returns true, false, or null if invalid
     */
    protected function normalizeBoolean($value)
    {
        if (is_bool($value)) {
            return $value;
        }

        $value = strtolower(trim((string)$value));

        $trueValues = ['yes', 'true', '1', 'on', 'y'];
        $falseValues = ['no', 'false', '0', 'off', 'n', ''];

        if (in_array($value, $trueValues)) {
            return true;
        }

        if (in_array($value, $falseValues)) {
            return false;
        }

        return null; // Invalid
    }

    /**
     * Normalize a value to numeric (float)
     * Returns float or null if invalid
     */
    protected function normalizeNumeric($value)
    {
        if (is_numeric($value)) {
            return (float)$value;
        }

        // Try to extract number from string
        if (preg_match('/(\d+\.?\d*)/', (string)$value, $matches)) {
            return (float)$matches[1];
        }

        return null; // Invalid
    }

    /**
     * Extract country IDs from form answers for country questions
     *
     * @param array $formData
     * @param array $questionIds
     * @return array Array of country IDs
     */
    protected function extractCountryIdsFromAnswers(array $formData, array $questionIds): array
    {
        if (empty($questionIds)) {
            return [];
        }

        // Get all country questions
        $countryQuestions = Question::whereIn('id', $questionIds)
            ->where('is_country_question', true)
            ->get();

        if ($countryQuestions->isEmpty()) {
            return [];
        }

        $countryIds = [];

        foreach ($countryQuestions as $question) {
            $formKey = 'career_q_' . $question->id;

            // Skip if no answer for this question
            if (!isset($formData[$formKey])) {
                continue;
            }

            $studentAnswer = $formData[$formKey];

            if ($studentAnswer === null || $studentAnswer === '') {
                continue;
            }

            // Handle different question types
            if ($question->type === 'select') {
                // For select: answer is the value (country ID)
                $countryId = $this->extractCountryIdFromSelectAnswer($studentAnswer);
                if ($countryId) {
                    $countryIds[] = $countryId;
                }
            } elseif ($question->type === 'radio') {
                // For radio: answer could be country ID (if using countries) or country name
                $countryId = $this->extractCountryIdFromRadioAnswer($studentAnswer, $question);
                if ($countryId) {
                    $countryIds[] = $countryId;
                }
            } elseif ($question->type === 'checkbox') {
                // For checkbox: answer is array, extract all country IDs
                $answerArray = is_array($studentAnswer) ? $studentAnswer : [$studentAnswer];
                foreach ($answerArray as $answer) {
                    $countryId = $this->extractCountryIdFromCheckboxAnswer($answer, $question);
                    if ($countryId) {
                        $countryIds[] = $countryId;
                    }
                }
            }
        }

        // Remove duplicates and return
        return array_unique(array_filter($countryIds));
    }

    /**
     * Extract country ID from select answer
     * For select type, the answer is directly the country ID (value)
     */
    protected function extractCountryIdFromSelectAnswer($answer)
    {
        // Answer is the value (country ID) from select
        $countryId = is_numeric($answer) ? (int)$answer : null;
        return $countryId;
    }

    /**
     * Extract country ID from radio answer
     * Could be country ID (if using countries) or country name (backward compatible)
     */
    protected function extractCountryIdFromRadioAnswer($answer, Question $question)
    {
        // Check if answer is numeric (country ID)
        if (is_numeric($answer)) {
            return (int)$answer;
        }

        // Check if options are stored as {value, label} format
        $options = $question->options ?? [];
        if (is_array($options)) {
            foreach ($options as $option) {
                if (is_array($option) && isset($option['value']) && isset($option['label'])) {
                    // If answer matches label, return the value (country ID)
                    if ($option['label'] === $answer) {
                        return is_numeric($option['value']) ? (int)$option['value'] : null;
                    }
                    // If answer matches value, return it
                    if ($option['value'] == $answer) {
                        return is_numeric($option['value']) ? (int)$option['value'] : null;
                    }
                }
            }
        }

        // Fallback: try to find country by name (backward compatible)
        $country = \App\Models\Country::where('name', $answer)->first();
        return $country ? $country->id : null;
    }

    /**
     * Extract country ID from checkbox answer
     * Could be country ID (if using countries) or country name (backward compatible)
     */
    protected function extractCountryIdFromCheckboxAnswer($answer, Question $question)
    {
        // Same logic as radio
        return $this->extractCountryIdFromRadioAnswer($answer, $question);
    }
}

