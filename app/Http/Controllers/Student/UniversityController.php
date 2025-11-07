<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\StudyLevel;
use App\Models\University;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    public function index(Request $request)
    {
        $data['pageTitle'] = __('Universities');
        $data['activeUniversities'] = 'active';
        $data['countryData'] = Country::where('status', STATUS_ACTIVE)->get();
        $data['studyLevels'] = StudyLevel::where('status', STATUS_ACTIVE)->get();

        // Initialize query
        $query = University::query()->where('status', STATUS_ACTIVE);

        // Check if search is triggered
        $isSearching = $request->has('country') || $request->has('university') || $request->has('search_key');

        if ($isSearching) {
            // Filter by country
            if ($request->has('country') && !empty($request->country)) {
                $query->whereIn('country_id', $request->country);
            }

            // Filter by university
            if ($request->has('university') && !empty($request->university) && !empty($request->university[0])) {
                $query->where('id', $request->university[0]);
            }

            // Search by keyword
            if ($request->has('search_key') && !empty($request->get('search_key'))) {
                $searchKey = $request->get('search_key');
                $query->where(function ($q) use ($searchKey) {
                    $q->where('name', 'like', "%{$searchKey}%")
                        ->orWhere('details', 'like', "%{$searchKey}%")
                        ->orWhere('address', 'like', "%{$searchKey}%");
                });
            }

            // Get results with pagination
            $data['universityData'] = $query->with('country')->paginate(12)->appends($request->query());
            $data['showResults'] = true;
        } else {
            // Create empty paginated result for consistency
            $data['universityData'] = new \Illuminate\Pagination\LengthAwarePaginator(
                [],
                0,
                12,
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
            $data['showResults'] = false;
        }

        if ($request->ajax()) {
            return view('student.universities.partials.university-list', $data)->render();
        }

        return view('student.universities.index', $data);
    }
}
