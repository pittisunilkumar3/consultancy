<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class University extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'country_id',
        'thumbnail_image',
        'world_ranking',
        'international_student',
        'details',
        'core_benefits_title',
        'core_benefits_icon',
        'gallery_image',
        'avg_cost',
        'feature',
        'top_university',
        'status',
    ];
    protected $casts = [
        'core_benefits_title' => 'array',
        'core_benefits_icon' => 'array',
        'gallery_image' => 'array'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
