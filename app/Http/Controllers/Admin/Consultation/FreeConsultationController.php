<?php

namespace App\Http\Controllers\Admin\Consultation;

use App\Http\Controllers\Controller;
use App\Models\FreeConsultation;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use function Clue\StreamFilter\fun;

class FreeConsultationController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Leads');
        $data['activeFreeConsultation'] = 'active';

        if ($request->ajax()) {
            if(auth()->user()->role == USER_ROLE_STAFF ){
                $freeConsultations = FreeConsultation::with('study_level')->where('assign_by', auth()->user()->id)->orderByDesc('id');
            }else{
                $freeConsultations = FreeConsultation::with('study_level')->orderByDesc('id');
            }

            return datatables($freeConsultations)
                ->addIndexColumn()
                ->editColumn('name', function($data){
                        return $data->first_name.' '.$data->last_name;
                })
                ->editColumn('study_level', function($data){
                        return $data->study_level?->name;
                })
                ->editColumn('status', function ($data) {
                    switch ($data->status) {
                        case WORKING_STATUS_PENDING:
                            return "<p class='zBadge zBadge-pending'>" . __('Pending') . "</p>";
                        case WORKING_STATUS_PROCESSING:
                            return "<p class='zBadge zBadge-processing'>" . __('Processing') . "</p>";
                        case WORKING_STATUS_CANCELED:
                            return "<p class='zBadge zBadge-cancelled'>" . __('Cancelled') . "</p>";
                        default:
                            return "<p class='zBadge zBadge-completed'>" . __('Converted To Customer') . "</p>";
                    }
                })
                ->addColumn('action', function ($data) {
                    return '<div class="d-flex align-items-center g-10 justify-content-end">
                            <button onclick="getEditModal(\'' . route('admin.free_consultations.view', encodeId($data->id)) . '\', \'#view-modal\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25"  title="'.__('View').'">
                                 ' . view('partials.icons.view')->render() . '
                            </button>
                        </div>';
                })
                ->rawColumns(['title', 'status', 'action'])
                ->make(true);
        }

        return view('admin.free_consultations.index', $data);
    }

    public function view($id)
    {
        $data['consultation'] = FreeConsultation::findOrFail(decodeId($id));
        $data['staffList'] = User::where(['role' => USER_ROLE_STAFF, 'status' => STATUS_ACTIVE])->get();

        return view('admin.free_consultations.view', $data);
    }

    public function changeStatus(Request $request, $id)
    {
        $consultation= FreeConsultation::where('id', decodeId($id))->with('study_level')->first();
        $consultation->update([
            'narration' => $request->narration,
            'status' => $request->status,
            'assign_by' => $request->assign_by,
        ]);
        return $this->success([], __(UPDATED_SUCCESSFULLY));
    }
}
