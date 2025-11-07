<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Country;
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

        return view('student.career-corner.index', $data);
    }
}
