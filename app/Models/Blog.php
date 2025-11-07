<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'slug',
        'title',
        'og_image',
        'details',
        'meta_title',
        'banner_image',
        'meta_keyword',
        'publish_date',
        'meta_description',
        'created_by',
        'blog_category_id',
    ];

    public function blogTag()
    {
        return $this->belongsToMany(BlogTag::class, 'blog_tag','blog_id','tag_id');
    }

}
