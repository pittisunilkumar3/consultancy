<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\EmailNotify;
use App\Models\Appointment;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Event;
use App\Models\Gateway;
use App\Models\Payment;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use function Clue\StreamFilter\fun;

class OrderController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        // Retrieve the orderType from the request
        $orderType = $request->query('orderType');

        // Check if request is AJAX
        if ($request->ajax()) {
            $status = null;
            if ($request->status == 'Paid') {
                $status = PAYMENT_STATUS_PAID;
            } elseif ($request->status == 'Pending') {
                $status = PAYMENT_STATUS_PENDING;
            } elseif ($request->status == 'Cancelled') {
                $status = PAYMENT_STATUS_CANCELLED;
            }

            // Define a mapping for order types to their respective models and fields
            $typeMapping = [
                'event' => [
                    'model' => Event::class,
                    'titleField' => 'events.title'
                ],
                'course' => [
                    'model' => Course::class,
                    'titleField' => 'courses.title'
                ],
                'consultation' => [
                    'model' => Appointment::class, // Use Appointment model for consultation type
                    'titleField' => "CONCAT(consultants.first_name, ' ', consultants.last_name)"
                ],
            ];

            $typeConfig = $typeMapping[$orderType] ?? null;

            if (!$typeConfig) {
                abort(404, 'Order type not found');
            }

            $paymentableType = $typeConfig['model'];

            // Retrieve payments based on type and apply filters
            $orders = Payment::with(['paymentable', 'user', 'gateway', 'bank'])
                ->where('paymentable_type', $paymentableType)
                ->when($status !== null, function ($query) use ($status) {
                    return $query->where('payments.payment_status', $status);
                })
                ->where(function ($query) {
                    $query->whereNotIn('gateways.slug', ['bank', 'cash'])
                        ->whereIn('payments.payment_status', [STATUS_ACTIVE, STATUS_REJECT])
                        ->orWhere(function ($query) {
                            $query->whereIn('gateways.slug', ['bank', 'cash']);
                        });
                })
                ->when($request->item_id, function ($query) use ($request, $orderType) {
                    if ($orderType === 'consultation') {
                        // For consultations, filter by consultant's ID
                        return $query->where('consultants.id', $request->item_id);
                    } else {
                        // For other types, filter by paymentable_id
                        return $query->where('paymentable_id', $request->item_id);
                    }
                })
                ->when($request->search_key, function ($query) use ($request) {
                    $searchKey = $request->search_key;
                    $query->where(function ($query) use ($searchKey) {
                        $query->where('payments.tnxId', 'like', "%$searchKey%")
                            ->orWhere('customer.first_name', 'like', "%$searchKey%")
                            ->orWhere('customer.last_name', 'like', "%$searchKey%")
                            ->orWhere('customer.email', 'like', "%$searchKey%")
                            ->orWhere('gateways.title', 'like', "%$searchKey%");
                    });
                })
                ->select([
                    'payments.*',
                    DB::raw($typeConfig['titleField'] . ' as itemTitle'),
                    DB::raw("CONCAT(customer.first_name, ' ', customer.last_name) as customerName"),
                    'customer.email as customerEmail',
                    'gateways.title as gatewayName',
                    'gateways.slug as gatewaySlug',
                    'banks.name as bankName'
                ])
                ->leftJoin('users as customer', 'payments.user_id', '=', 'customer.id')
                ->leftJoin('gateways', 'payments.gateway_id', '=', 'gateways.id')
                ->leftJoin('banks', 'payments.bank_id', '=', 'banks.id')
                ->when($paymentableType === Event::class, function ($query) {
                    $query->leftJoin('events', 'payments.paymentable_id', '=', 'events.id');
                })
                ->when($paymentableType === Course::class, function ($query) {
                    $query->leftJoin('courses', 'payments.paymentable_id', '=', 'courses.id');
                })
                ->when($paymentableType === Appointment::class, function ($query) {
                    // Join the appointments and users tables for consultation type
                    $query->leftJoin('appointments', 'payments.paymentable_id', '=', 'appointments.id')
                        ->leftJoin('users as consultants', 'appointments.consulter_id', '=', 'consultants.id');
                })->orderBy('payments.id', 'DESC');

            // Return DataTable response for AJAX
            return datatables($orders)
                ->addColumn('date', fn($order) => $order->created_at->format('Y-m-d h:i'))
                ->addColumn('amount', fn($order) => showPrice($order->sub_total))
                ->editColumn('gatewayName', function($order){
                    if($order->sub_total <1){
                        return __('N/A');
                    }
                    return $order->gatewayName;
                })
                ->addColumn('payment_info', function ($order) {
                    if($order->sub_total <1){
                        return __('N/A');
                    }
                    return $order->gatewaySlug === 'cash'
                        ? '<div class="text-nowrap">' . __('Cash Paid') . '</div>'
                        : '<div class="text-nowrap">' .
                        __('Bank Name') . ' : ' . $order->bankName . '<br>' .
                        __('Deposit Slip') . ' : ' . '<a href="' . (getFileUrl($order->deposit_slip)) . '" target="_blank">' . __('View slip') . '</a>' .
                        '</div>';
                })
                ->addColumn('status', function ($order) {
                    if($order->sub_total <1){
                        return __('Free');
                    }
                    return match ($order->payment_status) {
                        PAYMENT_STATUS_PAID => '<div class="zBadge zBadge-paid">' . __('Paid') . '</div>',
                        PAYMENT_STATUS_PENDING => '<div class="zBadge zBadge-pending">' . __('Pending') . '</div>',
                        default => '<div class="zBadge zBadge-cancelled">' . __('Cancelled') . '</div>',
                    };
                })
                ->addColumn('action', function ($data) {
                    return '<div class="d-flex align-items-center g-10 justify-content-end">
                        <button onclick="getEditModal(\'' . route('admin.orders.status_change_modal', $data->id) . '\', \'#order-status-change-modal\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Edit">
                            ' . view('partials.icons.edit')->render() . '
                        </button>
                    </div>';
                })
                ->rawColumns(['status', 'action', 'payment_info'])
                ->make(true);
        }

        // Non-AJAX request: Load the page with the order type data for the filter dropdown
        $typeMapping = [
            'event' => [
                'pageTitle' => __('Event Orders'),
                'filterFieldTitle' => __('Event'),
                'items' => Event::all(),
                'showFlag' => 'showManageEvent',
                'activeFlag' => 'activeEventOrder'
            ],
            'course' => [
                'pageTitle' => __('Course Orders'),
                'filterFieldTitle' => __('Course'),
                'items' => Course::all(),
                'showFlag' => 'showManageCourse',
                'activeFlag' => 'activeCourseOrder'
            ],
            'consultation' => [
                'pageTitle' => __('Consultation Orders'),
                'filterFieldTitle' => __('Consultant'),
                'items' => User::where(['role' => USER_ROLE_CONSULTANT])->get(),
                'showFlag' => 'showManageConsultation',
                'activeFlag' => 'activeAppointmentOrder'
            ],
        ];

        $typeConfig = $typeMapping[$orderType] ?? null;

        if (!$typeConfig) {
            abort(404, 'Order type not found');
        }

        // Set flags dynamically based on the order type
        $data[$typeConfig['showFlag']] = 'show';
        $data[$typeConfig['activeFlag']] = 'active';
        $data['pageTitle'] = $typeConfig['pageTitle'];
        $data['items'] = $typeConfig['items'];
        $data['orderType'] = $orderType;
        $data['filterFieldTitle'] = $typeConfig['filterFieldTitle'];
        return view('admin.orders.index', $data);
    }

    public function statusModal($id)
    {
        $data['order'] = Payment::where('id', $id)->first();
        $data['gateways'] = Gateway::where('status', STATUS_ACTIVE)->get();
        return view('admin.orders.status_change_modal', $data);
    }

    public function statusChange(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $order = Payment::where('id', $id)->with('gateway')->first();
            $order->update(['payment_status' => $request->payment_status, 'paid_by' => auth()->id()]);

            if ($request->payment_status == PAYMENT_STATUS_PAID) {
                $order->paymentId = sha1($order->id);
                $order->payment_time = now();
                $order->save();

                $reference = $order->paymentable;
                $className = class_basename(get_class($reference));

                // Notification and custom actions based on type
                if ($className == 'Event') {
                    $type = TRANSACTION_EVENT;
                    $purpose = __('Event reservation payment for ') . $reference->title;

                    if (getOption('app_mail_status')) {
                        $link = route('student.event.list');
                        $viewData = [
                            '{{name}}' => $order->user->name,
                            '{{email}}' => $order->user->email,
                            '{{link}}' => $link
                        ];
                        $data = getEmailTemplate('event-booking-confirmation', $viewData);
                        Mail::to($order->user->email)->send(new EmailNotify($data));
                    }

                } elseif ($className == 'Course') {
                    CourseEnrollment::create([
                        'user_id' => $order->user_id,
                        'course_id' => $reference->id,
                    ]);

                    $purpose = __('Course purchase payment for ') . $reference->title;
                    $type = TRANSACTION_COURSE;

                    if (getOption('app_mail_status')) {
                        $link = route('student.my_courses.list');
                        $viewData = [
                            '{{name}}' => $order->user->name,
                            '{{email}}' => $order->user->email,
                            '{{link}}' => $link
                        ];
                        $data = getEmailTemplate('course-purchase-success', $viewData);
                        Mail::to($order->user->email)->send(new EmailNotify($data));
                    }

                } elseif ($className == 'Appointment') {
                    $purpose = __('Consultation payment for ') . $reference->title;
                    $type = TRANSACTION_CONSULTATION;

                    if (getOption('app_mail_status')) {
                        $link = route('student.consultation-appointment.list');
                        $viewData = [
                            '{{name}}' => $order->user->name,
                            '{{email}}' => $order->user->email,
                            '{{link}}' => $link
                        ];
                        $data = getEmailTemplate('consultation-booking-success', $viewData);
                        Mail::to($order->user->email)->send(new EmailNotify($data));
                    }

                    // Notify the consultant
                    $consultant = $reference->consulter;
                    $consultationPurpose = __('You have a new consultation booking payment for ') . $reference->title;

                    // Send consultant notification
                    setCommonNotification(
                        $consultant->id,
                        __('New Consultation Booked'),
                        $consultationPurpose,
                        route('consultant.consultations.appointments.index', ['appointment' => $reference->id])
                    );

                    // Send email notification to consultant if enabled
                    if (getOption('app_mail_status')) {
                        $consultantLink = route('consultant.consultations.appointments.index', ['appointment' => $reference->id]);
                        $consultantViewData = [
                            '{{name}}' => $consultant->name,
                            '{{student_name}}' => $order->user->name,
                            '{{link}}' => $consultantLink
                        ];
                        $consultantData = getEmailTemplate('consultation-booking-consultant-notification', $consultantViewData);
                        Mail::to($consultant->email)->send(new EmailNotify($consultantData));
                    }
                }

                // Create Transaction Record
                $order->transaction()->create([
                    'user_id' => $order->user_id,
                    'reference_id' => $reference->id ?? null,
                    'type' => $type,
                    'tnxId' => $order->tnxId,
                    'amount' => $order->grand_total,
                    'purpose' => $purpose,
                    'payment_time' => $order->payment_time,
                    'payment_method' => $order->gateway->title
                ]);

                // Notify user about successful payment
                setCommonNotification($order->user_id, __('Payment has been successfully'), $purpose, route('student.transactions'));
            }

            DB::commit();
            return $this->success([], __('Updated Successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }
}
