<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Services\UniversityFilterService;
use App\Models\CareerCornerSubmission;
use App\Models\Country;
use App\Models\FormStructure;
use App\Models\Question;
use App\Models\StudyLevel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
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

            // Generate snapshot of current form structure and questions
            $snapshot = $this->generateFormStructureSnapshot($structure);

            // Check if user already has a submission for this form
            $existingSubmission = CareerCornerSubmission::where('user_id', $user->id)
                ->where('form_structure_id', $structure->id)
                ->orderBy('created_at', 'desc')
                ->first();

            // Process file uploads (pass existing submission for file replacement)
            $formData = $this->processFileUploads($request, $structure, $existingSubmission);

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

                // Notify all active admin users about form update
                $admins = User::where(['role' => USER_ROLE_ADMIN, 'status' => STATUS_ACTIVE])->get();
                foreach ($admins as $admin) {
                    setCommonNotification(
                        $admin->id,
                        __('Career Corner Form Updated'),
                        __('Career corner form has been updated by ') . $user->first_name . ' ' . $user->last_name,
                        route('admin.career-corner-submissions.show', $submission->id)
                    );
                }
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

                // Notify all active admin users about new submission
                $admins = User::where(['role' => USER_ROLE_ADMIN, 'status' => STATUS_ACTIVE])->get();
                foreach ($admins as $admin) {
                    setCommonNotification(
                        $admin->id,
                        __('New Career Corner Form Submission'),
                        __('A new career corner form has been submitted by ') . $user->first_name . ' ' . $user->last_name,
                        route('admin.career-corner-submissions.show', $submission->id)
                    );
                }
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

    /**
     * Process file uploads for career corner form
     */
    private function processFileUploads(Request $request, FormStructure $structure, $existingSubmission = null)
    {
        // Get all form data (excluding files - files are handled separately)
        $formData = $request->except(['_token']);
        
        // Get all questions used in this structure to identify file type questions
        $questionIds = $this->extractQuestionIdsFromStructure($structure->loadNestedStructure());
        
        // Also check for file uploads in the request that might not be in the structure
        // (e.g., from nested questions or previous form versions)
        $allFileFields = [];
        foreach ($request->allFiles() as $fieldName => $file) {
            if (strpos($fieldName, 'career_q_') === 0) {
                $allFileFields[] = $fieldName;
            }
        }
        
        $questions = Question::whereIn('id', $questionIds)
            ->where('type', 'file')
            ->get()
            ->keyBy('id');
        
        // Also get file questions that are in the request but might not be in the structure
        // Extract question IDs from file field names in the request
        $requestFileQuestionIds = [];
        foreach ($allFileFields as $fieldName) {
            $questionId = str_replace('career_q_', '', $fieldName);
            if (is_numeric($questionId)) {
                $requestFileQuestionIds[] = (int)$questionId;
            }
        }
        
        // Get all file questions (from structure + from request)
        $allFileQuestionIds = array_unique(array_merge($questionIds, $requestFileQuestionIds));
        $allFileQuestions = Question::whereIn('id', $allFileQuestionIds)
            ->where('type', 'file')
            ->get()
            ->keyBy('id');
        
        // Use all file questions for processing
        $questions = $allFileQuestions;
        
        // Remove file inputs from formData (they're UploadedFile objects, we'll process them separately)
        foreach ($questions as $questionId => $question) {
            $fieldName = 'career_q_' . $questionId;
            if (isset($formData[$fieldName]) && $formData[$fieldName] instanceof \Illuminate\Http\UploadedFile) {
                unset($formData[$fieldName]);
            }
        }

        // Get old file paths from existing submission if updating
        $oldFiles = [];
        if ($existingSubmission && $existingSubmission->form_data) {
            $oldFormData = $existingSubmission->form_data;
            foreach ($questions as $questionId => $question) {
                $fieldName = 'career_q_' . $questionId;
                if (isset($oldFormData[$fieldName]) && !empty($oldFormData[$fieldName])) {
                    $oldFiles[$fieldName] = $oldFormData[$fieldName];
                }
            }
        }

        // Ensure directory exists (do this once, not in loop)
        $directory = 'uploads/career-corner';
        $fullDirectoryPath = storage_path('app/public/' . $directory);
        if (!File::isDirectory($fullDirectoryPath)) {
            File::makeDirectory($fullDirectoryPath, 0755, true, true);
        }

        // Process each file upload
        // First, process files that are in the questions collection (from structure)
        // Also process any files in the request that might not be in the questions collection
        $processedFields = [];
        foreach ($questions as $questionId => $question) {
            $fieldName = 'career_q_' . $questionId;
            $processedFields[] = $fieldName;
            
            if ($request->hasFile($fieldName)) {
                $file = $request->file($fieldName);
                
                // Validate file
                if ($file && $file->isValid()) {
                    // Generate unique filename
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $fileName = time() . '_' . $questionId . '_' . uniqid() . '.' . $extension;
                    
                    // Store file in public storage
                    $path = $file->storeAs($directory, $fileName, 'public');
                    
                    if ($path) {
                        // Save file path in form data
                        $formData[$fieldName] = $path;
                        
                        // Delete old file if exists
                        if (isset($oldFiles[$fieldName]) && Storage::disk('public')->exists($oldFiles[$fieldName])) {
                            Storage::disk('public')->delete($oldFiles[$fieldName]);
                        }
                    } else {
                        // If file upload failed, keep old file or set to null
                        if (isset($oldFiles[$fieldName])) {
                            $formData[$fieldName] = $oldFiles[$fieldName];
                        }
                    }
                } else {
                    // If file is invalid, keep old file or set to null
                    if (isset($oldFiles[$fieldName])) {
                        $formData[$fieldName] = $oldFiles[$fieldName];
                    }
                }
            } else {
                // No new file uploaded - keep existing file if updating
                if ($existingSubmission && isset($oldFiles[$fieldName])) {
                    $formData[$fieldName] = $oldFiles[$fieldName];
                }
                // Don't unset - keep the field even if empty to preserve form structure
            }
        }
        
        // Process any file uploads that weren't in the questions collection
        // (e.g., from nested questions or questions not in current structure)
        foreach ($allFileFields as $fieldName) {
            if (!in_array($fieldName, $processedFields) && $request->hasFile($fieldName)) {
                $file = $request->file($fieldName);
                
                if ($file && $file->isValid()) {
                    // Extract question ID from field name
                    $questionId = str_replace('career_q_', '', $fieldName);
                    
                    // Generate unique filename
                    $extension = $file->getClientOriginalExtension();
                    $fileName = time() . '_' . $questionId . '_' . uniqid() . '.' . $extension;
                    
                    // Store file in public storage
                    $path = $file->storeAs($directory, $fileName, 'public');
                    
                    if ($path) {
                        $formData[$fieldName] = $path;
                        
                        // Delete old file if exists (for updates)
                        if ($existingSubmission && $existingSubmission->form_data) {
                            $oldFormData = $existingSubmission->form_data;
                            if (isset($oldFormData[$fieldName]) && Storage::disk('public')->exists($oldFormData[$fieldName])) {
                                Storage::disk('public')->delete($oldFormData[$fieldName]);
                            }
                        }
                    } else {
                        // If upload failed, preserve old file if present
                        if ($existingSubmission && $existingSubmission->form_data) {
                            $oldFormData = $existingSubmission->form_data;
                            if (isset($oldFormData[$fieldName])) {
                                $formData[$fieldName] = $oldFormData[$fieldName];
                            }
                        }
                    }
                } elseif ($existingSubmission && $existingSubmission->form_data) {
                    // Invalid file - keep old file if exists
                    $oldFormData = $existingSubmission->form_data;
                    if (isset($oldFormData[$fieldName])) {
                        $formData[$fieldName] = $oldFormData[$fieldName];
                    }
                }
            }
        }

        // FINAL STEP: Preserve any existing career-corner file fields that were not
        // touched in this request at all (including nested child file questions)
        if ($existingSubmission && $existingSubmission->form_data) {
            $oldFormData = $existingSubmission->form_data;
            foreach ($oldFormData as $fieldName => $value) {
                // Only consider career corner question fields that look like file paths
                if (
                    strpos($fieldName, 'career_q_') === 0 &&
                    !array_key_exists($fieldName, $formData) &&
                    is_string($value) &&
                    $value !== '' &&
                    strpos($value, 'uploads/career-corner/') === 0
                ) {
                    $formData[$fieldName] = $value;
                }
            }
        }

        return $formData;
    }

    /**
     * Get student context for AI chat
     * Returns student's career corner submission data formatted for AI
     */
    public function getStudentContext(Request $request)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'status' => false,
                'hasProfile' => false,
                'message' => __('User not authenticated')
            ]);
        }
        
        $structure = FormStructure::where('slug', 'career-corner')->first();
        
        if (!$structure) {
            return response()->json([
                'status' => false,
                'hasProfile' => false,
                'message' => __('Form structure not found')
            ]);
        }
        
        $submission = CareerCornerSubmission::where('user_id', $user->id)
            ->where('form_structure_id', $structure->id)
            ->orderBy('created_at', 'desc')
            ->first();
        
        if (!$submission) {
            return response()->json([
                'status' => true,
                'hasProfile' => false,
                'message' => __('No submission found')
            ]);
        }
        
        // Build student context from form_data
        $context = $this->buildStudentContext($submission);
        
        return response()->json([
            'status' => true,
            'hasProfile' => true,
            'studentContext' => $context,
            'submissionDate' => $submission->updated_at->toDateTimeString()
        ]);
    }

    /**
     * Build student context from submission
     */
    private function buildStudentContext($submission)
    {
        $formData = $submission->form_data;
        $snapshot = $submission->getFormStructureData();
        $questions = $snapshot['questions'] ?? [];
        
        // Collect question IDs that are in form_data but not in snapshot
        $missingQuestionIds = [];
        foreach ($formData as $fieldName => $value) {
            if (strpos($fieldName, 'career_q_') !== 0) continue;
            
            $questionId = str_replace('career_q_', '', $fieldName);
            $questionId = preg_replace('/\[\]$/', '', $questionId);
            
            if (!is_numeric($questionId)) continue;
            
            $questionId = (int)$questionId;
            
            // If question not in snapshot, add to missing list
            if (!isset($questions[$questionId])) {
                $missingQuestionIds[] = $questionId;
            }
        }
        
        // Load missing questions from database (child questions that might not be in snapshot)
        if (!empty($missingQuestionIds)) {
            $missingQuestions = Question::whereIn('id', $missingQuestionIds)
                ->get()
                ->keyBy('id')
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
                ->toArray();
            
            // Merge missing questions into questions array
            foreach ($missingQuestions as $id => $question) {
                $questions[$id] = $question;
            }
        }
        
        $context = [
            'rawAnswers' => [],
            'formattedAnswers' => [],
            'criteria' => []
        ];
        
        // Parse each answer
        foreach ($formData as $fieldName => $value) {
            if (strpos($fieldName, 'career_q_') !== 0) continue;
            
            $questionId = str_replace('career_q_', '', $fieldName);
            $questionId = preg_replace('/\[\]$/', '', $questionId);
            
            if (!is_numeric($questionId)) continue;
            
            $questionId = (int)$questionId;
            $question = $questions[$questionId] ?? null;
            
            if (!$question) continue;
            
            $questionText = $question['question'] ?? '';
            $questionKey = $question['key'] ?? '';
            
            // Format the answer based on type
            $formattedValue = $this->formatAnswerValue($value, $question);
            
            $context['rawAnswers'][$questionKey] = $value;
            $context['formattedAnswers'][] = [
                'question' => $questionText,
                'answer' => $formattedValue,
                'key' => $questionKey
            ];
        }
        
        // Extract key criteria
        $context['criteria'] = $this->extractKeyCriteria($formData, $questions);
        
        return $context;
    }

    /**
     * Format answer value based on question type
     */
    private function formatAnswerValue($value, $question)
    {
        if (is_array($value)) {
            return implode(', ', $value);
        }
        
        // For file type, return filename
        if (isset($question['type']) && $question['type'] === 'file' && !empty($value)) {
            return basename($value);
        }
        
        return $value;
    }

    /**
     * Extract key criteria from form data
     */
    private function extractKeyCriteria($formData, $questions)
    {
        $criteria = [
            'budget' => null,
            'preferredCountries' => [],
            'courseInterest' => null,
            'studyLevel' => null,
            'languageTests' => [],
            'academicBackground' => null,
            'intakePreference' => null
        ];
        
        foreach ($formData as $fieldName => $value) {
            if (strpos($fieldName, 'career_q_') !== 0) continue;
            
            $questionId = str_replace('career_q_', '', $fieldName);
            $questionId = preg_replace('/\[\]$/', '', $questionId);
            
            if (!is_numeric($questionId)) continue;
            
            $question = $questions[$questionId] ?? null;
            if (!$question) continue;
            
            $key = $question['key'] ?? '';
            $questionText = $question['question'] ?? '';
            
            // Map question keys to criteria (case-insensitive search)
            if (stripos($key, 'budget') !== false || stripos($questionText, 'budget') !== false) {
                $criteria['budget'] = is_array($value) ? implode(', ', $value) : $value;
            }
            
            // Handle country IDs - convert to names
            if (stripos($key, 'country') !== false || stripos($key, 'countries') !== false || 
                stripos($questionText, 'country') !== false || stripos($questionText, 'countries') !== false) {
                $countryIds = is_array($value) ? $value : [$value];
                $countryIds = array_filter($countryIds); // Remove empty values
                
                if (!empty($countryIds)) {
                    // Convert country IDs to names
                    $countries = \App\Models\Country::whereIn('id', $countryIds)
                        ->pluck('name')
                        ->toArray();
                    $criteria['preferredCountries'] = $countries;
                }
            }
            
            if (stripos($key, 'course') !== false || stripos($key, 'program') !== false || 
                stripos($key, 'field') !== false || stripos($key, 'major') !== false ||
                stripos($questionText, 'course') !== false || stripos($questionText, 'program') !== false) {
                $criteria['courseInterest'] = is_array($value) ? implode(', ', $value) : $value;
            }
            
            // Study level - STRICT detection to avoid false matches
            // Only match if question is specifically about education level/degree
            // Exclude: sponsor, financial, work-related questions
            $isEducationQuestion = (
                (stripos($key, 'degree') !== false || stripos($key, 'qualification') !== false) ||
                (stripos($questionText, 'highest degree') !== false || 
                 stripos($questionText, 'highest qualification') !== false ||
                 stripos($questionText, 'education level') !== false ||
                 stripos($questionText, 'completed degree') !== false ||
                 stripos($questionText, 'degree name') !== false ||
                 stripos($questionText, 'specialization') !== false ||
                 (stripos($questionText, 'graduation') !== false && stripos($questionText, 'year') === false))
            );
            
            // Exclude sponsor, financial, work questions
            $isExcluded = (
                stripos($questionText, 'sponsor') !== false ||
                stripos($questionText, 'financial') !== false ||
                stripos($questionText, 'work') !== false ||
                stripos($questionText, 'job') !== false ||
                stripos($questionText, 'employment') !== false
            );
            
            if ($isEducationQuestion && !$isExcluded && !empty($value)) {
                $studyLevelValue = is_array($value) ? implode(', ', $value) : $value;
                
                // Skip generic short answers like "UG", "PG" if we already have a detailed answer
                $isGenericAnswer = in_array(strtoupper(trim($studyLevelValue)), ['UG', 'PG', 'UNDERGRADUATE', 'POSTGRADUATE', 'GRADUATE']);
                
                // Only update if we don't have a value yet, OR if current value is generic and new one is detailed
                if (empty($criteria['studyLevel']) || 
                    ($isGenericAnswer === false && !empty($criteria['studyLevel']) && strlen($studyLevelValue) > strlen($criteria['studyLevel']))) {
                    
                    // Add context based on question text
                    if (stripos($questionText, 'completed') !== false || 
                        stripos($questionText, 'highest') !== false ||
                        stripos($questionText, 'current') !== false) {
                        $criteria['studyLevel'] = $studyLevelValue . ' (Completed)';
                    } elseif (stripos($questionText, 'pursuing') !== false || 
                              stripos($questionText, 'want to study') !== false ||
                              stripos($questionText, 'planning') !== false ||
                              stripos($questionText, 'target') !== false) {
                        $criteria['studyLevel'] = $studyLevelValue . ' (Target)';
                    } else {
                        $criteria['studyLevel'] = $studyLevelValue;
                    }
                }
            }
            
            // Language tests - detect test type and score
            if (stripos($key, 'ielts') !== false || stripos($key, 'toefl') !== false || 
                stripos($key, 'pte') !== false || stripos($key, 'language') !== false || 
                stripos($key, 'english') !== false || stripos($key, 'test') !== false ||
                stripos($questionText, 'ielts') !== false || stripos($questionText, 'toefl') !== false ||
                stripos($questionText, 'pte') !== false || stripos($questionText, 'test type') !== false ||
                stripos($questionText, 'english test') !== false || stripos($questionText, 'proficiency test') !== false ||
                stripos($questionText, 'overall score') !== false || stripos($questionText, 'test score') !== false) {
                
                $testValue = is_array($value) ? implode(', ', $value) : $value;
                if (!empty($testValue) && $testValue !== 'Yes' && $testValue !== 'YES' && $testValue !== 'NO' && $testValue !== 'No') {
                    // Check if this looks like a test name (IELTS, TOEFL, PTE)
                    $isTestName = (stripos($testValue, 'IELTS') !== false || 
                                   stripos($testValue, 'TOEFL') !== false || 
                                   stripos($testValue, 'PTE') !== false);
                    
                    // Check if this looks like a score (number or contains number)
                    $isScore = is_numeric($testValue) || preg_match('/\d/', $testValue);
                    
                    // If it's a test name, store it temporarily
                    if ($isTestName) {
                        $criteria['_tempTestName'] = $testValue;
                    }
                    // If it's a score, combine with test name if available
                    elseif ($isScore) {
                        if (isset($criteria['_tempTestName'])) {
                            $criteria['languageTests'][] = $criteria['_tempTestName'] . ' ' . $testValue;
                        } else {
                            $criteria['languageTests'][] = $testValue;
                        }
                    }
                    // Otherwise just add it
                    else {
                        $criteria['languageTests'][] = $testValue;
                    }
                }
            }
            
            if (stripos($key, 'cgpa') !== false || stripos($key, 'gpa') !== false || 
                stripos($key, 'percentage') !== false || stripos($key, 'marks') !== false ||
                stripos($questionText, 'cgpa') !== false || stripos($questionText, 'gpa') !== false) {
                $criteria['academicBackground'] = is_array($value) ? implode(', ', $value) : $value;
            }
            
            if (stripos($key, 'intake') !== false || stripos($key, 'semester') !== false || 
                stripos($key, 'session') !== false || stripos($questionText, 'intake') !== false) {
                $criteria['intakePreference'] = is_array($value) ? implode(', ', $value) : $value;
            }
        }
        
        // Clean up temporary variables
        unset($criteria['_tempTestName']);
        
        return $criteria;
    }
}
