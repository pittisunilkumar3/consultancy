<?php

namespace App\Http\Controllers\Admin\Consultation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ConsulterRequest;
use App\Mail\EmailNotify;
use App\Models\ConsultationSlot;
use App\Models\FileManager;
use App\Models\User;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Str;

class ConsulterController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $consulters = User::where('role', USER_ROLE_CONSULTANT)->orderByDesc('id');

            return datatables($consulters)
                ->editColumn('status', function ($data) {
                    return $data->status == STATUS_ACTIVE
                        ? "<p class='zBadge zBadge-active'>" . __('Active') . "</p>"
                        : "<p class='zBadge zBadge-inactive'>" . __('Deactivate') . "</p>";
                })
                ->addColumn('name', function ($user) {
                    return $user->name;
                })
                ->addColumn('off_days', function ($user) {
                    $dayNames = array_map(function ($day) {
                        return offDays($day);
                    }, $user->day_off ?? []);

                    return implode(', ', $dayNames);
                })
                ->editColumn('image', function ($data) {
                    return '<div class="min-w-160 d-flex align-items-center cg-10">
                            <div class="flex-shrink-0 w-41 h-41 bd-one bd-c-stroke rounded-circle overflow-hidden bg-eaeaea d-flex justify-content-center align-items-center">
                                <img src="' . getFileUrl($data->image) . '" alt="Image" class="rounded avatar-xs w-100 h-100 object-fit-cover">
                            </div>
                        </div>';
                })
                ->addColumn('action', function ($data) {
                    return '<div class="d-flex align-items-center g-10 justify-content-end">
                            <button onclick="getEditModal(\'' . route('admin.consultations.edit', encodeId($data->id)) . '\', \'#edit-modal\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25"  title="Edit">
                                 ' . view('partials.icons.edit')->render() . '
                            </button>
                            <button onclick="deleteItem(\'' . route('admin.consultations.delete', encodeId($data->id)) . '\', \'consulterDataTable\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Delete">
                                ' . view('partials.icons.delete')->render() . '
                            </button>
                        </div>';
                })
                ->rawColumns(['title', 'status', 'action', 'image'])
                ->make(true);
        }

        $data['pageTitle'] = __('Consultant');
        $data['showManageConsultation'] = 'show';
        $data['activeConsulter'] = 'active';
        $data['slots'] = ConsultationSlot::where('status', STATUS_ACTIVE)->get();

        return view('admin.consultations.index', $data);
    }

    public function edit($id)
    {
        $data['consulter'] = User::where('id', decodeId($id))->with('slots')->first();
        $data['slots'] = ConsultationSlot::where('status', STATUS_ACTIVE)->get();

        return view('admin.consultations.edit', $data);
    }

    public function profile()
    {
        $data['pageTitle'] = __('Consultation Profile');
        $data['activeConsultantProfile'] = 'active';
        $data['consulter'] = User::where('id', auth()->id())->with('slots')->first();
        $data['slots'] = ConsultationSlot::where('status', STATUS_ACTIVE)->get();

        return view('consultant.consultations.profile', $data);
    }

    public function store(ConsulterRequest $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->get('id', '');

            if (auth()->user()->role == USER_ROLE_CONSULTANT) {
                $id = auth()->id();
            }
            if ($id) {
                $consulter = User::findOrFail($id);
            } else {
                $consulter = new User();
            }

            $consulter->first_name = $request->first_name;
            $consulter->last_name = $request->last_name;
            if (auth()->user()->role == USER_ROLE_ADMIN) {
                $consulter->mobile = $request->mobile;
                $consulter->email = $request->email;
                $consulter->password = $request->password ? bcrypt($request->password) : $consulter->password;
                $consulter->status = $request->status;
                $consulter->gender = $request->geneder;
            }
            if (!$id) {
                $consulter->created_by = auth()->id();
                $consulter->role = USER_ROLE_CONSULTANT;
                $consulter->verify_token = str_replace('-', '', Str::uuid()->toString());
            }
            $consulter->fee = $request->fee ?? 0;
            $consulter->day_off = $request->off_days;
            $consulter->professional_title = $request->professional_title;
            $consulter->experience = $request->experience;
            $consulter->about_me = $request->about_me;

            if ($request->hasFile('image')) {
                $new_file = new FileManager();
                $uploaded = $new_file->upload('users', $request->image);
                if (!is_null($uploaded)) {
                    $consulter->image = $uploaded->id;
                } else {
                    DB::rollBack();
                    return $this->error([], getMessage(SOMETHING_WENT_WRONG));
                }
            }

            $consulter->save();

            $consulter->slots()->sync($request->slot_ids);

            $createConsulterData = [
                '{{name}}' => $consulter->name,
                '{{email}}' => $request->email,
                '{{link}}' => route('login'),
                '{{password}}' => $request->password,
            ];

            try {
                $data = getEmailTemplate('user-create', $createConsulterData);
                Mail::to($request->email)->send(new EmailNotify($data));
            } catch (\Exception $e) {
                Log::info($e->getMessage());
            }

            DB::commit();
            $message = $request->id ? __(UPDATED_SUCCESSFULLY) : __(CREATED_SUCCESSFULLY);
            return $this->success([], getMessage($message));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }

    public function delete($id)
    {
        try {
            $consulter = User::findOrFail(decodeId($id));
            $consulter->delete();

            return $this->success([], __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }
}
