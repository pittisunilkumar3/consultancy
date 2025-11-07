<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseEnrollment extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'course_id',
        'status',
    ];

    /**
     * Relationships
     */

    // An enrollment belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // An enrollment belongs to a course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
