<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Exception;

class FaqController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $faq = Faq::query()->orderBy('id','DESC');
            return datatables($faq)
                ->addIndexColumn()
                ->addColumn('status', function ($data) {
                    if ($data->status == STATUS_ACTIVE) {
                        return '<div class="zBadge zBadge-completed">'.__("Active").'</div>';
                    } else {
                        return '<div class="zBadge zBadge-deactive">'.__("Deactivate").'</div>';
                    }
                })
                ->addColumn('action', function ($data) {
                    return
                        '<div class="d-flex align-items-center g-10 justify-content-end">
                            <button onclick="getEditModal(\'' . route('admin.cms-settings.faqs.edit', $data->id) . '\'' . ', \'#edit-modal\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Edit">
                                 ' . view('partials.icons.edit')->render() . '
                            </button>
                            <button onclick="deleteItem(\'' . route('admin.cms-settings.faqs.delete', $data->id) . '\', \'faqDataTable\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Delete">
                                 ' . view('partials.icons.delete')->render() . '
                            </button>
                        </div>';
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        $data['pageTitle'] = __("Faq Section");
        $data['landingPage'] = 'active';
        $data['showCmsSettings'] = 'show';
        $data['subCmsSettingActiveClass'] = 'active';
        $data['subFaqsCmsSettingActiveClass'] = 'active';

        return view('admin.cms.faq.index', $data);
    }

    public function edit($id)
    {
        $data['faqData'] = Faq::find($id);
        return view('admin.cms.faq.edit', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $id = $request->id;
            if ($id) {
                $faqs = Faq::find($id);
            } else {
                $faqs = new Faq();
            }

            $faqs->question = $request->question;
            $faqs->answer = $request->answer;
            $faqs->status = $request->status;

            $faqs->save();
            DB::commit();
            $message = $request->id ? __(UPDATED_SUCCESSFULLY) : __(CREATED_SUCCESSFULLY);
            return $this->success([], getMessage($message));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], getMessage($e->getMessage()));
        }
    }

    public function delete($id)
    {
        try {
            $data = Faq::find($id);
            $data->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $exception) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }
}

