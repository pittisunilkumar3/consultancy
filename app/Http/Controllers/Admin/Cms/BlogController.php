<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\FileManager;
use App\Models\Blog;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class BlogController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $blog = Blog::leftJoin('blog_categories', 'blogs.blog_category_id', '=', 'blog_categories.id')
                ->select('blogs.*', 'blog_categories.name as blogCategoryName')
                ->orderBy('blogs.id', 'DESC');
            return datatables($blog)
                ->addIndexColumn()
                ->addColumn('banner_image', function ($data) {
                    return '<div class="min-w-160 d-flex align-items-center cg-10">
                            <div class="flex-shrink-0 w-41 h-41 bd-one bd-c-stroke rounded-circle overflow-hidden bg-eaeaea d-flex justify-content-center align-items-center">
                                <img src="' . getFileUrl($data->banner_image) . '" alt="banner_image" class="rounded avatar-xs w-100 h-100 object-fit-cover">
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
                ->addColumn('action', function ($data) {
                    return
                        '<div class="d-flex align-items-center g-10 justify-content-end">
                            <button onclick="getEditModal(\'' . route('admin.cms-settings.blogs.edit', $data->id) . '\'' . ', \'#edit-modal\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Edit">
                                 ' . view('partials.icons.edit')->render() . '
                            </button>
                            <button onclick="deleteItem(\'' . route('admin.cms-settings.blogs.delete', $data->id) . '\', \'blogDataTable\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Delete">
                                 ' . view('partials.icons.delete')->render() . '
                            </button>
                        </div>';
                })
                ->rawColumns(['status', 'action', 'banner_image'])
                ->make(true);
        }
        $data['pageTitle'] = __("Manage Blog");
        $data['showManageBlog'] = 'show';
        $data['activeBlog'] = 'active';
        $data['blogCategory'] = BlogCategory::where('status', STATUS_ACTIVE)->get();
        $data['blogTag'] = BlogTag::where('status', STATUS_ACTIVE)->get();

        return view('admin.cms.blog.index', $data);
    }

    public function edit($id)
    {
        $data['blogData'] = Blog::find($id);
        $data['blogCategory'] = BlogCategory::where('status', STATUS_ACTIVE)->get();
        $data['blogTag'] = BlogTag::where('status', STATUS_ACTIVE)->get();
        $data['oldTags'] = $data['blogData']->blogTag->pluck('id')->toArray();
        return view('admin.cms.blog.edit', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'details' => 'required',
            'publish_date' => 'required',
            'blog_category_id' => 'required',
            'tag_id' => 'required',
            'banner_image' => $request->id ? 'nullable|mimes:jpeg,png,jpg,svg,webp|max:1024' : 'required|mimes:jpeg,png,jpg,svg,webp|max:1024',
        ]);
        DB::beginTransaction();
        try {
            $id = $request->id;
            if ($id) {
                $blogs = Blog::find($id);
            } else {
                $blogs = new Blog();
            }
            if (Blog::where('slug', getSlug($request->title))->where('id', '!=', $id)->withTrashed()->count() > 0) {
                $slug = getSlug($request->title) . '-' . rand(100000, 999999);
            } else {
                $slug = getSlug($request->title);
            }

            $blogs->slug = $slug;
            $blogs->title = $request->title;
            $blogs->details = $request->details;
            $blogs->meta_title = $request->meta_title;
            $blogs->meta_keyword = $request->meta_keyword;
            $blogs->publish_date = $request->publish_date;
            $blogs->meta_description = $request->meta_description;
            $blogs->blog_category_id = $request->blog_category_id;
            $blogs->created_by = Auth::user()->id;
            $blogs->status = $request->status;

            if ($request->hasFile('banner_image')) {

                $newFile = new FileManager();
                $uploadedFile = $newFile->upload('blog', $request->banner_image);

                $blogs->banner_image = $uploadedFile->id;
            }
            if ($request->hasFile('og_image')) {

                $newFile = new FileManager();
                $uploadedFile = $newFile->upload('blog', $request->og_image);

                $blogs->og_image = $uploadedFile->id;
            }
            $blogs->save();
            $blogs->blogTag()->sync($request->tag_id);
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
            $data = Blog::find($id);
            $data->delete();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (Exception $exception) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }
}
