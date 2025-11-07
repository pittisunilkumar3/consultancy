<?php

namespace App\Http\Controllers\Consulter;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    use ResponseTrait;

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Dashboard');
        $data['isDashboard'] = true;
        $data['activeDashboard'] = 'active';
        $data['appointmentsCompleted'] = Appointment::where(['status' => STATUS_ACTIVE , 'consulter_id' => auth()->user()->id ])->where('appointments.payment_status', '!=', PAYMENT_STATUS_NOT_INIT)->count();
        $data['appointmentsPending'] = Appointment::where(['status' => STATUS_PENDING , 'consulter_id' => auth()->user()->id ])->where('appointments.payment_status', '!=', PAYMENT_STATUS_NOT_INIT)->count();
        $data['appointmentsProcessing'] = Appointment::where(['status' => STATUS_PROCESSING , 'consulter_id' => auth()->user()->id ])->where('appointments.payment_status', '!=', PAYMENT_STATUS_NOT_INIT)->count();
        $data['appointmentsRefund'] = Appointment::where(['status' => STATUS_REFUNDED , 'consulter_id' => auth()->user()->id ])->where('appointments.payment_status', '!=', PAYMENT_STATUS_NOT_INIT)->count();

        if ($request->ajax()) {

            $appointments = Appointment::where('appointments.payment_status', '!=', PAYMENT_STATUS_NOT_INIT)->with(['consulter', 'user', 'slot'])->where('consulter_id' , auth()->user()->id )->orderBy('id', 'DESC');

            return datatables($appointments)
                ->addColumn('student', fn($appointment) => $appointment->user->name)
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
                        STATUS_PENDING => __('Pending'),
                        STATUS_PROCESSING => __('Processing'),
                        STATUS_ACTIVE => __('Completed'),
                        STATUS_REFUNDED => __('Refunded')
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
                ->rawColumns(['status', 'action', 'consultation_type'])
                ->make(true);
            }

        return view('consultant.dashboard', $data);
    }
}
