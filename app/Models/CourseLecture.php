<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseLecture extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_id',
        'course_lesson_id',
        'title',
    ];

    /**
     * Relationships
     */

    // A lecture belongs to a course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // A lecture belongs to a course
    public function lesson()
    {
        return $this->belongsTo(CourseLesson::class, 'course_lesson_id');
    }

    // A lecture belongs to a lesson
    public function resources()
    {
        return $this->hasMany(CourseResource::class, 'course_lecture_id');
    }
}
