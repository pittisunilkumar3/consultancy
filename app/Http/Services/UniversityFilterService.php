<?php

namespace App\Http\Services;

use App\Models\CareerCornerSubmission;
use App\Models\Question;
use App\Models\QuestionCriteriaMapping;
use App\Models\University;
use App\Models\UniversityCriteriaField;
use App\Models\UniversityCriteriaValue;

class UniversityFilterService
{
    /**
     * Filter universities based on a student's career corner submission
     *
     * @param CareerCornerSubmission $submission
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function filterBySubmission(CareerCornerSubmission $submission)
    {
        $formData = $submission->form_data ?? [];
        
        if (empty($formData)) {
            // No form data, return empty collection
            return collect([]);
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
            return collect([]);
        }

        // Get all question-criteria mappings for these questions
        $mappings = QuestionCriteriaMapping::whereIn('question_id', $questionIds)
            ->with('criteriaField')
            ->get();

        if ($mappings->isEmpty()) {
            // No mappings found, return all active universities
            return $query->with('country')->get();
        }

        // Group mappings by criteria field
        $criteriaFilters = [];
        foreach ($mappings as $mapping) {
            $criteriaField = $mapping->criteriaField;
            $questionId = $mapping->question_id;
            $formKey = 'career_q_' . $questionId;
            
            if (!isset($formData[$formKey])) {
                continue; // Skip if no answer for this question
            }

            $studentAnswer = $formData[$formKey];
            
            if (!isset($criteriaFilters[$criteriaField->id])) {
                $criteriaFilters[$criteriaField->id] = [
                    'field' => $criteriaField,
                    'answers' => []
                ];
            }
            
            $criteriaFilters[$criteriaField->id]['answers'][] = $studentAnswer;
        }

        // Apply filters for each criteria field
        foreach ($criteriaFilters as $criteriaFieldId => $filterData) {
            $criteriaField = $filterData['field'];
            $studentAnswers = $filterData['answers'];
            
            // Use the first answer (or combine logic if multiple questions map to same criteria)
            $studentValue = is_array($studentAnswers) ? $studentAnswers[0] : $studentAnswers;
            
            $query = $this->applyCriteriaFilter($query, $criteriaField, $studentValue);
        }

        return $query->with('country')->get();
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

        // Find universities where this criteria value matches student's answer
        $universityIds = UniversityCriteriaValue::where('criteria_field_id', $criteriaField->id)
            ->where('value', $studentBool ? '1' : '0')
            ->pluck('university_id')
            ->toArray();

        if (empty($universityIds)) {
            // No universities match, return empty query
            return $query->whereRaw('1 = 0'); // Force empty result
        }

        return $query->whereIn('id', $universityIds);
    }

    /**
     * Filter by numeric criteria (number or decimal)
     * If student has value X, find universities where value <= X (for minimum requirements)
     */
    protected function filterNumeric($query, UniversityCriteriaField $criteriaField, $studentValue)
    {
        $studentNumeric = $this->normalizeNumeric($studentValue);
        
        if ($studentNumeric === null) {
            return $query; // Invalid answer, skip
        }

        // Get all universities with this criteria
        $criteriaValues = UniversityCriteriaValue::where('criteria_field_id', $criteriaField->id)
            ->get();

        $matchingUniversityIds = [];
        
        foreach ($criteriaValues as $criteriaValue) {
            $universityValue = $this->normalizeNumeric($criteriaValue->value);
            
            if ($universityValue === null) {
                continue; // Skip invalid values
            }

            // For minimum requirements (like minimum GPA, minimum IELTS):
            // Student's value should be >= university's requirement
            // Example: Student GPA 3.8 >= University minimum 3.5 ✅
            if ($studentNumeric >= $universityValue) {
                $matchingUniversityIds[] = $criteriaValue->university_id;
            }
        }

        if (empty($matchingUniversityIds)) {
            // No universities match, return empty query
            return $query->whereRaw('1 = 0'); // Force empty result
        }

        return $query->whereIn('id', $matchingUniversityIds);
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
        // Try to decode student value if it's JSON
        $studentData = is_string($studentValue) ? json_decode($studentValue, true) : $studentValue;
        
        if (!is_array($studentData)) {
            $studentData = [$studentValue]; // Convert to array
        }

        // Find universities where JSON contains any of the student's values
        $universityIds = [];
        $criteriaValues = UniversityCriteriaValue::where('criteria_field_id', $criteriaField->id)->get();
        
        foreach ($criteriaValues as $criteriaValue) {
            $universityData = json_decode($criteriaValue->value, true);
            
            if (!is_array($universityData)) {
                continue;
            }

            // Check if any student value is in university's array
            $hasMatch = false;
            foreach ($studentData as $studentItem) {
                if (in_array($studentItem, $universityData)) {
                    $hasMatch = true;
                    break;
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
}

