<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'details',
        'banner_image',
        'core_benefits_title',
        'core_benefits_icon',
        'gallery_image',
        'status'
    ];

    protected $casts = [
        'core_benefits_title' => 'array',
        'core_benefits_icon' => 'array',
        'gallery_image' => 'array'
    ];
}
