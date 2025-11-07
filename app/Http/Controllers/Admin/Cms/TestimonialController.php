<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\FileManager;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Exception;

class TestimonialController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $testimonial = Testimonial::query()->orderBy('id', 'DESC');
            return datatables($testimonial)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    return '<div class="min-w-160 d-flex align-items-center cg-10">
                            <div class="flex-shrink-0 w-41 h-41 bd-one bd-c-stroke rounded-circle overflow-hidden bg-eaeaea d-flex justify-content-center align-items-center">
                                <img src="' . getFileUrl($data->image) . '" alt="image" class="rounded avatar-xs w-100 h-100 object-fit-cover">
                            </div>
                        </div>';
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == STATUS_ACTIVE) {
                        return '<div class="zBadge zBadge-completed">' . __("Active") . '</div>';
                    } else {
                        return '<div class="zBadge zBadge-deactive">' . __("Deactivate") . '</div>';
                    }
                })
                ->editColumn('review_date', function ($data) {
                    return $data->review_date ? formatDate($data->review_date, 'F j, Y') : __("N/A");
                })
                ->addColumn('action', function ($data) {
                    return
                        '<div class="d-flex align-items-center g-10 justify-content-end">
                            <button onclick="getEditModal(\'' . route('admin.cms-settings.testimonials.edit', $data->id) . '\'' . ', \'#edit-modal\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Edit">
                                 ' . view('partials.icons.edit')->render() . '
                            </button>
                            <button onclick="deleteItem(\'' . route('admin.cms-settings.testimonials.delete', $data->id) . '\', \'testimonialDataTable\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Delete">
                                 ' . view('partials.icons.delete')->render() . '
                            </button>
                        </div>';
                })
                ->rawColumns(['status', 'action', 'image'])
                ->make(true);
        }
        $data['pageTitle'] = __("Testimonial Section");
        $data['landingPage'] = 'active';
        $data['showCmsSettings'] = 'show';
        $data['subCmsSettingActiveClass'] = 'active';
        $data['subTestimonialsCmsSettingActiveClass'] = 'active';

        return view('admin.cms.testimonial.index', $data);
    }

    public function edit($id)
    {
        $data['testimonialData'] = Testimonial::find($id);
        return view('admin.cms.testimonial.edit', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'review_date' => 'required',
            'description' => 'required',
            'image' => $request->id ? 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:1024' : 'required|image|mimes:jpeg,png,jpg,svg,webp|max:1024',
        ]);

        DB::beginTransaction();
        try {
            $id = $request->id;
            if ($id) {
                $testimonials = Testimonial::find($id);
            } else {
                $testimonials = new Testimonial();
            }

            $testimonials->name = $request->name;
            $testimonials->review_date = $request->review_date;
            $testimonials->description = $request->description;
            $testimonials->status = $request->status;

            if ($request->hasFile('image')) {

                $newFile = new FileManager();
                $uploadedFile = $newFile->upload('testimonial', $request->image);
                $testimonials->image = $uploadedFile->id;
            }
            $testimonials->save();
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
            $data = Testimonial::find($id);
            $data->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $exception) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }
}

