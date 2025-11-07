<?php

namespace App\Http\Controllers\Admin\Staffs;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StaffRequest;
use App\Mail\EmailNotify;
use App\Models\User;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Str;

class StaffController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $staffs = User::query()
                ->where('users.role', USER_ROLE_STAFF)
                ->with('roles')
                ->select('users.id', 'users.first_name', 'users.last_name', 'users.status', 'users.email', 'users.mobile');

            return datatables($staffs)
                ->addIndexColumn()
                ->addColumn('roles', function ($user) {
                    return implode(',', $user->roles()->pluck('name')->toArray());
                })
                ->addColumn('name', function ($user) {
                    return $user->name;
                })
                ->addColumn('status', function ($user) {
                    return $user->status == STATUS_ACTIVE
                        ? "<p class='zBadge zBadge-active'>" . __('Active') . "</p>"
                        : "<p class='zBadge zBadge-inactive'>" . __('Deactivate') . "</p>";
                })
                ->addColumn('action', function ($user) {
                    return '<div class="d-flex align-items-center g-10 justify-content-end">
                            <button onclick="getEditModal(\'' . route('admin.staffs.edit', $user->id) . '\'' . ', \'#edit-modal\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Edit">
                                 ' . view('partials.icons.edit')->render() . '
                            </button>
                            <button onclick="deleteItem(\'' . route('admin.staffs.delete', $user->id) . '\', \'staffDataTable\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Delete">
                                 ' . view('partials.icons.delete')->render() . '
                            </button>
                        </div>';
                })

                ->rawColumns(['roles', 'status', 'action'])
                ->make(true);
        }

        $data['pageTitle'] = __('Staff');
        $data['showManageStaff'] = 'show';
        $data['activeStaff'] = 'active';
        $data['roles'] = Role::where('status', STATUS_ACTIVE)->get();
        return view('admin.staffs.index', $data);
    }

    public function store(StaffRequest $request)
    {
        DB::beginTransaction();
        try {

            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->status = $request->status;
            $user->password = bcrypt($request->password);
            $user->role = USER_ROLE_STAFF;
            $user->created_by = auth()->id();
            $user->verify_token = str_replace('-', '', Str::uuid()->toString());
            $user->save();

            //roles
            $user->syncRoles($request->roles);
            /*End*/

            $createStaffData = [
                '{{name}}' => $user->name,
                '{{email}}' => $request->email,
                '{{link}}' => route('login'),
                '{{password}}' => $request->password,
            ];

            try {
                $data = getEmailTemplate('user-create', $createStaffData);
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

    public function update(StaffRequest $request, $id)
    {
        DB::beginTransaction();
        try {

            $user = User::where('id', $id)->first();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->status = $request->status;
            if ($request->password) {
                $user->password = bcrypt($request->password);
            }
            $user->status = $request->status;
            $user->save();

            //roles
            $user->syncRoles($request->roles);

            /*End*/
            DB::commit();

            $message = __(UPDATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function edit($id)
    {
        $data['user'] = User::findOrFail($id);
        $data['roles'] = Role::get();
        return view('admin.staffs.edit')->with($data);
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $user->delete();
            DB::commit();
            $message = __(DELETED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }
}
