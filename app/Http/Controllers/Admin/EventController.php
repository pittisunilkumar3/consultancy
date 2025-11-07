<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EventRequest;
use App\Models\Country;
use App\Models\Event;
use App\Models\FileManager;
use App\Models\StudyLevel;
use App\Models\University;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $studyLevels = StudyLevel::where('status', STATUS_ACTIVE)->get()->keyBy('id');
            $events = Event::orderBy('date_time', 'desc')
                ->select('id', 'title', 'image', 'study_levels', 'type', 'date_time', 'status', 'slug');

            return datatables($events)
                ->editColumn('status', function ($data) {
                    return $data->status == STATUS_ACTIVE
                        ? "<p class='zBadge zBadge-active'>" . __('Active') . "</p>"
                        : "<p class='zBadge zBadge-inactive'>" . __('Deactivate') . "</p>";
                })
                ->editColumn('type', function ($data) {
                    return $data->type == EVENT_TYPE_PHYSICAL
                        ? __('Physical')
                        : __('Virtual');
                })
                ->editColumn('image', function ($data) {
                    return '<div class="min-w-160 d-flex align-items-center cg-10">
                            <div class="flex-shrink-0 w-41 h-41 bd-one bd-c-stroke rounded-circle overflow-hidden bg-eaeaea d-flex justify-content-center align-items-center">
                                <img src="' . getFileUrl($data->image) . '" alt="Image" class="rounded avatar-xs w-100 h-100 object-fit-cover">
                            </div>
                        </div>';
                })
                ->editColumn('study_levels', function ($data) use ($studyLevels) {
                    // Fetch names based on study level IDs
                    $studyLevelNames = collect($data->study_levels)
                        ->map(fn($id) => $studyLevels[$id]->name ?? null)
                        ->filter()
                        ->join(', ');

                    return $studyLevelNames ?: 'N/A';
                })
                ->addColumn('action', function ($data) {
                    return '<div class="d-flex align-items-center g-10 justify-content-end">
                        <a href="' . route('admin.events.view', $data->slug) . '" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="View">
                            ' . view('partials.icons.view')->render() . '
                        </a>
                        <button onclick="getEditModal(\'' . route('admin.events.edit', $data->id) . '\', \'#edit-modal\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Edit">
                            ' . view('partials.icons.edit')->render() . '
                        </button>
                        <button onclick="deleteItem(\'' . route('admin.events.delete', $data->id) . '\', \'eventDataTable\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Delete">
                            ' . view('partials.icons.delete')->render() . '
                        </button>
                    </div>';
                })

                ->rawColumns(['action', 'status', 'image'])
                ->make(true);
        }

        $data['pageTitle'] = __('Manage Events');
        $data['showManageEvent'] = 'show';
        $data['activeEvent'] = 'active';
        $data['studyLevels'] = StudyLevel::where('status', STATUS_ACTIVE)->get();
        $data['countries'] = Country::where('status', STATUS_ACTIVE)->get();
        $data['universities'] = University::where('status', STATUS_ACTIVE)->get();
        return view('admin.events.index', $data);
    }

    public function edit($id)
    {
        // Find the event by ID
        $event = Event::findOrFail($id);

        // Load all study levels, countries, and universities
        $studyLevels = StudyLevel::where('status', STATUS_ACTIVE)->get();
        $countries = Country::where('status', STATUS_ACTIVE)->get();
        $universities = University::where('status', STATUS_ACTIVE)->get();

        return view('admin.events.edit', [
            'event' => $event,
            'studyLevels' => $studyLevels,
            'countries' => $countries,
            'universities' => $universities,
        ]);
    }


    public function store(EventRequest $request)
    {
        DB::beginTransaction();
        try {
            $event = new Event([
                'title' => $request->title,
                'slug' => getSlug($request->title),
                'type' => $request->type,
                'location' => $request->location,
                'link' => $request->link,
                'date_time' => $request->date_time,
                'price' => $request->price,
                'status' => $request->status,
                'description' => $request->description,
                'country_ids' => $request->input('country_ids', []),
                'university_ids' => $request->input('university_ids', []),
                'study_levels' => $request->input('study_levels', []),
            ]);

            if ($request->hasFile('image')) {
                $new_file = new FileManager();
                $uploaded = $new_file->upload('events', $request->image);
                if (!is_null($uploaded)) {
                    $event->image = $uploaded->id;
                } else {
                    DB::rollBack();
                    return $this->error([], getMessage(SOMETHING_WENT_WRONG));
                }
            }

            $event->save();
            DB::commit();
            return $this->success([], getMessage(CREATED_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function update(EventRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $event = Event::findOrFail($id);
            $event->fill([
                'title' => $request->title,
                'type' => $request->type,
                'location' => $request->location,
                'link' => $request->link,
                'date_time' => $request->date_time,
                'price' => $request->price,
                'status' => $request->status,
                'description' => $request->description,
                'country_ids' => $request->input('country_ids', []),
                'university_ids' => $request->input('university_ids', []),
                'study_levels' => $request->input('study_levels', []),
            ]);

            if ($request->hasFile('image')) {
                $new_file = new FileManager();
                $uploaded = $new_file->upload('events', $request->image);
                if (!is_null($uploaded)) {
                    $event->image = $uploaded->id;
                } else {
                    DB::rollBack();
                    return $this->error([], getMessage(SOMETHING_WENT_WRONG));
                }
            }

            $event->save();
            DB::commit();
            return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            Event::where('id', $id)->delete();

            DB::commit();
            $message = getMessage(DELETED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function view($slug)
    {
        $event = Event::where('slug', $slug)->with('percipients')->first();

        if (!$event) {
            return back()->with(['error' => __('Event Not Found')]);
        }

        // Get comma-separated lists of countries, study levels, and universities
        $countries = $event->getCountries()->pluck('name')->implode(', ');
        $studyLevels = $event->getStudyLevels()->pluck('name')->implode(', ');
        $universities = $event->getUniversities()->pluck('name')->implode(', ');

        $data = [
            'pageTitle' => __('Details'),
            'pageTitleParent' => __('Manage Events'),
            'showManageEvent' => 'show',
            'activeEvent' => 'active',
            'event' => $event,
            'countries' => $countries,
            'studyLevels' => $studyLevels,
            'universities' => $universities,
        ];

        return view('admin.events.view', $data);
    }
}
