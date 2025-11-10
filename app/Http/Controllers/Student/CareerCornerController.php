<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\FormStructure;
use App\Models\Question;
use App\Models\StudyLevel;
use Illuminate\Http\Request;

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
        
        if ($structure && $structure->is_published) {
            $data['formStructure'] = $structure;
            $data['formData'] = $structure->loadNestedStructure();
            // Load all questions for form rendering
            $data['questions'] = Question::orderBy('order')->get()->keyBy('id');
        }

        return view('student.career-corner.index', $data);
    }
}
