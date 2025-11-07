<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentServiceOrderTaskAssignee extends Model
{
    use SoftDeletes;

    protected $table = 'student_service_order_task_assignees'; // Define table name explicitly

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_task_id',
        'assign_to',
        'assign_by',
        'is_active',
    ];

    public function orderTask()
    {
        return $this->belongsTo(StudentServiceOrderTask::class, 'order_task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'assign_to');
    }
}
