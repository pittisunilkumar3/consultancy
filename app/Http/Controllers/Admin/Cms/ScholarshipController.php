<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\FileManager;
use App\Models\Scholarship;
use App\Models\StudyLevel;
use App\Models\Subject;
use App\Models\University;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Exception;

class ScholarshipController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $scholarship = Scholarship::leftjoin('universities', 'scholarships.university_id', '=', 'universities.id')
                ->leftjoin('study_levels', 'scholarships.study_level_id', '=', 'study_levels.id')
                ->leftjoin('countries', 'scholarships.country_id', '=', 'countries.id')
                ->select('scholarships.*', 'universities.name as universityName', 'study_levels.name as studyLevelsName', 'countries.name as countryName')
                ->orderBy('scholarships.id', 'DESC');
            return datatables($scholarship)
                ->addColumn('banner_image', function ($data) {
                    return '<div class="min-w-160 d-flex align-items-center cg-10">
                            <div class="flex-shrink-0 w-41 h-41 bd-one bd-c-stroke rounded-circle overflow-hidden bg-eaeaea d-flex justify-content-center align-items-center">
                                <img src="' . getFileUrl($data->banner_image) . '" alt="banner_image" class="rounded avatar-xs w-100 h-100 object-fit-cover">
                            </div>
                        </div>';
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == STATUS_ACTIVE) {
                        return '<div class="zBadge zBadge-completed">' . __("Active") . '</div>';
                    } else {
                        return '<div class="zBadge zBadge-deactive">' . __("Deactivate") . '</div>';
                    }
                })
                ->addColumn('action', function ($data) {
                    return
                        '<div class="d-flex align-items-center g-10 justify-content-end">
                            <button onclick="getEditModal(\'' . route('admin.cms-settings.scholarships.edit', $data->id) . '\'' . ', \'#edit-modal\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Edit">
                                 ' . view('partials.icons.edit')->render() . '
                            </button>
                            <button onclick="deleteItem(\'' . route('admin.cms-settings.scholarships.delete', $data->id) . '\', \'scholarshipDataTable\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Delete">
                                 ' . view('partials.icons.delete')->render() . '
                            </button>
                        </div>';
                })
                ->rawColumns(['status', 'action', 'banner_image'])
                ->make(true);
        }
        $data['pageTitle'] = __("Manage Scholarship");
        $data['scholarshipActive'] = 'active';
        $data['showCmsSettings'] = 'show';
        $data['university'] = University::where('status', STATUS_ACTIVE)->get();
        $data['country'] = Country::where('status', STATUS_ACTIVE)->get();
        $data['subject'] = Subject::where('status', STATUS_ACTIVE)->get();
        $data['studyLevel'] = StudyLevel::where('status', STATUS_ACTIVE)->get();
        return view('admin.cms.scholarships.index', $data);
    }

    public function edit($id)
    {
        $data['scholarshipData'] = Scholarship::find($id);
        $data['university'] = University::where('status', STATUS_ACTIVE)->get();
        $data['country'] = Country::where('status', STATUS_ACTIVE)->get();
        $data['subject'] = Subject::where('status', STATUS_ACTIVE)->get();
        $data['studyLevel'] = StudyLevel::where('status', STATUS_ACTIVE)->get();

        return view('admin.cms.scholarships.edit', $data);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'university_id' => 'required',
            'country_id' => 'required',
            'subject_id' => 'required',
            'study_level_id' => 'required',
            'funding_type' => 'required',
            'application_start_date' => 'required',
            'application_end_date' => 'required',
            'offers_received_from_date' => 'required',
            'available_award_number' => 'required',
            'details' => 'required',
            'banner_image' => $request->id ? 'nullable|mimes:jpeg,png,jpg,svg,webp|max:1024' : 'required|mimes:jpeg,png,jpg,svg,webp|max:1024',
        ]);
        DB::beginTransaction();
        try {
            $id = $request->id;
            $scholarship = $id ? Scholarship::find($id) : new Scholarship();

            if (Scholarship::where('slug', getSlug($request->title))->where('id', '!=', $id)->withTrashed()->count() > 0) {
                $slug = getSlug($request->title) . '-' . rand(100000, 999999);
            } else {
                $slug = getSlug($request->title);
            }

            $scholarship->title = $request->title;
            $scholarship->slug = $slug;
            $scholarship->university_id = $request->university_id;
            $scholarship->country_id = $request->country_id;
            $scholarship->subject_id = $request->subject_id;
            $scholarship->study_level_id = $request->study_level_id;
            $scholarship->funding_type = $request->funding_type;
            $scholarship->status = $request->status ?? STATUS_PENDING;
            $scholarship->application_start_date = $request->application_start_date;
            $scholarship->application_end_date = $request->application_end_date;
            $scholarship->offers_received_from_date = $request->offers_received_from_date;
            $scholarship->available_award_number = $request->available_award_number;
            $scholarship->details = $request->details;

            if ($request->hasFile('banner_image')) {
                $newFile = new FileManager();
                $uploadedFile = $newFile->upload('scholarship', $request->banner_image);
                $scholarship->banner_image = $uploadedFile->id;
            }

            $scholarship->save();
            DB::commit();

            $message = $request->id ? __('Updated successfully.') : __('Created successfully.');
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], getMessage($e->getMessage()));
        }
    }

    public function delete($id)
    {
        try {
            $data = Scholarship::find($id);
            $data->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $exception) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }
}
