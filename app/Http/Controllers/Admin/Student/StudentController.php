<?php

namespace App\Http\Controllers\Admin\Student;

use App\Http\Controllers\Controller;
use App\Mail\EmailNotify;
use App\Models\CourseEnrollment;
use App\Models\Program;
use App\Models\User;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Str;

class StudentController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $students = User::orderBy('users.id', 'desc')
                ->where('role', USER_ROLE_STUDENT)
                ->select('users.id', 'first_name', 'last_name', 'email', 'image', 'gender', 'mobile', 'status');
            return datatables($students)
                ->editColumn('name', function ($data) {
                    return $data->first_name . ' ' . $data->last_name;
                })
                ->editColumn('status', function ($data) {
                    switch ($data->status) {
                        case STATUS_ACTIVE:
                            return "<p class='zBadge zBadge-active'>" . __('Active') . "</p>";
                        case STATUS_SUSPENDED:
                            return "<p class='zBadge zBadge-suspended'>" . __('Suspended') . "</p>";
                        default:
                            return "<p class='zBadge zBadge-inactive'>" . __('Inactive') . "</p>";
                    }
                })
                ->editColumn('gender', function ($data) {
                    switch ($data->gender) {
                        case 1:
                            return __('Male');
                        case 2:
                            return __('Female');
                        case 3:
                            return __('Other');
                    }
                })
                ->editColumn('image', function ($data) {
                    return '<div class="min-w-160 d-flex align-items-center cg-10">
                            <div class="flex-shrink-0 w-41 h-41 bd-one bd-c-stroke rounded-circle overflow-hidden bg-eaeaea d-flex justify-content-center align-items-center">
                                <img src="' . getFileUrl($data->image) . '" alt="Image" class="rounded avatar-xs w-100 h-100 object-fit-cover">
                            </div>
                        </div>';
                })
                ->addColumn('action', function ($data) {
                    return '<ul class="d-flex align-items-center cg-5 justify-content-end">
                        <li class="align-items-center d-flex gap-2">
                            <button onclick="deleteItem(\'' . route('admin.students.delete', encodeId($data->id)) . '\', \'commonDataTable\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-stroke bg-white" title="' . __('Delete') . '">
                                ' . view('partials.icons.delete')->render() . '
                            </button>
                            <button onclick="getEditModal(\'' . route('admin.students.status_change_modal', encodeId($data->id)) . '\', \'#status-change-modal\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="' . __('Change Status') . '">
                                ' . view('partials.icons.change_status')->render() . '
                            </button>
                        </li>
                    </ul>';
                })
                ->rawColumns(['action', 'image', 'status'])
                ->make(true);
        }

        $data['pageTitle'] = __('Students');
        $data['activeStudent'] = 'active';

        return view('admin.students.index', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'max:120'],
            'last_name' => ['required', 'max:120'],
            'email' => ['required', 'email', 'unique:users,email'],
            'mobile' => ['required', 'min:6'],
            'password' => ['required'],
        ]);

        DB::beginTransaction();

        try {

            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->status = $request->status;
            $user->password = bcrypt($request->password);
            $user->role = USER_ROLE_STUDENT;
            $user->created_by = auth()->id();
            $user->verify_token = str_replace('-', '', Str::uuid()->toString());
            $user->save();

            $createStudentData = [
                '{{name}}' => $user->name,
                '{{email}}' => $request->email,
                '{{link}}' => route('login'),
                '{{password}}' => $request->password,
            ];

            try {
                $data = getEmailTemplate('user-create', $createStudentData);
                Mail::to($request->email)->send(new EmailNotify($data));
            } catch (\Exception $e) {
                Log::info($e->getMessage());
            }


            DB::commit();

            $message = __(CREATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function statusChangeModal($id)
    {
        $data['user'] = User::where('id', decodeId($id))->first();

        return view('admin.students.status_change_modal', $data);
    }

    public function statusChange(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = User::where('id', decodeId($id))->first();
            $user->status = $request->status;
            $user->save();

            DB::commit();
            return $this->success([], __('Updated Successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], __('Something went wrong! Please try again.'));
        }
    }

    public function delete($id)
    {
        try {
            $user = User::findOrFail(decodeId($id));
            $user->delete();

            return $this->success([], __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }

    public function courses(Request $request, $id)
    {
        if ($request->ajax()) {

            $myCourses = CourseEnrollment::join('courses', 'course_enrollments.course_id', '=', 'courses.id')
                ->join('programs', 'courses.program_id', '=', 'programs.id')
                ->where('course_enrollments.status', STATUS_ACTIVE)
                ->select([
                    'programs.title as program_title',
                    'courses.id',
                    'courses.title',
                    'courses.duration',
                    'courses.start_date',
                    'courses.program_id',
                    'courses.thumbnail',
                    'courses.status'
                ])
                ->orderBy('course_enrollments.id', 'DESC');


            // Apply search filter
            if ($request->has('search_key') && $request->search_key != '') {
                $myCourses->where(function ($query) use ($request) {
                    $query->where('courses.title', 'like', '%' . $request->search_key . '%')
                        ->orWhere('price', 'like', '%' . $request->search_key . '%')
                        ->orWhere('duration', 'like', '%' . $request->search_key . '%');
                });
            }

            // Apply program filter
            if ($request->has('program_id') && $request->program_id != '') {
                $myCourses->where('program_id', $request->program_id);
            }

            return datatables($myCourses)
                ->editColumn('status', function ($data) {
                    return $data->status == STATUS_ACTIVE
                        ? "<p class='zBadge zBadge-active'>" . __('Active') . "</p>"
                        : "<p class='zBadge zBadge-inactive'>" . __('Deactivate') . "</p>";
                })
                ->editColumn('thumbnail', function ($data) {
                    return '<div class="min-w-160 d-flex align-items-center cg-10">
                        <div class="flex-shrink-0 w-41 h-41 bd-one bd-c-stroke rounded-circle overflow-hidden bg-eaeaea d-flex justify-content-center align-items-center">
                            <img src="' . getFileUrl($data->thumbnail) . '" alt="Image" class="rounded avatar-xs w-100 h-100 object-fit-cover">
                        </div>
                    </div>';
                })
                ->rawColumns(['status', 'action', 'thumbnail'])
                ->make(true);
        }

        $data['user'] = User::findOrFail(decodeId($id));
        $data['pageTitle'] = $data['user']->name.' '.__('Purchase Course');
        $data['activeStudent'] = 'active';
        $data['programs'] = Program::where('status', STATUS_ACTIVE)->get();

        return view('admin.students.course_list', $data);
    }
}
