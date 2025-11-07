<?php

namespace App\Http\Controllers\Admin\Consultation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AppointmentRequest;
use App\Mail\EmailNotify;
use App\Models\Appointment;
use App\Models\Currency;
use App\Models\Gateway;
use App\Models\MeetingPlatform;
use App\Models\User;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use DateTimeInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Str;

class AppointmentController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $status = $request->query('status');
            $searchKey = $request->query('search_key');
            $consulterId = $request->query('consulter_id');

            $authUser = auth()->user();
            if ($authUser->role == USER_ROLE_CONSULTANT) {
                $consulterId = $authUser->id;
            }

            $appointments = Appointment::with(['consulter', 'user', 'slot']) // Include slot for start time
            ->when($status && $status !== 'All', function ($query) use ($status) {
                $statusMapping = [
                    'Pending' => STATUS_PENDING,
                    'Processing' => STATUS_PROCESSING,
                    'Completed' => STATUS_ACTIVE,
                    'Refunded' => STATUS_REFUNDED,
                ];
                return $query->where('status', $statusMapping[$status] ?? $status);
            })
                ->when($searchKey, function ($query) use ($searchKey) {
                    return $query->where(function ($q) use ($searchKey) {
                        $q->where('appointment_ID', 'like', "%{$searchKey}%")
                            ->orWhereHas('user', function ($subQuery) use ($searchKey) {
                                $subQuery->where('first_name', 'like', "%{$searchKey}%")
                                    ->orWhere('last_name', 'like', "%{$searchKey}%");
                            });
                    });
                })
                ->when($consulterId, function ($query) use ($consulterId) {
                    return $query->where('consulter_id', $consulterId);
                })
                ->where('appointments.payment_status', '!=', PAYMENT_STATUS_NOT_INIT)
                ->orderBy('id', 'DESC');

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
                    // Show the appointment date and the slot start time
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
                ->addColumn('action', function ($appointment) {
                    $user = auth()->user();
                    $buttons = '<div class="d-flex align-items-center g-10 justify-content-end">';

                    // Always show view button
                    $buttons .= '<a href="' . route(getPrefix() . '.consultations.appointments.view', encodeId($appointment->id)) . '" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="' . __('View') . '">
                    ' . view('partials.icons.view')->render() . '
                 </a>';

                    // Show additional options for admin only
                    if ($user->role === USER_ROLE_ADMIN) {
                        $buttons .= '<a href="' . route(getPrefix() . '.consultations.appointments.edit', encodeId($appointment->id)) . '" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="' . __('Edit') . '">
                        ' . view('partials.icons.edit')->render() . '
                     </a>
                     <button onclick="deleteItem(\'' . route(getPrefix() . '.consultations.appointments.delete', encodeId($appointment->id)) . '\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="' . __('Delete') . '">
                         ' . view('partials.icons.delete')->render() . '
                     </button>';
                    }

                    // Always show change status button
                    $buttons .= '<button onclick="getEditModal(\'' . route(getPrefix() . '.consultations.appointments.status_change_modal', $appointment->id) . '\', \'#status-change-modal\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="' . __('Change Status') . '">
                    ' . view('partials.icons.change_status')->render() . '
                 </button>';

                    $buttons .= '</div>';

                    return $buttons;
                })
                ->rawColumns(['status', 'action', 'consultation_type'])
                ->make(true);
        }

        $data['pageTitle'] = __('Appointments');
        $data['showManageConsultation'] = 'show';
        $data['activeAppointment'] = 'active';
        $data['consultants'] = User::where('role', USER_ROLE_CONSULTANT)->get();

        return view(getPrefix() . '.consultations.appointments.index', $data);
    }

    public function create()
    {
        $data['pageTitle'] = __('Create');
        $data['pageTitleParent'] = __('Appointments');
        $data['showManageConsultation'] = 'show';
        $data['activeAppointment'] = 'active';
        $data['consultants'] = User::where('role', USER_ROLE_CONSULTANT)->get();
        $data['students'] = User::where('role', USER_ROLE_STUDENT)->get();
        $data['gateways'] = Gateway::where('status', STATUS_ACTIVE)->get();

        return view('admin.consultations.appointments.create', $data);
    }

    public function edit($id)
    {
        $data['pageTitle'] = __('Update');
        $data['pageTitleParent'] = __('Appointments');
        $data['showManageConsultation'] = 'show';
        $data['activeAppointment'] = 'active';
        $data['consultants'] = User::where('role', USER_ROLE_CONSULTANT)->get();
        $data['students'] = User::where('role', USER_ROLE_STUDENT)->get();
        $data['gateways'] = Gateway::where('status', STATUS_ACTIVE)->get();
        $data['appointment'] = Appointment::where('id', decodeId($id))->with(['meeting_provider', 'slot', 'consulter', 'user', 'payment.gateway'])->first();

        return view('admin.consultations.appointments.edit', $data);
    }

    public function view($id)
    {
        $data['pageTitle'] = __('Details');
        $data['pageTitleParent'] = __('Appointments');
        $data['showManageConsultation'] = 'show';
        $data['activeAppointment'] = 'active';

        $data['appointment'] = Appointment::where('id', decodeId($id))->with(['meeting_provider', 'slot', 'consulter', 'user', 'payment.gateway', 'payment.paidByUser'])->first();
        return view('admin.consultations.appointments.view', $data);
    }

    private function saveAppointment(Request $request, $appointment = null)
    {
        DB::beginTransaction();
        try {
            $consultant = User::where('role', USER_ROLE_CONSULTANT)->findOrFail($request->get('consultant'));
            $consultantFee = $consultant->fee;

            // Prepare appointment data
            $appointmentData = [
                'user_id' => $request->get('student'),
                'consulter_id' => $consultant->id,
                'consultation_slot_id' => $request->get('consultation_slot_id'),
                'date' => $request->get('date'),
                'consultation_type' => $request->get('consultation_type'),
                'status' => STATUS_PENDING,
                'payment_status' => PAYMENT_STATUS_PAID,
                'created_by' => auth()->id(),
                'narration' => $request->narration
            ];

            $isNewAppointment = !$appointment;
            $timeOrSlotChanged = false;

            // If updating, determine if time or slot changed
            if ($appointment) {
                $timeOrSlotChanged =
                    $appointment->consultation_slot_id != $request->get('consultation_slot_id') ||
                    $appointment->date != $request->get('date');
                $appointment->update($appointmentData);
            } else {
                $appointment = Appointment::create($appointmentData);
            }

            if($consultant->fee < 1){
                DB::commit();
                $message = $isNewAppointment
                    ? __('Booking created successfully with payment marked as paid')
                    : __('Booking updated successfully');
                return $this->success([], $message);
            }

            $tnxId = uniqid();

            // Create or update the Payment record associated with the appointment
            $appointment->payments()->updateOrCreate(
                ['paymentable_id' => $appointment->id, 'paymentable_type' => Appointment::class],
                [
                    'user_id' => $request->get('student'),
                    'tnxId' => $tnxId,
                    'amount' => $consultantFee,
                    'system_currency' => Currency::where('current_currency', 'on')->first()->currency_code,
                    'gateway_id' => Gateway::where('slug', $request->get('gateway'))->first()->id,
                    'payment_currency' => $request->get('gateway_currency'),
                    'conversion_rate' => $request->get('conversion_rate', 1),
                    'sub_total' => $consultantFee,
                    'grand_total' => $consultantFee,
                    'grand_total_with_conversation_rate' => $consultantFee * $request->get('conversion_rate', 1),
                    'bank_id' => $request->get('bank_id'),
                    'deposit_slip' => $request->get('deposit_slip'),
                    'payment_status' => PAYMENT_STATUS_PAID,
                    'payment_time' => now(),
                    'paymentId' => $tnxId,
                    'paid_by' => auth()->id(),
                ]
            );

            DB::commit();

            // Send notifications and emails only if it's a new appointment or time/slot has changed
            if ($isNewAppointment || $timeOrSlotChanged) {
                $this->sendAppointmentNotifications($appointment, $consultant, $request->get('student'));
            }

            $message = $isNewAppointment
                ? __('Booking created successfully with payment marked as paid')
                : __('Booking updated successfully');

            return $this->success([], $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }

    public function store(AppointmentRequest $request)
    {
        return $this->saveAppointment($request);
    }

    public function update(AppointmentRequest $request, $id)
    {
        $appointment = Appointment::findOrFail(decodeId($id));
        return $this->saveAppointment($request, $appointment);
    }

    private function sendAppointmentNotifications($appointment, $consultant, $studentId)
    {
        // Send notification to student
        $purpose = __('Consultation payment for ') . $consultant->name;
        setCommonNotification(
            $studentId,
            __('Appointment confirmed and payment completed'),
            $purpose,
            route('student.transactions')
        );

        // Send email notification to student
        if (getOption('app_mail_status')) {
            $link = route('student.consultation-appointment.list');
            $viewData = [
                '{{name}}' => $appointment->user->name,
                '{{email}}' => $appointment->user->email,
                '{{link}}' => $link
            ];
            $data = getEmailTemplate('consultation-booking-success', $viewData);
            Mail::to($appointment->user->email)->send(new EmailNotify($data));
        }

        // Send notification to consultant
        $studentName = $appointment->user->name;
        $consultantPurpose = __('You have a new appointment booked by ') . $studentName;
        $consultantLink = route('consultant.consultations.appointments.index');

        setCommonNotification(
            $consultant->id,
            __('New Appointment Booked'),
            $consultantPurpose,
            $consultantLink
        );

        // Send email notification to consultant
        if (getOption('app_mail_status')) {
            $consultantViewData = [
                '{{name}}' => $consultant->name,
                '{{student_name}}' => $studentName,
                '{{link}}' => $consultantLink
            ];
            $consultantData = getEmailTemplate('consultation-booking-consultant-notification', $consultantViewData);
            Mail::to($consultant->email)->send(new EmailNotify($consultantData));
        }
    }

    public function delete($id)
    {
        try {
            $consulter = Appointment::findOrFail(decodeId($id));
            $consulter->delete();

            return $this->success([], __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }

    public function statusChangeModal($id)
    {
        $data['appointment'] = Appointment::where('id', $id)->first();
        $data['meetingPlatforms'] = MeetingPlatform::where('status', STATUS_ACTIVE)->get();

        return view('admin.consultations.appointments.status_change_modal', $data);
    }

    public function statusChange(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $appointment = Appointment::with('slot')->findOrFail($id);
            $appointment->status = $request->status;

            // Check if the meeting provider has changed
            $newProviderId = $request->meeting_platform;
            $providerChanged = $appointment->meeting_provider_id != $newProviderId;

            // Update meeting provider ID and reset link if provider has changed
            $appointment->meeting_provider_id = $newProviderId;
            if ($providerChanged) {
                $appointment->consultation_link = null; // Clear existing link if provider changed
            }

            $appointment->save();

            // Reload the meeting_provider relationship after saving
            $appointment->load('meeting_provider');

            $newLinkCreated = false; // Flag to track if a new link is generated

            // Check if virtual consultation requires a new link
            if ($request->status == STATUS_PROCESSING && $appointment->consultation_type == CONSULTATION_TYPE_VIRTUAL) {
                if (empty($appointment->consultation_link) || $providerChanged) {
                    if ($appointment->meeting_provider->type == MEETING_PLATFORMS_ZOOM) {
                        $meeting = $this->createZoomMeeting($appointment);
                        $appointment->consultation_link = $meeting['data']['join_url'];
                        $newLinkCreated = true;
                    } elseif ($appointment->meeting_provider->type == MEETING_PLATFORMS_MEET) {
                        $appointment->consultation_link = $this->createMeetMeeting($appointment);
                        $newLinkCreated = true;
                    }
                }
            }

            // Save the consultation link if a virtual link was generated
            $appointment->save();

            // Email and Notification
            $statusLabel = $this->getStatusLabel($request->status);
            if (getOption('app_mail_status')) {
                $viewData = [
                    '{{name}}' => $appointment->user->name,
                    '{{email}}' => $appointment->user->email,
                    '{{status}}' => $statusLabel
                ];
                $data = getEmailTemplate('consultation-booking-status-update', $viewData);
                Mail::to($appointment->user->email)->send(new EmailNotify($data));
            }

            setCommonNotification(
                $appointment->user_id,
                __('Appointment status has been changed'),
                __('Appointment status has been changed to') . ' ' . $statusLabel,
                route('student.consultation-appointment.list')
            );

            // Additional email and notification if a new link was created
            if ($newLinkCreated) {
                $linkNotificationText = __('Your consultation meeting link is ready');
                $linkNotificationDescription = __('Join your meeting using the provided link: ') . $appointment->consultation_link;

                // Send notification with the new link
                setCommonNotification(
                    $appointment->user_id,
                    $linkNotificationText,
                    $linkNotificationDescription,
                    $appointment->consultation_link
                );

                // Email for the new link
                if (getOption('app_mail_status')) {
                    $linkViewData = [
                        '{{name}}' => $appointment->user->name,
                        '{{link}}' => $appointment->consultation_link,
                    ];
                    $linkData = getEmailTemplate('consultation-meeting-link', $linkViewData);
                    Mail::to($appointment->user->email)->send(new EmailNotify($linkData));
                }
            }

            DB::commit();
            return $this->success([], __('Updated Successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], __('Something went wrong! Please try again.'));
        }
    }

    private function createZoomMeeting(Appointment $appointment)
    {
        config([
            'zoom.client_id' => $appointment->meeting_provider->key,
            'zoom.client_secret' => $appointment->meeting_provider->secret,
            'zoom.account_id' => $appointment->meeting_provider->account_id
        ]);

        // Combine appointment date with slot start time
        $startTime = Carbon::parse($appointment->date . ' ' . $appointment->slot->start_time);

        return \Jubaer\Zoom\Facades\Zoom::createMeeting([
            "topic" => 'Consultation - ' . $appointment->user->name,
            "type" => 2,
            "duration" => $appointment->slot->duration, // Duration in minutes
            "timezone" => $appointment->meeting_provider->timezone ?? 'Asia/Dhaka',
            "start_time" => $startTime->format('Y-m-d\TH:i:s'),
            "settings" => [
                'host_video' => @$appointment->meeting_provider->host_video == STATUS_ACTIVE,
                'participant_video' => @$appointment->meeting_provider->participant_video == STATUS_ACTIVE,
                'waiting_room' => @$appointment->meeting_provider->waiting_room == STATUS_ACTIVE,
            ],
        ]);
    }

    private function createMeetMeeting(Appointment $appointment)
    {
        $client = new \Google_Client();
        $client->setAccessToken(json_decode($appointment->meeting_provider->token, true));

        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                $authUrl = $client->createAuthUrl();
                return redirect($authUrl);
            }
        }

        $service = new \Google_Service_Calendar($client);
        $calendarId = $appointment->meeting_provider->calender_id;

        // Combine appointment date with slot times for start and end
        $startTime = Carbon::parse($appointment->date . ' ' . $appointment->slot->start_time);
        $endTime = Carbon::parse($appointment->date . ' ' . $appointment->slot->end_time);

        $event = new \Google_Service_Calendar_Event([
            'summary' => 'Consultation - ' . $appointment->user->name,
            'start' => [
                'dateTime' => $startTime->format(DateTimeInterface::RFC3339),
                'timeZone' => $appointment->meeting_provider->timezone,
            ],
            'end' => [
                'dateTime' => $endTime->format(DateTimeInterface::RFC3339),
                'timeZone' => $appointment->meeting_provider->timezone,
            ],
        ]);

        // Conference data for Google Meet link
        $conferenceData = new \Google_Service_Calendar_ConferenceData();
        $conferenceRequest = new \Google_Service_Calendar_CreateConferenceRequest();
        $conferenceRequest->setRequestId(Str::random(10));
        $conferenceData->setCreateRequest($conferenceRequest);

        $event->setConferenceData($conferenceData);

        // Insert and update event with conference data to get Google Meet link
        $event = $service->events->insert($calendarId, $event, ['conferenceDataVersion' => 1]);
        $event = $service->events->patch($calendarId, $event->id, $event, ['conferenceDataVersion' => 1]);

        return $event->hangoutLink;
    }

    /**
     * Get Status Label for Email Notification
     */
    private function getStatusLabel($status)
    {
        return match ($status) {
            STATUS_ACTIVE => __('Completed'),
            STATUS_PENDING => __('Pending'),
            STATUS_PROCESSING => __('Processing'),
            STATUS_REFUNDED => __('Refunded'),
            default => ''
        };
    }

}
