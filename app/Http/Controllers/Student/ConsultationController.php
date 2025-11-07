<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ConsulterFeedback;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ConsultationController extends Controller
{
    use ResponseTrait;

    public function list(Request $request)
    {
        if ($request->ajax()) {

            $appointments = Appointment::with(['consulter', 'user'])
                ->where('user_id', auth()->id())
                ->whereHas('user', function ($query) {
                    $query->where('role', USER_ROLE_STUDENT);
                })
                ->where('appointments.payment_status', '!=', PAYMENT_STATUS_NOT_INIT)
                ->when($request->filled('search_key'), function ($query) use ($request) {
                    $keyword = $request->search_key;

                    $query->where(function ($query) use ($keyword) {
                        $query->where('date', 'like', '%' . $keyword . '%');

                        $matchingTypes = array_keys(array_filter(getConsultationType(), function($value) use ($keyword) {
                            return stripos($value, $keyword) !== false;
                        }));

                        if (!empty($matchingTypes)) {
                            $query->orWhereIn('consultation_type', $matchingTypes);
                        }
                    });
                })
                ->orderBy('id', 'DESC');


            return datatables($appointments)
                ->addColumn('consultant', fn($appointment) => $appointment->consulter->name)
                ->editColumn('consultation_type', function ($appointment) {
                    $typeText = getConsultationType($appointment->consultation_type);
                    if ($appointment->consultation_type == CONSULTATION_TYPE_VIRTUAL && $appointment->consultation_link) {
                        $typeText .= ' - <a href="' . $appointment->consultation_link . '" target="_blank">' . __('Join Meeting') . '</a>';
                    }
                    return $typeText;
                })
                ->editColumn('date', function ($appointment) {
                    $date = $appointment->date ? Carbon::parse($appointment->date)->format('Y-m-d') : '';
                    $startTime = $appointment->slot ? Carbon::parse($appointment->slot->start_time)->format('H:i') : '';
                    return $date . ' ' . $startTime;
                })
                ->editColumn('status', function ($appointment) {
                    $statusLabels = [
                        STATUS_PENDING => 'Pending',
                        STATUS_PROCESSING => 'Processing',
                        STATUS_ACTIVE => 'Completed',
                        STATUS_REFUNDED => 'Refunded'
                    ];

                    $statusClass = [
                        STATUS_PENDING => 'zBadge-pending',
                        STATUS_PROCESSING => 'zBadge-processing',
                        STATUS_ACTIVE => 'zBadge-active',
                        STATUS_REFUNDED => 'zBadge-refunded'
                    ];

                    $status = $appointment->status;
                    $label = $statusLabels[$status] ?? $status;
                    $class = $statusClass[$status] ?? 'zBadge-default';

                    return "<p class='zBadge {$class}'>" . __($label) . "</p>";
                })
                ->addColumn('action', function ($appointment) {
                    return '<div class="d-flex align-items-center g-10 justify-content-end">
                     <a href="'.route('student.consultation-appointment.details', encodeId($appointment->id)) . '" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="' . __('View') . '">
                        ' . view('partials.icons.view')->render() . '
                    </a>
                </div>';
                })
                ->rawColumns(['status', 'action', 'consultation_type'])
                ->make(true);
        }

        $data['pageTitle'] = __('My Consultation');
        $data['activeConsultation'] = 'active';

        return view('student.consultation.list', $data);
    }

    public function details($id)
    {
        $data['pageTitle'] =__('Consultation Details');
        $data['activeConsultation'] = 'active';

        $data['appointment'] = Appointment::leftjoin('consulter_feedback','appointments.id','=','consulter_feedback.appointment_id')->select('appointments.*','consulter_feedback.appointment_id as consulterAppointment')->where('appointments.id', decodeId($id))->with(['meeting_provider', 'slot', 'consulter', 'user', 'payment.gateway','payment.paidByUser'])->first();
        return view('student.consultation.details', $data);
    }

    public function review($id)
    {
        $data['appointment'] = Appointment::find($id);
        return view('student.consultation.review',$data);
    }

    public function reviewStore(Request $request){

        $validated = $request->validate([
            'comment' => 'required',
        ]);

        DB::beginTransaction();
        try {

            $appointment = Appointment::find($request->appointment_id);
            $consulterFeedback = new ConsulterFeedback();

            $consulterFeedback->student_id = auth()->user()->id;
            $consulterFeedback->consulter_id = $appointment->consulter_id;
            $consulterFeedback->comment = $request->comment;
            $consulterFeedback->appointment_id = $appointment->id;
            $consulterFeedback->status = STATUS_PENDING;

            $consulterFeedback->save();
            DB::commit();
            $message = SENT_SUCCESSFULLY;
            return $this->success([], getMessage($message));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], getMessage($e->getMessage()));
        }
    }

}
