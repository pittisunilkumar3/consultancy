<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmailTemplateController extends Controller
{
    use ResponseTrait;

    public function emailTemplate(Request $request)
    {
        if($request->ajax()){
            $data = EmailTemplate::query();
            return datatables($data)
                ->addIndexColumn()
                ->editColumn('target_audience', function ($data) {
                    if ($data->target_audience == EMAIL_TARGET_AUDIENCE_OF_ADMIN) {
                        return '<div class="zBadge">' . __("Admin") . '</div>';
                    } else if ($data->target_audience == EMAIL_TARGET_AUDIENCE_OF_STUDENT)  {
                        return '<div class="zBadge">' . __("Student") . '</div>';
                    } else if ($data->target_audience == EMAIL_TARGET_AUDIENCE_OF_USER)  {
                        return '<div class="zBadge">' . __("All User") . '</div>';
                    } else if ($data->target_audience == EMAIL_TARGET_AUDIENCE_OF_CONSULTER)  {
                        return '<div class="zBadge">' . __("Consultant") . '</div>';
                    }
                })
                ->addColumn('action', function ($data) {
                    return
                        '<div class="d-flex align-items-center g-10 justify-content-end">
                            <button onclick="getEditModal(\'' . route('admin.setting.email-template-edit', $data->id) . '\'' . ', \'#edit-modal\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Edit">
                                 ' . view('partials.icons.edit')->render() . '
                            </button>
                        </div>';
                })
                ->rawColumns(['action', 'status', 'target_audience'])
                ->make(true);
        }
        $data['pageTitle'] = __("Email Template");
        $data['showManageEmailTemplateSetting'] = 'show';
        $data['activeSetting'] = 'active';
        $data['activeEmailTemplate'] = 'active';
        $data['subEmailTemplateSettingActiveClass'] = 'active';

        return view('admin.setting.email_temp.email-temp')->with($data);
    }

    public function emailTempEdit($id){
        $data['template'] = EmailTemplate::find($id);
        return view('admin.setting.email_temp.edit-form',$data);
    }

    public function emailTempUpdate(Request $request){

        $validated = $request->validate([
            'category' => 'required',
            'subject' => 'required',
            'body' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $emailTemplate = EmailTemplate::findOrFail($request->id);

            $emailTemplate->category = $request->category;
            $emailTemplate->subject = $request->subject;
            $emailTemplate->body = $request->body;
            $emailTemplate->status = $request->status;

            $emailTemplate->save();
            DB::commit();

            $message = __(UPDATED_SUCCESSFULLY);
            return $this->success([], getMessage($message));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], getMessage($e->getMessage()));
        }
    }
}
