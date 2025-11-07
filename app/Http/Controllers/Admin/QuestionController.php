<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of questions.
     */
    public function index()
    {
        $questions = Question::orderBy('order')->get();

        // set sidebar active variables and page title
        $showQuestions = 'show';
        $activeQuestion = 'active';
        $pageTitle = __('Questions');

        return view('admin.questions.index', compact('questions', 'showQuestions', 'activeQuestion', 'pageTitle'));
    }

    /**
     * Store a newly created question.
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'type' => 'required|string|in:text,textarea,number,file,select,radio,checkbox',
            'order' => 'nullable|integer|min:0',
            'required' => 'nullable|boolean'
        ]);

        $question = Question::create([
            'question' => $request->question,
            'type' => $request->type,
            'order' => $request->order ?? 0,
            'required' => $request->required ? true : false
        ]);

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
        $question = Question::findOrFail($id);
        return response()->json([
            'status' => true,
            'data' => $question
        ]);
    }

    /**
     * Update the specified question.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'type' => 'required|string|in:text,textarea,number,file,select,radio,checkbox',
            'order' => 'nullable|integer|min:0',
            'required' => 'nullable|boolean'
        ]);

        $question = Question::findOrFail($id);
        $question->update([
            'question' => $request->question,
            'type' => $request->type,
            'order' => $request->order ?? 0,
            'required' => $request->required ? true : false
        ]);

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
}
