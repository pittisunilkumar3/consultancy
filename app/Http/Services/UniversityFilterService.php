<?php

namespace App\Http\Services;

use App\Models\CareerCornerSubmission;
use App\Models\Question;
use App\Models\QuestionCriteriaMapping;
use App\Models\University;
use App\Models\UniversityCriteriaField;
use App\Models\UniversityCriteriaValue;
use App\Models\Country;
use Illuminate\Support\Facades\Log;

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
        $allMappings = QuestionCriteriaMapping::with(['criteriaField', 'question'])
            ->get();

        // Get mappings for answered questions only
        $mappings = $allMappings->whereIn('question_id', $questionIds);

        // Track helper messages for unanswered criteria
        $helperMessages = [];

        // Get all unique criteria fields that have mappings
        $allCriteriaFieldsWithMappings = $allMappings->pluck('criteriaField')->unique('id')->filter();

        // Check which criteria fields are mapped but not answered
        foreach ($allCriteriaFieldsWithMappings as $criteriaField) {
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
                    if (is_string($answer) && trim($answer) !== '') {
                        $hasAnswer = true;
                        break;
                    }
                }
            }

            // If no answer found for this criteria field, add helper message
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

                    Log::info('University Filter: JSON criteria applied', [
                        'criteria_field' => $criteriaField->name,
                        'student_values' => $allValues,
                        'universities_before' => $beforeCount,
                        'universities_after' => $afterCount
                    ]);
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

                    Log::info('University Filter: Criteria applied', [
                        'criteria_field' => $criteriaField->name,
                        'criteria_type' => $criteriaField->type,
                        'student_value' => $studentValue,
                        'is_maximum_constraint' => $criteriaField->type === 'number' || $criteriaField->type === 'decimal' ? $this->isMaximumConstraint($criteriaField) : null,
                        'universities_before' => $beforeCount,
                        'universities_after' => $afterCount
                    ]);
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

