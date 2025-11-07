<?php

namespace App\Http\Controllers\Admin\Consultation;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;


class StudentController extends Controller
{
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $consulterStudent = Appointment::where('appointments.payment_status', '!=', PAYMENT_STATUS_NOT_INIT)->where('consulter_id', auth()->id())->leftJoin('users', 'appointments.user_id', '=', 'users.id')
                ->select('appointments.consulter_id','users.*')
                ->groupBy('user_id');


            return datatables($consulterStudent)
                ->addIndexColumn()
                ->addColumn('name',function ($user) {
                    return $user->first_name . ' ' . $user->last_name;
                })
                ->editColumn('image', function ($data) {
                    return '<div class="min-w-160 d-flex align-items-center cg-10">
                            <div class="flex-shrink-0 w-41 h-41 bd-one bd-c-stroke rounded-circle overflow-hidden bg-eaeaea d-flex justify-content-center align-items-center">
                                <img src="' . getFileUrl($data->image) . '" alt="Image" class="rounded avatar-xs w-100 h-100 object-fit-cover">
                            </div>
                        </div>';
                })
                ->rawColumns(['image'])
                ->make(true);
        }

        $data['pageTitle'] = __('Student List');
        $data['activeConsulterStudent'] = 'active';

        return view('consultant.student.list', $data);
    }

}
