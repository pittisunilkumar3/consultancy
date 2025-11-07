<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Faq;
use App\Models\Subject;
use App\Models\University;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    public function list(Request $request)
    {
        $query = University::query();

        if ($request->has('country') && !empty($request->country)) {
            $query->whereIn('country_id', $request->country);
        }

        if ($request->has('university') && !empty($request->university[0])) {
            $query->where('id', $request->university[0]);
        }


        // Apply the search key filter if present in the request
        if ($request->has('search_key') && !empty($request->get('search_key'))) {
            $searchKey = $request->get('search_key');
            $query->where(function ($q) use ($searchKey) {
                $q->where('name', 'like', "%{$searchKey}%")
                    ->orWhere('details', 'like', "%{$searchKey}%"); // Adjust fields based on what you want to search
            });
        }

        // Apply sorting if sort_by is present in the request
        if ($request->has('sort_by')) {
            switch ($request->get('sort_by')) {
                case 'name-asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name-desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'ranking-asc':
                    $query->orderBy('world_ranking', 'asc');
                    break;
                case 'ranking-desc':
                    $query->orderBy('world_ranking', 'desc');
                    break;
                default:
                    // Default sorting if none selected (optional)
                    $query->orderBy('name', 'asc');
                    break;
            }
        }

        // Paginate the filtered/sorted results
        $data['universityData'] = $query->paginate(6)->appends($request->query());

        // Other data
        $data['countries'] = Country::where('status', STATUS_ACTIVE)->get();
        $data['pageTitle'] = __('Universities');
        $data['activeUniversityMenu'] = 'active';
        $data['activeStudyAbroadMenu'] = 'active';
        $data['bodyClass'] = 'bg-white';

        // Return the view with data
        return view('frontend.universities.list', $data);
    }

    public function details($slug){

        $data['activeUniversityMenu'] = 'active';
        $data['activeStudyAbroadMenu'] = 'active';
        $data['bodyClass'] = 'bg-white';
        $data['universityData'] = University::leftjoin('countries','universities.country_id','=','countries.id')
            ->select('universities.*','countries.name as countryName')
            ->where('universities.slug',$slug)->first();
        $data['faqData'] = Faq::where('status',STATUS_ACTIVE)->get();
        $data['subjectData'] = Subject::leftjoin('universities','subjects.university_id','=','universities.id')
             ->leftjoin('countries','subjects.country_id','=','countries.id')
             ->leftjoin('study_levels','subjects.study_level_id','=','study_levels.id')
             ->leftjoin('subject_categories','subjects.subject_category_id','=','subject_categories.id')
             ->select('subjects.name as subjectName',
                      'subjects.slug as subjectSlug',
                      'subjects.banner_image',
                      'subjects.intake_time as subjectIntakeTime',
                      'countries.name as countryName',
                      'study_levels.name as studyLevelName',
                      'subject_categories.name as subjectCategoryName')
             ->where('universities.slug', $slug)
             ->get();
        return view('frontend.universities.details', $data);
    }
}
