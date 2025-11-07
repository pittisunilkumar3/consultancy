<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\StudyLevel;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    public function index(Request $request)
    {
        $data['pageTitle'] = __('Universities');
        $data['activeUniversities'] = 'active';
        $data['countryData'] = Country::where('status', STATUS_ACTIVE)->get();
        $data['studyLevels'] = StudyLevel::where('status', STATUS_ACTIVE)->get();

        return view('student.universities.index', $data);
    }
}
