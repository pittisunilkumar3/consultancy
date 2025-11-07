<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'banner_image',
        'details',
        'university_id',
        'subject_category_id',
        'country_id',
        'study_level_id',
        'intake_time',
        'duration',
        'requirement_program',
        'requirement_score',
        'amount',
        'status',
    ];
}
