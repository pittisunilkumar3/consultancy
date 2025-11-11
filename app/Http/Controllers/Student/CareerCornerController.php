<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
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
            $data['formData'] = $structure->loadNestedStructure();
            // Load all questions for form rendering
            $data['questions'] = Question::orderBy('order')->get()->keyBy('id');
            
            // Check if user has already submitted the form
            $user = auth()->user();
            if ($user) {
                $submission = CareerCornerSubmission::where('user_id', $user->id)
                    ->where('form_structure_id', $structure->id)
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                if ($submission) {
                    $data['submission'] = $submission;
                    $data['submittedData'] = $submission->form_data;
                }
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

            // Check if user already has a submission for this form
            $existingSubmission = CareerCornerSubmission::where('user_id', $user->id)
                ->where('form_structure_id', $structure->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($existingSubmission) {
                // Update existing submission
                $existingSubmission->update([
                    'form_data' => $formData,
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
}
