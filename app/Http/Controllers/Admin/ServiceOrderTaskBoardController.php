<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceOrderTaskBoardRequest;
use App\Mail\EmailNotify;
use App\Models\StudentServiceOrder;
use App\Models\FileManager;
use App\Models\Label;
use App\Models\StudentServiceOrderFile;
use App\Models\StudentServiceOrderTask;
use App\Models\StudentServiceOrderTaskAssignee;
use App\Models\StudentServiceOrderTaskAttachment;
use App\Models\StudentServiceOrderTaskConversation;
use App\Models\StudentServiceOrderTaskConversationSeen;
use App\Models\User;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ServiceOrderTaskBoardController extends Controller
{
    use ResponseTrait;

    // List all tasks for an order
    public function list($order_id)
    {
        $order_id = decodeId($order_id);
        $data['showServiceOrder'] = 'active';
        $data['pageTitleParent'] = __('Service Order');

        $userId = auth()->id();
        $userRole = auth()->user()->role;

        $data['teamMember'] = User::where(['role' => USER_ROLE_STAFF])->get();
        $data['order'] = StudentServiceOrder::find($order_id);
        $data['pageTitle'] = $data['order']->orderID;
        $data['labels'] = Label::all();

        $orderTasksQuery = StudentServiceOrderTask::where('student_service_order_id', $order_id)
            ->with(['assignees.user', 'labels']);

        if ($userRole == USER_ROLE_STUDENT) {
            $orderTasksQuery->where('student_access', 1);
        } elseif ($userRole == USER_ROLE_STAFF) {
            $orderTasksQuery->join('student_service_order_task_assignees', 'student_service_order_tasks.id', '=', 'student_service_order_task_assignees.order_task_id')
                ->where('student_service_order_task_assignees.assign_to', $userId)
                ->whereNull('student_service_order_task_assignees.deleted_at')
                ->select('student_service_order_tasks.*');
        }

        $data['orderTasks'] = $orderTasksQuery->get();

        $assigneeList = [];
        if ($data['order'] != null) {
            foreach ($data['order']->assignees as $key => $assignee) {
                $assigneeList[$key] = $assignee->assigned_to;
            }
        }
        $data['orderAssignee'] = $assigneeList;

        return view('admin.services.orders.task-board.list', $data);
    }

    // Store or update a task
    public function store(ServiceOrderTaskBoardRequest $request, $order_id, $id = null)
    {
        try {
            DB::beginTransaction();

            $orderTask = $id ? StudentServiceOrderTask::find($id) : new StudentServiceOrderTask;
            $isUpdate = $id ? true : false;

            $orderTask->task_name = $request->task_name;
            $orderTask->student_service_order_id = $order_id;
            $orderTask->description = $request->description;
            $orderTask->start_date = $request->start_date;
            $orderTask->end_date = $request->end_date;
            $orderTask->priority = $request->priority;
            $orderTask->student_access = $request->has_student_access ? 1 : 0;
            $orderTask->created_by = $isUpdate ? $orderTask->created_by : auth()->id();
            $orderTask->status = $request->status;

            $orderTask->save();

            if (!$isUpdate) {
                $orderTask->taskId = generateUniqueTaskboardId($orderTask->id);
                $orderTask->save();
            }

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $newFile = new FileManager();
                    $uploaded = $newFile->upload('attachments', $file);

                    if ($uploaded) {
                        $orderTask->attachments()->create([
                            'file' => $uploaded->id,
                        ]);

                        $studentServiceOrder = StudentServiceOrder::findOrFail($order_id);

                        // Save or update the file information
                        StudentServiceOrderFile::create(
                            [
                                'student_service_order_id' => $order_id,
                                'student_id' => $studentServiceOrder->student_id,
                                'file' => $uploaded->id,
                                'name' => $uploaded->original_name,
                                'created_by' => auth()->id(),
                            ]
                        );

                    } else {
                        DB::rollBack();
                        return $this->error([], __('Something went wrong with the file upload.'));
                    }
                }
            }

            $labelIds = collect($request->labels)->map(function ($labelName) {
                $label = Label::firstOrCreate(['name' => $labelName]);
                return $label->id;
            });

            $orderTask->labels()->sync($labelIds);

            if ($request->assign_member) {
                $assignMemberIds = $request->assign_member;

                $currentAssignees = $orderTask->assignees->pluck('assign_to')->toArray();

                $assigneesToDelete = array_diff($currentAssignees, $assignMemberIds);
                $assigneesToAdd = array_diff($assignMemberIds, $currentAssignees);

                StudentServiceOrderTaskAssignee::where('order_task_id', $orderTask->id)
                    ->whereIn('assign_to', $assigneesToDelete)
                    ->delete();

                foreach ($assigneesToAdd as $userId) {
                    StudentServiceOrderTaskAssignee::create([
                        'order_task_id' => $orderTask->id,
                        'assign_to' => $userId,
                        'assign_by' => auth()->id(),
                    ]);
                }
            }

            DB::commit();
            return $this->success([], $isUpdate ? __('Updated Successfully') : __('Added Successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], __('Something went wrong, please try again'));
        }
    }

    // Update the status of a task
    public function updateStatus(Request $request, $order_id)
    {
        try {
            $task = StudentServiceOrderTask::where('id', $request->task_id)
                ->where('student_service_order_id', $order_id)
                ->first();

            $task->status = $request->new_status;
            $task->save();

            // Prepare the link for email and notification
            $link = route('student.service_orders.task-board.index', encodeId($task->student_service_order_id));

            // Send email if the application mail status is enabled
            if (getOption('app_mail_status')) {
                $viewData = [
                    '{{name}}' => $task->order->student->name,
                    '{{email}}' => $task->order->student->email,
                    '{{link}}' => $link,
                ];
                $templateData = getEmailTemplate('order-task-status-change', $viewData);
                Mail::to($task->order->student->email)->send(new EmailNotify($templateData));
            }

            // Set common notification
            setCommonNotification(
                $task->order->student_id,
                $task->order->orderID . ' ' . __('Order task status change'),
                __('Order task status has been changed. Please review the link'),
                $link
            );

            return $this->success();
        } catch (\Exception $e) {
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }

    // Edit a task
    public function edit($order_id, $id)
    {
        $data['orderTask'] = StudentServiceOrderTask::where('id', $id)
            ->where('student_service_order_id', $order_id)
            ->with(['assignees.user', 'labels', 'order'])
            ->first();

        $data['teamMember'] = User::where(['role' => USER_ROLE_STAFF])->get();
        $data['labels'] = Label::all();
        $data['order'] = $data['orderTask']->order;

        $assigneeList = [];
        if ($data['order'] != null) {
            foreach ($data['order']->assignees as $key => $assignee) {
                $assigneeList[$key] = $assignee->assigned_to;
            }
        }
        $data['orderAssignee'] = $assigneeList;

        return view('admin.services.orders.task-board.edit', $data)->render();
    }

    // Delete a task
    public function delete($order_id, $id)
    {
        try {
            StudentServiceOrderTask::where('id', $id)
                ->where('student_service_order_id', $order_id)
                ->delete();
            return $this->success([], __('Deleted Successfully'));
        } catch (\Exception $e) {
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }

    // View task details
    public function view($order_id, $id)
    {
        $data['orderTask'] = StudentServiceOrderTask::where('id', $id)
            ->where('student_service_order_id', $order_id)
            ->with(['assignees.user', 'labels', 'order', 'attachments.filemanager'])
            ->first();

        $data['order'] = $data['orderTask']->order;
        $data['conversationClientTypeData'] = StudentServiceOrderTaskConversation::where(['order_task_id' => $id, 'type' => CONVERSATION_TYPE_CLIENT])->with('user')->get();
        $data['conversationTeamTypeData'] = StudentServiceOrderTaskConversation::where(['order_task_id' => $id, 'type' => CONVERSATION_TYPE_TEAM])->with('user')->get();

        return view('admin.services.orders.task-board.view', $data)->render();
    }

    // Delete an attachment
    public function deleteAttachment($order_id, $task_id, $id)
    {
        StudentServiceOrderTaskAttachment::where(['order_task_id' => $task_id, 'file' => $id])->delete();

        // Save or update the file information
        StudentServiceOrderFile::where(['student_service_order_id' => $order_id, 'file' => $id])->delete();

        return $this->success([], __('Deleted Successfully'));
    }

    // Change progress of a task
    public function changeProgress(Request $request, $order_id, $id)
    {
        StudentServiceOrderTask::where(['student_service_order_id' => $order_id, 'id' => $id])->update(['progress' => $request->progress]);
        return $this->success([], __('Progress Updated Successfully'));
    }

    // Store a conversation
    public function conversationStore(Request $request, $order_id, $id)
    {
        $request->validate([
            'conversation_text' => 'required_without:file|string|nullable',
            'file' => 'nullable|array',
            'file.*' => 'file|mimes:jpg,jpeg,png,gif,bmp,svg,webp,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,rtf,odt,mp3,wav,ogg,aac,m4a,mp4,mov,avi,wmv,mkv,flv,zip,rar,7z',
        ], [
            'conversation_text.required_without' => 'The conversation text is required when no file is uploaded.',
            'file.*.file' => 'Each uploaded item must be a valid file.',
            'file.*.mimes' => 'Each file must be of a valid type (e.g., image, document, audio, video, or archive).',
        ]);

        DB::beginTransaction();
        try {
            $dataObj = new StudentServiceOrderTaskConversation();
            $dataObj->order_task_id = $id;
            $dataObj->conversation_text = $request->conversation_text ?? '';
            $dataObj->type = $request->type;
            $dataObj->user_id = auth()->id();

            /*File Manager Call upload*/
            if ($request->file) {
                $fileId = [];
                foreach ($request->file as $singleFile) {
                    $new_file = new FileManager();
                    $uploaded = $new_file->upload('order-task-conversation-documents', $singleFile);
                    array_push($fileId, $uploaded->id);
                }
                $dataObj->attachment = json_encode($fileId);
            }
            /*File Manager Call upload*/

            $dataObj->save();
            DB::commit();

            $renderData['conversationClientTypeData'] = StudentServiceOrderTaskConversation::where(['order_task_id'=> $id, 'type' => CONVERSATION_TYPE_CLIENT])->with('user')->get();
            $renderData['conversationTeamTypeData'] = StudentServiceOrderTaskConversation::where(['order_task_id'=> $id, 'type' => CONVERSATION_TYPE_TEAM])->with('user')->get();
            $renderData['type'] = $request->type;

            if (auth()->user()->role == USER_ROLE_STUDENT) {
                $data['conversationClientTypeData'] = view('student.services.orders.task-board.conversation_list_render', $renderData)->render();
            } else {
                $data['conversationClientTypeData'] = view('student.services.orders.task-board.conversation_list_render', $renderData)->render();
                $data['conversationTeamTypeData'] = view('admin.services.orders.task-board.conversation_list_render', $renderData)->render();
            }
            $data['type'] = $request->type;

            StudentServiceOrderTaskConversationSeen::where('order_task_id', $id)
                ->where('created_by', '!=', auth()->id())
                ->update(['is_seen' => 0]);

            StudentServiceOrderTask::where(['id' => $id])
                ->update([
                    'last_reply_id' => $dataObj->id,
                    'last_reply_by' => auth()->id(),
                    'last_reply_time' => now(),
                ]);

            return $this->success($data, __(CREATED_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }
}
