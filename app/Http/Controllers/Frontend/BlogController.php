<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Faq;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function list(Request $request)
    {
        $data['blogData'] = Blog::leftJoin('blog_categories','blogs.blog_category_id','blog_categories.id')
            ->leftjoin('users','blogs.created_by','=','users.id')
            ->leftjoin('designations','users.designation_id','=','designations.id')
            ->select(
                'blogs.title',
                'blogs.slug',
                'blogs.publish_date',
                'blogs.banner_image',
                'blog_categories.name as blogCategoryName',
                'users.first_name as firstName',
                'users.last_name as lastName',
                'users.image as userImage',
                'designations.title as userDesignation'
            )
            ->where('blogs.status', STATUS_ACTIVE)
            ->take(4)
            ->get();
        $data['blogMoreArticlesData'] = Blog::leftJoin('blog_categories','blogs.blog_category_id','blog_categories.id')
            ->leftjoin('users','blogs.created_by','=','users.id')
            ->leftjoin('designations','users.designation_id','=','designations.id')
            ->select(
                'blogs.title',
                'blogs.slug',
                'blogs.publish_date',
                'blogs.banner_image',
                'blog_categories.name as blogCategoryName',
                'users.first_name as firstName',
                'users.last_name as lastName',
                'users.image as userImage',
                'designations.title as userDesignation'
            )
            ->where('blogs.status', STATUS_ACTIVE)
            ->skip(4)
            ->limit(6)
            ->get();
        $data['faqData'] = Faq::where('status',STATUS_ACTIVE)->get();
        $data['pageTitle'] = __('Blog');
        $data['activeBlogMenu'] = 'active';
        return view('frontend.blog.list', $data);
    }


    public function details($slug){

        $data['activeBlogMenu'] = 'active';
        $data['bodyClass'] = 'bg-white';

        $data['blogDetails'] = Blog::leftjoin('blog_categories','blogs.blog_category_id','=','blog_categories.id')
            ->select('blogs.*',
                     'blog_categories.name as blogCategoryName',
            )->where('blogs.slug', $slug)
            ->first();
        $data['relatedPost'] = Blog::where('blog_category_id', '=', $data['blogDetails']->blog_category_id)
            ->take(6)
            ->get();
        return view('frontend.blog.details',$data);
    }
}
