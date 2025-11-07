<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificateType;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CertificationAndDegreeController extends Controller
{
    use ResponseTrait;
    public function index(Request $request)
    {
        $data['pageTitle'] = __('Certification and Degree');
        $data['activeCertification'] = 'active';
        $data['activeSetting'] = 'active';

        if ($request->ajax()) {
            $certification = CertificateType::orderByDesc('id');

            return datatables($certification)
                ->addIndexColumn()
                ->editColumn('title', fn($data) => $data->title)
                ->editColumn('status', function ($data) {
                    return $data->status == STATUS_ACTIVE
                        ? "<p class='zBadge zBadge-active'>" . __('Active') . "</p>"
                        : "<p class='zBadge zBadge-inactive'>" . __('Deactivate') . "</p>";
                })
                ->addColumn('action', function ($data) {
                    return '<div class="d-flex align-items-center g-10 justify-content-end">
                            <button onclick="getEditModal(\'' . route('admin.setting.certificate_degrees.edit', encodeId($data->id)) . '\', \'#edit-modal\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" data-bs-toggle="modal" data-bs-target="#alumniPhoneNo" title="Edit">
                                 ' . view('partials.icons.edit')->render() . '
                            </button>
                            <button onclick="deleteItem(\'' . route('admin.setting.certificate_degrees.delete', encodeId($data->id)) . '\', \'certificationDataTable\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Delete">
                                ' . view('partials.icons.delete')->render() . '
                            </button>
                        </div>';
                })
                ->rawColumns(['title', 'status', 'action'])
                ->make(true);
        }

        return view('admin.setting.certification_types.index', $data);
    }

    public function edit($id)
    {
        $certification = CertificateType::findOrFail(decodeId($id));

        return view('admin.setting.certification_types.edit', [
            'certification' => $certification
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'status' => 'required|integer'
        ]);

        DB::beginTransaction();
        try {
            $id = $request->get('id', '');
            if ($id) {
                $certification = CertificateType::findOrFail(decodeId($id));
            } else {
                $certification = new CertificateType();
            }
            $certification->title = $request->title;
            $certification->status = $request->status == STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVATE;
            $certification->save();

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
            $certification = CertificateType::findOrFail(decodeId($id));
            $certification->delete();

            return $this->success([], __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }
}
