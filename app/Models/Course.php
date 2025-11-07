<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'program_id',
        'title',
        'subtitle',
        'slug',
        'thumbnail',
        'intro_video_type',
        'intro_video',
        'duration',
        'price',
        'course_benefits',
        'start_date',
        'description',
        'description_point',
        'faqs',
        'instructors',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'created_by',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
        'start_date' => 'date',
        'intro_video_type' => 'integer',
        'instructors' => 'array',
        'description_point' => 'array',
        'course_benefits' => 'array',
        'faqs' => 'array',
    ];

    /**
     * Relationships
     */

    // Assuming a course belongs to a category
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class, 'course_id');
    }

    public function lessons()
    {
        return $this->hasMany(CourseLesson::class, 'course_id');
    }

    // Assuming a course was created by a user
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function enrolls()
    {
        return $this->hasMany(CourseEnrollment::class, 'course_id');
    }

    public function payments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }
}
