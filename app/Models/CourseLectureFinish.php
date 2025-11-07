<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseLectureFinish extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'enrollment_id',
        'user_id',
        'course_resource_id',
    ];

    /**
     * Relationships
     */

    // A lecture finish belongs to a specific course enrollment
    public function enrollment()
    {
        return $this->belongsTo(CourseEnrollment::class, 'enrollment_id');
    }

    // A lecture finish belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
