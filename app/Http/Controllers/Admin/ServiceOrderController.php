<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\EmailNotify;
use App\Models\Service;
use App\Models\StudentServiceOrder;
use App\Models\StudentServiceOrderAssignee;
use App\Models\StudentServiceOrderFile;
use App\Models\StudentServiceOrderInvoice;
use App\Models\StudentServiceOrderNote;
use App\Models\User;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ServiceOrderController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        // Check if request is AJAX
        if ($request->ajax()) {
            $status = $request->query('status');
            $searchKey = $request->query('search_key');
            $serviceOrder = $request->query('service_id');

            $serviceOrders = StudentServiceOrder::when(auth()->user()->role === USER_ROLE_STUDENT, function ($query) {
                return $query->where('student_service_orders.student_id', auth()->id());
            })->when(auth()->user()->role === USER_ROLE_STAFF, function ($query) {
                return $query->join('student_service_order_assignees', 'student_service_order_assignees.student_service_order_id', '=', 'student_service_orders.id')
                ->where('student_service_order_assignees.assigned_to', auth()->id());
            })->when($status && $status !== 'All', function ($query) use ($status) {
                $statusMapping = [
                    'Pending' => WORKING_STATUS_PENDING,
                    'Processing' => WORKING_STATUS_PROCESSING,
                    'Succeed' => WORKING_STATUS_SUCCEED,
                    'Failed' => WORKING_STATUS_FAILED,
                    'Cancelled' => WORKING_STATUS_CANCELED,
                    'Hold' => WORKING_STATUS_HOLD,
                ];
                return $query->where('student_service_orders.working_status', $statusMapping[$status] ?? $status);
            })->when($searchKey, function ($query) use ($searchKey) {
                return $query->where(function ($q) use ($searchKey) {
                    $q->where('student_service_orders.orderID', 'like', "%{$searchKey}%")
                        ->orWhereHas('student', function ($subQuery) use ($searchKey) {
                            $subQuery->where('first_name', 'like', "%{$searchKey}%")
                                ->orWhere('last_name', 'like', "%{$searchKey}%");
                        });
                });
            })->when($serviceOrder, function ($query) use ($serviceOrder) {
                return $query->where('student_service_orders.service_id', $serviceOrder);
            })->select('student_service_orders.*')
            ->orderBy('student_service_orders.id', 'DESC');

            return datatables($serviceOrders)
                ->addColumn('student', fn($serviceOrder) => $serviceOrder->student->name)
                ->addColumn('service', fn($serviceOrder) => $serviceOrder->service->title)
                ->editColumn('date', function ($serviceOrder) {
                    return $serviceOrder->created_at->format('Y-m-d');
                })
                ->addColumn('status', function ($serviceOrder) {
                    $statusLabels = [
                        WORKING_STATUS_PENDING => __('Pending'),
                        WORKING_STATUS_PROCESSING => __('Processing'),
                        WORKING_STATUS_SUCCEED => __('Succeed'),
                        WORKING_STATUS_FAILED => __('Failed'),
                        WORKING_STATUS_CANCELED => __('Cancelled'),
                        WORKING_STATUS_HOLD => __('Hold'),
                    ];

                    $statusClass = [
                        WORKING_STATUS_PENDING => 'zBadge-pending',
                        WORKING_STATUS_PROCESSING => 'zBadge-processing',
                        WORKING_STATUS_SUCCEED => 'zBadge-succeed',
                        WORKING_STATUS_FAILED => 'zBadge-failed',
                        WORKING_STATUS_CANCELED => 'zBadge-cancelled',
                        WORKING_STATUS_HOLD => 'zBadge-hold',
                    ];

                    $status = $serviceOrder->working_status;
                    $label = $statusLabels[$status] ?? $status;
                    $class = $statusClass[$status] ?? 'zBadge-default';

                    return "<p class='zBadge {$class}'>" . __($label) . "</p>";
                })->editColumn('payment_status', function ($serviceOrder) {
                    return match ($serviceOrder->payment_status) {
                        PAYMENT_STATUS_PAID => '<div class="zBadge zBadge-paid">' . __('Paid') . '</div>',
                        PAYMENT_STATUS_PENDING => '<div class="zBadge zBadge-pending">' . __('Pending') . '</div>',
                        PAYMENT_STATUS_CANCELLED => '<div class="zBadge zBadge-cancelled">' . __('Cancelled') . '</div>',
                        default => '<div class="zBadge zBadge-cancelled">' . __('Unknown') . '</div>',
                    };
                })->editColumn('transaction_amount', function ($serviceOrder) {
                    return showPrice($serviceOrder->total);
                })
                ->addColumn('order_board', function ($serviceOrder) {
                    return '<div class="align-items-center d-flex g-10 justify-content-center justify-content-end">
                        <a href="' . route(getPrefix() . '.service_orders.task-board.index', encodeId($serviceOrder->id)) . '" class="flipBtn sf-flipBtn-brand trackProgress-btn" title="' . __('Track Progress') . '"><span>' . __('Track Progress') . '</span><span>' . __('Track Progress') . '</span><span>' . __('Track Progress') . '</span></a>
                    </div>';
                })->addColumn('action', function ($serviceOrder) {
                    if (auth()->user()->role === USER_ROLE_STUDENT) {
                        return '';
                    }
                    return '<div class="d-flex align-items-center g-10 justify-content-end">
                        <button onclick="getEditModal(\'' . route('admin.service_orders.edit', encodeId($serviceOrder->id)) . '\', \'#edit-modal\')" type="button" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="' . __('Edit') . '">
                            ' . view('partials.icons.edit')->render() . '
                        </button>
                        <button onclick="deleteItem(\'' . route(getPrefix() . '.service_orders.delete', encodeId($serviceOrder->id)) . '\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="' . __('Delete') . '">
                            ' . view('partials.icons.delete')->render() . '
                        </button>
                    </div>';
                })->rawColumns(['status', 'payment_status', 'action', 'order_board'])
                ->make(true);
        }

        $data['pageTitle'] = __('Service Order');
        $data['showServiceOrder'] = 'active';
        $data['services'] = Service::select('id', 'title', 'price', 'status')->get();
        $data['activeServices'] = $data['services']->where('status', STATUS_ACTIVE);
        $data['students'] = User::where('role', USER_ROLE_STUDENT)->where('status', STATUS_ACTIVE)->get();
        return view('admin.services.orders.index', $data);
    }

    public function edit($id)
    {
        $data['serviceOrder'] = StudentServiceOrder::findOrFail(decodeId($id));
        $data['services'] = Service::select('id', 'title', 'price', 'status')->get();
        $data['activeServices'] = $data['services']->where('status', STATUS_ACTIVE);
        $data['students'] = User::where('role', USER_ROLE_STUDENT)->where('status', STATUS_ACTIVE)->get();
        return view('admin.services.orders.edit', $data);
    }

    public function store(Request $request, $id = null)
    {
        $request->validate([
            'service_id' => 'required',
            'student_id' => 'required',
            'amount' => 'required|numeric|min:1',
            'discount' => 'nullable|numeric|min:0|lt:amount',
            'sub_total' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();

        try {
            $id = decodeId($id);
            // Retrieve the existing order if an ID is provided
            $existingOrder = $id ? StudentServiceOrder::find($id) : null;
            // Create or update the order
            $studentServiceOrder = StudentServiceOrder::updateOrCreate(
                [
                    'id' => $id
                ],
                [
                    'student_id' => $request->student_id,
                    'amount' => $request->amount,
                    'discount' => $request->discount,
                    'subtotal' => $request->sub_total,
                    'total' => $request->sub_total,
                    'payment_status' => $existingOrder ? $existingOrder->payment_status : PAYMENT_STATUS_PENDING,
                    'working_status' => $existingOrder ? $existingOrder->working_status : WORKING_STATUS_PENDING,
                    'created_by' => $existingOrder ? $existingOrder->created_by : auth()->id(),
                    'service_id' => $request->service_id,
                ]
            );

            // Create or update invoices only if payment_status is not PAID
            if ($studentServiceOrder->payment_status !== PAYMENT_STATUS_PAID) {
                $invoiceData = [
                    'student_id' => $request->student_id,
                    'student_service_order_id' => $studentServiceOrder->id,
                    'service_id' => $studentServiceOrder->service_id,
                    'payable_amount' => $studentServiceOrder->total,
                    'total' => $studentServiceOrder->total,
                    'payment_status' => PAYMENT_STATUS_PENDING,
                ];

                if ($id) {
                    // Check if the invoice exists
                    $existingInvoice = StudentServiceOrderInvoice::where('student_service_order_id', $id)->first();

                    if ($existingInvoice) {
                        // Retain existing values
                        $invoiceData['due_date'] = $existingInvoice->due_date;
                        $invoiceData['payment_status'] = $existingInvoice->payment_status;

                        // Update the existing invoice
                        $existingInvoice->update($invoiceData);
                    } else {
                        // Set default values for new invoices
                        $invoiceData['due_date'] = now();
                        $invoiceData['created_by'] = auth()->id();

                        // Create a new invoice
                        $studentServiceOrderInvoice = StudentServiceOrderInvoice::create($invoiceData);
                    }
                } else {
                    // Set default values for new invoices
                    $invoiceData['due_date'] = now();
                    $invoiceData['created_by'] = auth()->id();

                    // Create a new invoice
                    $studentServiceOrderInvoice = StudentServiceOrderInvoice::create($invoiceData);

                    if(auth()->user()->role == USER_ROLE_STAFF){
                        StudentServiceOrderAssignee::updateOrCreate(
                            [
                                'student_service_order_id' => $studentServiceOrder->id,
                                'assigned_to' => auth()->id(),
                            ],
                            [
                                'assigned_by' => auth()->id(),
                                'is_active' => ACTIVE
                            ]
                        );
                    }
                }

                // Update the student service order with the invoice ID (if newly created)
                if (isset($studentServiceOrderInvoice)) {
                    $studentServiceOrder->update([
                        'student_service_order_invoice_id' => $studentServiceOrderInvoice->id,
                    ]);
                }
            }

            DB::commit();
            return $this->success([], $existingOrder? getMessage(UPDATED_SUCCESSFULLY) :  getMessage(CREATED_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function delete($id)
    {
        try {
            StudentServiceOrderInvoice::where('student_service_order_id', decodeId($id))->delete();
            StudentServiceOrder::where('id', decodeId($id))->delete();
            $message = getMessage(DELETED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }


    public function statusChange($order_id, $status)
    {
        DB::beginTransaction();
        try {
            // Find the service order
            $data = StudentServiceOrder::findOrFail(decodeId($order_id));

            // Check if the status is being changed
            if ($data->working_status != $status) {
                // Update the status
                $data->working_status = $status;
                $data->save();

                // Prepare the link for email and notification
                $link = route('student.service_orders.task-board.index', encodeId($data->id));

                // Send email if the application mail status is enabled
                if (getOption('app_mail_status')) {
                    $viewData = [
                        '{{name}}' => $data->student->name,
                        '{{email}}' => $data->student->email,
                        '{{link}}' => $link,
                    ];
                    $templateData = getEmailTemplate('service-order-status-change', $viewData);
                    Mail::to($data->student->email)->send(new EmailNotify($templateData));
                }

                // Set common notification
                setCommonNotification(
                    $data->student_id,
                    $data->orderID . ' ' . __('Service order status change'),
                    __('Service order status has been changed. Please review the link'),
                    $link
                );
            }

            DB::commit();
            return redirect()->back()->with(['success' => 'Status changed successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => SOMETHING_WENT_WRONG]);
        }
    }

    public function assignMember(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->checked_status == 1) {
                $data = StudentServiceOrderAssignee::updateOrCreate(
                    [
                        'student_service_order_id' => $request->order_id,
                        'assigned_to' => $request->member_id
                    ],
                    [
                        'assigned_by' => auth()->id(),
                        'is_active' => ACTIVE
                    ]
                );
            } else {
                if ($request->member_id == auth()->id() && auth()->user()->role == USER_ROLE_STAFF) {
                    throw new \Exception(__('You cannot unassign yourself'));
                }
                $data = StudentServiceOrderAssignee::where(['student_service_order_id' => $request->order_id, 'assigned_to' => $request->member_id])->first();
                $data->delete();
            }
            DB::commit();
            return $this->success($data, 'Assignee Update');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function noteStore(Request $request)
    {

        $request->validate([
            'order_id' => 'required',
            'details' => 'required'
        ]);

        DB::beginTransaction();
        try {
            if ($request->id) {
                $data = StudentServiceOrderNote::find(decodeId($request->id));
                $msg = __("Note Updated Successfully");
            } else {
                $data = new StudentServiceOrderNote();
                $msg = __("Note Created Successfully");
            }
            $data->student_service_order_id = decodeId($request->order_id);
            $data->details = $request->details;
            $data->user_id = auth()->id();
            $data->save();

            DB::commit();
            return $this->success([], $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function noteDelete($id)
    {
        try {
            DB::beginTransaction();
            $data = StudentServiceOrderNote::where('id', decodeId($id))->first();
            $data->delete();
            DB::commit();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function saveFile(Request $request, $serviceOrderId)
    {
        try {
            // Validate the incoming request
            $validated = $request->validate([
                'file_name' => 'required|string|max:255',
                'file' => 'required'
            ]);

            DB::beginTransaction();

            $studentServiceOrder = StudentServiceOrder::findOrFail($serviceOrderId);
            // Save or update the file information
            StudentServiceOrderFile::updateOrCreate(
                [
                    'student_service_order_id' => $serviceOrderId,
                    'student_id' => $studentServiceOrder->student_id,
                    'file' => $validated['file'],
                ],
                [
                    'name' => $validated['file_name'],
                    'file' => $validated['file'],
                    'created_by' => auth()->id(),
                ]
            );

            DB::commit();

            return $this->success([], __('File Saved Successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function documents(Request $request, $service_order_id)
    {
        $data['showServiceOrder'] = 'active';

        // Fetch the service order
        $data['serviceOrder'] = StudentServiceOrder::where('id', decodeId($service_order_id))->firstOrFail();
        $data['pageTitleParent'] =$data['serviceOrder']->orderID;

        // Base query for documents
        $documentsQuery = StudentServiceOrderFile::where('student_service_order_id', $data['serviceOrder']->id)
            ->with('filemanager');

        // Apply search filter if search_key is present
        if ($request->filled('search_key')) {
            $searchKey = '%' . $request->search_key . '%';

            $documentsQuery->where('name', 'like', $searchKey); // Search by name field in StudentServiceOrderFile
        }

        // Paginate the results
        $data['documents'] = $documentsQuery->paginate(12);

        // Set the page title
        $data['pageTitle'] = __('Service Order Documents');

        // Return AJAX partial view for search/pagination
        if ($request->ajax()) {
            return view('admin.services.orders.documents.file-list', $data)->render();
        }

        // Return the full view
        return view('admin.services.orders.documents.list', $data);
    }

    public function editDocument($id)
    {
        $data['file'] = StudentServiceOrderFile::findOrFail($id);
        return view('admin.services.orders.documents.edit', $data);
    }

    public function deleteDocument($id)
    {
        try {
            DB::beginTransaction();
            $data = StudentServiceOrderFile::where('id', $id)->first();
            $data->delete();
            DB::commit();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

}
