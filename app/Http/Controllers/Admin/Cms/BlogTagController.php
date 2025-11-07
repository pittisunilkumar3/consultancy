<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Exception;

class BlogTagController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $blogTag = BlogTag::query()->orderBy('id','DESC');
            return datatables($blogTag)
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
                            <button onclick="getEditModal(\'' . route('admin.cms-settings.blogs.tags.edit', $data->id) . '\'' . ', \'#edit-modal\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Edit">
                                 ' . view('partials.icons.edit')->render() . '
                            </button>
                            <button onclick="deleteItem(\'' . route('admin.cms-settings.blogs.tags.delete', $data->id) . '\', \'blogTagsDataTable\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Delete">
                                 ' . view('partials.icons.delete')->render() . '
                            </button>
                        </div>';
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        $data['pageTitle'] = __("Manage Blog Tag");
        $data['showManageBlog'] = 'show';
        $data['activeBlogTag'] = 'active';

        return view('admin.cms.blog.tags.index', $data);
    }

    public function edit($id)
    {
        $data['blogTagData'] = BlogTag::find($id);
        return view('admin.cms.blog.tags.edit', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $id = $request->id;
            if ($id) {
                $blogTags = BlogTag::find($id);
            } else {
                $blogTags = new BlogTag();
            }

            if (BlogTag::where('slug', getSlug($request->name))->where('id', '!=', $id)->withTrashed()->count() > 0) {
                $slug = getSlug($request->name) . '-' . rand(100000, 999999);
            } else {
                $slug = getSlug($request->name);
            }

            $blogTags->name = $request->name;
            $blogTags->slug = $slug;
            $blogTags->status = $request->status;

            $blogTags->save();
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
            $data = BlogTag::find($id);
            $data->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $exception) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }
}
