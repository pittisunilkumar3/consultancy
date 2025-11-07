<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DesignationRequest;
use App\Models\Designation;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DesignationController extends Controller
{
    use ResponseTrait;
    public function index(Request $request)
    {
        $data['pageTitle'] = __('Designation');
        $data['activeDesignation'] = 'active';
        $data['activeSetting'] = 'active';

        if ($request->ajax()) {
            $designations = Designation::orderByDesc('id');

            return datatables($designations)
                ->addIndexColumn()
                ->editColumn('title', fn($data) => $data->title)
                ->editColumn('status', function ($data) {
                    return $data->status == STATUS_ACTIVE
                        ? "<p class='zBadge zBadge-active'>" . __('Active') . "</p>"
                        : "<p class='zBadge zBadge-inactive'>" . __('Deactivate') . "</p>";
                })
                ->addColumn('action', function ($data) {
                    return '<div class="d-flex align-items-center g-10 justify-content-end">
                            <button onclick="getEditModal(\'' . route('admin.setting.designation.edit', encodeId($data->id)) . '\', \'#edit-modal\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" data-bs-toggle="modal" data-bs-target="#alumniPhoneNo" title="Edit">
                                 ' . view('partials.icons.edit')->render() . '
                            </button>
                            <button onclick="deleteItem(\'' . route('admin.setting.designation.delete', encodeId($data->id)) . '\', \'designationDataTable\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Delete">
                                ' . view('partials.icons.delete')->render() . '
                            </button>
                        </div>';
                })
                ->rawColumns(['title', 'status', 'action'])
                ->make(true);
        }

        return view('admin.setting.designation.index', $data);
    }

    public function edit($id)
    {
        $designation = Designation::findOrFail(decodeId($id));

        return view('admin.setting.designation.edit', [
            'designation' => $designation
        ]);
    }

    public function store(DesignationRequest $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->get('id', '');
            if ($id) {
                $designation = Designation::findOrFail(decodeId($id));
            } else {
                $designation = new Designation();
            }
            $designation->title = $request->title;
            $designation->slug = Str::slug($request->slug);
            $designation->status = $request->status == STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVATE;
            $designation->save();

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
            $designation = Designation::findOrFail(decodeId($id));
            $designation->delete();

            return $this->success([], __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }
}
