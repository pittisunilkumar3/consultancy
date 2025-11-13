<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Services\UniversityFilterService;
use App\Models\CareerCornerSubmission;
use App\Models\Country;
use App\Models\FormStructure;
use App\Models\Question;
use App\Models\StudyLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CareerCornerController extends Controller
{
    public function index(Request $request)
    {
        $data['pageTitle'] = __('Career Corner');
        $data['activeCareerCorner'] = 'active';
        $data['countryData'] = Country::where('status', STATUS_ACTIVE)->get();
        $data['studyLevels'] = StudyLevel::where('status', STATUS_ACTIVE)->get();

        // Load Career Corner form structure if published
        $structure = FormStructure::where('slug', 'career-corner')->first();
        $data['formStructure'] = null;
        $data['formData'] = null;
        $data['submission'] = null;
        $data['submittedData'] = null;

        if ($structure && $structure->is_published) {
            $data['formStructure'] = $structure;

            // Check if user has already submitted the form
            $user = auth()->user();
            $data['matchingUniversities'] = collect([]);
            $data['structureChanged'] = false;

            if ($user) {
                $submission = CareerCornerSubmission::where('user_id', $user->id)
                    ->where('form_structure_id', $structure->id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($submission) {
                    $data['submission'] = $submission;
                    // Ensure submittedData is always an array (form_data is cast to array, but ensure it's not null)
                    $formData = $submission->form_data;
                    $data['submittedData'] = is_array($formData) && !empty($formData) ? $formData : [];


                    // Check if structure has changed FIRST
                    $structureChanged = $submission->hasStructureChanged();
                    $data['structureChanged'] = $structureChanged;

                    // If structure has changed, use CURRENT structure so student can see new questions
                    // Otherwise, use snapshot to preserve original submission view
                    if ($structureChanged) {
                        // Structure changed - use current structure with all new questions
                        $data['formData'] = $structure->loadNestedStructure();
                        $questionsCollection = Question::orderBy('order')->get()->keyBy('id');
                        $questionsArray = [];
                        foreach ($questionsCollection as $id => $question) {
                            $questionsArray[$id] = $question->toArray();
                        }
                        $data['questions'] = $questionsArray;

                    } else {
                        // Structure unchanged - use snapshot to preserve original view
                        $snapshotData = $submission->getFormStructureData();

                        if ($snapshotData && isset($snapshotData['structure']) && isset($snapshotData['questions'])) {
                            // Use snapshot data for displaying submitted form
                            $data['formData'] = $snapshotData['structure'];

                        // Convert snapshot questions to collection, ensuring they're keyed by question ID
                        // Handle both array format (from JSON) and collection format
                        $snapshotQuestionsArray = $snapshotData['questions'];

                        // Always rekey by question ID to handle both old (numeric keys) and new (ID keys) formats
                        if (is_array($snapshotQuestionsArray) && !empty($snapshotQuestionsArray)) {
                            $firstKey = array_key_first($snapshotQuestionsArray);
                            $firstValue = $snapshotQuestionsArray[$firstKey] ?? null;

                            // Check if keys are question IDs or numeric indices
                            if (is_numeric($firstKey) && is_array($firstValue) && isset($firstValue['id'])) {
                                // Numeric keys (old format) - rekey by question ID
                                $snapshotQuestions = collect($snapshotQuestionsArray)->keyBy('id');
                            } elseif (is_numeric($firstKey) && !isset($firstValue['id'])) {
                                // Numeric keys but no 'id' field - this shouldn't happen, but handle it
                                $snapshotQuestions = collect($snapshotQuestionsArray);
                            } else {
                                // Check if keys match question IDs in values
                                $allKeysAreQuestionIds = true;
                                foreach ($snapshotQuestionsArray as $key => $question) {
                                    if (is_array($question) && isset($question['id'])) {
                                        if ($key != $question['id']) {
                                            $allKeysAreQuestionIds = false;
                                            break;
                                        }
                                    } else {
                                        $allKeysAreQuestionIds = false;
                                        break;
                                    }
                                }

                                if ($allKeysAreQuestionIds) {
                                    // Already properly keyed
                                    $snapshotQuestions = collect($snapshotQuestionsArray);
                                } else {
                                    // Rekey by question ID
                                    $snapshotQuestions = collect($snapshotQuestionsArray)->keyBy('id');
                                }
                            }
                        } else {
                            $snapshotQuestions = collect();
                        }

                        // Extract all question IDs from the snapshot structure to ensure we have all questions
                        $structureQuestionIds = $this->extractQuestionIdsFromStructure($snapshotData['structure']);
                        // Ensure we have a clean array of question IDs (not with numeric indices)
                        $structureQuestionIds = array_values(array_unique($structureQuestionIds));

                        // Get question IDs from snapshot (after rekeying, keys should be question IDs)
                        $snapshotQuestionIds = $snapshotQuestions->keys()->filter(function($key) {
                            // Filter out any non-numeric keys that aren't question IDs
                            return is_numeric($key) && $key > 0;
                        })->toArray();

                        // Also extract IDs from question data if keys don't match
                        if (empty($snapshotQuestionIds) || count($snapshotQuestionIds) !== $snapshotQuestions->count()) {
                            $snapshotQuestionIds = $snapshotQuestions->map(function($question) {
                                return is_array($question) ? ($question['id'] ?? null) : ($question->id ?? null);
                            })->filter()->unique()->values()->toArray();
                        }

                        $missingQuestionIds = array_diff($structureQuestionIds, $snapshotQuestionIds);

                        if (!empty($missingQuestionIds)) {
                            // Load missing questions from database
                            $missingQuestions = Question::whereIn('id', $missingQuestionIds)
                                ->get()
                                ->map(function ($question) {
                                    return [
                                        'id' => $question->id,
                                        'key' => $question->key,
                                        'question' => $question->question,
                                        'type' => $question->type,
                                        'options' => $question->options,
                                        'required' => $question->required,
                                        'help_text' => $question->help_text,
                                    ];
                                })
                                ->keyBy('id'); // Key by question ID AFTER mapping

                            // Merge snapshot questions with missing questions
                            // Use union to preserve keys properly
                            $data['questions'] = $snapshotQuestions->union($missingQuestions);

                            // Ensure the final array is keyed by question ID (as array, not collection)
                            $questionsArray = [];
                            foreach ($data['questions'] as $key => $question) {
                                $questionId = is_array($question) ? ($question['id'] ?? $key) : ($question->id ?? $key);
                                if ($questionId && is_numeric($questionId)) {
                                    $questionsArray[$questionId] = is_array($question) ? $question : (array)$question;
                                }
                            }
                            $data['questions'] = $questionsArray; // Pass as array, not collection

                        } else {
                            // Ensure snapshot questions are properly keyed (as array, not collection)
                            $questionsArray = [];
                            foreach ($snapshotQuestions as $key => $question) {
                                $questionId = is_array($question) ? ($question['id'] ?? $key) : ($question->id ?? $key);
                                if ($questionId && is_numeric($questionId)) {
                                    $questionsArray[$questionId] = is_array($question) ? $question : (array)$question;
                                }
                            }
                            $data['questions'] = $questionsArray; // Pass as array, not collection
                        }

                        } else {
                            // Fallback to current structure
                            $data['formData'] = $structure->loadNestedStructure();
                            $questionsCollection = Question::orderBy('order')->get()->keyBy('id');
                            // Convert to array with question IDs as keys
                            $questionsArray = [];
                            foreach ($questionsCollection as $id => $question) {
                                $questionsArray[$id] = $question->toArray();
                            }
                            $data['questions'] = $questionsArray;

                        }
                    }

                    // Get matching universities based on submission
                    $filterService = new UniversityFilterService();
                    $filterResult = $filterService->filterBySubmission($submission);
                    $data['matchingUniversities'] = $filterResult['universities'];
                    $data['helperMessages'] = $filterResult['helperMessages'];
                } else {
                    // No submission yet - use current structure
                    $data['formData'] = $structure->loadNestedStructure();
                    $questionsCollection = Question::orderBy('order')->get()->keyBy('id');
                    $questionsArray = [];
                    foreach ($questionsCollection as $id => $question) {
                        $questionsArray[$id] = $question->toArray();
                    }
                    $data['questions'] = $questionsArray;
                }
            } else {
                // No user logged in - use current structure
                $data['formData'] = $structure->loadNestedStructure();
                $questionsCollection = Question::orderBy('order')->get()->keyBy('id');
                $questionsArray = [];
                foreach ($questionsCollection as $id => $question) {
                    $questionsArray[$id] = $question->toArray();
                }
                $data['questions'] = $questionsArray;
            }
        }

        return view('student.career-corner.index', $data);
    }

    public function submit(Request $request)
    {
        try {
            // Get the form structure
            $structure = FormStructure::where('slug', 'career-corner')->first();

            if (!$structure || !$structure->is_published) {
                return response()->json([
                    'status' => false,
                    'message' => __('Form is not available')
                ], 404);
            }

            // Get authenticated user
            $user = auth()->user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => __('Please login to submit the form')
                ], 401);
            }

            // Collect all form data (excluding CSRF token)
            $formData = $request->except(['_token']);

            // Validate that we have some data
            if (empty($formData)) {
                return response()->json([
                    'status' => false,
                    'message' => __('No form data received')
                ], 422);
            }

            // Validate form data against question rules
            $validationErrors = $this->validateFormData($formData, $structure);
            if (!empty($validationErrors)) {
                return response()->json([
                    'status' => false,
                    'message' => __('Validation failed'),
                    'errors' => $validationErrors
                ], 422);
            }

            // Generate snapshot of current form structure and questions
            $snapshot = $this->generateFormStructureSnapshot($structure);

            // Check if user already has a submission for this form
            $existingSubmission = CareerCornerSubmission::where('user_id', $user->id)
                ->where('form_structure_id', $structure->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($existingSubmission) {
                // Update existing submission (also update snapshot in case structure changed)
                $existingSubmission->update([
                    'form_data' => $formData,
                    'form_structure_snapshot' => $snapshot,
                    'status' => STATUS_ACTIVE,
                ]);

                // Refresh to get the latest updated_at timestamp
                $existingSubmission->refresh();

                $submission = $existingSubmission;
                $message = __('Form updated successfully!');
            } else {
                // Create new submission
                $submission = CareerCornerSubmission::create([
                    'user_id' => $user->id,
                    'form_structure_id' => $structure->id,
                    'form_data' => $formData,
                    'form_structure_snapshot' => $snapshot,
                    'status' => STATUS_ACTIVE,
                ]);

                $message = __('Form submitted successfully!');
            }

            return response()->json([
                'status' => true,
                'message' => $message,
                'data' => $submission
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => __('Error submitting form: ') . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get matching universities for the authenticated user's submission
     */
    public function getMatchingUniversities(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => __('Please login to view matching universities')
                ], 401);
            }

            // Get the latest submission
            $structure = FormStructure::where('slug', 'career-corner')->first();

            if (!$structure) {
                return response()->json([
                    'status' => false,
                    'message' => __('Form structure not found')
                ], 404);
            }

            $submission = CareerCornerSubmission::where('user_id', $user->id)
                ->where('form_structure_id', $structure->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$submission) {
                return response()->json([
                    'status' => false,
                    'message' => __('Please submit the career corner form first'),
                    'data' => []
                ]);
            }

            // Filter universities
            $filterService = new UniversityFilterService();
            $filterResult = $filterService->filterBySubmission($submission);

            return response()->json([
                'status' => true,
                'message' => __('Matching universities retrieved successfully'),
                'data' => [
                    'universities' => $filterResult['universities']->load('country'),
                    'helperMessages' => $filterResult['helperMessages']
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => __('Error retrieving universities: ') . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate a snapshot of the form structure and questions at submission time
     * This ensures old submissions can be displayed correctly even if the form structure changes
     */
    private function generateFormStructureSnapshot(FormStructure $structure)
    {
        // Get the current form structure
        $formData = $structure->loadNestedStructure();

        // Get all questions used in this structure with their metadata
        $questionIds = collect($formData)
            ->flatMap(function ($element) {
                $ids = [];
                if ($element['type'] === 'section' && isset($element['items'])) {
                    $ids = $this->extractQuestionIds($element['items']);
                } elseif ($element['type'] === 'item' && isset($element['item'])) {
                    $ids = $this->extractQuestionIds([$element['item']]);
                }
                return $ids;
            })
            ->unique()
            ->toArray();

        // Load all questions with their full details
        $questions = Question::whereIn('id', $questionIds)
            ->get()
            ->map(function ($question) {
                return [
                    'id' => $question->id,
                    'key' => $question->key,
                    'question' => $question->question,
                    'type' => $question->type,
                    'options' => $question->options,
                    'required' => $question->required,
                    'help_text' => $question->help_text,
                    'placeholder' => $question->placeholder,
                    'step' => $question->step,
                ];
            })
            ->keyBy('id') // Key by question ID AFTER mapping to ensure proper keys
            ->toArray();

        // Ensure the array is properly keyed by question ID for JSON storage
        // This prevents Laravel from converting it to a numeric array
        $questionsKeyed = [];
        foreach ($questions as $id => $question) {
            $questionsKeyed[$id] = $question;
        }

        return [
            'structure' => $formData,
            'questions' => $questionsKeyed, // Use explicitly keyed array
            'snapshot_date' => now()->toDateTimeString(),
        ];
    }

    /**
     * Recursively extract question IDs from form structure items
     */
    private function extractQuestionIds($items)
    {
        $ids = [];

        foreach ($items as $item) {
            if (isset($item['question_id'])) {
                $ids[] = $item['question_id'];
            }

            // Recursively check children
            if (isset($item['children']) && is_array($item['children'])) {
                foreach ($item['children'] as $optionValue => $childGroup) {
                    if (isset($childGroup['items']) && is_array($childGroup['items'])) {
                        $ids = array_merge($ids, $this->extractQuestionIds($childGroup['items']));
                    }
                }
            }
        }

        return $ids;
    }

    /**
     * Extract all question IDs from a form structure (for ensuring all questions are loaded)
     */
    private function extractQuestionIdsFromStructure($structure)
    {
        $ids = [];

        if (!is_array($structure)) {
            return $ids;
        }

        foreach ($structure as $element) {
            if (isset($element['type'])) {
                if ($element['type'] === 'section' && isset($element['items'])) {
                    $ids = array_merge($ids, $this->extractQuestionIds($element['items']));
                } elseif ($element['type'] === 'item' && isset($element['item'])) {
                    $ids = array_merge($ids, $this->extractQuestionIds([$element['item']]));
                }
            }
        }

        // Return unique question IDs as a simple array (not keyed)
        return array_values(array_unique($ids));
    }

    /**
     * Validate form data against question validation rules
     */
    private function validateFormData(array $formData, FormStructure $structure)
    {
        $errors = [];

        // Get all questions used in this structure
        $questionIds = $this->extractQuestionIdsFromStructure($structure->loadNestedStructure());
        $questions = Question::whereIn('id', $questionIds)->get()->keyBy('id');

        // Validate each form field
        foreach ($formData as $fieldName => $fieldValue) {
            // Extract question ID from field name (format: career_q_{id} or career_q_{id}[])
            if (strpos($fieldName, 'career_q_') !== 0) {
                continue; // Skip non-question fields
            }

            // Extract question ID
            $questionId = str_replace('career_q_', '', $fieldName);
            $questionId = preg_replace('/\[\]$/', '', $questionId); // Remove [] for arrays

            if (!is_numeric($questionId)) {
                continue;
            }

            $questionId = (int)$questionId;
            $question = $questions->get($questionId);

            if (!$question) {
                continue; // Question not found, skip
            }

            // Handle array values (checkboxes)
            if (is_array($fieldValue)) {
                foreach ($fieldValue as $value) {
                    $this->validateFieldValue($question, $value, $fieldName, $errors);
                }
            } else {
                $this->validateFieldValue($question, $fieldValue, $fieldName, $errors);
            }
        }

        return $errors;
    }

    /**
     * Validate a single field value against question rules
     */
    private function validateFieldValue($question, $fieldValue, $fieldName, &$errors)
    {
        // Skip validation for empty optional fields
        if (empty($fieldValue) && !$question->required) {
            return;
        }

        // Validate email type
        if ($question->type === 'email' && !empty($fieldValue)) {
            if (!filter_var($fieldValue, FILTER_VALIDATE_EMAIL)) {
                $errors[$fieldName] = __('Please enter a valid email address');
                return;
            }
        }

    }
}
