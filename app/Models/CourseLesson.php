<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseLesson extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_id',
        'title',
        'description',
    ];

    /**
     * Relationships
     */

    // A lesson belongs to a course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // A lesson can have many lectures
    public function lectures()
    {
        return $this->hasMany(CourseLecture::class, 'course_lesson_id');
    }
}
