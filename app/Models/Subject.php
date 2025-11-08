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

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function university()
    {
        return $this->belongsTo(University::class, 'university_id');
    }

    public function studyLevel()
    {
        return $this->belongsTo(StudyLevel::class, 'study_level_id');
    }

    public function subjectCategory()
    {
        return $this->belongsTo(SubjectCategory::class, 'subject_category_id');
    }
}
