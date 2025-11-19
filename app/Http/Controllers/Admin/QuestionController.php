<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\UniversityCriteriaField;
use App\Models\QuestionCriteriaMapping;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

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
     * Show RAG Training file upload page.
     */
    public function ragTraining()
    {
        $showQuestions = 'show';
        $activeQuestion = 'active';
        $pageTitle = __('RAG Training');

        return view('admin.questions.rag-training', compact('showQuestions', 'activeQuestion', 'pageTitle'));
    }

    /**
     * Handle RAG Training file uploads.
     */
    public function ragTrainingUpload(Request $request)
    {
        $request->validate([
            'files' => 'required',
            'files.*' => 'file|max:5120', // 5MB per file
        ]);

        $uploadedFiles = [];

        if ($request->hasFile('files')) {
            $disk = Storage::disk('public');

            foreach ($request->file('files') as $file) {
                $path = $file->store('uploads/rag-training', 'public');

                $uploadedFiles[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'url' => $disk->url($path),
                    'size' => $disk->size($path),
                    'mime_type' => $disk->mimeType($path),
                ];
            }
        }

        return response()->json([
            'status' => true,
            'message' => __('Files uploaded successfully'),
            'files' => $uploadedFiles,
        ]);
    }

    public function ragTrainingFiles()
    {
        $directory = 'uploads/rag-training';
        $disk = Storage::disk('public');

        $files = [];

        if ($disk->exists($directory)) {
            foreach ($disk->files($directory) as $path) {
                $files[] = [
                    'original_name' => basename($path),
                    'path' => $path,
                    'url' => $disk->url($path),
                    'size' => $disk->size($path),
                    'mime_type' => $disk->mimeType($path),
                ];
            }
        }

        return response()->json([
            'status' => true,
            'files' => $files,
        ]);
    }

    public function ragTrainingDownload(Request $request)
    {
        $path = $request->query('path');

        if (!$path) {
            abort(404);
        }

        $disk = Storage::disk('public');

        if (!$disk->exists($path)) {
            abort(404);
        }

        $fileName = basename($path);

        if ($request->boolean('download')) {
            return $disk->download($path, $fileName);
        }

        $absolutePath = $disk->path($path);
        $mimeType = $disk->mimeType($path) ?: 'application/octet-stream';

        return response()->file($absolutePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        ]);
    }

    public function ragTrainingDownloadMultiple(Request $request)
    {
        $paths = $request->input('paths', []);

        if (!is_array($paths) || empty($paths)) {
            abort(400, 'No files selected');
        }

        $disk = Storage::disk('public');

        $zipDirectory = storage_path('app/temp');
        if (!is_dir($zipDirectory)) {
            mkdir($zipDirectory, 0775, true);
        }

        $zipFileName = 'rag-training-files-' . date('Ymd-His') . '.zip';
        $zipPath = $zipDirectory . DIRECTORY_SEPARATOR . $zipFileName;

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            abort(500, 'Unable to create ZIP archive');
        }

        foreach ($paths as $path) {
            if (!is_string($path) || !$disk->exists($path)) {
                continue;
            }

            $absolutePath = $disk->path($path);
            $zip->addFile($absolutePath, basename($path));
        }

        $zip->close();

        if (!file_exists($zipPath)) {
            abort(500, 'ZIP archive not found');
        }

        return response()->download($zipPath, $zipFileName, [
            'Content-Type' => 'application/zip',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Store a newly created question.
     */
    public function store(Request $request)
    {
        $rules = [
            'question' => 'required|string|max:255',
            'type' => 'required|string|in:text,textarea,number,email,file,select,radio,checkbox',
            'order' => 'nullable|integer|min:0',
            'required' => 'nullable|boolean',
            'placeholder' => 'nullable|string|max:255',
            'step' => 'nullable|string|max:50'
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
            'is_country_question' => $isCountryQuestion,
            'placeholder' => $request->placeholder,
            'step' => $request->step
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
            'type' => 'required|string|in:text,textarea,number,email,file,select,radio,checkbox',
            'order' => 'nullable|integer|min:0',
            'required' => 'nullable|boolean',
            'placeholder' => 'nullable|string|max:255',
            'step' => 'nullable|string|max:50'
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
            'is_country_question' => $isCountryQuestion,
            'placeholder' => $request->placeholder,
            'step' => $request->step
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
