<?php

namespace App\Http\Controllers\Admin\Consultation;

use App\Http\Controllers\Controller;
use App\Models\ConsulterFeedback;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    use ResponseTrait;

    public function list(Request $request)
    {
        $data['pageTitle'] = __('Consultation review');
        $data['showManageConsultation'] = 'show';
        $data['activeConsultationReview'] = 'active';

        if ($request->ajax()) {

            if (auth()->user()->role == USER_ROLE_ADMIN) {
                $consultationFeedback = ConsulterFeedback::with(['consulter', 'student'])->orderByDesc('id')->get();
            } elseif (auth()->user()->role == USER_ROLE_STUDENT) {
                $consultationFeedback = ConsulterFeedback::with('consulter')->where('student_id', auth()->user()->id)->orderByDesc('id')->get();
            } elseif (auth()->user()->role == USER_ROLE_CONSULTANT) {
                $consultationFeedback = ConsulterFeedback::with(['consulter', 'student'])->where('consulter_id',auth()->user()->id)->orderByDesc('id')->get();
            }

            return datatables($consultationFeedback)
                ->addIndexColumn()
                ->addColumn('student', function($data){
                    return $data->student->name;
                })
                ->addColumn('consulter', function($data){
                    return $data->consulter->name;
                })
                ->editColumn('status', function ($data) {
                    return $data->status == STATUS_ACTIVE
                        ? "<p class='zBadge zBadge-active'>" . __('Active') . "</p>"
                        : "<p class='zBadge zBadge-pending'>" . __('Pending') . "</p>";
                })
                ->addColumn('action', function ($data) {
                    if ((auth()->user()->role == USER_ROLE_ADMIN) || (auth()->user()->role == USER_ROLE_CONSULTANT)) {
                        return '<div class="d-flex align-items-center g-10 justify-content-end">
                            <button onclick="getEditModal(\'' . route(getPrefix() . '.consultations.review.status-change-modal', $data->id) . '\', \'#review-status-change-modal\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25"  title="Edit">
                                 ' . view('partials.icons.edit')->render() . '
                            </button>
                            <button onclick="deleteItem(\'' . route(getPrefix() . '.consultations.review.delete', $data->id) . '\', \'consulterReviewDataTable\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Delete">
                                 ' . view('partials.icons.delete')->render() . '
                            </button>
                        </div>';
                    }
                })
                ->rawColumns(['userName', 'status', 'action'])
                ->make(true);
        }


        if (auth()->user()->role == USER_ROLE_STUDENT) {
            return view('student.review', $data);
        }
        return view('admin.consultations.review.index', $data);

    }

    public function statusChangeModal($id)
    {
        $data['consulterReview'] = ConsulterFeedback::findOrFail($id);

        return view('admin.consultations.review.status-change-modal', $data);
    }

    public function updateStatus(Request $request)
    {
        DB::beginTransaction();
        try {

            $consultationReview = ConsulterFeedback::findOrFail($request->review_id);

            $consultationReview->status = $request->status;
            $consultationReview->update();

            DB::commit();
            $message = UPDATED_SUCCESSFULLY;
            return $this->success([], getMessage($message));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }

    public function delete($id)
    {
        try {
            $consultationReview = ConsulterFeedback::findOrFail($id);
            $consultationReview->delete();

            return $this->success([], __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }
}
