<?php

namespace App\Http\Controllers\Admin\Consultation;

use App\Http\Controllers\Controller;
use App\Models\ConsultationSlot;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultationSlotController extends Controller
{
    use ResponseTrait;
    public function index(Request $request)
    {
        $data['pageTitle'] = __('Consultation Slot');
        $data['showManageConsultation'] = 'show';
        $data['activeConsultationSlot'] = 'active';

        if ($request->ajax()) {
            $consultationSlots = ConsultationSlot::orderByDesc('id');

            return datatables($consultationSlots)
                ->addIndexColumn()
                ->editColumn('status', function ($data) {
                    return $data->status == STATUS_ACTIVE
                        ? "<p class='zBadge zBadge-active'>" . __('Active') . "</p>"
                        : "<p class='zBadge zBadge-inactive'>" . __('Deactivate') . "</p>";
                })
                ->addColumn('action', function ($data) {
                    return '<div class="d-flex align-items-center g-10 justify-content-end">
                            <button onclick="getEditModal(\'' . route('admin.consultations.slots.edit', encodeId($data->id)) . '\', \'#edit-modal\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25"  title="Edit">
                                 ' . view('partials.icons.edit')->render() . '
                            </button>
                            <button onclick="deleteItem(\'' . route('admin.consultations.slots.delete', encodeId($data->id)) . '\', \'consultationSlotDataTable\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Delete">
                                ' . view('partials.icons.delete')->render() . '
                            </button>
                        </div>';
                })
                ->rawColumns(['title', 'status', 'action'])
                ->make(true);
        }

        return view('admin.consultations.slots.index', $data);
    }

    public function edit($id)
    {
        $consultationSlot = ConsultationSlot::findOrFail(decodeId($id));

        return view('admin.consultations.slots.edit', [
            'consultationSlot' => $consultationSlot
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ]);

        DB::beginTransaction();
        try {
            $id = $request->get('id', '');
            if ($id) {
                $consultationSlot = ConsultationSlot::findOrFail($id);
            } else {
                $consultationSlot = new ConsultationSlot();
            }
            $consultationSlot->start_time = $request->start_time;
            $consultationSlot->end_time = $request->end_time;
            $consultationSlot->status = $request->status == STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_DEACTIVATE;
            $consultationSlot->save();

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
            $consultationSlot = ConsultationSlot::findOrFail(decodeId($id));
            $consultationSlot->delete();

            return $this->success([], __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }
}
