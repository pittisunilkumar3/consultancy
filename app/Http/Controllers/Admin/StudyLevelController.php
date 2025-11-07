<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudyLevel;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudyLevelController extends Controller
{
    use ResponseTrait;
    public function index(Request $request)
    {
        $data['pageTitle'] = __('Study Level');
        $data['activeStudyLevel'] = 'active';
        $data['showCmsSettings'] = 'show';

        if ($request->ajax()) {
            $studyLevels = StudyLevel::orderByDesc('id');

            return datatables($studyLevels)
                ->addIndexColumn()
                ->editColumn('status', function ($data) {
                    return $data->status == STATUS_ACTIVE
                        ? "<p class='zBadge zBadge-active'>" . __('Active') . "</p>"
                        : "<p class='zBadge zBadge-inactive'>" . __('Deactivate') . "</p>";
                })
                ->addColumn('action', function ($data) {
                    return '<div class="d-flex align-items-center g-10 justify-content-end">
                            <button onclick="getEditModal(\'' . route('admin.study_levels.edit', encodeId($data->id)) . '\', \'#edit-modal\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25"  title="Edit">
                                 ' . view('partials.icons.edit')->render() . '
                            </button>
                            <button onclick="deleteItem(\'' . route('admin.study_levels.delete', encodeId($data->id)) . '\', \'studyLevelDataTable\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Delete">
                                ' . view('partials.icons.delete')->render() . '
                            </button>
                        </div>';
                })
                ->rawColumns(['title', 'status', 'action'])
                ->make(true);
        }

        return view('admin.study-levels.index', $data);
    }

    public function edit($id)
    {
        $studyLevel = StudyLevel::findOrFail(decodeId($id));

        return view('admin.study-levels.edit', [
            'studyLevel' => $studyLevel
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:study_levels,name,'.$request->id.'|max:195',
        ]);

        DB::beginTransaction();
        try {
            $id = $request->get('id', '');
            if ($id) {
                $studyLevel = StudyLevel::findOrFail($id);
            } else {
                $studyLevel = new StudyLevel();
            }
            $studyLevel->name = $request->name;
            $studyLevel->status = $request->status == STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVATE;
            $studyLevel->save();

            DB::commit();
            $message = $request->id ? __(UPDATED_SUCCESSFULLY) : __(CREATED_SUCCESSFULLY);
            return $this->success([], getMessage($message));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }

    public function delete($id)
    {
        try {
            $studyLevel = StudyLevel::findOrFail(decodeId($id));
            $studyLevel->delete();

            return $this->success([], __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }
}
