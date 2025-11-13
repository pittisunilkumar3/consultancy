<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CareerCornerSubmission;
use Illuminate\Http\Request;

class CareerCornerSubmissionController extends Controller
{
    /**
     * Display a listing of career corner submissions.
     */
    public function index(Request $request)
    {
        // server-side datatable response
        if ($request->ajax()) {
            $data = CareerCornerSubmission::with(['user', 'formStructure', 'reviewer'])
                ->orderBy('updated_at', 'desc');

            return datatables()->of($data)
                ->addIndexColumn()
                ->editColumn('user.name', function ($row) {
                    return $row->user ? $row->user->name : __('N/A');
                })
                ->editColumn('user.email', function ($row) {
                    return $row->user ? $row->user->email : __('N/A');
                })
                ->editColumn('formStructure.name', function ($row) {
                    return $row->formStructure ? $row->formStructure->name : __('N/A');
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == STATUS_ACTIVE) {
                        return '<div class="zBadge zBadge-complete">' . __('Active') . '</div>';
                    } else {
                        return '<div class="zBadge zBadge-deactive">' . __('Inactive') . '</div>';
                    }
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('M d, Y h:i A') : __('N/A');
                })
                ->editColumn('updated_at', function ($row) {
                    return $row->updated_at ? $row->updated_at->format('M d, Y h:i A') : __('N/A');
                })
                ->addColumn('action', function ($row) {
                    $view = '<a href="' . route('admin.career-corner-submissions.show', $row->id) . '" class="sf-btn-primary-xs" title="' . __('View') . '"><i class="fa-solid fa-eye"></i></a>';
                    return '<div class="d-flex g-12">' . $view . '</div>';
                })
                ->filterColumn('user.name', function ($query, $keyword) {
                    $query->whereHas('user', function ($q) use ($keyword) {
                        $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$keyword}%"]);
                    });
                })
                ->filterColumn('user.email', function ($query, $keyword) {
                    $query->whereHas('user', function ($q) use ($keyword) {
                        $q->where('email', 'LIKE', "%{$keyword}%");
                    });
                })
                ->filterColumn('formStructure.name', function ($query, $keyword) {
                    $query->whereHas('formStructure', function ($q) use ($keyword) {
                        $q->where('name', 'LIKE', "%{$keyword}%");
                    });
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        // set sidebar active variables and page title for normal view
        $data['pageTitle'] = __('Career Corner Submissions');
        $data['activeCareerCornerSubmissions'] = 'active';

        return view('admin.career-corner-submissions.index', $data);
    }

    /**
     * Display the specified submission.
     */
    public function show($id)
    {
        $submission = CareerCornerSubmission::with(['user', 'formStructure', 'reviewer'])
            ->findOrFail($id);

        // Get previous and next submissions (ordered by updated_at desc to match index page datatable)
        // In a desc-ordered list: Previous = newer item (higher in list), Next = older item (lower in list)
        $currentSubmission = $submission;

        // Previous = newer submission (higher updated_at, or same updated_at with higher id)
        $previousSubmission = CareerCornerSubmission::where('updated_at', '>', $currentSubmission->updated_at)
            ->orWhere(function($query) use ($currentSubmission) {
                $query->where('updated_at', '=', $currentSubmission->updated_at)
                      ->where('id', '>', $currentSubmission->id);
            })
            ->orderBy('updated_at', 'asc')  // Get the closest newer one
            ->orderBy('id', 'asc')
            ->first();

        // Next = older submission (lower updated_at, or same updated_at with lower id)
        $nextSubmission = CareerCornerSubmission::where('updated_at', '<', $currentSubmission->updated_at)
            ->orWhere(function($query) use ($currentSubmission) {
                $query->where('updated_at', '=', $currentSubmission->updated_at)
                      ->where('id', '<', $currentSubmission->id);
            })
            ->orderBy('updated_at', 'desc')  // Get the closest older one
            ->orderBy('id', 'desc')
            ->first();

        // Load the form structure data - use snapshot if available, otherwise current structure
        // Match exactly how student view handles it
        $formData = null;
        $questions = [];
        $structureChanged = false;

        // Try to use snapshot first (preserves original form structure)
        $snapshotData = $submission->getFormStructureData();

        if ($snapshotData && isset($snapshotData['structure']) && isset($snapshotData['questions'])) {
            // Use snapshot data
            $formData = $snapshotData['structure'];

            // Convert snapshot questions exactly like student view does
            $snapshotQuestionsArray = $snapshotData['questions'];

            // Always rekey by question ID to handle both old (numeric keys) and new (ID keys) formats
            if (is_array($snapshotQuestionsArray) && !empty($snapshotQuestionsArray)) {
                $firstKey = array_key_first($snapshotQuestionsArray);
                $firstValue = $snapshotQuestionsArray[$firstKey] ?? null;

                if (is_numeric($firstKey) && is_array($firstValue) && isset($firstValue['id'])) {
                    $snapshotQuestions = collect($snapshotQuestionsArray)->keyBy('id');
                } elseif (is_numeric($firstKey) && !isset($firstValue['id'])) {
                    $snapshotQuestions = collect($snapshotQuestionsArray);
                } else {
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
                        $snapshotQuestions = collect($snapshotQuestionsArray);
                    } else {
                        $snapshotQuestions = collect($snapshotQuestionsArray)->keyBy('id');
                    }
                }
            } else {
                $snapshotQuestions = collect();
            }

            // Extract all question IDs from the snapshot structure to ensure we have all questions
            $structureQuestionIds = $this->extractQuestionIdsFromStructure($snapshotData['structure']);
            $structureQuestionIds = array_values(array_unique($structureQuestionIds));

            // Get question IDs from snapshot
            $snapshotQuestionIds = $snapshotQuestions->keys()->filter(function($key) {
                return is_numeric($key) && $key > 0;
            })->toArray();

            if (empty($snapshotQuestionIds) || count($snapshotQuestionIds) !== $snapshotQuestions->count()) {
                $snapshotQuestionIds = $snapshotQuestions->map(function($question) {
                    return is_array($question) ? ($question['id'] ?? null) : ($question->id ?? null);
                })->filter()->unique()->values()->toArray();
            }

            $missingQuestionIds = array_diff($structureQuestionIds, $snapshotQuestionIds);

            if (!empty($missingQuestionIds)) {
                $missingQuestions = \App\Models\Question::whereIn('id', $missingQuestionIds)
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
                    ->keyBy('id');

                $snapshotQuestions = $snapshotQuestions->union($missingQuestions);
            }

            // Convert to array keyed by question ID
            $questionsArray = [];
            foreach ($snapshotQuestions as $key => $question) {
                $questionId = is_array($question) ? ($question['id'] ?? $key) : ($question->id ?? $key);
                if ($questionId && is_numeric($questionId)) {
                    $questionsArray[$questionId] = is_array($question) ? $question : (array)$question;
                }
            }
            $questions = $questionsArray;

            $structureChanged = $submission->hasStructureChanged();
        } elseif ($submission->formStructure) {
            // Fallback to current structure if no snapshot
            $formData = $submission->formStructure->loadNestedStructure();
            $questionsCollection = \App\Models\Question::orderBy('order')->get()->keyBy('id');
            $questionsArray = [];
            foreach ($questionsCollection as $id => $question) {
                $questionsArray[$id] = $question->toArray();
            }
            $questions = $questionsArray;
        }

        // Ensure submittedData is always an array
        $formDataFromSubmission = $submission->form_data;
        $submittedData = is_array($formDataFromSubmission) && !empty($formDataFromSubmission) ? $formDataFromSubmission : [];

        $data['pageTitle'] = __('View Submission');
        $data['activeCareerCornerSubmissions'] = 'active';
        $data['submission'] = $submission;
        $data['formData'] = $formData;
        $data['questions'] = $questions;
        $data['submittedData'] = $submittedData;
        $data['previousSubmission'] = $previousSubmission;
        $data['nextSubmission'] = $nextSubmission;
        $data['structureChanged'] = $structureChanged;

        return view('admin.career-corner-submissions.show', $data);
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
