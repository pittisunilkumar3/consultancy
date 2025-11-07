<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\StudyLevel;
use App\Models\University;
use App\Models\Subject;
use App\Models\SubjectCategory;
use App\Models\Scholarship;
use App\Models\Event;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    public function index(Request $request)
    {
        $data['pageTitle'] = __('Universities');
        $data['activeUniversities'] = 'active';
        $data['countryData'] = Country::where('status', STATUS_ACTIVE)->get();
        $data['studyLevels'] = StudyLevel::where('status', STATUS_ACTIVE)->get();
        $data['subjectCategories'] = SubjectCategory::where('status', STATUS_ACTIVE)->get();

        // Initialize all result sets as empty
        $data['universityData'] = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12, 1, ['path' => $request->url(), 'query' => $request->query()]);
        $data['subjectData'] = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12, 1, ['path' => $request->url(), 'query' => $request->query()]);
        $data['scholarshipData'] = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12, 1, ['path' => $request->url(), 'query' => $request->query()]);
        $data['eventData'] = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12, 1, ['path' => $request->url(), 'query' => $request->query()]);
        
        $data['showResults'] = false;
        $data['showSubjectResults'] = false;
        $data['showScholarshipResults'] = false;
        $data['showEventResults'] = false;

        // Determine which tab is being searched
        $searchTab = $request->get('tab', 'university');

        switch ($searchTab) {
            case 'subject':
                $this->handleSubjectSearch($request, $data);
                break;
            case 'scholarship':
                $this->handleScholarshipSearch($request, $data);
                break;
            case 'event':
                $this->handleEventSearch($request, $data);
                break;
            default:
                $this->handleUniversitySearch($request, $data);
                break;
        }

        if ($request->ajax()) {
            return view('student.universities.partials.university-list', $data)->render();
        }

        return view('student.universities.index', $data);
    }

    private function handleUniversitySearch($request, &$data)
    {
        $query = University::query()->where('status', STATUS_ACTIVE);
        $isSearching = $request->has('country') || $request->has('university') || $request->has('search_key');

        if ($isSearching) {
            if ($request->has('country') && !empty($request->country)) {
                $query->whereIn('country_id', $request->country);
            }

            if ($request->has('university') && !empty($request->university) && !empty($request->university[0])) {
                $query->where('id', $request->university[0]);
            }

            if ($request->has('search_key') && !empty($request->get('search_key'))) {
                $searchKey = $request->get('search_key');
                $query->where(function ($q) use ($searchKey) {
                    $q->where('name', 'like', "%{$searchKey}%")
                        ->orWhere('details', 'like', "%{$searchKey}%")
                        ->orWhere('address', 'like', "%{$searchKey}%");
                });
            }

            $data['universityData'] = $query->with('country')->paginate(12)->appends($request->query());
            $data['showResults'] = true;
        }
    }

    private function handleSubjectSearch($request, &$data)
    {
        $query = Subject::leftJoin('countries','subjects.country_id','=','countries.id')
            ->leftJoin('subject_categories','subjects.subject_category_id','=','subject_categories.id')
            ->leftJoin('universities','subjects.university_id','=','universities.id')
            ->leftJoin('study_levels','subjects.study_level_id','=','study_levels.id')
            ->select('subjects.*',
                'countries.name as countryName',
                'universities.name as universitiesName',
                'subject_categories.name as subjectCategoriesName',
                'study_levels.name as studyLevels')
            ->where('subjects.status', STATUS_ACTIVE);
        
        $isSearching = $request->has('country') || $request->has('university') || $request->has('subject_category') || $request->has('study_level');

        if ($isSearching) {
            if ($request->has('country') && !empty($request->country)) {
                $query->whereIn('subjects.country_id', $request->country);
            }

            if ($request->has('university') && !empty($request->university) && !empty($request->university[0])) {
                $query->whereIn('subjects.university_id', $request->university);
            }

            if ($request->has('subject_category') && !empty($request->subject_category) && !empty($request->subject_category[0])) {
                $query->whereIn('subjects.subject_category_id', $request->subject_category);
            }

            if ($request->has('study_level') && !empty($request->study_level) && !empty($request->study_level[0])) {
                $query->whereIn('subjects.study_level_id', $request->study_level);
            }

            $data['subjectData'] = $query->paginate(12)->appends($request->query());
            $data['showSubjectResults'] = true;
        }
    }

    private function handleScholarshipSearch($request, &$data)
    {
        $query = Scholarship::leftJoin('countries','scholarships.country_id','=','countries.id')
            ->leftJoin('universities','scholarships.university_id','=','universities.id')
            ->leftJoin('study_levels','scholarships.study_level_id','=','study_levels.id')
            ->select('scholarships.*',
                'countries.name as countryName',
                'universities.name as universitiesName',
                'study_levels.name as studyLevels')
            ->where('scholarships.status', STATUS_ACTIVE);
        
        $isSearching = $request->has('country') || $request->has('study_level');

        if ($isSearching) {
            if ($request->has('country') && !empty($request->country)) {
                $query->whereIn('scholarships.country_id', $request->country);
            }

            if ($request->has('study_level') && !empty($request->study_level) && !empty($request->study_level[0])) {
                $query->whereIn('scholarships.study_level_id', $request->study_level);
            }

            $data['scholarshipData'] = $query->paginate(12)->appends($request->query());
            $data['showScholarshipResults'] = true;
        }
    }

    private function handleEventSearch($request, &$data)
    {
        $query = Event::query()->where('status', STATUS_ACTIVE);
        $isSearching = $request->has('country') || $request->has('university') || $request->has('study_level');

        if ($isSearching) {
            if ($request->has('country') && !empty($request->country)) {
                $countryIds = array_map('strval', $request->country);
                $query->where(function($q) use ($countryIds) {
                    foreach ($countryIds as $countryId) {
                        $q->orWhereJsonContains('country_ids', $countryId);
                    }
                });
            }

            if ($request->has('university') && !empty($request->university) && !empty($request->university[0])) {
                $universityIds = array_map('strval', $request->university);
                $query->where(function($q) use ($universityIds) {
                    foreach ($universityIds as $universityId) {
                        $q->orWhereJsonContains('university_ids', $universityId);
                    }
                });
            }

            if ($request->has('study_level') && !empty($request->study_level) && !empty($request->study_level[0])) {
                $studyLevelIds = array_map('strval', $request->study_level);
                $query->where(function($q) use ($studyLevelIds) {
                    foreach ($studyLevelIds as $levelId) {
                        $q->orWhereJsonContains('study_levels', $levelId);
                    }
                });
            }

            $data['eventData'] = $query->paginate(12)->appends($request->query());
            $data['showEventResults'] = true;
        }
    }
}
