<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Subject;
use App\Models\StudyLevel;
use App\Models\SubjectCategory;
use App\Models\University;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function list(Request $request)
    {
        $query = Subject::leftJoin('countries','subjects.country_id','=','countries.id')
            ->leftJoin('subject_categories','subjects.subject_category_id','=','subject_categories.id')
            ->leftJoin('universities','subjects.university_id','=','universities.id')
            ->leftJoin('study_levels','subjects.study_level_id','=','study_levels.id')
            ->select('subjects.*',
                'countries.name as countryName',
                'universities.name as universitiesName',
                'subject_categories.name as subjectCategoriesName',
                'study_levels.name as studyLevels' )
            ->where('subjects.status',STATUS_ACTIVE);

        if ($request->has('country') && !empty($request->country)) {
            $countryIds = $request->country;
            $query->whereIn('subjects.country_id', $countryIds);

            $universities = University::whereIn('country_id', $countryIds)
                ->where('status', STATUS_ACTIVE)
                ->get();
        } else {
            $universities = University::where('status', STATUS_ACTIVE)->get();
        }

        if ($request->has('university') && !empty($request->university[0])) {
            $query->whereIn('subjects.university_id', $request->university);
        }

        if ($request->has('subject_category') && !empty($request->subject_category)) {
            $query->whereIn('subjects.subject_category_id', $request->subject_category);
        }

        if ($request->has('study_level') && !empty($request->study_level[0])) {
            $query->whereIn('study_level_id', $request->study_level);
        }

        $studyLevelQuery = StudyLevel::where('status', STATUS_ACTIVE);

        $studyLevel = $studyLevelQuery->get();

        if ($request->has('search_key') && !empty($request->get('search_key'))) {
            $searchKey = $request->get('search_key');
            $query->where(function ($q) use ($searchKey) {
                $q->where('subjects.name', 'like', "%{$searchKey}%")
                    ->orWhere('subjects.details', 'like', "%{$searchKey}%");
            });
        }

        if ($request->has('sort_by')) {
            switch ($request->get('sort_by')) {
                case 'name-asc':
                    $query->orderBy('subjects.name', 'asc');
                    break;
                case 'name-desc':
                    $query->orderBy('subjects.name', 'desc');
                    break;
                default:
                    $query->orderBy('subjects.name', 'asc');
                    break;
            }
        }

        $data['subjectData'] = $query->paginate(6)->appends($request->query());

        $data['countries'] = Country::where('status', STATUS_ACTIVE)->get();
        $data['subjectCategory'] = SubjectCategory::where('status', STATUS_ACTIVE)->get();
        $data['universities'] = $universities;
        $data['studyLevel'] = $studyLevel;
        $data['pageTitle'] = __('Subject');
        $data['activeSubjectMenu'] = 'active';
        $data['activeStudyAbroadMenu'] = 'active';
        $data['bodyClass'] = 'bg-white';

        return view('frontend.subjects.list', $data);
    }

    public function details($slug){

        $data['activeSubjectMenu'] = 'active';
        $data['activeStudyAbroadMenu'] = 'active';
        $data['bodyClass'] = 'bg-white';
        $data['subjectDetails'] = Subject::leftjoin('countries','subjects.country_id','=','countries.id')
            ->leftjoin('universities','subjects.university_id','=','universities.id')
            ->leftjoin('study_levels','subjects.study_level_id','=','study_levels.id')
            ->select('subjects.*',
                     'countries.name as countryName',
                     'universities.name as universityName',
                     'study_levels.name as studyLevelName',
            )->where('subjects.slug', $slug)
            ->first();

        $data['exploreSubjects'] = Subject::leftJoin('countries', 'subjects.country_id', '=', 'countries.id')
            ->leftJoin('subject_categories', 'subjects.subject_category_id', '=', 'subject_categories.id')
            ->leftJoin('universities', 'subjects.university_id', '=', 'universities.id')
            ->leftJoin('study_levels', 'subjects.study_level_id', '=', 'study_levels.id')
            ->where('subjects.id', '!=', $data['subjectDetails']->id)
            ->select(
                'subjects.name as subjectName',
                'subjects.intake_time as subjectIntakeTime',
                'subjects.slug as subjectSlug',
                'subjects.banner_image as subjectBannerImage',
                'countries.name as countryName',
                'universities.name as universityName',
                'subject_categories.name as subjectCategoryName',
                'study_levels.name as studyLevelName'
            )
            ->where('subjects.subject_category_id', $data['subjectDetails']->subject_category_id)
            ->get();

        return view('frontend.subjects.details',$data);
    }
}
