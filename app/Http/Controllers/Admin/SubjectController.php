<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\FileManager;
use App\Models\Subject;
use App\Models\StudyLevel;
use App\Models\SubjectCategory;
use App\Models\University;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Exception;

class SubjectController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $subject = Subject::leftjoin('universities', 'subjects.university_id', '=', 'universities.id')
                ->leftjoin('study_levels', 'subjects.study_level_id', '=', 'study_levels.id')
                ->leftjoin('countries', 'subjects.country_id', '=', 'countries.id')
                ->select('subjects.*', 'universities.name as universityName', 'study_levels.name as studyLevelsName', 'countries.name as countryName')
                ->orderBy('subjects.id', 'DESC');
            return datatables($subject)
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
                            <button onclick="getEditModal(\'' . route('admin.subjects.edit', $data->id) . '\'' . ', \'#edit-modal\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Edit">
                                 ' . view('partials.icons.edit')->render() . '
                            </button>
                            <button onclick="deleteItem(\'' . route('admin.subjects.delete', $data->id) . '\', \'subjectsDataTable\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Delete">
                                 ' . view('partials.icons.delete')->render() . '
                            </button>
                        </div>';
                })
                ->rawColumns(['status', 'action', 'banner_image'])
                ->make(true);
        }
        $data['pageTitle'] = __("Manage Subject");
        $data['showSubjectSettings'] = 'show';
        $data['subjectActive'] = 'active';
        $data['university'] = University::where('status', STATUS_ACTIVE)->get();
        $data['country'] = Country::where('status', STATUS_ACTIVE)->get();
        $data['subjectCategory'] = SubjectCategory::where('status', STATUS_ACTIVE)->get();
        $data['studyLevel'] = StudyLevel::where('status', STATUS_ACTIVE)->get();
        return view('admin.subjects.index', $data);
    }

    public function edit($id)
    {
        $data['subjectData'] = Subject::find($id);
        $data['university'] = University::where('status', STATUS_ACTIVE)->get();
        $data['country'] = Country::where('status', STATUS_ACTIVE)->get();
        $data['subjectCategory'] = SubjectCategory::where('status', STATUS_ACTIVE)->get();
        $data['studyLevel'] = StudyLevel::where('status', STATUS_ACTIVE)->get();

        return view('admin.subjects.edit', $data);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'study_level_id' => 'required',
            'details' => 'required',
            'university_id' => 'required',
            'subject_category_id' => 'required',
            'requirement_program' => 'required',
            'requirement_score' => 'required',
            'country_id' => 'required',
            'intake_time' => 'required',
            'duration' => 'required',
            'amount' => 'required',
            'banner_image' => $request->id ? 'nullable|mimes:jpeg,png,jpg,svg,webp|max:1024' : 'required|mimes:jpeg,png,jpg,svg,webp|max:1024',
        ]);
        DB::beginTransaction();
        try {
            $id = $request->id;
            $subject = $id ? Subject::find($id) : new Subject();

            if (Subject::where('slug', getSlug($request->name))->where('id', '!=', $id)->withTrashed()->count() > 0) {
                $slug = getSlug($request->name) . '-' . rand(100000, 999999);
            } else {
                $slug = getSlug($request->name);
            }

            $subject->slug = $slug;
            $subject->name = $request->name;
            $subject->amount = $request->amount;
            $subject->details = $request->details;
            $subject->duration = $request->duration;
            $subject->country_id = $request->country_id;
            $subject->requirement_program = $request->requirement_program;
            $subject->requirement_score = $request->requirement_score;
            $subject->intake_time = $request->intake_time;
            $subject->university_id = $request->university_id;
            $subject->study_level_id = $request->study_level_id;
            $subject->subject_category_id = $request->subject_category_id;
            $subject->status = $request->status ?? STATUS_PENDING;

            if ($request->hasFile('banner_image')) {

                $newFile = new FileManager();
                $uploadedFile = $newFile->upload('subject', $request->banner_image);

                $subject->banner_image = $uploadedFile->id;
            }

            $subject->save();
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
            $data = Subject::find($id);
            $data->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $exception) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }
}
