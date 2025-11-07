<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Scholarship;
use App\Models\StudyLevel;
use App\Models\Subject;
use App\Models\University;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    public function list(Request $request)
    {
        $query = Scholarship::leftJoin('countries','scholarships.country_id','=','countries.id')
            ->leftJoin('universities','scholarships.university_id','=','universities.id')
            ->leftJoin('study_levels','scholarships.study_level_id','=','study_levels.id')
            ->select('scholarships.*',
                'countries.name as countryName',
                'universities.name as universitiesName',
                'study_levels.name as studyLevels' )
            ->where('scholarships.status',STATUS_ACTIVE);

        if ($request->has('country') && !empty($request->country)) {
            $countryIds = $request->country;
            $query->whereIn('scholarships.country_id', $countryIds);

            $universities = University::whereIn('country_id', $countryIds)
                ->where('status', STATUS_ACTIVE)
                ->get();
        } else {
            $universities = University::where('status', STATUS_ACTIVE)->get();
        }

        if ($request->has('university') && !empty($request->university)) {
            $query->whereIn('scholarships.university_id', $request->university);
        }
        if ($request->has('subject') && !empty($request->subject)) {
            $query->whereIn('subject_id', $request->subject);
        }

        $subjectQuery = Subject::where('status', STATUS_ACTIVE);

        if ($request->has('country') && !empty($request->country)) {
            $subjectQuery->whereIn('country_id', $request->country);
        }

        if ($request->has('university') && !empty($request->university)) {
            $subjectQuery->whereIn('university_id', $request->university);
        }
        if ($request->has('study_level') && !empty($request->study_level)) {
            $subjectQuery->whereIn('study_level_id', $request->study_level);
        }

        $subjects = $subjectQuery->get();

        if ($request->has('study_level') && !empty($request->study_level[0])) {
            $query->whereIn('study_level_id', $request->study_level);
        }

        $studyLevelQuery = StudyLevel::where('status', STATUS_ACTIVE);

        $studyLevel = $studyLevelQuery->get();

        // Apply the search key filter if present in the request
        if ($request->has('search_key') && !empty($request->get('search_key'))) {
            $searchKey = $request->get('search_key');
            $query->where(function ($q) use ($searchKey) {
                $q->where('scholarships.title', 'like', "%{$searchKey}%")
                    ->orWhere('scholarships.details', 'like', "%{$searchKey}%");
            });
        }

        // Apply sorting if sort_by is present in the request
        if ($request->has('sort_by')) {
            switch ($request->get('sort_by')) {
                case 'name-asc':
                    $query->orderBy('scholarships.title', 'asc');
                    break;
                case 'name-desc':
                    $query->orderBy('scholarships.title', 'desc');
                    break;
                default:
                    // Default sorting if none selected (optional)
                    $query->orderBy('scholarships.title', 'asc');
                    break;
            }
        }

        $data['scholarshipData'] = $query->paginate(6)->appends($request->query());

        $data['countries'] = Country::where('status', STATUS_ACTIVE)->get();
        $data['universities'] = $universities;
        $data['subjects'] = $subjects;
        $data['studyLevel'] = $studyLevel;
        $data['pageTitle'] = __('Scholarship');
        $data['activeScholarshipMenu'] = 'active';
        $data['activeStudyAbroadMenu'] = 'active';
        $data['bodyClass'] = 'bg-white';

        return view('frontend.scholarship.list', $data);
    }

    public function details($slug){

        $data['activeScholarshipMenu'] = 'active';
        $data['activeStudyAbroadMenu'] = 'active';
        $data['bodyClass'] = 'bg-white';
        $data['scholarshipDetails'] = Scholarship::leftjoin('countries','scholarships.country_id','=','countries.id')
            ->leftjoin('universities','scholarships.university_id','=','universities.id')
            ->leftjoin('study_levels','scholarships.study_level_id','=','study_levels.id')
            ->select('scholarships.*',
                     'countries.name as countryName',
                     'universities.name as universityName',
                     'study_levels.name as studyLevelName',
            )->where('scholarships.slug', $slug)
            ->first();
        return view('frontend.scholarship.details',$data);
    }
}
