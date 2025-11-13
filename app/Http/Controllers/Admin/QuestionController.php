<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\UniversityCriteriaField;
use App\Models\QuestionCriteriaMapping;
use App\Models\Country;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of questions.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        // server-side datatable response
        if ($request->ajax()) {
            $data = Question::orderBy('id', 'DESC');
            return datatables()->of($data)
                ->addIndexColumn()
                ->editColumn('required', function ($row) {
                    return $row->required ? '<div class="zBadge">' . __('Yes') . '</div>' : '<div class="zBadge zBadge--danger">' . __('No') . '</div>';
                })
                ->addColumn('action', function ($row) {
                    $edit = '<a href="#" class="sf-btn-primary-xs edit-btn" data-id="' . $row->id . '"><i class="fa-solid fa-pen-to-square"></i></a>';
                    $delete = '<a href="#" class="sf-btn-danger-xs delete-btn" data-question="' . $row->id . '"><i class="fa-solid fa-trash-can"></i></a>';
                    return '<div class="d-flex g-12">' . $edit . $delete . '</div>';
                })
                ->rawColumns(['required', 'action'])
                ->make(true);
        }

        // set sidebar active variables and page title for normal view
        $questions = Question::orderBy('id', 'DESC')->get();
        $criteriaFields = UniversityCriteriaField::where('status', STATUS_ACTIVE)->orderBy('order')->get();
        $showQuestions = 'show';
        $activeQuestion = 'active';
        $pageTitle = __('Questions');

        return view('admin.questions.index', compact('questions', 'criteriaFields', 'showQuestions', 'activeQuestion', 'pageTitle'));
    }

    /**
     * Store a newly created question.
     */
    public function store(Request $request)
    {
        $rules = [
            'question' => 'required|string|max:255',
            'type' => 'required|string|in:text,textarea,number,file,select,radio,checkbox',
            'order' => 'nullable|integer|min:0',
            'required' => 'nullable|boolean'
        ];

        // Require options for select/radio/checkbox types
        if (in_array($request->type, ['select', 'radio', 'checkbox'])) {
            $rules['options'] = 'required|json';
        }

        $request->validate($rules);

        // Decode options JSON if present
        $options = null;
        if ($request->filled('options')) {
            $options = json_decode($request->options, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid options format'
                ], 422);
            }
        }

        // Check if "Use Countries from Database" checkbox was checked
        $isCountryQuestion = $request->has('use_countries') && $request->input('use_countries') == '1';

        $question = Question::create([
            'question' => $request->question,
            'type' => $request->type,
            'order' => $request->order ?? 0,
            'required' => $request->required ? true : false,
            'options' => $options,
            'is_country_question' => $isCountryQuestion
        ]);

        // Save criteria field mappings
        // Note: With FormData, unchecked checkboxes won't be sent, so we check if the field exists
        $criteriaFields = $request->input('criteria_fields', []);
        if (!is_array($criteriaFields)) {
            $criteriaFields = [];
        }

        foreach ($criteriaFields as $criteriaFieldId) {
            if (!empty($criteriaFieldId)) {
                QuestionCriteriaMapping::create([
                    'question_id' => $question->id,
                    'criteria_field_id' => $criteriaFieldId
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => __('Question created successfully'),
            'data' => $question
        ]);
    }

    /**
     * Display the specified question.
     */
    public function show($id)
    {
        $question = Question::with('criteriaFields')->findOrFail($id);
        $mappedCriteriaIds = $question->criteriaFields->pluck('id')->toArray();

        $data = $question->toArray();
        $data['mapped_criteria_fields'] = $mappedCriteriaIds;

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    /**
     * Update the specified question.
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'question' => 'required|string|max:255',
            'type' => 'required|string|in:text,textarea,number,file,select,radio,checkbox',
            'order' => 'nullable|integer|min:0',
            'required' => 'nullable|boolean'
        ];

        // Require options for select/radio/checkbox types
        if (in_array($request->type, ['select', 'radio', 'checkbox'])) {
            $rules['options'] = 'required|json';
        }

        $request->validate($rules);

        // Decode options JSON if present
        $options = null;
        if ($request->filled('options')) {
            $options = json_decode($request->options, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid options format'
                ], 422);
            }
        }

        // Check if "Use Countries from Database" checkbox was checked
        $isCountryQuestion = $request->has('use_countries') && $request->input('use_countries') == '1';

        $question = Question::findOrFail($id);
        $question->update([
            'question' => $request->question,
            'type' => $request->type,
            'order' => $request->order ?? 0,
            'required' => $request->required ? true : false,
            'options' => $options,
            'is_country_question' => $isCountryQuestion
        ]);

        // Update criteria field mappings
        // First, delete all existing mappings
        QuestionCriteriaMapping::where('question_id', $question->id)->delete();

        // Then, create new mappings
        // Note: With FormData, unchecked checkboxes won't be sent, so we check if the field exists
        $criteriaFields = $request->input('criteria_fields', []);
        if (!is_array($criteriaFields)) {
            $criteriaFields = [];
        }

        foreach ($criteriaFields as $criteriaFieldId) {
            if (!empty($criteriaFieldId)) {
                QuestionCriteriaMapping::create([
                    'question_id' => $question->id,
                    'criteria_field_id' => $criteriaFieldId
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => __('Question updated successfully'),
            'data' => $question
        ]);
    }

    /**
     * Remove the specified question.
     */
    public function delete($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return response()->json([
            'status' => true,
            'message' => __('Question deleted successfully')
        ]);
    }

    /**
     * Fetch countries for use in question options.
     */
    public function getCountries()
    {
        $countries = Country::where('status', STATUS_ACTIVE)
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);

        return response()->json([
            'status' => true,
            'data' => $countries
        ]);
    }
}
