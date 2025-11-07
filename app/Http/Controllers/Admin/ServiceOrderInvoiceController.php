<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\EmailNotify;
use App\Models\Currency;
use App\Models\Gateway;
use App\Models\GatewayCurrency;
use App\Models\Service;
use App\Models\StudentServiceOrder;
use App\Models\StudentServiceOrderInvoice;
use App\Models\User;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ServiceOrderInvoiceController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        // Check if request is AJAX
        if ($request->ajax()) {
            $status = $request->query('status');
            $searchKey = $request->query('search_key');
            $serviceOrder = $request->query('service_id');

            $serviceOrderInvoices = StudentServiceOrderInvoice::with(['student', 'service_order', 'service', 'payment.gateway']) // Eager load related models
            ->when(auth()->user()->role === USER_ROLE_STUDENT, function ($query) {
                return $query->where('student_id', auth()->user()->id);
            })->when($status && $status !== 'All', function ($query) use ($status) {
                $statusMapping = [
                    'Pending' => PAYMENT_STATUS_PENDING,
                    'Paid' => PAYMENT_STATUS_PAID,
                    'Cancelled' => PAYMENT_STATUS_CANCELLED,
                ];
                return $query->where('payment_status', $statusMapping[$status] ?? $status);
            })->when($searchKey, function ($query) use ($searchKey) {
                return $query->where(function ($q) use ($searchKey) {
                    $q->where('invoiceID', 'like', "%{$searchKey}%")
                        ->orWhereHas('student', function ($subQuery) use ($searchKey) {
                            $subQuery->where('first_name', 'like', "%{$searchKey}%")
                                ->orWhere('last_name', 'like', "%{$searchKey}%");
                        })
                        ->orWhereHas('service_order', function ($subQuery) use ($searchKey) {
                            $subQuery->where('orderID', 'like', "%{$searchKey}%");
                        });
                });
            })->when($serviceOrder, function ($query) use ($serviceOrder) {
                return $query->whereHas('service_order', function ($q) use ($serviceOrder) {
                    $q->where('service_id', $serviceOrder);
                });
            })->orderBy('id', 'DESC');


            return datatables($serviceOrderInvoices)
                ->addColumn('student', fn($serviceOrderInvoice) => $serviceOrderInvoice->student->name)
                ->addColumn('service', fn($serviceOrderInvoice) => $serviceOrderInvoice->service->title)
                ->editColumn('due_date', function ($serviceOrderInvoice) {
                    return Carbon::parse($serviceOrderInvoice->due_date)->format('Y-m-d');
                })
                ->addColumn('orderID', function ($serviceOrderInvoice) {
                    return $serviceOrderInvoice->service_order->orderID;
                })
                ->editColumn('payment_status', function ($serviceOrderInvoice) {
                    return match ($serviceOrderInvoice->payment_status) {
                        PAYMENT_STATUS_PAID => '<div class="zBadge zBadge-paid">' . __('Paid') . '</div>',
                        PAYMENT_STATUS_PENDING => '<div class="zBadge zBadge-pending">' . __('Pending') . '</div>',
                        PAYMENT_STATUS_CANCELLED => '<div class="zBadge zBadge-cancelled">' . __('Cancelled') . '</div>',
                        default => '<div class="zBadge zBadge-cancelled">' . __('Unknown') . '</div>',
                    };

                })->editColumn('payment_info', function ($serviceOrderInvoice) {
                    $statusHtml = '';

                    // Check if payment is pending and there is a payment relation
                    if ($serviceOrderInvoice->payment_status == PAYMENT_STATUS_PENDING && $serviceOrderInvoice->payment) {
                        $payment = $serviceOrderInvoice->payment;

                        // Append payment info based on gateway type
                        if ($payment?->gateway->slug === 'cash') {
                            $statusHtml .= '<div class="text-nowrap">' . __('Cash Paid') . '</div>';
                        } elseif ($payment?->gateway->slug === 'bank') {
                            $statusHtml .= '<div class="text-nowrap">' .
                                __('Bank Name') . ' : ' . $payment?->bankName . '<br>' .
                                __('Deposit Slip') . ' : ' . '<a href="' . getFileUrl($payment?->deposit_slip) . '" target="_blank">' . __('View slip') . '</a>' .
                                '</div>';
                        } else {
                            return __('Gateway') . ' : ' . $payment->gateway?->title;
                        }
                    } elseif ($serviceOrderInvoice->payment) {
                        return __('Gateway') . ' : ' . $serviceOrderInvoice->payment?->gateway?->title;
                    } else {
                        return __('N/A');
                    }

                    return $statusHtml;
                })->editColumn('total', function ($serviceOrderInvoice) {
                    return showPrice($serviceOrderInvoice->total);
                })
                ->addColumn('action', function ($serviceOrderInvoice) {
                    $buttons = '<div class="d-flex align-items-center g-10 justify-content-end">';

                    // View button (always available)
                    $buttons .= '<button onclick="getEditModal(\'' . route(getPrefix() . '.service_invoices.details', encodeId($serviceOrderInvoice->id)) . '\', \'#view-modal\')" type="button"
                                class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25"
                                title="' . __('View') . '">' .
                        view('partials.icons.view')->render() .
                        '</button>';

                    if (auth()->user()->role === USER_ROLE_STUDENT) {
                        // Pay button only if payment status is Pending
                        if ($serviceOrderInvoice->payment_status === PAYMENT_STATUS_PENDING) {
                            $buttons .= '<a href="' . route('student.checkout', ['type' => 'service-invoice', 'id' => encodeId($serviceOrderInvoice->id)]) . '" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25"
                                title="' . __('Pay') . '">' .
                                view('partials.icons.pay')->render() .
                                '</a>';
                        }
                    } else {
                        // For non-student roles
                        if ($serviceOrderInvoice->payment_status !== PAYMENT_STATUS_PAID && $serviceOrderInvoice->payment_status !== PAYMENT_STATUS_CANCELLED) {
                            // Edit button
                            $buttons .= '<button onclick="getEditModal(\'' . route('admin.service_invoices.edit', encodeId($serviceOrderInvoice->id)) . '\', \'#edit-modal\')"
                                    type="button" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25"
                                    title="' . __('Edit') . '">' .
                                view('partials.icons.edit')->render() .
                                '</button>';

                            // Change Status button
                            $buttons .= '<button onclick="getEditModal(\'' . route('admin.service_invoices.status_change_modal', encodeId($serviceOrderInvoice->id)) . '\', \'#edit-modal\')"
                                    type="button" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25"
                                    title="' . __('Change Status') . '">' .
                                view('partials.icons.change_status')->render() .
                                '</button>';
                            // Delete button
                            $buttons .= '<button onclick="deleteItem(\'' . route(getPrefix() . '.service_invoices.delete', encodeId($serviceOrderInvoice->id)) . '\')"
                                    class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25"
                                    title="' . __('Delete') . '">' .
                                view('partials.icons.delete')->render() .
                                '</button>';
                        }

                    }

                    $buttons .= '</div>';

                    return $buttons;
                })
                ->rawColumns(['payment_status', 'action', 'payment_info'])
                ->make(true);
        }

        $data['pageTitle'] = __('Service Order Invoice');
        $data['showServiceOrderInvoice'] = 'active';
        $data['students'] = User::where('role', USER_ROLE_STUDENT)->where('status', STATUS_ACTIVE)->get();
        $data['services'] = Service::select('id', 'title', 'price', 'status')->get();
        $data['serviceOrders'] = collect([]);
        return view('admin.services.invoices.index', $data);
    }

    public function edit($id)
    {
        $serviceOrderInvoice = StudentServiceOrderInvoice::with(['service_order'])->findOrFail(decodeId($id));

        // Fetch all service orders for the related student
        $serviceOrders = StudentServiceOrder::where('student_id', $serviceOrderInvoice->student_id)
            ->join('services', 'services.id', '=', 'student_service_orders.service_id') // Adjust the foreign key if necessary
            ->where('payment_status', PAYMENT_STATUS_PAID)
            ->where('working_status', '!=', WORKING_STATUS_CANCELED)
            ->selectRaw("student_service_orders.id, CONCAT(student_service_orders.orderID, ' - ', services.title) as orderID")
            ->get();

        $data = [
            'serviceOrderInvoice' => $serviceOrderInvoice,
            'students' => User::where('role', USER_ROLE_STUDENT)->where('status', STATUS_ACTIVE)->get(),
            'serviceOrders' => $serviceOrders, // Pass the service orders
        ];

        return view('admin.services.invoices.edit', $data);
    }

    public function store(Request $request, $id = null)
    {
        $request->validate([
            'student_id' => 'required',
            'service_order_id' => 'required',
            'details' => 'required|string|min:10',
            'amount' => 'required|numeric|min:1',
            'due_date' => 'nullable|date',
        ]);

        DB::beginTransaction();

        try {
            $id = decodeId($id);

            // Retrieve the existing order if an ID is provided
            $existingOrderInvoice = $id ? StudentServiceOrderInvoice::find($id) : null;

            // If payment status is PAID, prevent the update
            if ($existingOrderInvoice && $existingOrderInvoice->payment_status == PAYMENT_STATUS_PAID) {
                return $this->error([], 'This invoice has already been paid and cannot be updated.');
            }

            $studentServiceOrder = StudentServiceOrder::where('id', $request->service_order_id)->first();

            StudentServiceOrderInvoice::updateOrCreate(
                [
                    'id' => $id
                ],
                [
                    'student_id' => $request->student_id,
                    'student_service_order_id' => $request->service_order_id,
                    'service_id' => $studentServiceOrder->service_id,
                    'due_date' => $request->due_date,
                    'payable_amount' => $request->amount,
                    'total' => $request->amount,
                    'details' => $request->details,
                    'payment_status' => $existingOrderInvoice ? $existingOrderInvoice->payment_status : PAYMENT_STATUS_PENDING,
                    'created_by' => $existingOrderInvoice ? $existingOrderInvoice->created_by : auth()->id(),
                ]
            );

            DB::commit();
            return $this->success([], getMessage(CREATED_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function delete($id)
    {
        try {
            StudentServiceOrderInvoice::where('student_service_order_id', decodeId($id))->delete();
            $message = getMessage(DELETED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function getServiceOrder($student_id)
    {
        $universities = StudentServiceOrder::where('student_id', $student_id)
            ->where('payment_status', PAYMENT_STATUS_PAID)
            ->where('working_status', '!=', WORKING_STATUS_CANCELED)
            ->join('services', 'services.id', '=', 'student_service_orders.service_id') // Adjust the foreign key if necessary
            ->selectRaw("student_service_orders.id, CONCAT(student_service_orders.orderID, ' - ', services.title) as orderID")
            ->get();

        return $this->success($universities, __('SSuccessfully retrieved'));
    }

    public function statusChangeModal($id)
    {
        $data['orderInvoice'] = StudentServiceOrderInvoice::with(['service_order', 'payment'])->findOrFail(decodeId($id));
        $data['gateways'] = Gateway::where('status', STATUS_ACTIVE)->get();
        return view('admin.services.invoices.status_change_modal', $data);
    }

    public function statusChange(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|integer',
            'gateway' => 'required_if:payment_status,1',
            'gateway_currency' => 'required_if:payment_status,1',
        ], [
            'gateway.required_if' => __('The gateway is required when the payment status is set to Paid.'),
            'gateway_currency.required_if' => __('The currency is required when the payment status is set to Paid.'),
        ]);

        DB::beginTransaction();
        try {
            $serviceOrderInvoice = StudentServiceOrderInvoice::findOrFail(decodeId($id));
            $serviceOrderInvoice->payment_status = $request->payment_status;
            $serviceOrderInvoice->save();

            $serviceOrder = StudentServiceOrder::where('student_service_order_invoice_id', $serviceOrderInvoice->id)->first();

            if ($serviceOrder) {
                $serviceOrder->update([
                    'payment_status' => $request->payment_status
                ]);
            }


            $gateway = Gateway::where(['slug' => $request->gateway, 'status' => ACTIVE])->first();

            $paymentTime = now();
            if ($request->payment_status == PAYMENT_STATUS_PAID) {
                if ($serviceOrder) {
                    $serviceOrder->working_status = WORKING_STATUS_PROCESSING;
                    $serviceOrder->save();
                }

                if ($serviceOrderInvoice->payment_status == PAYMENT_STATUS_PAID && $serviceOrderInvoice->payment && $serviceOrderInvoice->payment?->gateway?->slug == $request->gateway) {
                    $serviceOrderInvoice->payment->update([
                        'paymentId' => sha1($serviceOrderInvoice->payment->id),
                        'payment_status' => PAYMENT_STATUS_PAID,
                        'payment_time' => $paymentTime,
                        'paid_by' => auth()->id()
                    ]);

                    $order = $serviceOrderInvoice->payment;

                } else {
                    if (is_null($gateway)) {
                        return $this->error(__('Gateway Not Found'));
                    }

                    // Validate the gateway currency
                    $gatewayCurrency = GatewayCurrency::where(['gateway_id' => $gateway->id, 'currency' => $request->gateway_currency])->first();
                    if (is_null($gatewayCurrency)) {
                        return $this->error(__('Gateway Currency Not Found'));
                    }

                    $order = $this->placeOrder(
                        $serviceOrderInvoice,
                        $serviceOrderInvoice->payable_amount,
                        $gateway,
                        $gatewayCurrency,
                    );

                    $order->update([
                        'paymentId' => sha1($order->id),
                        'payment_status' => PAYMENT_STATUS_PAID,
                        'payment_time' => $paymentTime,
                        'paid_by' => auth()->id()
                    ]);
                }

                // Notify user about successful payment
                setCommonNotification(
                    $order->user_id,
                    __('Payment has been successfully received'),
                    $purpose = __('Service purchase payment for ') . $serviceOrderInvoice->service?->title,
                    route('student.transactions')
                );

                // Send email to the user
                if (getOption('app_mail_status')) {
                    $viewData = [
                        '{{name}}' => $order->user->name,
                        '{{email}}' => $order->user->email,
                        '{{link}}' => route('student.service_orders')
                    ];

                    $userEmailData = getEmailTemplate('invoice-payment-success', $viewData);
                    Mail::to($order->user->email)->send(new EmailNotify($userEmailData));
                }

                // Record the transaction
                $order->transaction()->create([
                    'user_id' => $order->user_id,
                    'reference_id' => $reference->id ?? null,
                    'type' => TRANSACTION_INVOICE,
                    'tnxId' => $order->tnxId,
                    'amount' => $order->grand_total,
                    'purpose' => $purpose,
                    'payment_time' => $paymentTime,
                    'payment_method' => $gateway->title
                ]);

            }

            DB::commit();
            return $this->success([], __('Updated Successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], __('Something went wrong! Please try again.'));
        }
    }

    public function placeOrder($object, $price, $gateway, $gatewayCurrency, $bank_id = null, $deposit_slip_id = null)
    {
        return $object->payments()->create([
            'user_id' => auth()->id(),
            'tnxId' => uniqid(),
            'amount' => $price,
            'system_currency' => Currency::where('current_currency', 'on')->first()->currency_code,
            'gateway_id' => $gateway->id,
            'payment_currency' => $gatewayCurrency->currency,
            'conversion_rate' => $gatewayCurrency->conversion_rate,
            'sub_total' => $price,
            'grand_total' => $price,
            'grand_total_with_conversation_rate' => $price * $gatewayCurrency->conversion_rate,
            'bank_id' => $bank_id,
            'deposit_slip' => $deposit_slip_id,
            'payment_details' => json_encode($object),
            'payment_status' => PAYMENT_STATUS_PENDING
        ]);
    }

    public function invoiceDetails($id)
    {
        $data['orderInvoice'] = StudentServiceOrderInvoice::find(decodeId($id));

        return view('admin.services.invoices.invoice-details', $data);
    }

    public function invoicePrint($id)
    {
        $data['title'] = __('Invoice Print');
        $data['orderInvoice'] = StudentServiceOrderInvoice::find(decodeId($id));
        return view('admin.services.invoices.print-invoice', $data);
    }
}
