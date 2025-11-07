<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubjectCategory;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Exception;

class SubjectCategoriesController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $subjectCategory = SubjectCategory::query()->orderBy('id','DESC');
            return datatables($subjectCategory)
                ->addIndexColumn()
                ->addColumn('status', function ($data) {
                    if ($data->status == STATUS_ACTIVE) {
                        return '<div class="zBadge zBadge-completed">'.__("Active").'</div>';
                    } else {
                        return '<div class="zBadge zBadge-deactive">'.__("Deactivate").'</div>';
                    }
                })
                ->addColumn('action', function ($data) {
                    return
                        '<div class="d-flex align-items-center g-10 justify-content-end">
                            <button onclick="getEditModal(\'' . route('admin.subjects.categories.edit', $data->id) . '\'' . ', \'#edit-modal\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Edit">
                                 ' . view('partials.icons.edit')->render() . '
                            </button>
                            <button onclick="deleteItem(\'' . route('admin.subjects.categories.delete', $data->id) . '\', \'subjectCategoryDataTable\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Delete">
                                 ' . view('partials.icons.delete')->render() . '
                            </button>
                        </div>';
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        $data['pageTitle'] = __("Manage Subject Category");
        $data['showSubjectSettings'] = 'show';
        $data['showSubjectCategorySettings'] = 'active';
        return view('admin.subjects.categories.index', $data);
    }

    public function edit($id)
    {
        $data['subjectCategoryData'] = SubjectCategory::find($id);

        return view('admin.subjects.categories.edit', $data);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $id = $request->id;
            $subjectCategory = $id ? SubjectCategory::find($id) : new SubjectCategory();

            $subjectCategory->name = $request->name;
            $subjectCategory->status = $request->status ?? STATUS_PENDING;

            $subjectCategory->save();
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
            $data = SubjectCategory::find($id);
            $data->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $exception) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }
}
