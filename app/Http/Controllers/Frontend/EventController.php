<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Event;
use App\Models\StudyLevel;
use App\Models\University;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function list(Request $request)
    {
        $query = Event::where('status',STATUS_ACTIVE);

        if ($request->has('country') && !empty($request->country[0])) {
            $countryIds = array_map('strval', $request->country);

            $query->where(function ($q) use ($countryIds) {
                foreach ($countryIds as $id) {
                    $q->orWhereJsonContains('country_ids', $id);
                }
            });

            $universities = University::whereIn('country_id', $request->country)
                ->where('status', STATUS_ACTIVE)
                ->get();
        } else {
            $universities = University::where('status', STATUS_ACTIVE)->get();
        }


        if ($request->has('university') && !empty($request->university[0])) {

            $universityIds = array_map('strval', $request->university);
            $query->where(function ($q) use ($universityIds) {
                foreach ($universityIds as $id) {
                    $q->orWhereJsonContains('university_ids', $id);
                }
            });
        }

        if ($request->has('study_level') && !empty($request->study_level[0])) {

            $studyLevelIds = array_map('strval', $request->study_level);
            $query->where(function ($q) use ($studyLevelIds) {
                foreach ($studyLevelIds as $id) {
                    $q->orWhereJsonContains('study_levels', $id);
                }
            });
        }

        $studyLevelQuery = StudyLevel::where('status', STATUS_ACTIVE);

        $studyLevel = $studyLevelQuery->get();

        if ($request->has('search_key') && !empty($request->get('search_key'))) {
            $searchKey = $request->get('search_key');
            $query->where(function ($q) use ($searchKey) {
                $q->where('events.title', 'like', "%{$searchKey}%")
                    ->orWhere('events.description', 'like', "%{$searchKey}%");
            });
        }

        if ($request->has('sort_by')) {
            switch ($request->get('sort_by')) {
                case 'title-asc':
                    $query->orderBy('events.title', 'asc');
                    break;
                case 'title-desc':
                    $query->orderBy('events.title', 'desc');
                    break;
                default:
                    $query->orderBy('events.title', 'asc');
                    break;
            }
        }

        $data['eventData'] = $query->paginate(6)->appends($request->query());

        $data['countries'] = Country::where('status', STATUS_ACTIVE)->get();
        $data['universities'] = $universities;
        $data['studyLevel'] = $studyLevel;
        $data['pageTitle'] = __('Event');
        $data['activeEventMenu'] = 'active';
        $data['bodyClass'] = 'bg-white';

        return view('frontend.event.list', $data);
    }

    public function details($slug)
    {
        $data['activeEventMenu'] = 'active';
        $data['bodyClass'] = 'bg-white';

        $event = Event::where('slug', $slug)->first();

        $studyLevelIds = is_array($event->study_levels) ? $event->study_levels : json_decode($event->study_levels);
        $countryIds = is_array($event->country_ids) ? $event->country_ids : json_decode($event->country_ids);
        $universityIds = is_array($event->university_ids) ? $event->university_ids : json_decode($event->university_ids);

        $data['eventData'] = $event;
        $data['eventData']->studyLevelsName =  is_array($studyLevelIds) && count($studyLevelIds) > 0
            ? StudyLevel::whereIn('id', $studyLevelIds)->pluck('name')
            : collect();
        $data['eventData']->countryName = is_array($countryIds) && count($countryIds) > 0
            ? Country::whereIn('id', $countryIds)->pluck('name')
            : collect();
        $data['eventData']->universityName = is_array($universityIds) && count($universityIds) > 0
            ? University::whereIn('id', $universityIds)->pluck('name')
            : collect();

        $data['moreEvent'] = Event::where('status', STATUS_ACTIVE)
            ->where('slug', '!=', $slug)
            ->orderBy('date_time', 'ASC')
            ->take(6)
            ->get();

        return view('frontend.event.details', $data);
    }

}
