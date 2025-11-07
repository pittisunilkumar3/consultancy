<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageProficiencyTest;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LanguageProficiencyController extends Controller
{
    use ResponseTrait;
    public function index(Request $request)
    {
        $data['pageTitle'] = __('Language Proficiency Test');
        $data['activeLanguageProficiency'] = 'active';
        $data['activeSetting'] = 'active';

        if ($request->ajax()) {
            $languageProficiency = LanguageProficiencyTest::orderByDesc('id');

            return datatables($languageProficiency)
                ->addIndexColumn()
                ->editColumn('title', fn($data) => $data->title)
                ->editColumn('status', function ($data) {
                    return $data->status == STATUS_ACTIVE
                        ? "<p class='zBadge zBadge-active'>" . __('Active') . "</p>"
                        : "<p class='zBadge zBadge-inactive'>" . __('Deactivate') . "</p>";
                })
                ->addColumn('action', function ($data) {
                    return '<div class="d-flex align-items-center g-10 justify-content-end">
                            <button onclick="getEditModal(\'' . route('admin.setting.language_proficiencies.edit', encodeId($data->id)) . '\', \'#edit-modal\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" data-bs-toggle="modal" data-bs-target="#alumniPhoneNo" title="Edit">
                                 ' . view('partials.icons.edit')->render() . '
                            </button>
                            <button onclick="deleteItem(\'' . route('admin.setting.language_proficiencies.delete', encodeId($data->id)) . '\', \'languageProficiencyDataTable\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Delete">
                                ' . view('partials.icons.delete')->render() . '
                            </button>
                        </div>';
                })
                ->rawColumns(['title', 'status', 'action'])
                ->make(true);
        }

        return view('admin.setting.language_proficiencies.index', $data);
    }

    public function edit($id)
    {
        $languageProficiency = LanguageProficiencyTest::findOrFail(decodeId($id));

        return view('admin.setting.language_proficiencies.edit', [
            'languageProficiency' => $languageProficiency
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
                $languageProficiency = LanguageProficiencyTest::findOrFail(decodeId($id));
            } else {
                $languageProficiency = new LanguageProficiencyTest();
            }
            $languageProficiency->title = $request->title;
            $languageProficiency->status = $request->status == STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVATE;
            $languageProficiency->save();

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
            $languageProficiency = LanguageProficiencyTest::findOrFail(decodeId($id));
            $languageProficiency->delete();

            return $this->success([], __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }
}
