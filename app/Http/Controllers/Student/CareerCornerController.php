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
                    $data['submittedData'] = $submission->form_data;
                    
                    // Use snapshot if available, otherwise use current structure
                    $snapshotData = $submission->getFormStructureData();
                    
                    if ($snapshotData && isset($snapshotData['structure']) && isset($snapshotData['questions'])) {
                        // Use snapshot data for displaying submitted form
                        $data['formData'] = $snapshotData['structure'];
                        $data['questions'] = collect($snapshotData['questions'])->keyBy('id');
                        
                        // Check if structure has changed
                        $data['structureChanged'] = $submission->hasStructureChanged();
                    } else {
                        // Fallback to current structure
                        $data['formData'] = $structure->loadNestedStructure();
                        $data['questions'] = Question::orderBy('order')->get()->keyBy('id');
                    }
                    
                    // Get matching universities based on submission
                    $filterService = new UniversityFilterService();
                    $data['matchingUniversities'] = $filterService->filterBySubmission($submission);
                } else {
                    // No submission yet - use current structure
                    $data['formData'] = $structure->loadNestedStructure();
                    $data['questions'] = Question::orderBy('order')->get()->keyBy('id');
                }
            } else {
                // No user logged in - use current structure
                $data['formData'] = $structure->loadNestedStructure();
                $data['questions'] = Question::orderBy('order')->get()->keyBy('id');
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
            $universities = $filterService->filterBySubmission($submission);

            return response()->json([
                'status' => true,
                'message' => __('Matching universities retrieved successfully'),
                'data' => $universities->load('country')
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

        return [
            'structure' => $formData,
            'questions' => $questions,
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
}
